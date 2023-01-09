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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

// Fonction exécutée automatiquement après l'installation du plugin
function mideawifi_install() {
    $portDocker = config::byKey('portDocker', 'mideawifi');
    if(empty($portDocker))
      config::save('portDocker', '5000', 'mideawifi');

    $cronMideawifi = config::byKey('cronMideawifi', 'mideawifi');
    if(empty($cronMideawifi)) {
        $cron = cron::byClassAndFunction('mideawifi', 'pull');
        if (!is_object($cron)) {
            $cron = new cron();
            $cron->setClass('mideawifi');
            $cron->setFunction('pull');
            $cron->setEnable(1);
            $cron->setDeamon(0);
            $cron->setDeamonSleepTime(0);
            $cron->setSchedule('*/5 * * * *');
            $cron->setTimeout(2);
            $cron->save();
        }
    }
  	$cron->start();
}

// Fonction exécutée automatiquement après la mise à jour du plugin
function mideawifi_update() {
      $portDocker = config::byKey('portDocker', 'mideawifi');
      foreach (mideawifi::byType('mideawifi', true) as $mideawifi) {
          try {
            if(empty($portDocker)) {
              $mideawifi->remove(); //ancienne version sans docker on supprime léquipement
            } else {
              $mideawifi->save();
            }
          } catch (Exception $e) {

          }
      }

    if(empty($portDocker))
      	config::save('portDocker', '5000', 'mideawifi');

    $cronMideawifi = config::byKey('cronMideawifi', 'mideawifi');
    if(empty($cronMideawifi)) {
        $cron = cron::byClassAndFunction('mideawifi', 'pull');
        if (!is_object($cron)) {
            $cron = new cron();
            $cron->setClass('mideawifi');
            $cron->setFunction('pull');
            $cron->setEnable(1);
            $cron->setDeamon(0);
            $cron->setDeamonSleepTime(0);
            $cron->setSchedule('*/5 * * * *');
            $cron->setTimeout(2);
            $cron->save();
        }
    }
  
  	$cron->start();
}

// Fonction exécutée automatiquement après la suppression du plugin
function mideawifi_remove() {
  	$cron = cron::byClassAndFunction('mideawifi', 'pull');
  	if (is_object($cron)) {
      	$cron->stop();
  		$cron->remove();
    }
}