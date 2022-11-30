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

<<<<<<< HEAD
=======
require_once dirname(__FILE__) . '/../php/mideawifi.inc.php';
>>>>>>> alpha

class mideawifi extends eqLogic {
	/*     * *************************Attributs****************************** */
	public static function templateWidget() {
		$return = array('action' => array('other' => array()));
		$return['action']['other']['boutonOnOff'] = array(
			'template' => 'tmplicon',
			'display' => array(
				'#icon#' => '<i class=\'icon jeedom-prise\'></i>',
			),
			'replace' => array(
				'#_icon_on_#' => '<i class=\'icon jeedom-prise\'></i>',
				'#_icon_off_#' => '<i class=\'icon fas fa-times\'></i>',
				'#_time_widget_#' => '0'
				)
			);
		$return['action']['other']['swingH'] = array(
			'template' => 'tmplicon',
			'display' => array(
				'#icon#' => '<i class=\'icon fas fa-arrows-alt-h\'></i>',
			),
			'replace' => array(
				'#_icon_on_#' => '<i class=\'icon fas fa-arrows-alt-h\'></i>',
				'#_icon_off_#' => '<i class=\'icon fas fa-times\'></i>',
				'#_time_widget_#' => '0'
				)
			);
        $return['action']['other']['swingV'] = array(
			'template' => 'tmplicon',
			'display' => array(
				'#icon#' => '<i class=\'icon fas fa-arrows-alt-v\'></i>',
			),
			'replace' => array(
				'#_icon_on_#' => '<i class=\'icon fas fa-arrows-alt-v\'></i>',
				'#_icon_off_#' => '<i class=\'icon fas fa-times\'></i>',
				'#_time_widget_#' => '0'
				)
			);
        $return['action']['other']['turbo'] = array(
			'template' => 'tmplicon',
			'display' => array(
				'#icon#' => '<i class=\'icon fas fa-fan\'></i>',
			),
			'replace' => array(
				'#_icon_on_#' => '<i class=\'icon fas fa-fan\'></i>',
				'#_icon_off_#' => '<i class=\'icon fas fa-times\'></i>',
				'#_time_widget_#' => '0'
				)
			);
		$return['action']['other']['sleep'] = array(
			'template' => 'tmplicon',
			'display' => array(
				'#icon#' => '<i class=\'icon fas fa-bed\'></i>',
			),
			'replace' => array(
				'#_icon_on_#' => '<i class=\'icon fas fa-bed\'></i>',
				'#_icon_off_#' => '<i class=\'icon fas fa-times\'></i>',
				'#_time_widget_#' => '0'
				)
			);
		$return['action']['other']['boutonFahrenheit'] = array(
			'template' => 'tmplicon',
			'display' => array(
				'#icon#' => '<i class=\'icon fas fa-check\'></i>',
			),
			'replace' => array(
				'#_icon_on_#' => '<i class=\'icon fas fa-check\'></i>',
				'#_icon_off_#' => '<i class=\'icon fas fa-times\'></i>',
				'#_time_widget_#' => '0'
				)
			);

  
        /*$return['action']['other']['eco'] = array(
			'template' => 'tmplicon',
			'display' => array(
				'#icon#' => '<i class=\'icon fas fa-leaf\'></i>',
			),
			'replace' => array(
				'#_icon_on_#' => '<i class=\'icon fas fa-leaf\'></i>',
				'#_icon_off_#' => '<i class=\'icon fas fa-times\'></i>',
				'#_time_widget_#' => '0'
				)
			);*/

<<<<<<< HEAD
	public static function templateWidget(){
		$return['action']['slider']['temperature_consigne'] = array(
			'template' => 'setTemperature'
		);
		$return['action']['slider']['setHumidityDehumidifierCloud'] = array(
			'template' => 'setHumidityDehumidifierCloud'
		);
		$return['action']['select']['setMode'] = array(
			'template' => 'tmplSelect'
		);
		$return['action']['select']['setFanSpeedDehumidifierCloud'] = array(
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
=======
>>>>>>> alpha
		return $return;
    }
	/*
	* Permet de définir les possibilités de personnalisation du widget (en cas d'utilisation de la fonction 'toHtml' par exemple)
	* Tableau multidimensionnel - exemple: array('custom' => true, 'custom::layout' => false)
	public static $_widgetPossibility = array();
	*/

	/*
	* Permet de crypter/décrypter automatiquement des champs de configuration du plugin
	* Exemple : "param1" & "param2" seront cryptés mais pas "param3"
	public static $_encryptConfigKey = array('param1', 'param2');
	*/

	/*     * ***********************Methode static*************************** */

	/*
	* Fonction exécutée automatiquement toutes les minutes par Jeedom
	public static function cron() {}
	*/

	/*
	* Fonction exécutée automatiquement toutes les 5 minutes par Jeedom*/
	public static function cron5() {
		$eqLogics = self::byType('mideawifi');
		if(count($eqLogics) > 0) {
			// check if midea container is running
			$dockerContainer = eqLogic::byLogicalId('1::mideawifi', 'docker2');
			
			if(is_object($dockerContainer)) {
				$info = $dockerContainer->getCmd(null, 'state');
				if(is_object($info) && $info->execCmd() != "running") {
					log::add('mideawifi', 'info', 'Mideawifi Container state: ' . $info->execCmd() . ' | Starting/restarting now... ');
					$dockerContainer->startDocker();
					sleep(30); // sleep 30sec to let the time to start, then pull infos
					try {
						plugin::byId('docker2');
						docker2::pull(); // refresh infos containers
					} catch (Exception $e) {
					}
					return;
				}
				foreach($eqLogics as $eqLogic) {
					if($eqLogic->getIsEnable() == 1) {
						$eqLogic->createAndUpdateCmd(false);
					}
				} //foreach
			} // object
		} // > 0
	}
	

	/*
	* Fonction exécutée automatiquement toutes les 10 minutes par Jeedom
	public static function cron10() {}
	*/

	/*
	* Fonction exécutée automatiquement toutes les 15 minutes par Jeedom
	public static function cron15() {}
	*/

	/*
	* Fonction exécutée automatiquement toutes les 30 minutes par Jeedom
	public static function cron30() {}
	*/

	/*
	* Fonction exécutée automatiquement toutes les heures par Jeedom
	public static function cronHourly() {}
	*/

	/*
	* Fonction exécutée automatiquement tous les jours par Jeedom
	public static function cronDaily() {}
	*/

<<<<<<< HEAD
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

		// PYTHON3
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

		// PYTHON
		$python = shell_exec('python --version 2>&1');
		log::add('mideawifi', 'debug', 'version python2: ' . $python);
		$arrPython = explode(" ", $python);

		if( $arrPython[0] != "Python") { // python installé ok
			$return['state'] = 'nok';
			log::add('mideawifi', 'debug', 'probleme avec python2');
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

		// PIP
		$pip3 = shell_exec('pip --version 2>&1');
		if(substr($pip3, 0, 3) != "pip") {
			$return['state'] = 'nok';
			log::add('mideawifi', 'debug', 'probleme avec pip');
		} else {
			// midea_inventor_lib
			$midea_inventor_lib = shell_exec('pip show midea_inventor_lib 2>&1');
			if(!$midea_inventor_lib) {
				$return['state'] = 'nok';
				log::add('mideawifi', 'debug', 'probleme avec module pip midea_inventor_lib');
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

			log::add('mideawifi', 'debug', 'update Equipement ' . $eqLogicMideawifi->getName());
		}
	}

=======
	/*     * *********************Méthodes d'instance************************* */

	// Fonction exécutée automatiquement avant la création de l'équipement
>>>>>>> alpha
	public function preInsert() {
	}

	// Fonction exécutée automatiquement après la création de l'équipement
	public function postInsert() {
	}

	// Fonction exécutée automatiquement avant la mise à jour de l'équipement
	public function preUpdate() {
	}

	// Fonction exécutée automatiquement après la mise à jour de l'équipement
	public function postUpdate() {
	}

	// Fonction exécutée automatiquement avant la sauvegarde (création ou mise à jour) de l'équipement
	public function preSave() {
	}

	// Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement
	public function postSave() {
		//log::add('mideawifi', 'debug', 'postSave called');
      	if($this->getIsEnable() == 1)
			self::createAndUpdateCmd();
	}

<<<<<<< HEAD
		$_type = $this->getConfiguration('hexadecimalType', '0xac');
		//$_version = $this->getConfiguration('version', 'v2');

		if($_type == '0xac') // clim
		self::_postSaveAC();

		if($_type == '0xa1') // Déshumidificateur
		self::_postSaveDehumidifier();

		// à la fin, on contact directement léquipement pour récupérer les infos courantes
		$this->updateInfos();

	} // fin postSave()

	// @DEVHELP https://github.com/jeedom/core/blob/06fb34c895b420630bfa9d9317547088b13f81d7/core/config/jeedom.config.php
	private function _postSaveAC() {
		$order = 1;
		// ================================================================================================================= //
		// ===================================================== INFOS ===================================================== //
		// ================================================================================================================= //
=======
	// Fonction exécutée automatiquement avant la suppression de l'équipement
	public function preRemove() {
	}

	// Fonction exécutée automatiquement après la suppression de l'équipement
	public function postRemove() {
	}
>>>>>>> alpha

	/*
	* Permet de crypter/décrypter automatiquement des champs de configuration des équipements
	* Exemple avec le champ "Mot de passe" (password)
	public function decrypt() {
		$this->setConfiguration('password', utils::decrypt($this->getConfiguration('password')));
	}
	public function encrypt() {
		$this->setConfiguration('password', utils::encrypt($this->getConfiguration('password')));
	}
	*/

	/*
	* Permet de modifier l'affichage du widget (également utilisable par les commandes)
	public function toHtml($_version = 'dashboard') {}
	*/

	/*
	* Permet de déclencher une action avant modification d'une variable de configuration du plugin
	* Exemple avec la variable "param3"
	public static function preConfig_param3( $value ) {
		// do some checks or modify on $value
		return $value;
	}
	*/

	/*
	* Permet de déclencher une action après modification d'une variable de configuration du plugin
	* Exemple avec la variable "param3"
	public static function postConfig_param3($value) {
		// no return value
	}
	*/

	/*     * **********************Getteur Setteur*************************** */

public function createAndUpdateCmd($bCreateCmd = true) {
	// create commands with appliance status informations
	$id = 			$this->getConfiguration('id');
	$ip = 			$this->getConfiguration('ip');
	$token = 		$this->getConfiguration('token');
	$key = 			$this->getConfiguration('key');
	$model = 		$this->getConfiguration('model'); // 0xac = AC, 0xa1 = dehumidifier
	
	$accountmail = 	trim(config::byKey('accountmail', 'mideawifi'));
	$accountpass = 	trim(config::byKey('accountpass', 'mideawifi'));
	if(!empty($ip) && !empty($token) && !empty($key)) {
		log::add('mideawifi', 'debug', '[ENDPOINT] /appliance_status_with_token_key');
		$data = curlMideawifiDocker("/appliance_status_with_token_key", array("ipaddress" => $ip, "token" => $token, "key" => $key));
	} else if(!empty($ip) && !empty($accountmail) && !empty($accountpass)) {
		log::add('mideawifi', 'debug', '[ENDPOINT] /appliance_status_with_account');
		$data = curlMideawifiDocker("/appliance_status_with_account", array("ipaddress" => $ip, "accountmail" => $accountmail, "accountpass" => $accountpass));
	} else {
		log::add('mideawifi', 'debug', "Can't update $id, missing:<br/> Either => credentials + ip <br/> Either => token + key + ip");
		return; // can't update
	}
	log::add('mideawifi', 'debug', '[GET STATUS] ' . $data);
	
	$json = json_decode($data);
	
	if($json->status == "nok") {
		log::add('mideawifi', 'debug', 'Error while updating : Midea cloud not responding or error');
		return;
	}
	
	$includeKeys = [
		"online" =>     
		[
			"type" => "info", "subType" => "binary", "name" => "En ligne",
			"order" => 1, "visible" => 1, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "default"] 
		],
		"running" =>    
		[
			"type" => "info", "subType" => "binary", "name" => "Etat",
			"order" => 880, "visible" => 0, "historized" => 1,
			"display" => ["forceReturnLineBefore" => 0],
			"generic_type" => "ENERGY_STATE",
			"configuration" => ['repeatEventManagement' => 'never'],
			"template" => ["dashboard" => "default"] 
		],
		"target" =>     
		[
			"type" => "info", "subType" => "string", "name" => "Température désirée",
			"order" => 12, "visible" => 0, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "default"], "unite" => "°C" ],
		"indoor" =>     
		[
			"type" => "info", "subType" => "numeric", "name" => "Température intérieure",
			"order" => 13, "visible" => 1, "historized" => 1,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "line"], "unite" => "°C" ],
		"outdoor" =>    
		[
			"type" => "info", "subType" => "numeric", "name" => "Température extérieure",
			"order" => 14, "visible" => 1, "historized" => 1,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "line"], "unite" => "°C" ],
		"fan" =>        
		[
			"type" => "info", "subType" => "string", "name" => "Vitesse de ventilation",
			"order" => 15, "visible" => 0, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "default"] 
		],
		"mode" =>       
		[
			"type" => "info", "subType" => "string", "name" => "Mode",
			"order" => 16, "visible" => 0, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "default"] 
		],
		"horizontalswing" => 
		[
			"type" => "info", "subType" => "binary", "name" => "Swing Horizontal",
			"order" => 100, "visible" => 0, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
			"configuration" => ['repeatEventManagement' => 'never'],
			"template" => ["dashboard" => "default"] 
		],
		"verticalswing" => 
		[
			"type" => "info", "subType" => "binary", "name" => "Swing Vertical",
			"order" => 110, "visible" => 0, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
			"configuration" => ['repeatEventManagement' => 'never'],
			"template" => ["dashboard" => "default"] 
		],
		"turbo" =>      
		[
			"type" => "info", "subType" => "binary", "name" => "Turbo",
			"order" => 120, "visible" => 0, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "default"] 
		],
		"sleep" =>      
		[
			"type" => "info", "subType" => "binary", "name" => "Sleep",
			"order" => 121, "visible" => 0, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "default"] 
		],
		"F" =>      	
		[
			"type" => "info", "subType" => "binary", "name" => "Fahrenheit", 
			"order" => 122, "visible" => 0, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "default"] 
		],
		/*"eco" =>      
		[
			"type" => "info", "subType" => "binary", "name" => "Eco",
			"order" => 130, "visible" => 0, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "default"] 
		],*/
		/*"purify" =>     
		[
			"type" => "info", "subType" => "binary", "name" => "Mode Purificateur",
			"order" => 8, "visible" => 1, "historized" => 1,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "default"] 
		],*/
		"error" =>      
		[
			"type" => "info", "subType" => "numeric", "name" => "Erreur", // semble retourner des valeurs numériques et non string, a voir avec Damien
			"order" => 888, "visible" => 0, "historized" => 1,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "line"]  // msie en line pour eviter que ça prenne trop de place
		]
	];
	//{'eco': 0, 'heat_8': 1, 'mode': 1, 'fan_swing': 1, 'electricity': 1, 'filter_reminder': 0, 'strong_fan': 1}
	$includeKeysSupport = 
	[
		/*"fan_swing" =>  
		[
			"type" => "info", "subType" => "binary", "name" => "Position de ventilation",
			"order" => 11, "visible" => 0, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "default"] 
		],*/
		/*"strong_fan" =>  
		[
			"type" => "info", "subType" => "binary", "name" => "Turbo",
			"order" => 9, "visible" => 1, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "default"] 
		],*/
		/*"eco" =>  
		[
			"type" => "info", "subType" => "binary", "name" => "Etat Eco",
			"order" => 17, "visible" => 1, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "default"] 
		],*/
	];
	
	$re2 = '/(.+)(?:$|\n)/i';
	preg_match_all($re2, $json->response, $matches);
	if(!$matches) {
		log::add("mideawifi", "debug", "[GET STATUS] Can't parse informations for " . $id);
	} else {
		foreach($matches[0] as $match) {
			if(strpos($match, '=') === false)
			continue; // first line is long id and no equal, skip
			
			$keyValue = explode("=", $match);
			$key = trim($keyValue[0]);
			$value = trim($keyValue[1]);
			
			if($key == "supports") {
				$value = str_replace("'", '"', $value); //json needs double quotes to be decoded
				$jsonSupportedValue = json_decode($value, true);
				foreach($jsonSupportedValue as $supportedKey => $supportedVal) {
					if(array_key_exists($supportedKey, $includeKeysSupport)) {
						// create cmd INFO
						$cmdLabel = $includeKeysSupport[$supportedKey];
						if($bCreateCmd)
						self::_saveEqLogic($supportedKey, $cmdLabel);
						
						$this->checkAndUpdateCmd($supportedKey, $supportedVal); // update with current value
						
					} //key exists
				}
				continue;
			}
			
			if(array_key_exists($key, $includeKeys)) {
				// create cmd INFO
				$cmdLabel = $includeKeys[$key];
				if($bCreateCmd)
				self::_saveEqLogic($key, $cmdLabel);
				
				$this->checkAndUpdateCmd($key, $value); // update with current value
				
			} //key exists
			
		} // foreach retrivied cmd
	} // matches
	
	// create cmd ACTION
	$cmdActions0xac = [
		"marche" =>			  
		[
			"type" => "action", "subType" => "other", "name" => "Marche",
			"order" => 2, "visible" => 1, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
			"value" => $this->getCmd(null, "running")->getId(),
			"generic_type" => "ENERGY_ON",
			"template" => ["dashboard" => "mideawifi::boutonOnOff", "mobile" => "mideawifi::boutonOnOff"] 
		],
		"arret" =>			  
		[
			"type" => "action", "subType" => "other", "name" => "Arret",
			"order" => 3, "visible" => 1, "historized" => 0,
			"display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 1],
			"value" => $this->getCmd(null, "running")->getId(),
			"generic_type" => "ENERGY_OFF",
			"template" => ["dashboard" => "mideawifi::boutonOnOff", "mobile" => "mideawifi::boutonOnOff"] 
		],
		"horizontalSwingOn" =>  
		[
			"type" => "action", "subType" => "other", "name" => "HorizontalSwing on",
			"order" => 4, "visible" => 1, "historized" => 0,
			"display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
			"value" => $this->getCmd(null, "horizontalswing")->getId(),
			"template" => ["dashboard" => "mideawifi::swingH", "mobile" => "mideawifi::swingH"] 
		],
		"horizontalSwingOff" =>				  
		[
			"type" => "action", "subType" => "other", "name" => "HorizontalSwing off",
			"order" => 5, "visible" => 1, "historized" => 0,
			"display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
			"value" => $this->getCmd(null, "horizontalswing")->getId(),
			"generic_type" => "ENERGY_OFF",
			"template" => ["dashboard" => "mideawifi::swingH", "mobile" => "mideawifi::swingH"] 
		],
		"verticalSwingOn" =>  
		[
			"type" => "action", "subType" => "other", "name" => "VerticalSwing on",
			"order" => 6, "visible" => 1, "historized" => 0,
			"display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
			"value" => $this->getCmd(null, "verticalswing")->getId(),
			"template" => ["dashboard" => "mideawifi::swingV", "mobile" => "mideawifi::swingV"] 
		],
		"verticalSwingOff" =>	  
		[
			"type" => "action", "subType" => "other", "name" => "VerticalSwing off",
			"order" => 7, "visible" => 1, "historized" => 0,
			"display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 1],
			"value" => $this->getCmd(null, "verticalswing")->getId(),
			"template" => ["dashboard" => "mideawifi::swingV", "mobile" => "mideawifi::swingV"] 
		],
		"turboOn" => 			  
		[
			"type" => "action", "subType" => "other", "name" => "Turbo on",
			"order" => 8, "visible" => 1, "historized" => 0,
			"display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
			"value" => $this->getCmd(null, "turbo")->getId(),
			"template" => ["dashboard" => "mideawifi::turbo", "mobile" => "mideawifi::turbo"] 
		],
		"turboOff" =>			  
		[
			"type" => "action", "subType" => "other", "name" => "Turbo off",
			"order" => 9, "visible" => 1, "historized" => 0,
			"display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
			"value" => $this->getCmd(null, "turbo")->getId(),
			"template" => ["dashboard" => "mideawifi::turbo", "mobile" => "mideawifi::turbo"] 
		],
		"comfortSleepOn" => 			  
		[
			"type" => "action", "subType" => "other", "name" => "Sleep on",
			"order" => 10, "visible" => 1, "historized" => 0,
			"display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
			"value" => $this->getCmd(null, "sleep")->getId(),
			"template" => ["dashboard" => "mideawifi::sleep", "mobile" => "mideawifi::sleep"] 
		],
		"comfortSleepOff" =>			  
		[
			"type" => "action", "subType" => "other", "name" => "Sleep off",
			"order" => 11, "visible" => 1, "historized" => 0,
			"display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 1],
			"value" => $this->getCmd(null, "sleep")->getId(),
			"template" => ["dashboard" => "mideawifi::sleep", "mobile" => "mideawifi::sleep"] 
		],
		"fahrenheitOn" => 		
		[
			"type" => "action", "subType" => "other", "name" => "Fahrenheit on",
			"order" => 12, "visible" => 1, "historized" => 0,
			"display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
			"value" => $this->getCmd(null, "F")->getId(),
			"template" => ["dashboard" => "mideawifi::boutonOnOff", "mobile" => "mideawifi::boutonOnOff"] 
		],
		"fahrenheitOff" =>		
		[
			"type" => "action", "subType" => "other", "name" => "Fahrenheit off",
			"order" => 13, "visible" => 1, "historized" => 0,
			"display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 1],
			"value" => $this->getCmd(null, "F")->getId(),
			"template" => ["dashboard" => "mideawifi::boutonFahrenheit", "mobile" => "mideawifi::boutonFahrenheit"] 
		],
		/*"ecoOn" =>  [
			"type" => "action", "subType" => "other", "name" => "Eco on",
			"order" => 10, "visible" => 1, "historized" => 0,
			"display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
			"value" => $this->getCmd(null, "eco")->getId(),
			"template" => ["dashboard" => "mideawifi::eco", "mobile" => "mideawifi::eco"] 
		],
		"ecoOff" =>				  [
			"type" => "action", "subType" => "other", "name" => "Eco off",
			"order" => 11, "visible" => 1, "historized" => 0,
			"display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
			"value" => $this->getCmd(null, "eco")->getId(),
			"template" => ["dashboard" => "mideawifi::eco", "mobile" => "mideawifi::eco"] 
		],*/
		"setTemperature" =>	  
		[
			"type" => "action", "subType" => "slider", "name" => "Température de consigne",
			"order" => 52, "visible" => 1, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 1, "forceReturnLineAfter" => 1],
			"value" => $this->getCmd(null, "target")->getId(),
			"unite" => "°C",
			"configuration" => ["minValue" => 16, "maxValue" => 30],
			"template" => ["dashboard" => "mideawifi::setTemperature", "mobile" => "mideawifi::setTemperature"] 
		],
		/*
		auto = 1
		cool = 2
		dry = 3
		heat = 4
		fan_only = 5*
		*/
		"setMode" => 			  
		[
			"type" => "action", "subType" => "select", "name" => "Mode Opérationnel",
			"order" => 53, "visible" => 1, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 0],
			"configuration" => ["listValue" => "1|Automatique;2|Climatiseur;3|Déshumidificateur;4|Chauffage;5|Ventilation seulement"],
			"value" => $this->getCmd(null, "mode")->getId(),
			"template" => ["dashboard" => "default"] 
		],
		/*
		Auto = 102
		Full = 100 => semble ne pas fonctionner, en tout cas chez julien80
		High = 80
		Medium = 60
		Low = 40
		Silent = 20
		*/
		"setFanSpeed" =>
      		[
			"type" => "action", "subType" => "select", "name" => "Vitesse ventilation",
			"order" => 54, "visible" => 1, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 1, "forceReturnLineAfter" => 1],
			"configuration" => ["listValue" => "102|Automatique;80|Rapide;60|Normal;40|Lent;20|Silencieux"],
			"value" => $this->getCmd(null, "fan")->getId(),
			"template" => ["dashboard" => "default"] 
		],
		"customCommands" =>
      		[
			"type" => "action", "subType" => "other", "name" => "Commandes personnalisées",
			"order" => 999, "visible" => 0, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 1, "forceReturnLineAfter" => 1],
			"template" => ["dashboard" => "default"] 
		],
	];
	
	if($bCreateCmd) {
		if($model == "0xac") {
			foreach($cmdActions0xac as $keyAction => $action) {
				self::_saveEqLogic($keyAction, $action);
			}
		} else if ($model == "0xa1") {
			foreach($cmdActions0xa1 as $keyAction => $action) {
				self::_saveEqLogic($keyAction, $action);
			}
		}
<<<<<<< HEAD
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
		$cmd->setSubType('slider');
		$cmd->setConfiguration('minValue', 16);
		$cmd->setConfiguration('maxValue', 30);
		$cmd->setUnite('°C');
		$cmd->setValue($infoTemp->getId());
		if(version_compare(jeedom::version(), "4", "<")) {
			$cmd->setTemplate('dashboard', 'setTemperature');
			$cmd->setTemplate('mobile', 'setTemperature');
		} else {
			$cmd->setTemplate('dashboard', 'mideawifi::setTemperature');
			$cmd->setTemplate('mobile', 'mideawifi::setTemperature');
		}
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
=======
		
		// create refresh action
>>>>>>> alpha
		$refresh = $this->getCmd(null, 'refresh');
		if (!is_object($refresh)) {
			$refresh = new mideawifiCmd();
			$refresh->setName(__('Rafraichir', __FILE__));
		}
		$refresh->setEqLogic_id($this->getId());
		$refresh->setOrder(999);
		$refresh->setLogicalId('refresh');
		$refresh->setType('action');
		$refresh->setSubType('other');
		$refresh->save();
<<<<<<< HEAD

	}

	private function _postSaveDehumidifier() {
		/*
			[id=xxxxxxxxx type=0xA1 name=Dehumidifier]
			DeHumidification [powerMode=0, mode=3, Filter=False, Water tank=False, Current humidity=73, Current humidity (decimal)=0, Wind speed=60
			Set humidity=60, Set humidity (decimal)=0, ionSetSwitch=0, isDisplay=True, dryClothesSetSwitch=0, Up&Down Swing=0]
		*/

			$order = 1;
		// ================================================================================================================= //
		// ===================================================== INFOS ===================================================== //
		// ================================================================================================================= //
		// etat alimentation
			$infoState = $this->getCmd(null, 'power_mode');
			if (!is_object($infoState)) {
				$infoState = new mideawifiCmd();
				$infoState->setName(__('Etat courant', __FILE__));
			}
			$infoState->setOrder($order++);
			$infoState->setLogicalId('power_mode');
			$infoState->setEqLogic_id($this->getId());
			$infoState->setType('info');
			$infoState->setSubType('binary');
			$infoState->setIsVisible(1);
			$infoState->setIsHistorized(1);
			$infoState->setDisplay('forceReturnLineBefore', false);
			$infoState->save();

		// mode de fonctionnement (1:TARGET_MODE, 2:CONTINOUS_MODE, 3:SMART_MODE, 4:DRYER_MODE)
			$infoMode = $this->getCmd(null, 'mode');
			if (!is_object($infoMode)) {
				$infoMode = new mideawifiCmd();
				$infoMode->setName(__('Mode', __FILE__));
			}
			$infoMode->setOrder($order++);
			$infoMode->setLogicalId('mode');
			$infoMode->setEqLogic_id($this->getId());
			$infoMode->setType('info');
			$infoMode->setSubType('string');
			$infoMode->setIsVisible(0);
			$infoMode->setIsHistorized(0);
			$infoMode->setDisplay('forceReturnLineBefore', false);
			$infoMode->save();

		// alerte filtre à nettoyer/changer
			$infoFilter = $this->getCmd(null, 'filter');
			if (!is_object($infoFilter)) {
				$infoFilter = new mideawifiCmd();
				$infoFilter->setName(__('Maintenance filtre nécessaire', __FILE__));
			}
			$infoFilter->setOrder($order++);
			$infoFilter->setLogicalId('filter');
			$infoFilter->setEqLogic_id($this->getId());
			$infoFilter->setType('info');
			$infoFilter->setSubType('binary');
			$infoFilter->setIsVisible(0);
			$infoFilter->setIsHistorized(0);
			$infoFilter->setDisplay('forceReturnLineBefore', false);
			$infoFilter->save();

		// alerte récupérateur d'eau
			$infoWaterTank = $this->getCmd(null, 'water_tank');
			if (!is_object($infoWaterTank)) {
				$infoWaterTank = new mideawifiCmd();
				$infoWaterTank->setName(__('Récupérateur d\'eau plein', __FILE__));
			}
			$infoWaterTank->setOrder($order++);
			$infoWaterTank->setLogicalId('water_tank');
			$infoWaterTank->setEqLogic_id($this->getId());
			$infoWaterTank->setType('info');
			$infoWaterTank->setSubType('binary');
			$infoWaterTank->setIsVisible(0);
			$infoWaterTank->setIsHistorized(0);
			$infoWaterTank->setDisplay('forceReturnLineBefore', false);
			$infoWaterTank->save();

		// humidité actuelle
			$infoHumidity = $this->getCmd(null, 'humidity');
			if (!is_object($infoHumidity)) {
				$infoHumidity = new mideawifiCmd();
				$infoHumidity->setName(__('Humidité actuelle', __FILE__));
			}
			$infoHumidity->setOrder($order++);
			$infoHumidity->setLogicalId('humidity');
			$infoHumidity->setEqLogic_id($this->getId());
			$infoHumidity->setType('info');
			$infoHumidity->setSubType('string');
			$infoHumidity->setUnite('%');
			$infoHumidity->setIsVisible(1);
			$infoHumidity->setIsHistorized(1);
			$infoHumidity->setDisplay('forceReturnLineBefore', false);
			$infoHumidity->save();

		// humidité désirée
			$infoTargetHumidity = $this->getCmd(null, 'targetHumidity');
			if (!is_object($infoTargetHumidity)) {
				$infoTargetHumidity = new mideawifiCmd();
				$infoTargetHumidity->setName(__('Humidité désirée', __FILE__));
			}
			$infoTargetHumidity->setOrder($order++);
			$infoTargetHumidity->setLogicalId('targetHumidity');
			$infoTargetHumidity->setEqLogic_id($this->getId());
			$infoTargetHumidity->setType('info');
			$infoTargetHumidity->setSubType('string');
			$infoTargetHumidity->setUnite('%');
			$infoTargetHumidity->setIsVisible(0);
			$infoTargetHumidity->setIsHistorized(0);
			$infoTargetHumidity->setDisplay('forceReturnLineBefore', false);
			$infoTargetHumidity->save();

		// vitesse du vent
			$infoWindSpeed = $this->getCmd(null, 'wind_speed');
			if (!is_object($infoWindSpeed)) {
				$infoWindSpeed = new mideawifiCmd();
				$infoWindSpeed->setName(__('Vitesse du vent', __FILE__));
			}
			$infoWindSpeed->setOrder($order++);
			$infoWindSpeed->setLogicalId('wind_speed');
			$infoWindSpeed->setEqLogic_id($this->getId());
			$infoWindSpeed->setType('info');
			$infoWindSpeed->setSubType('string');
			//$infoWindSpeed->setUnite('%');
			$infoWindSpeed->setIsVisible(0);
			$infoWindSpeed->setIsHistorized(0);
			$infoWindSpeed->setDisplay('forceReturnLineBefore', false);
			$infoWindSpeed->save();

		// autres: Set humidity=60, Set 
# humidity (decimal)=0, ionSetSwitch=0, isDisplay=True, dryClothesSetSwitch=0, Up&Down Swing=0]


		// ================================================================================================================= //
		// ==================================================== ACTIONS ==================================================== //
		// ================================================================================================================= //

			$cmd = $this->getCmd('action', 'onDehumidifierCloud');
			if (!is_object($cmd)) {
				$cmd = new mideawifiCmd();
				$cmd->setName(__('Allumer', __FILE__));
			}
			$cmd->setOrder($order++);
			$cmd->setIsVisible(1);
			$cmd->setLogicalId('onDehumidifierCloud');
			$cmd->setEqLogic_id($this->getId());
			$cmd->setType('action');
			$cmd->setSubType('other');
			$cmd->setDisplay('generic_type', 'ENERGY_ON');
			$cmd->setDisplay('forceReturnLineBefore', true);
		//$info->setDisplay('forceReturnLineAfter', true);
			$cmd->save();

		// Extinction clim
			$cmd = $this->getCmd('action', 'offDehumidifierCloud');
			if (!is_object($cmd)) {
				$cmd = new mideawifiCmd();
				$cmd->setName(__('Eteindre', __FILE__));
			}
			$cmd->setOrder($order++);
			$cmd->setIsVisible(1);
			$cmd->setLogicalId('offDehumidifierCloud');
			$cmd->setEqLogic_id($this->getId());
			$cmd->setType('action');
			$cmd->setSubType('other');
			$cmd->setDisplay('generic_type', 'ENERGY_OFF');
		//$cmd->setDisplay('forceReturnLineBefore', true);
			$cmd->setDisplay('forceReturnLineAfter', true);
			$cmd->save();

		// Changement du mode
			$cmd = $this->getCmd('action', 'setModeDehumidifierCloud');
			if (!is_object($cmd)) {
				$cmd = new mideawifiCmd();
				$cmd->setName(__('Choix du Mode', __FILE__));
			}           
			$cmd->setOrder($order++);
			$cmd->setIsVisible(1);
			$cmd->setLogicalId('setModeDehumidifierCloud');
			$cmd->setEqLogic_id($this->getId());
			$cmd->setType('action');
			$cmd->setSubType('select');
			$cmd->setValue($infoMode->getId());
			$cmd->setConfiguration('listValue', "2|continu;3|intelligent;4|déshumidificateur"); //1|ciblé;
			$cmd->setTemplate('dashboard', 'mideawifi::tmplSelect');
			$cmd->setDisplay('forceReturnLineBefore', true);
			$cmd->save();

			// Changement humidité
			$cmd = $this->getCmd('action', 'setHumidityDehumidifierCloud');
			if (!is_object($cmd)) {
				$cmd = new mideawifiCmd();
				$cmd->setName(__('Humidité demandée', __FILE__));
			}
			$cmd->setOrder($order++);
			$cmd->setIsVisible(1);
			$cmd->setLogicalId('setHumidityDehumidifierCloud');
			$cmd->setEqLogic_id($this->getId());
			$cmd->setType('action');
			$cmd->setSubType('slider');
			$cmd->setConfiguration('minValue', 30);
			$cmd->setConfiguration('maxValue', 70);
			$cmd->setUnite('%');
			$cmd->setValue($infoTargetHumidity->getId());
			if(version_compare(jeedom::version(), "4", "<")) {
				$cmd->setTemplate('dashboard', 'setHumidityDehumidifierCloud');
				$cmd->setTemplate('mobile', 'setHumidityDehumidifierCloud');
			} else {
				$cmd->setTemplate('dashboard', 'mideawifi::setHumidityDehumidifierCloud');
				$cmd->setTemplate('mobile', 'mideawifi::setHumidityDehumidifierCloud');
			}
			$cmd->setDisplay('forceReturnLineBefore', true);
			$cmd->save();

		// Vitesse ventilateur
			$cmd = $this->getCmd('action', 'setFanSpeedDehumidifierCloud');
			if (!is_object($cmd)) {
				$cmd = new mideawifiCmd();
				$cmd->setName(__('Vitesse de ventilation', __FILE__));
			}
			$cmd->setOrder($order++);
			$cmd->setIsVisible(1);
			$cmd->setLogicalId('setFanSpeedDehumidifierCloud');
			$cmd->setEqLogic_id($this->getId());
			$cmd->setType('action');
			$cmd->setSubType('select');
			$cmd->setValue($infoWindSpeed->getId());
			$cmd->setConfiguration('listValue', "80|Rapide;60|Moyenne;40|Silencieuse");
			$cmd->setTemplate('dashboard', 'mideawifi::tmplSelect');
			$cmd->setDisplay('forceReturnLineBefore', true);
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

		}

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
	/*public static function postConfig_timeout() {
		// @TODO
		log::add('mideawifi', 'debug', 'timeout modifié');
	}*/

=======
	} // create cmds
	
} //createCmd()


private function _saveEqLogic($key, $cmdLabel) {
	$newCmd = $this->getCmd(null, $key);
	
	if (!is_object($newCmd)) {
		$newCmd = new mideawifiCmd();
		$newCmd->setName(__($cmdLabel["name"], __FILE__));
	}
	$newCmd->setLogicalId($key);
	$newCmd->setEqLogic_id($this->getId());
	$newCmd->setType($cmdLabel["type"]);
	$newCmd->setSubType($cmdLabel["subType"]);
	$newCmd->setOrder($cmdLabel["order"]);
	$newCmd->setIsVisible($cmdLabel["visible"]);
	$newCmd->setIsHistorized($cmdLabel["historized"]);
	
	if(array_key_exists("template", $cmdLabel)) {
		foreach($cmdLabel["template"] as $templateKey => $templateVal) {
			$newCmd->setTemplate($templateKey, $templateVal);
		}
	}
	if(array_key_exists("display", $cmdLabel)) {
		foreach($cmdLabel["display"] as $displayKey => $displayVal) {
			$newCmd->setDisplay($displayKey, $displayVal);
		}
	}
	if(array_key_exists("configuration", $cmdLabel)) {
		foreach($cmdLabel["configuration"] as $configKey => $configVal) {
			$newCmd->setConfiguration($configKey, $configVal);
		}
	}
>>>>>>> alpha
	
	if(isset($cmdLabel["unite"]))
	$newCmd->setUnite($cmdLabel['unite']);
	if(isset($cmdLabel["generic_type"]))
	$newCmd->setGeneric_type($cmdLabel['generic_type']);
	if(isset($cmdLabel["value"]))
	$newCmd->setValue($cmdLabel['value']);
	
	$newCmd->save();
} // _saveEqLogic

public function sendCmd($cmd, $val = "") {
	// create commands with appliance status informations
	$id = 			$this->getConfiguration('id');
	$ip = 			$this->getConfiguration('ip');
	$token = 		$this->getConfiguration('token');
	$key = 			$this->getConfiguration('key');
	$model = 		$this->getConfiguration('model'); // 0xac = AC, 0xa1 = dehumidifier
	
	$accountmail = 	trim(config::byKey('accountmail', 'mideawifi'));
	$accountpass = 	trim(config::byKey('accountpass', 'mideawifi'));
	
	$cmdLabel = "";
	$cmdValue = "";
	switch ($cmd) {
		case "marche":
			$cmdLabel = "running";
			$cmdValue = "1";
			break;
		case "arret":
			$cmdLabel = "running";
			$cmdValue = "0";
			break;
		case "setTemperature":
			$cmdLabel = "target-temperature";
			$cmdValue = strval(floor($val * 2) / 2); // 0.5 floor
			self::createAndUpdateCmd(false); // update datas before sending new vals
			$this->checkAndUpdateCmd('target', $cmdValue);
			break;
		case "setMode":
			$cmdLabel = "mode";
			$cmdValue = $val; 
			break;
		case "setFanSpeed":
			$cmdLabel = "fan-speed";
			$cmdValue = $val; 
			break;
		case "horizontalSwingOn":
			$cmdLabel = "horizontal-swing";
			$cmdValue = "1";
			break;
		case "horizontalSwingOff":
			$cmdLabel = "horizontal-swing";
			$cmdValue = "0";
			break;
		case "verticalSwingOn":
			$cmdLabel = "vertical-swing";
			$cmdValue = "1";
			break;
		case "verticalSwingOff":
			$cmdLabel = "vertical-swing";
			$cmdValue = "0";
			break;
		case "turboOn":
			$cmdLabel = "turbo";
			$cmdValue = "1";
			break;
		case "turboOff":
			$cmdLabel = "turbo";
			$cmdValue = "0";
			break;
		case "comfortSleepOn":
			$cmdLabel = "comfort-sleep";
			$cmdValue = "1";
			break;
		case "comfortSleepOff":
			$cmdLabel = "comfort-sleep";
			$cmdValue = "0";
			break;
		case "fahrenheitOn":
			$cmdLabel = "fahrenheit";
			$cmdValue = "1";
			break;
		case "fahrenheitOff":
			$cmdLabel = "fahrenheit";
			$cmdValue = "0";
			break;
      		case "customCommands":
        		break;
		/*case "ecoOn":
			$cmdLabel = "eco-mode";
			$cmdValue = "1";
			break;
		case "ecoOff":
			$cmdLabel = "eco-mode";
			$cmdValue = "0";
			break;*/
		default:
		throw new Error('This should not append!');
		log::add('mideawifi', 'warn', 'Error while executing cmd ' . $this->getLogicalId());
		return;
	}
	
  	$additionalParams = "";

<<<<<<< HEAD
	// Hash le mot de passe du cloud midea
	public static function preConfig_passCloud($value) {
		return (!empty($value)) ? hash('sha256', $value) : "";
	}

	/*     * **********************Getteur Setteur*************************** */
	public function getInfosAC() {
		// 2 trames dexemple
		//$get = "{'name': '192.168.102.79', 'fan_speed': <fan_speed_enum.Low: 40>, 'turbo_mode': False, 'prompt_tone': False, 'outdoor_temperature': 33.0, 'power_state': True, 'id': '140f00000014', 'target_temperature': 28.0, 'operational_mode': <operational_mode_enum.cool: 2>, 'swing_mode': <swing_mode_enum.Off: 0>, 'indoor_temperature': 28.0, 'eco_mode': False}";
		//$get = "{'name': '192.168.102.80', 'fan_speed': <fan_speed_enum.High: 80>, 'turbo_mode': True, 'prompt_tone': False, 'outdoor_temperature': 38.0, 'power_state': True, 'id': '140f00000015', 'target_temperature': 24.0, 'operational_mode': <operational_mode_enum.auto: 1>, 'swing_mode': <swing_mode_enum.Off: 0>, 'indoor_temperature': 26.0, 'eco_mode': False}";
		$ip = $this->getConfiguration('ip');
		$id = $this->getConfiguration('id');
		$port = $this->getConfiguration('port');
		$get = shell_exec("python3 " . __DIR__ . "/../../resources/getAC.py $ip $id $port 2>&1");

		$formattedGet = strtolower(preg_replace("/<(?:.*)([0-9]+)>/mU", "$1", $get, -1)); // fix json format
		//$formattedGet = preg_replace("/: ,/", ": false,", $formattedGet, -1); // fix json empty value for swingmode
		$formattedGet = preg_replace("/'/", "\"", $formattedGet, -1); // simple to double quotes

		log::add('mideawifi', 'debug', 'Get Infos AC = ' . $formattedGet);

		$infos = json_decode($formattedGet, true);

		return $infos;
=======
    if($cmd == "customCommands") {
      $command = trim($val);
      $cmdLabel = substr($command, -2); // just to display cmd on debug log
    } else {
      $command = "--$cmdLabel $cmdValue ";
      log::add('mideawifi', 'debug', "commande à envoyer: $command");

      if($cmdLabel == "" || $cmdValue == "")
      return;
>>>>>>> alpha

      // when set a new lower temp target, a bug occurs. forcing eco-mode state fix that
      if($cmd == "setTemperature") {
          $currentMode = $this->getCmd(null, "mode")->execCmd();
          if($currentMode == 2) { // eco mode exists only in cooling mode
              $cmdEco = $this->getCmd(null, "eco");
              $isEco = $cmdEco->execCmd();
              $additionalParams = ($isEco) ? "--eco-mode 1" : "--eco-mode 0";
          } else {
              $additionalParams = "--eco-mode 0";
          }
      }
    }
	
	if(!empty($ip) && !empty($token) && !empty($key)) {
		log::add('mideawifi', 'debug', '[ENDPOINT] /set_appliance_attribute');
		log::add('mideawifi', 'debug', 'midea-beautiful-air-cli set --ip ' . $ip . ' --token ' . $token . ' --key ' . $key . ' --' . $cmdLabel . ' ' . $cmdValue . $additionalParams);
		$data = curlMideawifiDocker("/set_appliance_attribute", array("ipaddress" => $ip, "token" => $token, "key" => $key, "commands" => $command . $additionalParams));
		log::add('mideawifi', 'debug', "Data => " . $data);
	} else if(!empty($ip) && !empty($accountmail) && !empty($accountpass)) {
		log::add('mideawifi', 'debug', '[ENDPOINT] /set_appliance_attribute_with_account');
		log::add('mideawifi', 'debug', 'midea-beautiful-air-cli set --ip ' . $ip . ' --account ' . $accountmail . ' --password ****** --' . $cmdLabel . ' ' . $cmdValue);
		$data = curlMideawifiDocker("/set_appliance_attribute_with_account", array("ipaddress" => $ip, "accountmail" => $accountmail, "accountpass" => $accountpass, "commands" => $command . $additionalParams));
		log::add('mideawifi', 'debug', "Data => " . $data);
	} else {
		log::add('mideawifi', 'debug', "Can't update $id, missing:<br/> Either => credentials + ip <br/> Either => token + key + ip");
		return; // can't update
	}
<<<<<<< HEAD

	public function getInfosDehumidifier() {
		// exemple de données en retour:
		// DeHumidification [powerMode=0, mode=3, Filter=False, Water tank=False, Current humidity=73, Current humidity (decimal)=0, Wind speed=60, Set humidity=60, 
		// Set humidity (decimal)=0, ionSetSwitch=0, isDisplay=True, dryClothesSetSwitch=0, Up&Down Swing=0]
		$id = $this->getConfiguration('id');
		$login = config::byKey('mailCloud', 'mideawifi');
		$pass = config::byKey('passCloud', 'mideawifi');
		
		$get = shell_exec("python " . __DIR__ . "/../../resources/getDehumidifier.py $id $login $pass 2>&1");

		$formattedGet = strtolower(preg_replace("/<(?:.*)([0-9]+)>/mU", "$1", $get, -1)); // fix json format
		//$formattedGet = preg_replace("/: ,/", ": false,", $formattedGet, -1); // fix json empty value for swingmode
		$formattedGet = preg_replace("/'/", "\"", $formattedGet, -1); // simple to double quotes

		log::add('mideawifi', 'debug', 'Get Infos Dehumidifier= ' . $formattedGet);

		return json_decode($formattedGet, true);
	}

	public function updateInfos() {

		//log::add('mideawifi','debug', "enter updateInfos()");
		$currentTypeEq = $this->getConfiguration('hexadecimalType');
		
		if ($currentTypeEq == '0xac') { //clim
			$infos = self::getInfosAC();
			self::_updateInfosAC($infos);
		} elseif ($currentTypeEq == '0xa1') { // déshumidificateur
			$infos = self::getInfosDehumidifier();
			//log::add('mideawifi','debug', "test info" . $infos['windspeed']);
			if(gettype($infos) == "string") { // si on a un message d'erreur du cloud au lieu de l'array contenant les valeurs récupérées
				log::add('mideawifi', 'error', 'Merci de vérifier les identifiants de connexion cloud sur la page de configuration.');
			} else {
				self::_updateInfosDehumidifier($infos);
			}
	}

}

private function _updateInfosAC($infos) {
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

private function _updateInfosDehumidifier($infos) {
	$this->checkAndUpdateCmd("power_mode", 			$infos["powermode"]);
	$this->checkAndUpdateCmd("mode", 				$infos["mode"]);
	$this->checkAndUpdateCmd("filter", 				$infos["filter"]);
	$this->checkAndUpdateCmd("water_tank", 			$infos["watertank"]);
	$this->checkAndUpdateCmd("humidity", 			$infos["currenthumidity"]);
	$this->checkAndUpdateCmd("wind_speed", 			$infos["windspeed"]);
		// isDisplay pas créé
}

//cmd info power_mode mode filter water_tank humidity wind_speed
private function _sendCmdToAC($params) {
	log::add('mideawifi', 'debug', "paramAC: ". $params);
		$infos = self::getInfosAC(); // récup les dernieres infos
		$ip = $this->getConfiguration('ip');
		$id = $this->getConfiguration('id');
		$port = $this->getConfiguration('port');

		$script = "python3 ../../plugins/mideawifi/resources/setAC.py --ip $ip --id $id --port $port " . $params . " 2>&1";
		log::add("mideawifi", "debug", "scriptAC => $script");
		$set = shell_exec($script);
		log::add("mideawifi", "debug", "retour scriptAC => $set");

		return true;
	}


	private function _sendCmdToDehumidifier($params) {
		log::add('mideawifi', 'debug', "paramDe: ". $params);
		$infos = self::getInfosDehumidifier(); // récup les dernieres infos
		$id = $this->getConfiguration('id');
		$login = config::byKey('mailCloud', 'mideawifi');
		$pass = config::byKey('passCloud', 'mideawifi');

		//$script = "python ../../plugins/mideawifi/resources/setDehumidifier.py --id $id --login $login --password $pass " .
        //$script = "python " . __DIR__ . "/../../plugins/mideawifi/resources/setDehumidifier.py --id $id --login $login --password $pass " .
        $script = "python " . __DIR__ . "/../../resources/setDehumidifier.py --id $id --login $login --password $pass " .
          
		$params . " 2>&1";
		log::add("mideawifi", "debug", "scriptDehumidifier => $script");
		$set = shell_exec($script);
		log::add("mideawifi", "debug", "retour scriptDehumidifier => $set");

		return true;
	}

	// ####################################################################//
	// COMMANDES ACTIONS AC LOCAL
	// ####################################################################//
	public function allumer() {
		self::_sendCmdToAC("--power_state 1");
		$this->checkAndUpdateCmd("power_state", 1);
	}

	public function eteindre() {
		self::_sendCmdToAC("--power_state 0");
		$this->checkAndUpdateCmd("power_state", 0);
	}

	public function setEcomode() {
		self::_sendCmdToAC("--mode_eco 1");
		$this->checkAndUpdateCmd("eco_mode", 1);
		$this->checkAndUpdateCmd("turbo_mode", 0);
	}

	public function setTurbomode() {
		self::_sendCmdToAC("--mode_turbo 1");
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
		$this->checkAndUpdateCmd("target_temperature", $consigne);
	}

	public function setMode($mode = "auto") {
		if(!in_array($mode, ["auto", "cool", "dry", "heat", "fan_only"]))
			return;

		self::_sendCmdToAC("--operational_mode $mode");
		$this->checkAndUpdateCmd("operational_mode", $mode);
	}

	public function setFanspeed($speed = "Auto") {
		if(!in_array($speed, ["Auto", "High", "Medium", "Low", "Silent"]))
			return;
		
		self::_sendCmdToAC("--fan_speed $speed");
		$this->checkAndUpdateCmd("fan_speed", $speed);
	}

	public function setSwingmode($swing = "Both") {
		if(!in_array($swing, ["Off", "Vertical", "Horizontal", "Both"]))
			return;

		self::_sendCmdToAC("--swing_mode $swing");
		$this->checkAndUpdateCmd("swing_mode", $swing);
	}

	public function bipsOn() {
		self::_sendCmdToAC("--prompt_tone 1");

		$this->checkAndUpdateCmd("prompt_tone", 1);
	}

	public function bipsOff() {
		self::_sendCmdToAC("--prompt_tone 0");

		$this->checkAndUpdateCmd("prompt_tone", 0);
	}

	// ####################################################################//
	// COMMANDES ACTIONS DEHUMIDIFIER CLOUD
	// ####################################################################//
	public function allumerDehumidifierCloud() {
		log::add('mideawifi', 'debug', 'fonction allumage Dehumidifier');
		self::_sendCmdToDehumidifier("--power_mode 1");
		$this->checkAndUpdateCmd("power_mode", 1);
	}

	public function eteindreDehumidifierCloud() {
		self::_sendCmdToDehumidifier("--power_mode 0");
		$this->checkAndUpdateCmd("power_mode", 0);
	}

	public function setModeDehumidifierCloud($mode) {
		//1: TARGET_MODE, 2: CONTINUOUS_MODE, 3: SMART_MODE, 4: DRYER_MODE
		if($mode < 1 || $mode > 4)
			return;

		self::_sendCmdToDehumidifier("--mode $mode");
		$this->checkAndUpdateCmd("mode", $mode);
	}

	public function setHumidityDehumidifierCloud($target) {
		log::add('mideawifi', 'debug', 'debug target humidity' . $target);
		if($target < 30 || $target > 70)
			return;

		self::_sendCmdToDehumidifier("--target_humidity $target");
		$this->checkAndUpdateCmd("targetHumidity", $target);
	}

	public function setFanSpeedDehumidifierCloud($speed) {
		// [0..100] (silent:40, medium:60, high:80)
		if(!in_array($speed, [40, 60, 80]))
			return;

		self::_sendCmdToDehumidifier("--wind_speed $speed");
		$this->checkAndUpdateCmd("wind_speed", $speed);
	}
	//cmd info power_mode mode filter water_tank humidity wind_speed
=======
	
	$json = json_decode($data);
	
	if($json->status == "nok")
	log::add('mideawifi', 'debug', 'Error while sending : Midea cloud not responding or error');
	
} // sendCmd
>>>>>>> alpha
}

class mideawifiCmd extends cmd {
	
	// Exécution d'une commande
	public function execute($_options = array()) {
		
		$eqLogic = $this->getEqLogic(); // Récupération de l’eqlogic
<<<<<<< HEAD

		log::add("mideawifi", "debug", "LogicalId action => " . $this->getLogicalId());
		Log::add('mideawifi', 'debug', '$_options[] traité: ' . json_encode($_options));

		switch ($this->getLogicalId()) {
			case 'refresh': 
			$eqLogic->updateInfos();
			break;

			// ####################################################################//
			// COMMANDES ACTIONS LOCAL AC
			// ####################################################################//
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
			$temp = isset($_options['text']) ? $_options['text'] : $_options['slider'];
			$eqLogic->setTemperature($temp);
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

			// ####################################################################//
			// COMMANDES CLOUD DEHUMIDIFIER
			// ####################################################################//
			case 'onDehumidifierCloud':
			log::add('mideawifi','debug', "Lancement script Set Dehumidifier");
			$eqLogic->allumerDehumidifierCloud();
			break;
			case 'offDehumidifierCloud':
			$eqLogic->eteindreDehumidifierCloud();
			break;
			case 'setModeDehumidifierCloud':
			$eqLogic->setModeDehumidifierCloud($_options['select']);
			break;
			case 'setHumidityDehumidifierCloud':
			$temp = isset($_options['text']) ? $_options['text'] : $_options['slider'];
			$eqLogic->setHumidityDehumidifierCloud($temp);
			break;
			case 'setFanSpeedDehumidifierCloud':
			$eqLogic->setFanSpeedDehumidifierCloud($_options['select']);
			break;

			default:
			throw new Error('This should not append!');
			log::add('mideawifi', 'warn', 'Error while executing cmd ' . $this->getLogicalId());
			break;
		}

=======
		log::add("mideawifi", "debug", "LogicalId action => " . $this->getLogicalId());
		Log::add('mideawifi', 'debug', '$_options[] traité: ' . json_encode($_options));
		
		if($eqLogic->getIsEnable() == 0)
>>>>>>> alpha
		return;
		
		switch ($this->getLogicalId()) {
			case 'refresh': 
				$eqLogic->createAndUpdateCmd(false);
				break;
				
			case 'marche':
				$eqLogic->checkAndUpdateCmd('running', 1);
				$eqLogic->sendCmd('marche');
				break;
					
			case 'arret':
				$eqLogic->checkAndUpdateCmd('running', 0);
				$eqLogic->sendCmd('arret');
				break;
						
			case 'setTemperature':
				$temperature = isset($_options['text']) ? $_options['text'] : $_options['slider'];  // scenario compatibility
				if($temperature < 16 || $temperature > 30)
				return;
				$eqLogic->sendCmd('setTemperature', $temperature);
				break;
							
			case 'setMode':
				$mode = isset($_options['select']) ? $_options['select'] : $_options['slider']; // scenario compatibility
				$eqLogic->checkAndUpdateCmd('mode', $mode);
				$eqLogic->sendCmd('setMode', $mode);
				break;
								
			case 'setFanSpeed':
				$fanSpeed = isset($_options['select']) ? $_options['select'] : $_options['slider']; // scenario compatibility
				if($_options['checkAutoFan'] == 1 || $fanSpeed == 102) {
					$fanSpeed = 102;
				} else {
					$fanSpeed = ceil($fanSpeed / 20) * 20; // round to 20
				}
				
				$eqLogic->checkAndUpdateCmd('fan', $fanSpeed);
				$eqLogic->sendCmd('setFanSpeed', $fanSpeed);
				break;
									
			case 'horizontalSwingOn':
				$eqLogic->checkAndUpdateCmd('horizontalswing', 1);
				$eqLogic->sendCmd('horizontalSwingOn');
				break;
										
			case 'horizontalSwingOff':
				$eqLogic->checkAndUpdateCmd('horizontalswing', 0);
				$eqLogic->sendCmd('horizontalSwingOff');
				break;
											
			case 'verticalSwingOn':
				$eqLogic->checkAndUpdateCmd('verticalswing', 1);
				$eqLogic->sendCmd('verticalSwingOn');
				break;
												
			case 'verticalSwingOff':
				$eqLogic->checkAndUpdateCmd('verticalswing', 0);
				$eqLogic->sendCmd('verticalSwingOff');
				break;
													
			case 'turboOn':
				$eqLogic->checkAndUpdateCmd('turbo', 1);
				$eqLogic->sendCmd('turboOn');
				break;
														
			case 'turboOff':
				$eqLogic->checkAndUpdateCmd('turbo', 0);
				$eqLogic->sendCmd('turboOff');
				break;
															
			case 'comfortSleepOn':
				$eqLogic->checkAndUpdateCmd('sleep', 1);
				$eqLogic->sendCmd('comfortSleepOn');
				break;
																
			case 'comfortSleepOff':
				$eqLogic->checkAndUpdateCmd('sleep', 0);
				$eqLogic->sendCmd('comfortSleepOff');
				break;
																	
			case 'fahrenheitOn':
				$eqLogic->checkAndUpdateCmd('F', 1);
				$eqLogic->sendCmd('fahrenheitOn');
				break;
																		
			case 'fahrenheitOff':
				$eqLogic->checkAndUpdateCmd('F', 0);
				$eqLogic->sendCmd('fahrenheitOff');
				break;
          		case 'customCommands':
            			$eqLogic->sendCmd('customCommands', $_options['text']);
            			break;
			/*case 'ecoOn':
				log::add('mideawifi', 'debug', "Action ecoOn");
				$currentMode = $eqLogic->getCmd(null, "mode")->execCmd(); // @TODO verif si nécessaire de mettre ->execCmd() ???
				if($currentMode != 2) //eco mode exists only in cooling mode
				return;
				
				$eqLogic->checkAndUpdateCmd('eco', 1);
				$eqLogic->sendCmd('ecoOn');
				break;
																				
			case 'ecoOff':
				log::add('mideawifi', 'debug', "Action ecoOff");
				$currentMode = $eqLogic->getCmd(null, "mode")->execCmd(); // @TODO verif si nécessaire de mettre ->execCmd() ???
				if($currentMode != 2) //eco mode exists only in cooling mode
				return;
				
				$eqLogic->checkAndUpdateCmd('eco', 0);
				$eqLogic->sendCmd('ecoOff');
				break;*/
				
			default:
			throw new Error('This should not append!');
			log::add('mideawifi', 'warn', 'Error while executing cmd ' . $this->getLogicalId());
			break;
		}
																				
	return;
																				
	}
																			
	public function getWidgetTemplateCode($_version = 'dashboard', $_clean = true, $_widgetName = '') {
		$eqLogic = $this->getEqLogic();
		$data = null;
		if ($_version != 'scenario') 
		return parent::getWidgetTemplateCode($_version, $_clean, $_widgetName);
		
		if ($this->getLogicalId() == 'setFanSpeed')
		$data = getTemplate('core', 'scenario', 'cmd.setFanSpeed', 'mideawifi');
		
		$currentValue = "";
		if (!is_null($data)) {
			if (version_compare(jeedom::version(),'4.2.0','>=')) {
				if(!is_array($data)) return array('template' => $data, 'isCoreWidget' => false);
			} else return $data;
		}
		
		return parent::getWidgetTemplateCode($_version, $_clean, $_widgetName);
	}

/*     * **********************Getteur Setteur*************************** */

}