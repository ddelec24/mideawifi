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

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }

  	require_once dirname(__FILE__) . '/../php/mideawifi.inc.php';
  /* Fonction permettant l'envoi de l'entête 'Content-Type: application/json'
    En V3 : indiquer l'argument 'true' pour contrôler le token d'accès Jeedom
    En V4 : autoriser l'exécution d'une méthode 'action' en GET en indiquant le(s) nom(s) de(s) action(s) dans un tableau en argument
  */
    ajax::init();

	if (init('action') == 'installMideawifiDocker') { 
		ajax::success(installMideawifiDocker());
	}
	if (init('action') == 'startMideawifiContainer') { 
		ajax::success(startMideawifiContainer());
	}

	if (init('action') == 'discover') { 
		ajax::success(discover());
	}
  
	if (init('action') == 'discoverFromIp') { 
		ajax::success(discoverFromIp());
	}
	/*if (init('action') == 'applianceStatusWithTokenAndKey') { 
		ajax::success(applianceStatusWithTokenAndKey());
	}
  	if (init('action') == 'applianceStatusWithAccount') { 
		ajax::success(applianceStatusWithAccount());
	}
  	if (init('action') == 'applianceStatusWithIdAnAccountCloud') { 
		ajax::success(applianceStatusWithIdAnAccountCloud());
	}*/
  
    throw new Exception(__('Aucune méthode correspondante à', __FILE__) . ' : ' . init('action'));
    /*     * *********Catch exeption*************** */
}
catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}


// install Docker Management
function installDocker2() {
  try {
    $plugin = plugin::byId('docker2');
    if (!$plugin->isActive()) {
      $plugin->setIsEnable(1);
      $plugin->dependancy_install();
      log::add("mideawifi", "debug", "[DOCKER2] Installation des dépendances.");
    }
  } catch (Exception $e) {
    log::add("mideawifi", "debug", "[DOCKER2] docker2 introuvable, on l'installe");
    event::add('jeedom::alert', array(
      'level' => 'warning',
      'page' => 'plugin',
      'message' => __('Installation du plugin Docker Management', __FILE__),
    ));
    $update = update::byLogicalId('docker2');
    if (!is_object($update)) {
      $update = new update();
    }
    $update->setLogicalId('docker2');
    $update->setSource('market');
    $update->setConfiguration('version', 'stable');
    $update->save();
    $update->doUpdate();
    $plugin = plugin::byId('docker2');

    if (!is_object($plugin)) {
      throw new Exception(__('Le plugin Docker management doit être installé', __FILE__));
    }
    if (!$plugin->isActive()) {
      $plugin->setIsEnable(1);
      $plugin->dependancy_install();
    }
    if (!$plugin->isActive()) {
      throw new Exception(__('Le plugin Docker management doit être actif', __FILE__));
    }
    event::add('jeedom::alert', array(
      'level' => 'warning',
      'page' => 'plugin',
      'ttl' => 250000,
      'message' => __('Pause de 120s le temps de l\'installation des dépendances du plugin Docker Management', __FILE__),
    ));
    $i = 0;
    while (system::installPackageInProgress('docker2')) {
      sleep(5);
      $i++;
      if ($i > 50) {
        throw new Exception(__('Delai maximum autorisé pour l\'installation des dépendances dépassé', __FILE__));
      }
    }
  }

}

// prepare docker2 plugin & eqLogic
function installMideawifiDocker() {
  installDocker2();

  if (!class_exists('docker2')) {
    include_file('core', 'docker2', 'class', 'docker2');
  }
  
  // build docker image
  $arch = (system::getArch() == "arm") ? "arm" : ""; // image docker spéciale si arm 32bits

  $removeOldBuild = shell_exec(system::getCmdSudo() . "docker rmi -f midea-beautiful-air:latest");

  $cmd = system::getCmdSudo() . "docker build --tag midea-beautiful-air -f " . 
    								dirname(__FILE__) . "/../../resources/containerDocker/" . $arch . "Dockerfile " .
    								dirname(__FILE__) . "/../../resources/containerDocker";
  log::add("mideawifi", "debug", "[DOCKER2] Build cmd: " . $cmd);
  event::add('jeedom::alert', array(
    'level' => 'info',
    'page' => 'plugin',
    'ttl' => 250000,
    'message' => __('Préparation de l\'image docker, cela peut prendre plusieurs minutes...', __FILE__),
  ));
  
  $ret = exec($cmd);
  
  log::add("mideawifi", "debug", "[DOCKER2] : " . $ret);
  
  return $ret;

}


function startMideawifiContainer() {
 
	$command = "docker run -d --restart=always --network host --name mideawifi midea-beautiful-air";
  $docker = eqLogic::byLogicalId('1::mideawifi', 'docker2');
  if (!is_object($docker)) {
      $docker = new docker2();
    }
  $docker->setLogicalId('1::mideawifi');
  $docker->setName('mideawifi');
  $docker->setIsEnable(1);
  $docker->setEqType_name('docker2');
  $docker->setConfiguration('name', 'mideawifi');
  $docker->setConfiguration('docker_number', 1);
  $docker->setConfiguration('create::mode', 'jeedom_run');
  $docker->setConfiguration('create::run', $command);
  $docker->save();
  try {
    $docker->rm();
    sleep(5);
  } catch (\Throwable $th) {
  }

    event::add('jeedom::alert', array(
    'level' => 'info',
    'page' => 'plugin',
    'ttl' => 30000,
    'message' => __('Lancement du service docker mideawifi...', __FILE__),
  ));
  sleep(10); // on attend un peu
  $docker->create(); // on a toutes les infos, on démarre le container
  
  sleep(5);
  return "ok";
}

function discover() {
  $accountmail = 	trim(config::byKey('accountmail', 'mideawifi'));
  $accountpass = 	trim(config::byKey('accountpass', 'mideawifi'));
  $useMsmartHome = 	(config::byKey('useMsmartHome', 'mideawifi')) ? "y" : "n";
  log::add("mideawifi", "debug", "MsmartHome: " . $useMsmartHome);
  $data = 			curlMideawifiDocker("/discover", array("accountmail" => $accountmail, "accountpass" => $accountpass, "withapp" => $useMsmartHome));

  log::add("mideawifi", "debug", "[ENDPOINT] /discover : " . $data);
  log::add("mideawifi", "debug", "============================ DISCOVER ============================");
  $json = 			json_decode($data);
  $response = 		$json->response;
  $status = 		$json->status;
  
  if($status == "nok") {
    event::add('jeedom::alert', array(
      'level' => 'info',
      'page' => 'plugin',
      'ttl' => 10000,
      'message' => __('Erreur Cloud Midea, merci d\'attendre un peu avant de retenter. Erreur: ' . $response, __FILE__),
  	));
    
    return ["countResults" => 0,
          "newEq" => 0,
          "results"=> []
          ];
  }
	
  //log::add("mideawifi", "debug", "JSON Response : " . $response);
  //log::add("mideawifi", "debug", "JSON Response error: " . json_last_error_msg());
  //$re = '/^(id (?:.*)(?:\n.*)+key(?:.*\n))$/imU'; // capture les équipements 
  $re = '/^(id (?:.*)(?:\n.*)+key(?:.*))$/miU';
  preg_match_all($re, $response, $matches);
  $countResults = 0;
  $arrayResults = [];
  
  if(!$matches) {
    log::add("mideawifi", "debug", "[DISCOVER] No Equipments Found.");
    
  } else {
    $countResults = sizeof($matches[0]);
    log::add("mideawifi", "debug", "[DISCOVER] " . $countResults . " equipments detected.");
        
    foreach($matches[0] as $match) {
      //$re2 = '/((.*) [=]{1} (.*)(?:[\s{3}$]))+/miU';
      $re2 = '/(.+)(?:$|\n)/i';
      //((.*)\n)*
      preg_match_all($re2, $match, $matches2);
        if(!$matches2) {
    		log::add("mideawifi", "debug", "[DISCOVER] No Matches in the current discovered equipment.");
        } else {
          $currentId = "";
          foreach($matches2[0] as $match2) {
            if(strpos($match2, '=') === false)
              continue; // first line is long id and no equal, skip
            
            $keyValue = explode("=", $match2);
            $key = trim($keyValue[0]);
            $value = trim($keyValue[1]);
            
            if($key == "id")
            	$currentId = $value;
            
            $arrayResults[$currentId][$key] = $value;
          } // loop cmd
        }
    } // loop eq
  } // matches found
  
  // now check if equipment already exists
  $allDevices = eqLogic::byType('mideawifi');
  $newEq = 0;
  foreach($arrayResults as $id => $values) {
    $alreadyExists = false;
    //if(sizeof($allDevices) > 0) {
      foreach($allDevices as $d) {
        //if(empty($id))
        //  break;
        if($id == $d->getLogicalId()) { // check if id already present 
            $alreadyExists = true;
            unset($arrayResults[$id]); // remove from array
            log::add("mideawifi", "debug", "[DISCOVER] ALREADY EXISTS id=" . $id);
            break;
        }
      } // loop device
    //}
          
    // save new EqLogic
    if(!$alreadyExists) {
      $newEq++;
	  $name = $values["name"];
      $ip = ($values["addr"] != "Unknown") ? $values["addr"] : "";
      $serialnumber = $values["s/n"];
      $model = ($values['model'] == "Air conditioner") ? "0xac": "0xa1";
      $ssid = ($values["ssid"] != "None") ? $values["ssid"] : "";
      $token = ($values["token"] != "None") ? $values["token"] : "";
      $key = $values["key"];
      
      log::add("mideawifi", "debug", "[DISCOVER] NEW EQUIPMENT " . $name . " ( id = " . $id . ")" );
      
      $eqlogic = new mideawifi();
      $eqlogic->setName($name);
      $eqlogic->setIsEnable(1);
      $eqlogic->setIsVisible(0);
      $eqlogic->setLogicalId($id);
      $eqlogic->setEqType_name('mideawifi');
      $eqlogic->setConfiguration('id', $id);
      $eqlogic->setConfiguration('ip', $ip);
      $eqlogic->setConfiguration('serialnumber', $serialnumber);      
      $eqlogic->setConfiguration('model', $model);
      $eqlogic->setConfiguration('ssid', $ssid);
      $eqlogic->setConfiguration('token', $token);
      $eqlogic->setConfiguration('key', $key);
      
      $eqlogic->save();
      $arrayResults[$id]["jeedomId"] = $eqlogic->getId(); // add eqLogic id to generate the link
      //eqLogic::saveCommands($eqLogic, $values);
    } // already exists
  } // loop discovered eq
  
  return ["countResults" => $countResults,
          "newEq" => $newEq,
          "results"=> $arrayResults
          ];
  
}