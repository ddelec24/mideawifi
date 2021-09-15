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

	include_file('core', 'mideawifi', 'class', 'mideawifi');

	if (!isConnect()) {
		throw new \Exception('401 Unauthorized');
	}

	ajax::init();

	if (init('action') == 'scanDevices') { 
		ajax::success(scanDevices());
	}
	throw new \Exception('Aucune méthode correspondante');
} catch (\Exception $e) {
	ajax::error(displayException($e), $e->getCode());
}

// @TODO probleme de class not found quand je met la fonction dans la class principale
function scanDevices() {
	// $timeout = config::byKey('timeout', 'mideawifi', 5); // charge le timeout configuré dans le plugin
	// Exemple de trame exploitable => NFO:msmart.cli: Found a supported '0xac' at <IP> - id: <ID> - sn: <SN> - ssid: <SSID>

	// regex correspondante
	//$re = "/(?:0xac' at (?:(.*) - id: (.*) - sn))+/m"; //msmart 0.1.21
	$re = "/(?:(supported|unsupported) device - type: \'(.*)\' - version: (.*) - ip: (.*) - port: (.*) - id: (.*) - sn)+/m"; //msmart 0.1.23
	// VM locale with shell_exec =< https://stackoverflow.com/a/6066720
	$locale = 'fr_FR.UTF-8';
	setlocale(LC_ALL, $locale);
	putenv('LC_ALL='.$locale);
	$raw = shell_exec('midea-discover 2>&1');
	//DEBUG uniquement
	// données 2 clims OK avec msmart 0.1.21
	/*$raw = "INFO:msmart.cli:Midea Local Data 192.168.1.121 5a5a011178007a80000007bbab733c5584b4532378778734202fd22aea83fcb89ece21924e79489adebbc68a4fd38e9d4e7be67a0d6686d03d96642a542b1899cb7a51a4dce15ce2bb7ee30b6738522fc258fbff6f557d6fe6db437f21ccade892e96eaf0b208bb9b1fb5c895316a92168a9695182750b39
	INFO:msmart.cli: Found a supported '0xac' at 192.168.1.121 - id: 12345678912345 - sn: P0000000Q1B000000000000000 - ssid: net_ac_0000
	INFO:msmart.cli:Midea Local Data 192.168.1.122 5a5a011178007a8000000443a0ea6912359b4ccea7d8377b9f3f959617e697909952e6f0a4f351f92023240a63ab1b7a160d4ebaf4adf202790e8d4f8ac64c80be110acf231d558fe2e9225266434fec7c63b094f0ec04c4a3745f535b40d3cbedb5207834ba575182333d3c39badb1af4b5f70a6609bbc18
	INFO:msmart.cli: Found a supported '0xac' at 192.168.11.122 - id: 98765432198765 - sn: P0000000Q1B000000000000000 - ssid: net_ac_0000";*/

	// exemple clim avec msmart 0.1.23
	/*$raw = "INFO:msmart.cli: Found a supported device - type: '0xac' - version: V2 - ip: 192.168.1.44 - port: 6444 - id: 32132165465478 - sn: 000000P0000000111111111111110000 - ssid: net_ac_0000
	INFO:msmart.cli: Found an unsupported device - type: '0xac' - version: V3 - ip: 192.168.1.45 - port: 6444 - id: 32132165465477 - sn: 000000P0000000111111111111110000 - ssid: net_ac_0000
	INFO:msmart.cli: Found a supported device - type: '0xac' - version: V2 - ip: 192.168.1.46 - port: 6444 - id: 32132165465474 - sn: 000000P0000000111111111111110000 - ssid: net_ac_0000*/
	/*$raw = "INFO:msmart.cli:*** Found a supported device - type: '0xac' - version: V2 - ip: 192.168.1.47 - port: 6444 - id: 2000000067811 - sn: 000000P0000000Q1F000000000000000 - ssid: net_ac_0000
	INFO:msmart.cli:Decrypt Reply: 192.168.1.109 6d01a8c02c19000030303030303050303030303030305131413036383143414445323545303030300b6e65745f61315f4532354500000000010
	00000040000000000a100000000000000a0681cade25e069fcd0300080103000000000000000000000000000000000000000000
	INFO:msmart.lan:Couldn't connect with Device 192.168.1.109:6444
	INFO:msmart.cli:*** Found a unsupported device - type: '0xa1' - version: V3 - ip: 192.168.1.109 - port: 6444 - id: 31885837346305 - sn: 000000P0000000Q1A0681CADE25E0000 - ssid: net_a1_E25E";*/
	preg_match_all($re, $raw, $matches, PREG_SET_ORDER, 0);

	$new = 0;
	if(sizeof($matches) > 0) {
		$devices = [];
		//tous les équipements déjà enregistrés
		$allDevices=eqLogic::byType('mideawifi');

		foreach($matches as $match) {
			$supported = $match[1];
			$hexadecimalType = strtolower($match[2]);
			$version = strtolower($match[3]);
			$ip = $match[4];
			$port = $match[5];
			$id = $match[6];


			$alreadyExists = false;
			foreach($allDevices as $d) {
				// l'appareil est déjà connu
				if($id == $d->getConfiguration('id')) {
					// on actualise juste l'ip
					$d->setConfiguration('ip', $ip);
					$d->save();
					$alreadyExists = true;
				}
			}

			if(!$alreadyExists) {
				// ajout nouvel équipement
				$conditioner = new mideawifi();
				$conditioner->setName($id);
				$conditioner->setIsEnable(1);
				$conditioner->setIsVisible(0);
				$conditioner->setLogicalId($id);
				$conditioner->setEqType_name('mideawifi');
				$conditioner->setConfiguration('id', $id);
				$conditioner->setConfiguration('hexadecimalType', $hexadecimalType);
				$conditioner->setConfiguration('version', $version);
				$conditioner->setConfiguration('swingmode', 'Both');
				$conditioner->setConfiguration('ip', $ip);
				$conditioner->setConfiguration('port', $port);
				
				$conditioner->save();

				$devices[] = [	
					"ip" => $ip,
					"id" => $id,
					"port" => $port,
					"supported" => $supported,
					"hexadecimalType" => $hexadecimalType,
					"version" => $version,
					"eqlogic" => $conditioner->getId()
				];

				$new++;
			}
		}
	}
	
	$return = ['new' => $new, 'devices' => $devices, 'raw' => $raw];
	log::add('mideawifi', 'debug', '======== DISCOVER MIDEA ========');
	log::add('mideawifi', 'debug', 'Retour complet = ' . json_encode($return));
	return $return;  
}