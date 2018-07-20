<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<div>
	<div data-fields-config-title>
		<h4><?php echo $title; ?></h4>
	</div>

	<div data-fields-config-params>
		<div class="tab-box tab-box-alt">
			<div class="tabbable">
				<ul class="nav nav-tabs fd-reset-list" data-fields-config-tab-nav>
					<?php foreach ($tabs as $key => $tab) { ?>
						<?php if (isset($params->$tab)) { ?>
							<li>
								<a href="#<?php echo $tab; ?>" data-tabname="<?php echo $tab; ?>" data-bs-toggle="tab"><?php echo $params->$tab->title; ?></a>
							</li>
						<?php } ?>
					<?php } ?>
				</ul>

				<div class="tab-content" data-fields-config-tab-content>
					<?php foreach ($tabs as $key => $tab) { ?>

						<?php if (isset($params->$tab)) { ?>
						<div id="<?php echo $tab; ?>" class="tab-pane">
							<?php foreach ($params->$tab->fields as $name => $field) { ?>
								<?php if (isset($field->subfields)) { ?>
									<?php foreach ($field->subfields as $subname => $subfield) { ?>
										<div class="form-group">
											<label>
												<strong><?php echo isset ($subfield->label) ? $subfield->label : $field->label . ': ' . $subname; ?></strong>
													
												<?php if (isset($subfield->tooltip)) { ?>
													<i class="icon-es-help mr-5" data-es-provide="tooltip" data-placement="right" data-original-title="<?php echo $subfield->tooltip; ?>"></i>
												<?php } ?>
											</label>

											<div class="form-inline">
												<?php echo $this->loadTemplate('admin/profiles/fields/config.' . $field->type, array('name' => $name . '_' . $subname, 'field' => $subfield, 'value' => $values->get($name . '_' . $subname))); ?>
											</div>
										</div>
									<?php } ?>
								<?php } else { ?>
									<div class="form-group">
										<label>
											<strong><?php echo $field->label; ?></strong>

											<?php if (isset($field->tooltip)) { ?>
											<i class="icon-es-help mr-5" data-es-provide="tooltip" data-placement="right" data-original-title="<?php echo $field->tooltip; ?>"></i>
											<?php } ?>
										</label>
										
										<?php if (isset($field->info)) { ?>
										<div class="alert"><?php echo $field->info; ?></div>
										<?php } ?>

										<div class="form-inline">
											<?php echo $this->loadTemplate('admin/profiles/fields/config.' . $field->type, array('name' => $name, 'field' => $field, 'value' => $values->get($name))); ?>
										</div>
									</div>
								<?php } ?>
							<?php } ?>
						</div>
						<?php } ?>

					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
