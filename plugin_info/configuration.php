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
include_file('core', 'authentification', 'php');
if (!isConnect()) {
  include_file('desktop', '404', 'php');
  die();
}


// Vérifie si docker signal actif
$dockerContainer = eqLogic::byLogicalId('1::mideawifi', 'docker2');
$colorCheckContainer = "red";
$classCheckContainer = "fa-times-circle";

try {
	plugin::byId('docker2');
	docker2::pull(); // refresh infos containers
      } catch (Exception $e) {
}


if(is_object($dockerContainer)) {
	$info = $dockerContainer->getCmd(null, 'state');
	//log::add('signal', 'debug', "Etat du container docker signal: " . $info->execCmd());
	if(is_object($info) && $info->execCmd() == "running") {
		$colorCheckContainer = "green";
		$classCheckContainer = "fa-check-circle";
	}
}

$isDockerRunning = trim(shell_exec(system::getCmdSudo() . ' pidof dockerd'));
if($isDockerRunning) {
	$colorCheckService = "green";
	$classCheckService = "fa-check-circle";
} else {
	$colorCheckService = "red";
	$classCheckService = "fa-times-circle";
}

?>
<form class="form-horizontal">
  <fieldset>
    <div class="form-group">
      <label class="col-md-4 control-label">{{Adresse mail}}
        <sup><i class="fas fa-question-circle tooltips" title="{{Renseignez l'adresse mail ici}}"></i></sup>
      </label>
      <div class="col-md-4">
        <input class="configKey form-control" data-l1key="accountmail" autocomplete="off" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-4 control-label">{{Mot de passe}}
        <sup><i class="fas fa-question-circle tooltips" title="{{Renseignez le mot de passe ici}}"></i></sup>
      </label>
      <div class="col-md-4">
        <input class="configKey form-control" data-l1key="accountpass" id="accountpass" type="password" autocomplete="off" /> 
        <span toggle="#accountpass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
      </div>
    </div>
    <hr/>
    <div class="form-group">
      <label class="col-md-4 control-label">{{Port de communication}}
        <sup><i class="fas fa-question-circle tooltips" title="{{Défaut: 5000, ne changez que si le port est déjà utilisé}}"></i></sup>
      </label>
      <div class="col-md-4">
        <input class="configKey form-control" data-l1key="portDocker" /> 
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-4 control-label">{{Mise en route}}
        <sup><i class="fas fa-question-circle tooltips" title="{{L&apos;action peut prendre plusieurs minutes, merci de patienter}}"></i></sup>
      </label>
      <div class="col-md-4"  style="line-height: 35px">
        <button id="prepareDocker" class="form-control btn btn-primary">Activation Docker</button>
        <br />
        <button id="startContainer" class="form-control btn btn-success" <?=(!$isDockerRunning) ? 'disabled="disabled"' : ''?>>Démarrage du container Midea</button>
      </div>
    </div>
    <div class="form-group">
    	<label class="col-md-4 control-label">{{Etat}}:
        <sup><i class="fas fa-question-circle tooltips" title="{{Si le service docker n&apos;est pas actif après quelques minutes, il faudra peut-être redémarrer votre machine.}}"></i></sup>
      </label>
      <div class="col-md-4">
        <span id="currentDockerState">
          <strong>Service docker:</strong> <i class="fas fa-lg <?=$classCheckService?>" style=" color: <?=$colorCheckService?>"></i>
          <br />
          <strong>Container midea:</strong> <i class="fas fa-lg <?=$classCheckContainer?>" style=" color: <?=$colorCheckContainer?>"></i>
        </span>
      </div>
    </div>
  </fieldset>
</form>
          
<style>
.field-icon {
  float: right;
  margin-top: -25px;
  position: relative;
  z-index: 2;
}
</style>

<script>
$(".toggle-password").click(function() {

  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});
$("#prepareDocker").click(function(e) {
  e.preventDefault();
  $(this).attr("disabled","disabled"); // prevent multiple click
  $(this).removeClass('btn-primary').addClass('btn-warning');
  $(this).text("Merci de patienter, préparation en cours...");
  
<<<<<<< HEAD
  <form class="form-horizontal">
    <fieldset>
        <div class="form-group"><label class="col-lg-6 control-label">{{Identifiants de connexion au cloud}}</label></div>
        <div class="form-group">
            <label class="col-lg-4 control-label">{{Adresse mail}}</label>
            <div class="col-lg-4">
                <input class="configKey form-control" data-l1key="mailCloud" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">{{Mot de passe}} *</label>
            <div class="col-lg-4">
                <input class="configKey form-control" data-l1key="passCloud" />
            </div>
        </div>
  </fieldset>

  <div class="form-group"><label class="col-lg-8 control-label"><em>* Le mot de passe apparaitra ensuite hashé, le plugin ne stock pas votre mot de passe en clair.</em></label></div>

    <!-- <fieldset>
        <div class="form-group">
            <label class="col-lg-4 control-label">{{Timeout scan}}</label>
            <div class="col-lg-2">
                <input class="configKey form-control" data-l1key="timeout" />
            </div>
        </div>
  </fieldset> -->
</form>
=======
  prepareDocker(this);
});

$("#startContainer").click(function(e) {
  e.preventDefault();
  $(this).attr("disabled","disabled"); // prevent multiple click
  $(this).removeClass('btn-success').addClass('btn-warning');
  $(this).text("Merci de patienter, Démarrage en cours...");
  
  startContainer(this);
});

function prepareDocker(that) {
	console.log('=== Preparing Docker ===');
  	
	$.ajax({
		type: 'POST',
		url: 'plugins/mideawifi/core/ajax/mideawifi.ajax.php',
		data: {
			action: 'installMideawifiDocker'
		},
		dataType: 'json',
		error: function (request, status, error) {
			handleAjaxError(request, status, error);
		},
		success: function (data) {	
          console.log(data);
          location.replace("/index.php?v=d&p=plugin&id=mideawifi");
		}
	});
}

function startContainer(that) {
	console.log('=== Starting Midea Container ===');
  	
	$.ajax({
		type: 'POST',
		url: 'plugins/mideawifi/core/ajax/mideawifi.ajax.php',
		data: {
			action: 'startMideawifiContainer'
		},
		dataType: 'json',
		error: function (request, status, error) {
			handleAjaxError(request, status, error);
		},
		success: function (data) {	
          console.log(data);
          location.replace("/index.php?v=d&p=plugin&id=mideawifi");
		}
	});
}

</script>
>>>>>>> alpha
