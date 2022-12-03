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

require_once dirname(__FILE__) . '/../php/mideawifi.inc.php';

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
		$return['action']['other']['pump'] = array(
			'template' => 'tmplicon',
			'display' => array(
				'#icon#' => '<i class=\'icon fas fa-undo\'></i>',
			),
			'replace' => array(
				'#_icon_on_#' => '<i class=\'icon fas fa-undo\'></i>',
				'#_icon_off_#' => '<i class=\'icon fas fa-times\'></i>',
				'#_time_widget_#' => '0'
				)
			);

		return $return;
    }

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

	/*     * *********************Méthodes d'instance************************* */

	// Fonction exécutée automatiquement avant la création de l'équipement
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

	// Fonction exécutée automatiquement avant la suppression de l'équipement
	public function preRemove() {
	}

	// Fonction exécutée automatiquement après la suppression de l'équipement
	public function postRemove() {
	}


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
		"pump" =>    
		[
			"type" => "info", "subType" => "binary", "name" => "Etat pompe",
			"order" => 880, "visible" => 0, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "default"] 
		],
		"target" =>
		[
			"type" => "info", "subType" => "string", "name" => "Température désirée",
			"order" => 12, "visible" => 0, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "default"], "unite" => "°C"
		],
		"target%" =>
		[
			"type" => "info", "subType" => "string", "name" => "Humidité désirée",
			"order" => 12, "visible" => 0, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "default"], "unite" => "%"
		],
		"humid%" =>
		[
			"type" => "info", "subType" => "string", "name" => "Humidité actuelle",
			"order" => 14, "visible" => 1, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "default"], "unite" => "%"
		],
		"temp" =>     
		[
			"type" => "info", "subType" => "numeric", "name" => "Température actuelle",
			"order" => 15, "visible" => 1, "historized" => 1,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "line"], "unite" => "°C"
		],
		"indoor" =>     
		[
			"type" => "info", "subType" => "numeric", "name" => "Température intérieure",
			"order" => 16, "visible" => 1, "historized" => 1,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "line"], "unite" => "°C"
		],
		"outdoor" =>    
		[
			"type" => "info", "subType" => "numeric", "name" => "Température extérieure",
			"order" => 17, "visible" => 1, "historized" => 1,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "line"], "unite" => "°C"
		],
		"fan" =>        
		[
			"type" => "info", "subType" => "string", "name" => "Vitesse de ventilation",
			"order" => 18, "visible" => 0, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "default"] 
		],
		"tank" =>        
		[
			"type" => "info", "subType" => "binary", "name" => "Alerte Réservoir",
			"order" => 19, "visible" => 1, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "default"] 
		],
		/*"ion" =>       
		[
			"type" => "info", "subType" => "binary", "name" => "ION",
			"order" => 20, "visible" => 0, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "default"] 
		],*/
		"filter" =>       
		[
			"type" => "info", "subType" => "binary", "name" => "Alerte Filtre",
			"order" => 21, "visible" => 1, "historized" => 0,
			"display" => ["forceReturnLineBefore" => 0, "forceReturnLineAfter" => 1],
			"template" => ["dashboard" => "default"] 
		],
		"mode" =>       
		[
			"type" => "info", "subType" => "string", "name" => "Mode",
			"order" => 22, "visible" => 0, "historized" => 0,
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
		"error" =>      
		[
			"type" => "info", "subType" => "numeric", "name" => "Erreur", // semble retourner des valeurs numériques et non string, a voir avec Damien
			"order" => 888, "visible" => 0, "historized" => 1,
			"display" => ["forceReturnLineBefore" => 0],
			"template" => ["dashboard" => "line"]  // msie en line pour eviter que ça prenne trop de place
		]
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
	if($bCreateCmd) {
		if($model == "0xac") {
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
				"showScreenOn" => 			  
				[
					"type" => "action", "subType" => "other", "name" => "Ecran on",
					"order" => 12, "visible" => 0, "historized" => 0,
					"display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
					"template" => ["dashboard" => "default", "mobile" => "default"] 
				],
				"showScreenOff" =>			  
				[
					"type" => "action", "subType" => "other", "name" => "Ecran off",
					"order" => 13, "visible" => 0, "historized" => 0,
					"display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
					"template" => ["dashboard" => "default", "mobile" => "default"] 
				],
				"fahrenheitOn" => 		
				[
					"type" => "action", "subType" => "other", "name" => "Fahrenheit on",
					"order" => 14, "visible" => 0, "historized" => 0,
					"display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
					"value" => $this->getCmd(null, "F")->getId(),
					"template" => ["dashboard" => "mideawifi::boutonOnOff", "mobile" => "mideawifi::boutonOnOff"] 
				],
				"fahrenheitOff" =>		
				[
					"type" => "action", "subType" => "other", "name" => "Fahrenheit off",
					"order" => 15, "visible" => 0, "historized" => 0,
					"display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 1],
					"value" => $this->getCmd(null, "F")->getId(),
					"template" => ["dashboard" => "mideawifi::boutonFahrenheit", "mobile" => "mideawifi::boutonFahrenheit"] 
				],
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

			foreach($cmdActions0xac as $keyAction => $action) {
				self::_saveEqLogic($keyAction, $action);
			}

		} else if ($model == "0xa1") {
			$cmdActions0xa1 = [
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
				"pumpOn" =>			  
				[
					"type" => "action", "subType" => "other", "name" => "Pompe on",
					"order" => 4, "visible" => 1, "historized" => 0,
					"display" => ["forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
					"value" => $this->getCmd(null, "pump")->getId(),
					"template" => ["dashboard" => "mideawifi::pump", "mobile" => "mideawifi::pump"] 
				],
				"pumpOff" =>			  
				[
					"type" => "action", "subType" => "other", "name" => "Pompe off",
					"order" => 5, "visible" => 1, "historized" => 0,
					"display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
					"value" => $this->getCmd(null, "pump")->getId(),
					"template" => ["dashboard" => "mideawifi::pump", "mobile" => "mideawifi::pump"] 
				],
				/*"ionOn" =>			  
				[
					"type" => "action", "subType" => "other", "name" => "Ion on",
					"order" => 6, "visible" => 1, "historized" => 0,
					"display" => ["forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
					"value" => $this->getCmd(null, "ion")->getId(),
					"template" => ["dashboard" => "mideawifi::ion", "mobile" => "mideawifi::ion"] 
				],
				"ionOff" =>			  
				[
					"type" => "action", "subType" => "other", "name" => "Ion off",
					"order" => 7, "visible" => 1, "historized" => 0,
					"display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 1],
					"value" => $this->getCmd(null, "ion")->getId(),
					"template" => ["dashboard" => "mideawifi::ion", "mobile" => "mideawifi::ion"] 
				],*/
				"comfortSleepOn" => 			  
				[
					"type" => "action", "subType" => "other", "name" => "Sleep on",
					"order" => 8, "visible" => 1, "historized" => 0,
					"display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
					"value" => $this->getCmd(null, "sleep")->getId(),
					"template" => ["dashboard" => "mideawifi::sleep", "mobile" => "mideawifi::sleep"] 
				],
				"comfortSleepOff" =>			  
				[
					"type" => "action", "subType" => "other", "name" => "Sleep off",
					"order" => 9, "visible" => 1, "historized" => 0,
					"display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 1],
					"value" => $this->getCmd(null, "sleep")->getId(),
					"template" => ["dashboard" => "mideawifi::sleep", "mobile" => "mideawifi::sleep"] 
				],
				"verticalSwingOn" =>  
				[
					"type" => "action", "subType" => "other", "name" => "VerticalSwing on",
					"order" => 10, "visible" => 1, "historized" => 0,
					"display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
					"value" => $this->getCmd(null, "verticalswing")->getId(),
					"template" => ["dashboard" => "mideawifi::swingV", "mobile" => "mideawifi::swingV"] 
				],
				"verticalSwingOff" =>	  
				[
					"type" => "action", "subType" => "other", "name" => "VerticalSwing off",
					"order" => 11, "visible" => 1, "historized" => 0,
					"display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 1],
					"value" => $this->getCmd(null, "verticalswing")->getId(),
					"template" => ["dashboard" => "mideawifi::swingV", "mobile" => "mideawifi::swingV"] 
				],
				"setHumidity" =>	  
				[
					"type" => "action", "subType" => "slider", "name" => "Consigne d'humidité",
					"order" => 52, "visible" => 1, "historized" => 0,
					"display" => ["forceReturnLineBefore" => 1, "forceReturnLineAfter" => 1],
					"value" => $this->getCmd(null, "target%")->getId(),
					"unite" => "%",
					"configuration" => ["minValue" => 0, "maxValue" => 100],
					"template" => ["dashboard" => "mideawifi::setTemperature", "mobile" => "mideawifi::setTemperature"] 
				],
				/*
				Déshumidificateur = 1
				Continu = 2
				Intelligent = 3
				Séchage linge = 4
				*/
				"setMode" => 			  
				[
					"type" => "action", "subType" => "select", "name" => "Mode Opérationnel",
					"order" => 53, "visible" => 1, "historized" => 0,
					"display" => ["forceReturnLineBefore" => 0],
					"configuration" => ["listValue" => "1|Déshumidificateur;2|Continu;3|Intelligent;4|Séchage linge"],
					"value" => $this->getCmd(null, "mode")->getId(),
					"template" => ["dashboard" => "default"] 
				],
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

			foreach($cmdActions0xa1 as $keyAction => $action) {
				self::_saveEqLogic($keyAction, $action);
			}
		}
		
		// create refresh action
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
		case "setHumidity":
			$cmdLabel = "target-humidity";
			$cmdValue = strval($val);
			self::createAndUpdateCmd(false); // update datas before sending new vals
			$this->checkAndUpdateCmd('target%', $cmdValue);
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
		case "showScreenOn":
			$cmdLabel = "show-screen";
			$cmdValue = "1";
			break;
		case "showScreenOff":
			$cmdLabel = "show-screen";
			$cmdValue = "0";
			break;
		case "pumpOn":
			$cmdLabel = "pump";
			$cmdValue = "1";
			break;
		case "pumpOff":
			$cmdLabel = "pump";
			$cmdValue = "0";
			break;
		/*case "ionOn":
			$cmdLabel = "ion-mode";
			$cmdValue = "1";
			break;
		case "ionOff":
			$cmdLabel = "ion-mode";
			$cmdValue = "0";
			break;*/
      	case "customCommands":
        	break;

		default:
		throw new Error('This should not append!');
		log::add('mideawifi', 'warn', 'Error while executing cmd ' . $this->getLogicalId());
		return;
	}
	
  	$additionalParams = "";

  	if($cmd == "customCommands") {
      $command = trim($val);
    } else {
      $command = "--$cmdLabel $cmdValue ";
      log::add('mideawifi', 'debug', "commande à envoyer: $command");

      if($cmdLabel == "" || $cmdValue == "")
      return;

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
	
	$json = json_decode($data);
	
	if($json->status == "nok")
	log::add('mideawifi', 'debug', 'Error while sending : Midea cloud not responding or error');
	
} // sendCmd
}

class mideawifiCmd extends cmd {
	
	// Exécution d'une commande
	public function execute($_options = array()) {
		
		$eqLogic = $this->getEqLogic(); // Récupération de l’eqlogic
		log::add("mideawifi", "debug", "LogicalId action => " . $this->getLogicalId());
		Log::add('mideawifi', 'debug', '$_options[] traité: ' . json_encode($_options));
		
		if($eqLogic->getIsEnable() == 0)
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
						
			case 'setHumidity':
				$humidity = isset($_options['text']) ? $_options['text'] : $_options['slider'];  // scenario compatibility
				if($humidity < 0 || $humidity > 100)
					return;
				$eqLogic->sendCmd('setHumidity', $humidity);
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
																	
			case 'showScreenOn':
				$eqLogic->sendCmd('showScreenOn');
				break;
																		
			case 'showScreenOff':
				$eqLogic->sendCmd('showScreenOff');
				break;
																	
			case 'pumpOn':
				$eqLogic->checkAndUpdateCmd('pump', 1);
				$eqLogic->sendCmd('pumpOn');
				break;
																		
			case 'pumpOff':
				$eqLogic->checkAndUpdateCmd('pump', 0);
				$eqLogic->sendCmd('pumpOff');
				break;
																	
			/*case 'ionOn':
				$eqLogic->checkAndUpdateCmd('ion', 1);
				$eqLogic->sendCmd('ionOn');
				break;
																		
			case 'ionOff':
				$eqLogic->checkAndUpdateCmd('ion', 0);
				$eqLogic->sendCmd('ionOff');
				break;*/

          	case 'customCommands':
            	$eqLogic->sendCmd('customCommands', $_options['text']);
            	break;
				
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
