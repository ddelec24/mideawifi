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

/* Permet la réorganisation des commandes dans l'équipement */
$("#table_cmd").sortable({
	axis: "y",
	cursor: "move",
	items: ".cmd",
	placeholder: "ui-state-highlight",
	tolerance: "intersect",
	forcePlaceholderSize: true
})

/* Fonction permettant l'affichage des commandes dans l'équipement */
function addCmdToTable(_cmd) {
	if (!isset(_cmd)) {
		var _cmd = {configuration: {}}
	}
	if (!isset(_cmd.configuration)) {
		_cmd.configuration = {}
	}
	var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">'
	tr += '<td class="hidden-xs">'
	tr += '<span class="cmdAttr" data-l1key="id"></span>'
	tr += '</td>'
	tr += '<td>'
	tr += '<div class="input-group">'
	tr += '<input class="cmdAttr form-control input-sm roundedLeft" data-l1key="name" placeholder="{{Nom de la commande}}">'
	tr += '<span class="input-group-btn"><a class="cmdAction btn btn-sm btn-default" data-l1key="chooseIcon" title="{{Choisir une icône}}"><i class="fas fa-icons"></i></a></span>'
	tr += '<span class="cmdAttr input-group-addon roundedRight" data-l1key="display" data-l2key="icon" style="font-size:19px;padding:0 5px 0 0!important;"></span>'
	tr += '</div>'
	tr += '<select class="cmdAttr form-control input-sm" data-l1key="value" style="display:none;margin-top:5px;" title="{{Commande info liée}}">'
	tr += '<option value="">{{Aucune}}</option>'
	tr += '</select>'
	tr += '</td>'
	tr += '<td>'
	tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>'
	tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>'
	tr += '</td>'
	tr += '<td>'
	tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="isVisible" checked/>{{Afficher}}</label> '
	tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="isHistorized" checked/>{{Historiser}}</label> '
	tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="invertBinary"/>{{Inverser}}</label> '
	tr += '<div style="margin-top:7px;">'
	tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="{{Min}}" title="{{Min}}" style="width:30%;max-width:80px;display:inline-block;margin-right:2px;">'
	tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" placeholder="{{Max}}" title="{{Max}}" style="width:30%;max-width:80px;display:inline-block;margin-right:2px;">'
	tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="unite" placeholder="Unité" title="{{Unité}}" style="width:30%;max-width:80px;display:inline-block;margin-right:2px;">'
	tr += '</div>'
	tr += '</td>'
	tr += '<td>';
	tr += '<span class="cmdAttr" data-l1key="htmlstate"></span>'; 
	tr += '</td>';
	tr += '<td>'
	if (is_numeric(_cmd.id)) {
		tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> '
		tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> Tester</a>'
	}
	tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove" title="{{Supprimer la commande}}"></i></td>'
	tr += '</tr>'
	$('#table_cmd tbody').append(tr)
	var tr = $('#table_cmd tbody tr').last()
	jeedom.eqLogic.buildSelectCmd({
		id:  $('.eqLogicAttr[data-l1key=id]').value(),
		filter: {type: 'info'},
		error: function (error) {
			$('#div_alert').showAlert({message: error.message, level: 'danger'})
		},
		success: function (result) {
			tr.find('.cmdAttr[data-l1key=value]').append(result)
			tr.setValues(_cmd, '.cmdAttr')
			jeedom.cmd.changeType(tr, init(_cmd.subType))
		}
	})
}

<<<<<<< HEAD
$('.eqLogicAttr[data-l1key=configuration][data-l2key=hexadecimalType]').on('change',function(){
	type = $(this).value()
	var humanType = {'0xac': 'Climatiseur', '0xa1': 'Déshumidificateur'};
	$("#nameType").html(humanType[type]);
	$("#imgAppareil").attr('src', 'plugins/mideawifi/core/images/' + type + '.png');
});

$('.eqLogicAttr[data-l1key=configuration][data-l2key=version]').on('change',function(){
	version = $(this).value()
	var cloudVersion = (version == 'v2') ? 'Non' : 'Oui'
	;
	$("#cloudVersion").html(cloudVersion);
	if(version != 'v2') {
		$("#cloudVersion").removeClass('label-primary').addClass('label-warning');
		$('.noneCloudOptions').hide();
	} else {
		$("#cloudVersion").removeClass('label-warning').addClass('label-primary');
		$('.noneCloudOptions').show();
	}

});


// Lance un scan Midea
$('.eqLogicAction[data-action=scanMideaDevices]').on('click', function() {
	$('.eqLogicAction span:first').text("{{Scan en cours...}}").css({'color' : 'red'});
	$('.eqLogicAction i:first').css({'color' : 'red'});
	runMideaDiscovery();
=======
$('.eqLogicAttr[data-l1key=configuration][data-l2key=model]').on('change', function(){
	type = $(this).value();
	var humanType = {'0xac': 'Climatiseur', '0xa1': 'Déshumidificateur'};
	$('.eqLogicAttr[data-l1key=configuration][data-l2key=modelHuman]').text(humanType[type]);
	$("#imgAppareil").attr('src', 'plugins/mideawifi/data/' + type + '.png');
>>>>>>> alpha
});

$('.eqLogicAction[data-action=discover]').on('click', function() {
	$('.eqLogicAction span:first').text("{{Scan en cours...}}").css({'color' : 'red'});
	$('.eqLogicAction i:first').css({'color' : 'red'});
	discover();
});

function discover() {
	console.log('=== Midea Discovery in progress ===');

	$.ajax({
		type: 'POST',
		url: 'plugins/mideawifi/core/ajax/mideawifi.ajax.php',
		data: {
			action: 'discover'
		},
		dataType: 'json',
		error: function (request, status, error) {
			handleAjaxError(request, status, error);
		},
		success: function (data) {
			$('.eqLogicAction span:first').text("{{Ajout automatique}}").removeAttr('style');
			$('.eqLogicAction i:first').removeAttr('style');
			if (data.state != 'ok') {
				$('#div_alert').showAlert({message: data.result, level: 'danger'});
				return;
			}
			console.log(data);
<<<<<<< HEAD

			if(data.result.new == 0){
				$('#div_results').empty().append("<center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Aucun nouvel appareil détecté}}</span></center>");
				return;
			} else {
				var plurilizedNewDevices = (data.result.new == 1) ? "Nouvel appareil détecté" : "Nouveaux appareils détectés";
				$('#div_results').empty().append("<center><span style='color:#767676;font-size:1.2em;font-weight: bold;'> " + data.result.new + "{{ " + plurilizedNewDevices + "}}</span></center>");
			}


			// create div container for results
			$("#div_results").append('<div class="eqLogicThumbnailContainer" id="newEqDetected" style="position: relative; height: 173px;"></div>');

			var html = '';
			var currentLeft = 0;
			for (var i in data.result.devices) {

				supported = true;
				redColor = '';
				if(data.result.devices[i].supported == "unsupported") {
					$('#div_alert').showAlert({message: "Equipement " + data.result.devices[i].id + " détecté mais à priori non supporté ou utilisation du cloud!", level: 'danger'});
					supported = false;
				}
=======
			countResults = data.result.newEq + " {{équipement(s) trouvé(s) lors de la recherche automatique}}.";
			$('.infoCountResults').text(countResults);

			var containerDiv = '<div class="eqLogicThumbnailContainer">';

			$.each(data.result.results, function(key, currentResult) {
				//console.log(currentResult);
				let eqType = (currentResult.model == "Air conditioner") ? "0xac" : "0xa1";
				containerDiv += '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' + currentResult.id + '">';
				containerDiv += '<a href="index.php?v=d&m=mideawifi&p=mideawifi&id=' + currentResult.jeedomId + '">';
				containerDiv += '<img src="plugins/mideawifi/data/' + eqType + '.png">';
				containerDiv += '<br>';
				containerDiv += '<span class="name">' + currentResult.name  + '</span>';
				containerDiv += '<span class="hiddenAsCard displayTableRight hidden">';
				containerDiv += '</span>';
				containerDiv += '</a>';
				containerDiv += '</div>';
			});
          	containerDiv += '</div>';
			$('.infoCountResults').append(containerDiv);
>>>>>>> alpha


		},
		done: function(data) {
			console.log('=== Midea Discovery finished ===');
		}
	});


}
