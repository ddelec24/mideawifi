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
          $return['action']['other']['eco'] = array(
              'template' => 'tmplicon',
              'display' => array(
                  '#icon#' => '<i class=\'icon fas fa-leaf\'></i>',
              ),
              'replace' => array(
                  '#_icon_on_#' => '<i class=\'icon fas fa-leaf\'></i>',
                  '#_icon_off_#' => '<i class=\'icon fas fa-times\'></i>',
                  '#_time_widget_#' => '0'
              )
          );

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
	* Fonction exécutée automatiquement toutes les 5 minutes par Jeedom
	public static function cron5() {}
	*/

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
		self::createAndUpdateCmd();
	}

	// Fonction exécutée automatiquement avant la suppression de l'équipement
	public function preRemove() {
	}

	// Fonction exécutée automatiquement après la suppression de l'équipement
	public function postRemove() {
	}

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
			
		/*$excludeKeys = ["id","addr","s/n","model","ssid","name","supports","F","version"];*/
		
		$includeKeys = [
			"online" =>     [
							"type" => "info", "subType" => "binary", "name" => "En ligne",
							"order" => 1, "visible" => 1, "historized" => 0,
							"display" => ["forceReturnLineBefore" => 0],
							"template" => ["dashboard" => "default"] 
							],
			"running" =>    [
							"type" => "info", "subType" => "binary", "name" => "Etat",
							"order" => 880, "visible" => 0, "historized" => 1,
							"display" => ["forceReturnLineBefore" => 0],
							"generic_type" => "ENERGY_STATE",
							"configuration" => ['repeatEventManagement' => 'never'],
							"template" => ["dashboard" => "default"] 
							],
			"target" =>     [
							"type" => "info", "subType" => "string", "name" => "Température désirée",
							"order" => 12, "visible" => 0, "historized" => 0,
							"display" => ["forceReturnLineBefore" => 0],
							"template" => ["dashboard" => "default"], "unite" => "°C" ],
			"indoor" =>     [
							"type" => "info", "subType" => "string", "name" => "Température intérieure",
							"order" => 13, "visible" => 1, "historized" => 1,
							"display" => ["forceReturnLineBefore" => 0],
							"template" => ["dashboard" => "default"], "unite" => "°C" ],
			"outdoor" =>    [
							"type" => "info", "subType" => "string", "name" => "Température extérieure",
							"order" => 14, "visible" => 1, "historized" => 1,
							"display" => ["forceReturnLineBefore" => 0],
							"template" => ["dashboard" => "default"], "unite" => "°C" ],
			"fan" =>        [
							"type" => "info", "subType" => "string", "name" => "Vitesse de ventilation",
							"order" => 15, "visible" => 0, "historized" => 0,
							"display" => ["forceReturnLineBefore" => 0],
							"template" => ["dashboard" => "default"] 
							],
			"mode" =>       [
							"type" => "info", "subType" => "string", "name" => "Mode",
							"order" => 16, "visible" => 0, "historized" => 0,
							"display" => ["forceReturnLineBefore" => 0],
							"template" => ["dashboard" => "default"] 
							],
			"horizontalswing" => [
							"type" => "info", "subType" => "binary", "name" => "Swing Horizontal",
							"order" => 100, "visible" => 0, "historized" => 0,
							"display" => ["forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
              				"configuration" => ['repeatEventManagement' => 'never'],
							"template" => ["dashboard" => "default"] 
							],
			"verticalswing" => [
							"type" => "info", "subType" => "binary", "name" => "Swing Vertical",
							"order" => 110, "visible" => 0, "historized" => 0,
							"display" => ["forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
              				"configuration" => ['repeatEventManagement' => 'never'],
							"template" => ["dashboard" => "default"] 
							],
			"turbo" =>      [
							"type" => "info", "subType" => "binary", "name" => "Turbo",
							"order" => 120, "visible" => 0, "historized" => 0,
							"display" => ["forceReturnLineBefore" => 0],
							"template" => ["dashboard" => "default"] 
							],
			"eco" =>      [
							"type" => "info", "subType" => "binary", "name" => "Eco",
							"order" => 130, "visible" => 0, "historized" => 0,
							"display" => ["forceReturnLineBefore" => 0],
							"template" => ["dashboard" => "default"] 
							],
			/*"purify" =>     [
							"type" => "info", "subType" => "binary", "name" => "Mode Purificateur",
							"order" => 8, "visible" => 1, "historized" => 1,
							"display" => ["forceReturnLineBefore" => 0],
							"template" => ["dashboard" => "default"] 
							],*/
			"error" =>      [
							"type" => "info", "subType" => "string", "name" => "Erreur",
							"order" => 888, "visible" => 0, "historized" => 1,
							"display" => ["forceReturnLineBefore" => 0],
							"template" => ["dashboard" => "default"] 
							]
		];
	  //{'eco': 0, 'heat_8': 1, 'mode': 1, 'fan_swing': 1, 'electricity': 1, 'filter_reminder': 0, 'strong_fan': 1}
		$includeKeysSupport = [
			/*"fan_swing" =>  [
							"type" => "info", "subType" => "binary", "name" => "Position de ventilation",
							"order" => 11, "visible" => 0, "historized" => 0,
							"display" => ["forceReturnLineBefore" => 0],
							"template" => ["dashboard" => "default"] 
							],*/
			/*"strong_fan" =>  [
							"type" => "info", "subType" => "binary", "name" => "Turbo",
							"order" => 9, "visible" => 1, "historized" => 0,
							"display" => ["forceReturnLineBefore" => 0],
							"template" => ["dashboard" => "default"] 
							],*/
			/*"eco" =>  [
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
		  "marche" =>			  [
								  "type" => "action", "subType" => "other", "name" => "Marche",
								  "order" => 2, "visible" => 1, "historized" => 0,
								  "display" => ["forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
								  "value" => $this->getCmd(null, "running")->getId(),
								  "generic_type" => "ENERGY_ON",
								  "template" => ["dashboard" => "mideawifi::boutonOnOff", "mobile" => "mideawifi::boutonOnOff"] 
								  ],
		  "arret" =>			  [
								  "type" => "action", "subType" => "other", "name" => "Arret",
								  "order" => 3, "visible" => 1, "historized" => 0,
								  "display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 1],
								  "value" => $this->getCmd(null, "running")->getId(),
								  "generic_type" => "ENERGY_OFF",
								  "template" => ["dashboard" => "mideawifi::boutonOnOff", "mobile" => "mideawifi::boutonOnOff"] 
								  ],
		  "horizontalSwingOn" =>  [
								  "type" => "action", "subType" => "other", "name" => "HorizontalSwing on",
								  "order" => 4, "visible" => 1, "historized" => 0,
								  "display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
								  "value" => $this->getCmd(null, "horizontalswing")->getId(),
								  "template" => ["dashboard" => "mideawifi::swingH", "mobile" => "mideawifi::swingH"] 
								  ],
		  "horizontalSwingOff" =>				  [
								  "type" => "action", "subType" => "other", "name" => "HorizontalSwing off",
								  "order" => 5, "visible" => 1, "historized" => 0,
								  "display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
								  "value" => $this->getCmd(null, "horizontalswing")->getId(),
								  "generic_type" => "ENERGY_OFF",
								  "template" => ["dashboard" => "mideawifi::swingH", "mobile" => "mideawifi::swingH"] 
								  ],
		  "verticalSwingOn" =>  [
								  "type" => "action", "subType" => "other", "name" => "VerticalSwing on",
								  "order" => 6, "visible" => 1, "historized" => 0,
								  "display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
								  "value" => $this->getCmd(null, "verticalswing")->getId(),
								  "template" => ["dashboard" => "mideawifi::swingV", "mobile" => "mideawifi::swingV"] 
								  ],
		  "verticalSwingOff" =>				  [
								  "type" => "action", "subType" => "other", "name" => "VerticalSwing off",
								  "order" => 7, "visible" => 1, "historized" => 0,
								  "display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
								  "value" => $this->getCmd(null, "verticalswing")->getId(),
								  "template" => ["dashboard" => "mideawifi::swingV", "mobile" => "mideawifi::swingV"] 
								  ],
		  "turboOn" =>  [
								  "type" => "action", "subType" => "other", "name" => "Turbo on",
								  "order" => 8, "visible" => 1, "historized" => 0,
								  "display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
								  "value" => $this->getCmd(null, "turbo")->getId(),
								  "template" => ["dashboard" => "mideawifi::turbo", "mobile" => "mideawifi::turbo"] 
								  ],
		  "turboOff" =>				  [
								  "type" => "action", "subType" => "other", "name" => "Turbo off",
								  "order" => 9, "visible" => 1, "historized" => 0,
								  "display" => [ "forceReturnLineBefore" => 0, "forceReturnLineAfter" => 0],
								  "value" => $this->getCmd(null, "turbo")->getId(),
								  "template" => ["dashboard" => "mideawifi::turbo", "mobile" => "mideawifi::turbo"] 
								  ],
		  "ecoOn" =>  [
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
								  ],
		  "setTemperature" =>	  [
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
		  "setMode" => 			  [
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
		  "setFanSpeed" =>		  [
								  "type" => "action", "subType" => "select", "name" => "Vitesse ventilation",
								  "order" => 54, "visible" => 1, "historized" => 0,
								  "display" => ["forceReturnLineBefore" => 1, "forceReturnLineAfter" => 1],
								  "configuration" => ["listValue" => "102|Automatique;80|Rapide;60|Normal;40|Lent;20|Silencieux"],
								  "value" => $this->getCmd(null, "fan")->getId(),
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
		  case "ecoOn":
				$cmdLabel = "eco-mode";
				$cmdValue = "1";
				break;
		  case "ecoOff":
				$cmdLabel = "eco-mode";
				$cmdValue = "0";
				break;
		  default:
			  throw new Error('This should not append!');
			  log::add('mideawifi', 'warn', 'Error while executing cmd ' . $this->getLogicalId());
			  return;
		}
    
		$command = "--$cmdLabel $cmdValue ";
		log::add('mideawifi', 'debug', "commande à envoyer: $command");
    
		if($cmdLabel == "" || $cmdValue == "")
		  return;

		// when set a new lower temp target, a bug occurs. forcing eco-mode state fix that
		$additionalParams = "";
		if($cmd == "setTemperature") {
			$currentMode = $this->getCmd(null, "mode")->execCmd(); // @TODO verif si nécessaire de mettre ->execCmd() ???
			if($currentMode == 2) { // eco mode exists only in cooling mode
				$cmdEco = $this->getCmd(null, "eco");
				$isEco = $cmdEco->execCmd();
				$additionalParams = ($isEco) ? "--eco-mode 1" : "--eco-mode 0"; // @TODO a vérifier si la valeur "eco" récupérée dans les supports correspond bien au mode de clim éco
			} else {
				$additionalParams = "--eco-mode 0";
			}
		}
   		
		if(!empty($ip) && !empty($token) && !empty($key)) {
			log::add('mideawifi', 'debug', '[ENDPOINT] /set_appliance_attribute');
			log::add('mideawifi', 'debug', 'midea-beautiful-air-cli set --ip ' . $ip . ' --token ' . $token . ' --key ' . $key . ' --' . $cmdLabel . ' ' . $cmdValue . $additionalParams);
			$data = curlMideawifiDocker("/set_appliance_attribute", array("ipaddress" => $ip, "token" => $token, "key" => $key, "commands" => $command . $additionalParams));
			log::add('mideawifi', 'debug', "Data => " . $data);
		} else if(!empty($id) && !empty($accountmail) && !empty($accountpass)) {
			log::add('mideawifi', 'debug', '[ENDPOINT] /set_appliance_attribute_with_account');
			log::add('mideawifi', 'debug', 'midea-beautiful-air-cli set --id ' . $id . ' --account ' . $accountmail . ' --password ' . $accountpass . ' --' . $cmdLabel . ' ' . $cmdValue . ' --cloud');
			$data = curlMideawifiDocker("/set_appliance_attribute_with_account", array("applianceid" => $id, "accountmail" => $accountmail, "accountpass" => $accountpass, "commands" => $command . $additionalParams));
			log::add('mideawifi', 'debug', "Data => " . $data);
		} else {
			log::add('mideawifi', 'debug', "Can't update $id, missing:<br/> Either => credentials + appliance id <br/> Either => token + key + ip");
			return; // can't update
		}
        
  } // sendCmd
}

class mideawifiCmd extends cmd {

	// Exécution d'une commande
	public function execute($_options = array()) {

		$eqLogic = $this->getEqLogic(); // Récupération de l’eqlogic
		log::add("mideawifi", "debug", "LogicalId action => " . $this->getLogicalId());
		Log::add('mideawifi', 'debug', '$_options[] traité: ' . json_encode($_options));
	  
		switch ($this->getLogicalId()) {
			case 'refresh': 
				$eqLogic->createAndUpdateCmd(false);
				break;
            
			case 'marche':
				log::add('mideawifi', 'debug', "Action Marche");
				$eqLogic->checkAndUpdateCmd('running', 1);
				$eqLogic->sendCmd('marche');
				break;
            
			case 'arret':
				log::add('mideawifi', 'debug', "Action Arret");
				$eqLogic->checkAndUpdateCmd('running', 0);
				$eqLogic->sendCmd('arret');
				break;
		  	case 'setTemperature':
				log::add('mideawifi', 'debug', "Action setTemperature");
            	$temperature = isset($_options['text']) ? $_options['text'] : $_options['slider'];
            	$eqLogic->checkAndUpdateCmd('target', $temperature);
				$eqLogic->sendCmd('setTemperature', $temperature); // scenario compatibility
				break;
            
			case 'setMode':
				log::add('mideawifi', 'debug', "Action setMode");
            	$mode = isset($_options['select']) ? $_options['select'] : $_options['slider'];
            	$eqLogic->checkAndUpdateCmd('mode', $mode);
				$eqLogic->sendCmd('setMode', $mode); // scenario compatibility
				break;
            
			case 'setFanSpeed':
				log::add('mideawifi', 'debug', "Action setFanSpeed");
            	$fanSpeed = isset($_options['select']) ? $_options['select'] : $_options['slider'];
            	$eqLogic->checkAndUpdateCmd('fan', $fanSpeed);
				$eqLogic->sendCmd('setFanSpeed', $fanSpeed); // scenario compatibility
				break;
            
			case 'horizontalSwingOn':
				log::add('mideawifi', 'debug', "Action horizontalSwingOn");
            	$eqLogic->checkAndUpdateCmd('horizontalswing', 1);
				$eqLogic->sendCmd('horizontalSwingOn');
				break;
            
			case 'horizontalSwingOff':
				log::add('mideawifi', 'debug', "Action horizontalSwingOff");
            	$eqLogic->checkAndUpdateCmd('horizontalswing', 0);
				$eqLogic->sendCmd('horizontalSwingOff');
				break;
            
			case 'verticalSwingOn':
				log::add('mideawifi', 'debug', "Action verticalSwingOn");
            	$eqLogic->checkAndUpdateCmd('verticalswing', 1);
				$eqLogic->sendCmd('verticalSwingOn');
				break;
            
			case 'verticalSwingOff':
				log::add('mideawifi', 'debug', "Action verticalSwingOff");
            	$eqLogic->checkAndUpdateCmd('verticalswing', 0);
				$eqLogic->sendCmd('verticalSwingOff');
				break;
            
			case 'turboOn':
				log::add('mideawifi', 'debug', "Action turboOn");
            	$eqLogic->checkAndUpdateCmd('turbo', 1);
				$eqLogic->sendCmd('turboOn');
				break;
            
			case 'turboOff':
				log::add('mideawifi', 'debug', "Action turboOff");
            	$eqLogic->checkAndUpdateCmd('turbo', 0);
				$eqLogic->sendCmd('turboOff');
				break;
            
			case 'ecoOn':
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
				break;
            
			default:
			  throw new Error('This should not append!');
			  log::add('mideawifi', 'warn', 'Error while executing cmd ' . $this->getLogicalId());
			  break;
		}

		return;
			
	}

	/*     * **********************Getteur Setteur*************************** */

}
