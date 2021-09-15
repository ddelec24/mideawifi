<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('mideawifi');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoPrimary" data-action="scanMideaDevices">
				<i class="fas fa-search"></i>
				<br>
				<span>{{Lancer un scan}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="add">
				<i class="fas fa-plus-square"></i>
				<br>
				<span>{{Ajout manuel}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
				<i class="fas fa-wrench"></i>
				<br>
				<span>{{Configuration}}</span>
			</div>
		</div>
		<legend><i class="fas fa-table"></i> {{Mes climatiseurs}}</legend>
		<input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
		<div id="div_results"></div>
		<div class="eqLogicThumbnailContainer">
			<?php
			foreach ($eqLogics as $eqLogic) {
				$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
				echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
				echo '<img src="' . $plugin->getPathImgIcon() . '" />';
				echo '<br>';
				echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
				echo '</div>';
			}
			?>
		</div>
	</div>



	<div class="col-xs-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a><a class="btn btn-default btn-sm eqLogicAction" data-action="copy"><i class="fas fa-copy"></i> {{Dupliquer}}</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
		</div>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
			<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
		</ul>
		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<br/>
				<form class="form-horizontal col-md-8">
					<fieldset>
						<div class="form-group">
							<label class="col-sm-3 control-label">{{Nom de la clim}}</label>
							<div class="col-sm-3">
								<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
								<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de la clim}}"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" >{{Objet parent}}</label>
							<div class="col-sm-3">
								<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
									<option value="">{{Aucun}}</option>
									<?php
									foreach (jeeObject::all() as $object) {
										echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">{{Catégorie}}</label>
							<div class="col-sm-9">
								<?php
								foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
									echo '<label class="checkbox-inline">';
									echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
									echo '</label>';
								}
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-sm-9">
								<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
								<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
							</div>
						</div>

						<div class="form-group"><div class="col-sm-12">&nbsp;</div></div>

						<div class="form-group">
							<label class="col-sm-3 control-label">{{Adresse IP}} *</label>
							<div class="col-sm-3">
								<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ip" />
							</div>
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
				<table id="table_cmd" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>{{Nom}}</th><th>{{Type}}</th><th>{{Action}}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>

	</div>
</div>

<?php include_file('desktop', 'mideawifi', 'js', 'mideawifi');?>
<?php include_file('core', 'plugin.template', 'js');?>