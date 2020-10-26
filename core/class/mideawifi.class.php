<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

/* * ***************************Includes********************************* */
require_once __DIR__  . '/../../../../core/php/core.inc.php';

//include_file('3rdparty', 'msmart', 'php', 'mideawifi');

class mideawifi extends eqLogic {

	/*     * *************************Attributs****************************** */

	public static $_widgetPossibility = array('custom' => true);

	public static function templateWidget(){
		$return['action']['other']['temperature_consigne'] = array(
			'template' => 'setTemperature'
		);
		$return['action']['select']['setMode'] = array(
			'template' => 'tmplSelect'
		);
		$return['action']['select']['setSwingmode'] = array(
			'template' => 'tmplSelect'
		);
		$return['action']['select']['setFanspeed'] = array(
			'template' => 'tmplSelect'
		);
		/*$return['action']['other']['powerState'] = array(
			'template' => 'tmplPowerState'
		);*/
		return $return;
	}
	/**
	* @var string Dependancy installation log file
	*/
	private static $_depLogFile;

	/**
	* @var string Dependancy installation progress value log file
	*/
	private static $_depProgressFile;

	/*
	* Fonction exécutée automatiquement toutes les heures par Jeedom
	public static function cronHourly() {

	}
	*/

	/*
	* Fonction exécutée automatiquement tous les jours par Jeedom
	public static function cronDaily() {

	}
	*/

	/*     * ********************* GESTION DES DEPENDANCES************************* */

	public static function dependancy_install() {
		plugin::byId('mideawifi')->dependancy_info(true);
		log::add('mideawifi', 'info', 'Installation des dépendances, voir log dédié (' . self::$_depLogFile . ')');
		$timeout = config::byKey('timeout', 'mideawifi');

		log::remove(self::$_depLogFile);
		return array(
			'script' => dirname(__FILE__) . '/../../resources/install_#stype#.sh ' . self::$_depProgressFile . ' ' . $timeout,'log' => log::getPathToLog(self::$_depLogFile));
	}

	public static function dependancy_info() {
		if (! isset(self::$_depLogFile))
			self::$_depLogFile = __CLASS__ . '_dep';

		if (! isset(self::$_depProgresFile))
			self::$_depProgressFile = jeedom::getTmpFolder(__CLASS__) . '/progress_dep.txt';

		$return = array();
		$return['log'] = log::getPathToLog(self::$_depLogFile);
		$return['progress_file'] = self::$_depProgressFile;
		$return['state'] = 'ok';

		// PYTHON
			$python = shell_exec('python3 --version 2>&1');
			log::add('mideawifi', 'debug', 'version python3: ' . $python);
			$arrPython = explode(" ", $python);

		if( $arrPython[0] == "Python") { // python installé ok
			$versionPython = explode(".", $arrPython[1]);
		if($versionPython[1] < 5 || $versionPython[1] > 7) { // compatible de python 3.5 à 3.7
			$return['state'] = 'nok';
			log::add('mideawifi', 'debug', 'probleme de version mineure: ' . $python);
		}
		} else {
			$return['state'] = 'nok';
			log::add('mideawifi', 'debug', 'probleme avec python3');
		}

		// PIP3
		$pip3 = shell_exec('pip3 --version 2>&1');
		if(substr($pip3, 0, 3) != "pip") {
			$return['state'] = 'nok';
			log::add('mideawifi', 'debug', 'probleme avec pip3');
		} else {
		// msmart
			$msmart = shell_exec('pip3 show msmart 2>&1');
			if(!$msmart) {
				$return['state'] = 'nok';
				log::add('mideawifi', 'debug', 'probleme avec module pip3 msmart');
			}
		}

		return $return;
	}

	/*     * *********************Méthodes d'instance************************* */

  	public static function cron10() {
    	
		foreach (self::byType('mideawifi') as $eqLogicMideawifi) {
          	//log::add('mideawifi', 'debug', 'valeur enable' . $eqLogicMideawifi->getIsEnable());
          	if($eqLogicMideawifi->getIsEnable() == 1)
				$eqLogicMideawifi->updateInfos();

			log::add('mideawifi', 'debug', 'update clim ' . $eqLogicMideawifi->getName());
		}
    }
  
	public function preInsert() {

	}

	public function postInsert() {

	}

	public function preSave() {

	}

	public function postSave() {

		$order = 1;

		// ================================================================================================================= //
		// ===================================================== INFOS ===================================================== //
		// ================================================================================================================= //

		// etat alimentation
		$infoState = $this->getCmd(null, 'power_state');
		if (!is_object($infoState)) {
			$infoState = new mideawifiCmd();
			$infoState->setName(__('Etat courant', __FILE__));
		}
		$infoState->setOrder($order++);
		$infoState->setLogicalId('power_state');
		$infoState->setEqLogic_id($this->getId());
		$infoState->setType('info');

		$infoState->setSubType('binary');
		$infoState->setIsVisible(1);
		$infoState->setIsHistorized(1);
		$infoState->setDisplay('forceReturnLineBefore', false);
		$infoState->save();

		// bips de changement
		$info = $this->getCmd(null, 'prompt_tone');
		if (!is_object($info)) {
			$info = new mideawifiCmd();
			$info->setName(__('prompt_tone', __FILE__));
		}
		$info->setOrder($order++);
		$info->setLogicalId('prompt_tone');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setTemplate('dashboard', 'default'); //template pour le dashboard
		$info->setSubType('binary');
		$info->setIsVisible(0);
		$info->setIsHistorized(0);
		$info->setDisplay('forceReturnLineBefore', false);
		$info->save();

		// température désirée
		$infoTemp = $this->getCmd(null, 'target_temperature');
		if (!is_object($infoTemp)) {
			$infoTemp = new mideawifiCmd();
			$infoTemp->setName(__('Température désirée', __FILE__));
		}
		$infoTemp->setOrder($order++);
		$infoTemp->setLogicalId('target_temperature');
		$infoTemp->setEqLogic_id($this->getId());
		$infoTemp->setType('info');
		$infoTemp->setTemplate('dashboard', 'tile'); //template pour le dashboard
		$infoTemp->setSubType('string');
		$infoTemp->setIsVisible(0);
		$infoTemp->setUnite('°C');
		//$infoTemp->setDisplay('generic_type', 'TEMPERATURE');
		$infoTemp->setDisplay('forceReturnLineBefore', false);
		$infoTemp->save();


		// vitesse ventilateur
		$infoSpeedfan = $this->getCmd(null, 'fan_speed');
		if (!is_object($infoSpeedfan)) {
			$infoSpeedfan = new mideawifiCmd();
			$infoSpeedfan->setName(__('Vitesse', __FILE__));
		}
		$infoSpeedfan->setOrder($order++);
		$infoSpeedfan->setLogicalId('fan_speed');
		$infoSpeedfan->setEqLogic_id($this->getId());
		$infoSpeedfan->setType('info');
		$infoSpeedfan->setSubType('string');
		$infoSpeedfan->setIsVisible(0);
      	$infoSpeedfan->setDisplay('forceReturnLineBefore', false);
		$infoSpeedfan->save();

		// Mode de ventilation
		$infoSwingmode = $this->getCmd(null, 'swing_mode');
		if (!is_object($infoSwingmode)) {
			$infoSwingmode = new mideawifiCmd();
			$infoSwingmode->setName(__('Direction', __FILE__));
		}
		$infoSwingmode->setOrder($order++);
		$infoSwingmode->setLogicalId('swing_mode');
		$infoSwingmode->setEqLogic_id($this->getId());
		$infoSwingmode->setType('info');
		$infoSwingmode->setSubType('string');
		$infoSwingmode->setIsVisible(0);
      	$infoSwingmode->setDisplay('forceReturnLineBefore', false);
		$infoSwingmode->save();

		// Mode éco
		$info = $this->getCmd(null, 'eco_mode');
		if (!is_object($info)) {
			$info = new mideawifiCmd();
			$info->setName(__('Mode éco', __FILE__));
		}
		$info->setOrder($order++);
		$info->setLogicalId('eco_mode');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setTemplate('dashboard', 'default'); //template pour le dashboard
		$info->setSubType('binary');
		$info->setIsVisible(1);
		$info->setIsHistorized(0);
		$info->setDisplay('forceReturnLineBefore', false);
		$info->save();

		// Mode Turbo
		$info = $this->getCmd(null, 'turbo_mode');
		if (!is_object($info)) {
			$info = new mideawifiCmd();
			$info->setName(__('Mode turbo', __FILE__));
		}
		$info->setOrder($order++);
		$info->setLogicalId('turbo_mode');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setTemplate('dashboard', 'default'); //template pour le dashboard
		$info->setSubType('binary');
		$info->setIsVisible(1);
		$info->setIsHistorized(0);
		$info->setDisplay('forceReturnLineBefore', false);
		$info->save();

		// mode opérationnel
		$infoMode = $this->getCmd(null, 'operational_mode');
		if (!is_object($infoMode)) {
			$infoMode = new mideawifiCmd();
			$infoMode->setName(__('Mode courant', __FILE__));
		}
		$infoMode->setOrder($order++);
		$infoMode->setLogicalId('operational_mode');
		$infoMode->setEqLogic_id($this->getId());
		$infoMode->setType('info');
		/*if ( version_compare(jeedom::version(), "4", "<") ) {
		$infoMode->setTemplate('dashboard', 'displayModeInfo'); //template pour le dashboard en v3
		} else {
		$infoMode->setTemplate('dashboard', 'mideawifi::displayModeInfo'); //template pour le dashboard
		}*/
		$infoMode->setSubType('string');
		$infoMode->setIsVisible(0);
		//$infoMode->setDisplay('generic_type', 'MODE_STATE');
		$infoMode->setDisplay('forceReturnLineBefore', true);
		$infoMode->save();

		// température intérieure
		$info = $this->getCmd(null, 'indoor_temperature');
		if (!is_object($info)) {
			$info = new mideawifiCmd();
			$info->setName(__('Température intérieure', __FILE__));
		}
		$info->setOrder($order++);
		$info->setLogicalId('indoor_temperature');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setTemplate('dashboard', 'default'); //template pour le dashboard
		$info->setSubType('string');
		$info->setIsVisible(1);
		$info->setIsHistorized(1);
		$info->setUnite('°C');
		$info->setDisplay('forceReturnLineBefore', true);
		$info->save();

		// température extérieure
		$info = $this->getCmd(null, 'outdoor_temperature');
		if (!is_object($info)) {
			$info = new mideawifiCmd();
			$info->setName(__('Température extérieure', __FILE__));
		}
		$info->setOrder($order++);
		$info->setLogicalId('outdoor_temperature');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setTemplate('dashboard', 'default'); //template pour le dashboard
		$info->setSubType('string');
		$info->setIsVisible(1);
		$info->setIsHistorized(1);
		$info->setUnite('°C'); 
		$info->setDisplay('forceReturnLineBefore', true);
		$info->setDisplay('forceReturnLineAfter', true);
		$info->save();

		// ================================================================================================================= //
		// ==================================================== ACTIONS ==================================================== //
		// ================================================================================================================= //

		// @DEVHELP https://github.com/jeedom/core/blob/06fb34c895b420630bfa9d9317547088b13f81d7/core/config/jeedom.config.php

		// Allumage/Extinction clim
		/*$cmd = $this->getCmd('action', 'setPowerState');
		if (!is_object($cmd)) {
			$cmd = new mideawifiCmd();
			$cmd->setName(__('Etat', __FILE__));
		}
		$cmd->setOrder(1);
		$cmd->setIsVisible(1);
		$cmd->setLogicalId('setPowerState');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setValue($infoState->getId());
		$cmd->setTemplate('dashboard', 'mideawifi::powerState'); //template pour le dashboard
		$cmd->setDisplay('forceReturnLineBefore', true);
		$cmd->save();*/

      	$cmd = $this->getCmd('action', 'on');
		if (!is_object($cmd)) {
		$cmd = new mideawifiCmd();
		$cmd->setName(__('Allumer', __FILE__));
		}
		$cmd->setOrder($order++);
		$cmd->setIsVisible(1);
		$cmd->setLogicalId('on');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setDisplay('generic_type', 'ENERGY_ON');
		$info->setDisplay('forceReturnLineBefore', true);
		//$info->setDisplay('forceReturnLineAfter', true);
		$cmd->save();
      
		// Extinction clim
		$cmd = $this->getCmd('action', 'off');
		if (!is_object($cmd)) {
		$cmd = new mideawifiCmd();
		$cmd->setName(__('Eteindre', __FILE__));
		}
		$cmd->setOrder($order++);
		$cmd->setIsVisible(1);
		$cmd->setLogicalId('off');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setDisplay('generic_type', 'ENERGY_OFF');
		//$info->setDisplay('forceReturnLineBefore', true);
		$info->setDisplay('forceReturnLineAfter', true);
		$cmd->save();

		// Changement température de consigne
		$cmd = $this->getCmd('action', 'setTemperature');
		if (!is_object($cmd)) {
			$cmd = new mideawifiCmd();
			$cmd->setName(__('Température de consigne', __FILE__));
		}
		$cmd->setOrder($order++);
		$cmd->setIsVisible(1);
		$cmd->setLogicalId('setTemperature');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setConfiguration('minValue', 16);
		$cmd->setConfiguration('maxValue', 30);
		$cmd->setUnite('°C');
		$cmd->setValue($infoTemp->getId());
		$cmd->setTemplate('dashboard', 'mideawifi::setTemperature');
		$cmd->setDisplay('forceReturnLineBefore', true);
		$cmd->save();

		// Changement du mode
		$cmd = $this->getCmd('action', 'setMode');
		if (!is_object($cmd)) {
			$cmd = new mideawifiCmd();
			$cmd->setName(__('Mode', __FILE__));
		}           
		$cmd->setOrder($order++);
		$cmd->setIsVisible(1);
		$cmd->setLogicalId('setMode');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setType('action');
		$cmd->setSubType('select');
		$cmd->setValue($infoMode->getId());
		$cmd->setConfiguration('listValue', "auto|auto;cool|climatisation;dry|déshumidificateur;heat|Chauffage;fan_only|Ventilation");
		$cmd->setTemplate('dashboard', 'mideawifi::tmplSelect');
		$cmd->setDisplay('forceReturnLineBefore', true);
		$cmd->save();

		// Changement de l'orientation de la ventilation
		log::add('mideawifi', 'debug', '===== Save Swingmode =====');
		$cmd = $this->getCmd('action', 'setSwingmode');
		if (!is_object($cmd)) {
			$cmd = new mideawifiCmd();
			$cmd->setName(__('Type de ventilation', __FILE__));
		}           
		$cmd->setOrder($order++);
		$cmd->setIsVisible(1);
		$cmd->setLogicalId('setSwingmode');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setType('action');
		$cmd->setSubType('select');
		// MAJ de l'énumeration des orientations par rapport à la configuration choisie
		$currentSwingmodes = $this->getConfiguration('swingmode');
		log::add('mideawifi', 'debug', 'swingMode sélectionné = ' . $currentSwingmodes);
		if($currentSwingmodes == "Vertical") {
			$cmd->setConfiguration('listValue', "Off|Eteint;Vertical|Vertical");
		} elseif ($currentSwingmodes == "Horizontal") {
			$cmd->setConfiguration('listValue', "Off|Eteint;Horizontal|Horizontal");
		} else {
			$cmd->setConfiguration('listValue', "Off|Eteint;Vertical|Vertical;Horizontal|Horizontal;Both|Les deux");
		}
		// on met à jour la commande info avec la configuration (choix le plus logique) choisie
		$this->checkAndUpdateCmd("swing_mode", $currentSwingmodes);
		$cmd->setValue($infoSwingmode->getId());
		$cmd->setTemplate('dashboard','mideawifi::tmplSelect');
		$cmd->setDisplay('forceReturnLineBefore', true);
		$cmd->save();

		// Changement de la vitesse de ventilation
		$cmd = $this->getCmd('action', 'setFanspeed');
		if (!is_object($cmd)) {
			$cmd = new mideawifiCmd();
			$cmd->setName(__('Vitesse de ventilation', __FILE__));
		}         
		$cmd->setOrder($order++);
		$cmd->setIsVisible(1);
		$cmd->setLogicalId('setFanspeed');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setType('action');
		$cmd->setSubType('select');
		$cmd->setValue($infoSpeedfan->getId());
		$cmd->setConfiguration('listValue', "Auto|Automatique;High|Rapide;Medium|Moyenne;Low|Lente;Silent|Silencieuse");
		$cmd->setTemplate('dashboard', 'mideawifi::tmplSelect');
		$cmd->setDisplay('forceReturnLineBefore', true);
		$cmd->save();

		// Mise en route du mode Eco
		$cmd = $this->getCmd('action', 'setEcomode');
		if (!is_object($cmd)) {
			$cmd = new mideawifiCmd();
			$cmd->setName(__('Eco', __FILE__));
		}
		$cmd->setOrder($order++);
		$cmd->setIsVisible(1);
		$cmd->setLogicalId('setEcomode');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setDisplay('forceReturnLineBefore', true);
		$cmd->save();

		// Mise en route du mode Turbo
		$cmd = $this->getCmd('action', 'setTurbomode');
		if (!is_object($cmd)) {
			$cmd = new mideawifiCmd();
			$cmd->setName(__('Turbo', __FILE__));
		}
		$cmd->setOrder($order++);
		$cmd->setIsVisible(1);
		$cmd->setLogicalId('setTurbomode');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setDisplay('forceReturnLineBefore', false);
		$cmd->save();

		// Désactivation des modes turbo/eco
		$cmd = $this->getCmd('action', 'setNormalmode');
		if (!is_object($cmd)) {
			$cmd = new mideawifiCmd();
			$cmd->setName(__('Normal', __FILE__));
		}         
		$cmd->setOrder($order++);
		$cmd->setIsVisible(1);
		$cmd->setLogicalId('setNormalmode');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setDisplay('forceReturnLineBefore', false);
		$cmd->setDisplay('forceReturnLineAfter', true);
		$cmd->save();

		// activation des bips
		$cmd = $this->getCmd('action', 'bipsOn');
		if (!is_object($cmd)) {
			$cmd = new mideawifiCmd();
			$cmd->setName(__('Bips ON', __FILE__));
		}         
		$cmd->setOrder($order++);
		$cmd->setIsVisible(1);
		$cmd->setLogicalId('bipsOn');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setDisplay('forceReturnLineBefore', true);
		$cmd->save();

		// Désactivation des bips
		$cmd = $this->getCmd('action', 'bipsOff');
		if (!is_object($cmd)) {
			$cmd = new mideawifiCmd();
			$cmd->setName(__('Bips OFF', __FILE__));
		}         
		$cmd->setOrder($order++);
		$cmd->setIsVisible(1);
		$cmd->setLogicalId('bipsOff');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setDisplay('forceReturnLineBefore', false);
		$cmd->save();
		// rafraichir
		$refresh = $this->getCmd(null, 'refresh');
		if (!is_object($refresh)) {
			$refresh = new mideawifiCmd();
			$refresh->setName(__('Rafraichir', __FILE__));
		}
		$refresh->setEqLogic_id($this->getId());
		$refresh->setLogicalId('refresh');
		$refresh->setType('action');
		$refresh->setSubType('other');
		$refresh->save();

		// à la fin, on contact directement léquipement pour récupérer les infos courantes
		$this->updateInfos();
	} // fin postSave()

	public function preUpdate() {

	}

	public function postUpdate() {
		// clear le cache widget
		$this->refreshWidget();
	}

	public function preRemove() {

	}

	public function postRemove() {

	}

	/*
	* Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
	public function toHtml($_version = 'dashboard') {

	}
	*/

	
	//* Non obligatoire mais ca permet de déclencher une action après modification de variable de configuration
	// Mets à jour le timeout dans le cli.py
	public static function postConfig_timeout() {
		// @TODO
		log::add('mideawifi', 'debug', 'timeout modifié');
	}
	

	/*
	* Non obligatoire mais ca permet de déclencher une action avant modification de variable de configuration
	public static function preConfig_<Variable>() {
	}
	*/

	/*     * **********************Getteur Setteur*************************** */
	public function getInfos() {
		// 2 trames dexemple
		//$get = "{'name': '192.168.102.79', 'fan_speed': <fan_speed_enum.Low: 40>, 'turbo_mode': False, 'prompt_tone': False, 'outdoor_temperature': 33.0, 'power_state': True, 'id': '140f00000014', 'target_temperature': 28.0, 'operational_mode': <operational_mode_enum.cool: 2>, 'swing_mode': <swing_mode_enum.Off: 0>, 'indoor_temperature': 28.0, 'eco_mode': False}";
		//$get = "{'name': '192.168.102.80', 'fan_speed': <fan_speed_enum.High: 80>, 'turbo_mode': True, 'prompt_tone': False, 'outdoor_temperature': 38.0, 'power_state': True, 'id': '140f00000015', 'target_temperature': 24.0, 'operational_mode': <operational_mode_enum.auto: 1>, 'swing_mode': <swing_mode_enum.Off: 0>, 'indoor_temperature': 26.0, 'eco_mode': False}";
		$ip = $this->getConfiguration('ip');
		$id = $this->getConfiguration('id');
      	$port = $this->getConfiguration('port');
		$get = shell_exec("python3 " . __DIR__ . "/../../resources/get.py $ip $id $port 2>&1");

		$formattedGet = strtolower(preg_replace("/<(?:.*)([0-9]+)>/mU", "$1", $get, -1)); // fix json format
		//$formattedGet = preg_replace("/: ,/", ": false,", $formattedGet, -1); // fix json empty value for swingmode
		$formattedGet = preg_replace("/'/", "\"", $formattedGet, -1); // simple to double quotes

		log::add('mideawifi', 'debug', 'Get Infos = ' . $formattedGet);

		$infos = json_decode($formattedGet, true);

		return $infos;

	}

	public function updateInfos() {
		$infos = self::getInfos();

		self::_updateInfos($infos);
	}

	private function _updateInfos($infos) {
		$this->checkAndUpdateCmd("power_state", 		$infos["power_state"]);
		$this->checkAndUpdateCmd("power_tone", 			$infos["power_tone"]);
		$this->checkAndUpdateCmd("target_temperature", 	$infos["target_temperature"]);
		$this->checkAndUpdateCmd("operational_mode", 	$infos["operational_mode"]);
		$this->checkAndUpdateCmd("fan_speed", 			$infos["fan_speed"]);
		$this->checkAndUpdateCmd("swing_mode", 			$infos["swing_mode"]);
		$this->checkAndUpdateCmd("eco_mode", 			$infos["eco_mode"]);
		$this->checkAndUpdateCmd("turbo_mode", 			$infos["turbo_mode"]);
		$this->checkAndUpdateCmd("indoor_temperature", 	$infos["indoor_temperature"]);
		$this->checkAndUpdateCmd("outdoor_temperature", $infos["outdoor_temperature"]); 
	}

	private function _sendCmdToAC($params) {
		$infos = self::getInfos(); // récup les dernieres infos
		$ip = $this->getConfiguration('ip');
		$id = $this->getConfiguration('id');
		$port = $this->getConfiguration('port');

		$script = "python3 ../../plugins/mideawifi/resources/set.py --ip $ip --id $id --port $port " . $params . " 2>&1";
		log::add("mideawifi", "debug", "script => $script");
		$set = shell_exec($script);
		log::add("mideawifi", "debug", "retour script => $set");

		return true;
	}

	/*public function setPowerState($currentState) {
		$state = !$currentState;
		self::_sendCmdToAC("--power_state $state");

		// MAJ commande info associee
		$this->checkAndUpdateCmd("power_state", $state);
	}*/


	public function allumer() {
		self::_sendCmdToAC("--power_state 1");

		// MAJ commande info associee
		$this->checkAndUpdateCmd("power_state", 1);
	}

	public function eteindre() {
		self::_sendCmdToAC("--power_state 0");

		// MAJ commande info associee
		$this->checkAndUpdateCmd("power_state", 0);
	}

	public function setEcomode() {
		self::_sendCmdToAC("--mode_eco 1");

		// MAJ commande info associee
		$this->checkAndUpdateCmd("eco_mode", 1);
		$this->checkAndUpdateCmd("turbo_mode", 0);
	}

	public function setTurbomode() {
		self::_sendCmdToAC("--mode_turbo 1");

		// MAJ commande info associee
		$this->checkAndUpdateCmd("turbo_mode", 1);
		$this->checkAndUpdateCmd("eco_mode", 0);
	}

	public function setNormalmode() {
		self::_sendCmdToAC("--mode_normal 1");

		// MAJ commandes infos associees
		$this->checkAndUpdateCmd("turbo_mode", 0);
		$this->checkAndUpdateCmd("eco_mode", 0);
	}

	public function setTemperature($consigne) {
		if($consigne < 1 || $consigne > 35)
			return;

		self::_sendCmdToAC("--target_temperature $consigne");

		// MAJ commande info associee
		$this->checkAndUpdateCmd("target_temperature", $consigne);
	}

	public function setMode($mode = 'auto') {
		if(!in_array($mode, ["auto", "cool", "dry", "heat", "fan_only"]))
			return;

		self::_sendCmdToAC("--operational_mode $mode");

		// MAJ commande info associee
		$this->checkAndUpdateCmd("operational_mode", $mode);
	}

	public function setFanspeed($speed = "Auto") {
		if(!in_array($speed, ["Auto", "High", "Medium", "Low", "Silent"]))
			return;
		
		self::_sendCmdToAC("--fan_speed $speed");

		// MAJ commande info associee
		$this->checkAndUpdateCmd("fan_speed", $speed);
	}

	public function setSwingmode($swing = "Both") {
		if(!in_array($swing, ["Off", "Vertical", "Horizontal", "Both"]))
			return;

		self::_sendCmdToAC("--swing_mode $swing");

		// MAJ commande info associee
		$this->checkAndUpdateCmd("swing_mode", $swing);
	}

	public function bipsOn() {
		self::_sendCmdToAC("--prompt_tone 1");
		
		// MAJ commande info associee
		$this->checkAndUpdateCmd("prompt_tone", 1);
	}

	public function bipsOff() {
		self::_sendCmdToAC("--prompt_tone 0");
		
		// MAJ commande info associee
		$this->checkAndUpdateCmd("prompt_tone", 0);
	}
}

class mideawifiCmd extends cmd {
	/*     * *************************Attributs****************************** */


	/*     * ***********************Methode static*************************** */


	/*     * *********************Methode d'instance************************* */

	/*
	* Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
	public function dontRemoveCmd() {
	return true;
	}
	*/

	public function execute($_options = array()) {

		$eqLogic = $this->getEqLogic(); // Récupération de l’eqlogic

		switch ($this->getLogicalId()) {                
			case 'refresh': 
				$eqLogic->updateInfos();
				break;
			/*case 'setPowerState':
				$eqLogic->setPowerState($_options['state']);
				break;*/
			case 'on':
				$eqLogic->allumer();
				break;
			case 'off':
				$eqLogic->eteindre();
				break;
			case 'setEcomode':
				$eqLogic->setEcomode();
				break;
			case 'setTurbomode':
				$eqLogic->setTurbomode();
				break;
			case 'setNormalmode':
				$eqLogic->setNormalmode();
				break;
			case 'setTemperature':
				$eqLogic->setTemperature($_options['text']);
				break;
			case 'setMode':
				$eqLogic->setMode($_options['select']);
				break;
			case 'setFanspeed':
				$eqLogic->setFanspeed($_options['select']);
				break;
			case 'setSwingmode':
				$eqLogic->setSwingmode($_options['select']);
				break;
			case 'bipsOn':
				$eqLogic->bipsOn();
				break;
			case 'bipsOff':
				$eqLogic->bipsOff();
				break;          
			default:
				throw new Error('This should not append!');
				log::add('mideawifi', 'warn', 'Error while executing cmd ' . $this->getLogicalId());
				break;
		}

		//Log::add('mideawifi', 'debug', json_encode($_options));

		return;
	}

	/*     * **********************Getteur Setteur*************************** */

}