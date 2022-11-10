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

require_once __DIR__  . '/../../../../core/php/core.inc.php';
/*
*
* Fichier d’inclusion si vous avez plusieurs fichiers de class ou 3rdParty à inclure
*
*/

function curlMideawifiDocker($endpoint, $params) {

  $params = json_encode($params); //array to json
  $port = trim(config::byKey('portDocker', 'mideawifi'));
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:" . $port . $endpoint); // docker management ne prend que du local et on est en mode host donc docker transparent => ip locale
  curl_setopt($ch, CURLOPT_PORT, $port);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
  //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  $headers = array(
    'Content-Type:application/json',
    'Content-Length: ' . strlen($params),
    //"Accept: application/json"
  );
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $data = curl_exec($ch);
  
  if (curl_error($ch)) {
    log::add('mideawifi', 'warn', 'Communication error : ' . curl_error($ch));
  }
  
  curl_close($ch);
  
  /*log::add("mideawifi", "debug", "RAW : " . $data);
  $data = str_replace('\r\n','aaaa', $data);
  $data = str_replace('\n','aaaa', $data);
  $data = str_replace('\r','aaaa', $data);
  log::add("mideawifi", "debug", "FORMATTED : " . $data);*/
  $data = str_replace("\r\n", '\r\n', $data); // tricks for new line in json
  return $data;
}
