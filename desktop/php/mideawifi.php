<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
// Déclaration des variables obligatoires
$plugin = plugin::byId('mideawifi');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
	<!-- Page d accueil du plugin -->
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
		<!-- Boutons de gestion du plugin -->
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoPrimary" data-action="discover">
				<i class="fas fa-plus-circle"></i>
				<br>
				<span>{{Ajout automatique}}</span>
			</div>
			<div class="cursor eqLogicAction logoPrimary" data-action="add">
				<i class="fas fa-plus-circle"></i>
				<br>
				<span>{{Ajout manuel avec l&apos;adresse IP}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
				<i class="fas fa-wrench"></i>
				<br>
				<span>{{Configuration}}</span>
			</div>
		</div>
		<legend><i class="fas fa-table"></i> {{Mes équipements Midea}}</legend>
		<?php
		if (count($eqLogics) == 0) {
			echo '<br><div class="text-center infoCountResults" style="font-size:1.2em;font-weight:bold;">{{Aucun équipement trouvé, cliquer sur "Ajout automatique" pour commencer}}</div>';
		} else {
			// Champ de recherche
			echo '<div class="input-group" style="margin:5px;">';
			echo '<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic">';
			echo '<div class="input-group-btn">';
			echo '<a id="bt_resetSearch" class="btn" style="width:30px"><i class="fas fa-times"></i></a>';
			echo '<a class="btn roundedRight hidden" id="bt_pluginDisplayAsTable" data-coreSupport="1" data-state="0"><i class="fas fa-grip-lines"></i></a>';
			echo '</div>';
			echo '</div>';
			echo '<div class="text-center infoCountResults" style="font-size:1.2em;font-weight:bold;"></div>';
			// Liste des équipements du plugin
			echo '<div class="eqLogicThumbnailContainer">';
			
			foreach ($eqLogics as $eqLogic) {
				$model = $eqLogic->getConfiguration('model','0xac');
				$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
              	$onlineColor = ($eqLogic->getCmd(null, "online")) ? "green" : "red";
				echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
				echo '<img src="plugins/mideawifi/data/' . $model . '.png">';
              	echo '<span style="position: absolute; float: right; color: '. $onlineColor . '"><i class="fa fa-plug" aria-hidden="true"></i></span>';
				echo '<br>';
				echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
				echo '<span class="hiddenAsCard displayTableRight hidden">';
				echo ($eqLogic->getIsVisible() == 1) ? '<i class="fas fa-eye" title="{{Equipement visible}}"></i>' : '<i class="fas fa-eye-slash" title="{{Equipement non visible}}"></i>';
				echo '</span>';
				echo '</div>';
			}
			echo '</div>';
		}
		?>
	</div> <!-- /.eqLogicThumbnailDisplay -->

	<!-- Page de présentation de l équipement -->
	<div class="col-xs-12 eqLogic" style="display: none;">
		<!-- barre de gestion de l équipement -->
		<div class="input-group pull-right" style="display:inline-flex;">
			<span class="input-group-btn">
				<!-- Les balises <a></a> sont volontairement fermées à la ligne suivante pour éviter les espaces entre les boutons. Ne pas modifier -->
				<a class="btn btn-sm btn-default eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i><span class="hidden-xs"> {{Configuration avancée}}</span>
				</a><a class="btn btn-sm btn-default eqLogicAction" data-action="copy"><i class="fas fa-copy"></i><span class="hidden-xs">  {{Dupliquer}}</span>
				</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
				</a><a class="btn btn-sm btn-danger eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}
				</a>
			</span>
		</div>
		<!-- Onglets -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
			<li role="presentation"><a href="#commandtab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-list"></i> {{Commandes}}</a></li>
		</ul>
		<div class="tab-content">
			<!-- Onglet de configuration de l équipement -->
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
<<<<<<< HEAD
				<br/>
				<form class="form-horizontal col-md-8">
=======
				<!-- Partie gauche de l'onglet "Equipements" -->
				<!-- Paramètres généraux et spécifiques de l'équipement -->
				<form class="form-horizontal">
>>>>>>> alpha
					<fieldset>
						<div class="col-lg-6">
							<legend><i class="fas fa-wrench"></i> {{Paramètres généraux}}</legend>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Nom de l&apos;équipement}}</label>
								<div class="col-sm-6">
									<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display:none;">
									<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" >{{Objet parent}}</label>
								<div class="col-sm-6">
									<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
										<option value="">{{Aucun}}</option>
										<?php
										$options = '';
										foreach ((jeeObject::buildTree(null, false)) as $object) {
											$options .= '<option value="' . $object->getId() . '">' . str_repeat('&nbsp;&nbsp;', $object->getConfiguration('parentNumber')) . $object->getName() . '</option>';
										}
										echo $options;
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Catégorie}}</label>
								<div class="col-sm-6">
									<?php
									foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
										echo '<label class="checkbox-inline">';
										echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" >' . $value['name'];
										echo '</label>';
									}
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Options}}</label>
								<div class="col-sm-6">
									<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked>{{Activer}}</label>
									<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked>{{Visible}}</label>
								</div>
							</div>

							<legend><i class="fas fa-cogs"></i> {{Paramètres spécifiques}}</legend>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Identifiant ID}}
									<sup><i class="fas fa-question-circle tooltips" title="{{ID récupéré automatiquement, en lecture seule}}"></i></sup>
								</label>
								<div class="col-sm-6">
									<input type="text" class="eqLogicAttr form-control italic" data-l1key="configuration" data-l2key="id" readonly/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Adresse IP}} *
									<sup><i class="fas fa-question-circle tooltips" title="{{Adresse IP locale}}"></i></sup>
								</label>
								<div class="col-sm-6">
									<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ip" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Token}}
									<sup><i class="fas fa-question-circle tooltips" title="{{Récupéré automatiquement si vous avez un appareil Cloud. Laissez vide si vous avez les anciens contrôleurs wifi OSK-102 qui ne sont pas cloud.}}"></i></sup>
								</label>
								<div class="col-sm-6">
									<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="token" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Key}}
									<sup><i class="fas fa-question-circle tooltips" title="{{Récupéré automatiquement si vous avez un appareil Cloud. Laissez vide si vous avez les anciens contrôleurs wifi OSK-102 qui ne sont pas cloud.}}"></i></sup>
                                </label>
								<div class="col-sm-6">
									<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="key" />
								</div>
							</div>
							<br />
							<div class="form-group">
								<div class="col-sm-2"></div>
								<div class="col-sm-10">
									* {{Il est fortement conseillé de fixer les adresses IP de vos appareils pour éviter d&apos;éventuels changements par votre box.}}
								</div>
							</div>
							<!--
							 <div class="form-group">
								<label class="col-sm-4 control-label">{{Port}}</label>
								<div class="col-sm-6">
									<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="port" placeholder="{{6444}}"/>
								</div>
							</div>
						-->

						<!-- Exemple de champ de saisie du cron d auto-actualisation avec assistant -->
						<!-- La fonction cron de la classe du plugin doit contenir le code prévu pour que ce champ soit fonctionnel -->
							<!--
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Auto-actualisation}}
									<sup><i class="fas fa-question-circle tooltips" title="{{Fréquence de rafraîchissement des commandes infos de l'équipement}}"></i></sup>
								</label>
								<div class="col-sm-6">
									<div class="input-group">
										<input type="text" class="eqLogicAttr form-control roundedLeft" data-l1key="configuration" data-l2key="autorefresh" placeholder="{{Cliquer sur ? pour afficher l'assistant cron}}">
										<span class="input-group-btn">
											<a class="btn btn-default cursor jeeHelper roundedRight" data-helper="cron" title="Assistant cron">
												<i class="fas fa-question-circle"></i>
											</a>
										</span>
									</div>
								</div>
							</div>
<<<<<<< HEAD
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">{{ID unique}}</label>
							<div class="col-sm-3">
								<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="id" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">{{Port}}</label>
							<div class="col-sm-3">
								<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="port" />
							</div>
						</div>
						<div class="form-group noneCloudOptions">
							<label class="col-sm-3 control-label">{{Type de ventilation possible}}</label>
							<div class="col-sm-3">
								<?php $currentSwingmode = jeedom::getConfiguration('swingmode'); ?>
								<select id="swingmode" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="swingmode">
                                  <option value="Vertical">Vertical</option>
                                  <option value="Horizontal">Horizontal</option>
                                  <option value="Both">Les deux</option>
                                </select>
							</div>
						</div>
						<div class="alert-info bg-success" style="margin-top: 2em">
							<em>* Si l'adresse IP n'est plus correcte, relancez simplement un scan et elle se mettra à jour.<br />
							Il est conseillé de mettre une ip fixe (voir dans la configuration de l'appareil Midea) ou configurer des baux DHCP statiques (voir documentation de votre routeur)</em>
						</div>

					</fieldset>
				</form>
				<legend><i class="fas fa-info"></i> {{Informations}}</legend>
				<form class="form-horizontal col-md-4">
					<label class="col-sm-3 control-label">{{Type d'équipement}}</label>
					<div class="col-sm-3">
						<input type="hidden" class="eqLogicAttr cmdAttr" data-l1key="configuration" data-l2key="hexadecimalType" />
						<div class="form-group cmdAttr label label-primary" id="nameType" style="font-size : 1em"></div>
						<div class="form-group">
						<img src="plugins/mideawifi/core/images/0xac.png" data-original=".png" id="imgAppareil" class="img-responsive" style="width:120px" onerror="this.src='plugins/mideawifi/core/images/0xac.png'" /></div>
					</div>
				</form>
				<form class="form-horizontal col-md-4" style="margin-top: 20px;">
					<label class="col-sm-3 control-label">{{Cloud nécessaire}}</label>
					<div class="col-sm-3">
						<input type="hidden" class="eqLogicAttr cmdAttr" data-l1key="configuration" data-l2key="version" />
						<div class="form-group cmdAttr label label-primary" id="cloudVersion" style="font-size : 1em"></div>
					</div>
				</form>
			</div>
			<div role="tabpanel" class="tab-pane" id="commandtab">
				<a class="btn btn-success btn-sm cmdAction pull-right" data-action="add" style="margin-top:5px;"><i class="fa fa-plus-circle"></i> {{Commandes}}</a><br/><br/>
=======
						-->
					</div>

					<!-- Partie droite de l onglet "Équipement" -->
					<!-- Affiche un champ de commentaire par défaut mais vous pouvez y mettre ce que vous voulez -->
					<div class="col-lg-6">
						<legend><i class="fas fa-info"></i> {{Informations}}</legend>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{Numéro de série}}</label>
							<div class="col-sm-6">
								<span class="eqLogicAttr cmdAttr" data-l1key="configuration" data-l2key="serialnumber"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{SSID}}</label>
							<div class="col-sm-6">
								<span class="eqLogicAttr cmdAttr" data-l1key="configuration" data-l2key="ssid"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{Type d&apos;appareil}}</label>
							<div class="col-sm-6">
                                <input type="hidden" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="model" />
								<span class="eqLogicAttr cmdAttr" data-l1key="configuration" data-l2key="modelHuman"></span>
								<img src="plugins/mideawifi/data/0xac.png" data-original=".png" id="imgAppareil" class="img-responsive" style="width:120px" onerror="this.src='plugins/mideawifi/data/0xac.png'" />
							</div>
						</div>
						<br />
						<div class="form-group">
							<label class="col-sm-4 control-label">{{Description}}</label>
							<div class="col-sm-6">
								<textarea class="form-control eqLogicAttr autogrow" data-l1key="comment"></textarea>
							</div>
						</div>
					</div>
				</fieldset>
			</form>
		</div><!-- /.tabpanel #eqlogictab-->

		<!-- Onglet des commandes de l équipement -->
		<div role="tabpanel" class="tab-pane" id="commandtab">
			<a class="btn btn-default btn-sm pull-right cmdAction" data-action="add" style="margin-top:5px;"><i class="fas fa-plus-circle"></i> {{Ajouter une commande}}</a>
			<br><br>
			<div class="table-responsive">
>>>>>>> alpha
				<table id="table_cmd" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th class="hidden-xs" style="min-width:50px;width:70px;">ID</th>
							<th style="min-width:200px;width:350px;">{{Nom}}</th>
							<th>{{Type}}</th>
							<th style="min-width:260px;">{{Options}}</th>
							<th>{{Etat}}</th>
							<th style="min-width:80px;width:200px;">{{Actions}}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div><!-- /.tabpanel #commandtab-->

	</div><!-- /.tab-content -->
</div><!-- /.eqLogic -->
</div><!-- /.row row-overflow -->

<!-- Inclusion du fichier javascript du plugin (dossier, nom_du_fichier, extension_du_fichier, id_du_plugin) -->
<?php include_file('desktop', 'mideawifi', 'js', 'mideawifi');?>
<!-- Inclusion du fichier javascript du core - NE PAS MODIFIER NI SUPPRIMER -->
<?php include_file('core', 'plugin.template', 'js');?>
