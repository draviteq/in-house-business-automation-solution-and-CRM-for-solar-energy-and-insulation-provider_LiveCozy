<?php
$input_type_options = ['' => '--Select Input Type--'];
$ignoreInputTypes = [_INPUT_TYPE_HEADING,_INPUT_TYPE_ONLY_DATE];
if (!empty($leadWorkFlow['lead_work_flow_sections'])):
    foreach ($leadWorkFlow['lead_work_flow_sections'] as $lead_work_flow_section):
	$section_options[$lead_work_flow_section->id] = $lead_work_flow_section->section_name;
    endforeach;
endif;

if (!empty($inputTypes)):
    foreach ($inputTypes as $inputType):
	if (!in_array($inputType['id'], $ignoreInputTypes)):
	    $input_type_options[$inputType['id']] = $inputType['name'];
	endif;
    endforeach;
endif;
?>
<style>
    .remove_button_product {
        background: none;
        color: #FF4500;
        padding: 4px 9px;
        position: relative;
        font-size: 21px;
    }
    .sortable {border: 1px #555 solid; margin-bottom: 1px;};
    .form-field-action{position: relative;}
    .form-field-action-new:hover .form-field-body-new {
	display: block;
	position: absolute;
	right: 10px;
	z-index: 9999;
    }
    .form-field-action-new{padding:5px;}
    .form-field-action-new:hover{box-shadow:1px 0px 20px rgba(0, 0, 0, 0.08);}
    .form-field-body-new {
	display: none;
    }
    hr.leadworkflow-hr{
	margin-top: 8px;
	margin-bottom: 8px;
	border: 0;
	border-top: 1px solid #eee;
    }
    h4.page-title.leadworkflow-h4 {
	margin: 0px 0px;
	font-size: 17px;
    }
    .height-label {
	height: 65px;
    }
    @media (max-width: 991px){
	.col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9 {
	    float: left;
	}
    }
    span.text-small {
	color: gray;
	font-size: 13px;
    }
    .height-label label {
	min-height: 39px;
	padding-right: 15px;
    }
    .height-label {
    height: auto;
    }

	.custom-checkbox-report .checkmark:after {
    left: 7px;
    top: 3px;
    }

	.checkmark {
    height: 20px;
    width: 20px;
}

</style>
<div class="row second-nav-padd">
    <?= $this->element('common_nav'); ?></div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="white-box white-box-padd">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Create Installation Forms</h4>
                    </div>
                </div>
                <div class="row addproduct">
                    <div class="form-group col-lg-3  col-md-4  col-sm-6 height-label">
                        <label for="inputName1" class="control-label col-md-12">Installation Type </label>
                        <?php
                        echo $this->Form->control(' ', ['options' => $installationType, 'escape' => false, 'required' => true, 'id' => 'installation_type', 'label' => false, 'default' => $id, 'class' => 'form-control', 'empty' => "Select Installation Type"]);
                        ?>
                    </div>
					<div class="form-group col-lg-3 col-md-4  col-sm-6 m-t-20">
                        <div class="input-group m-t-30">
                            <label for="site_audit_flag" class="custom-checkbox-report m-l-5">Site Audit
                                <input type="checkbox" name="site_audit_flag" class="lead_status_filters"  id="site_audit_flag" value="<?= _STATUS_INSTALLATION_PROCESS_SITE_AUDIT ?>" style="position: absolute; opacity: 0;">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>

					<div class="form-group col-lg-3 col-md-4  col-sm-6 m-t-20">
				<div class="input-group m-t-30">
                            <label for="installation_flag" class="custom-checkbox-report m-l-5">Installation
					            <input type="checkbox" name="installation_flag" id="installation_flag" class="lead_status_filters" value="<?= _STATUS_INSTALLATION_PROCESS_INSTALLATION ?>" style="position: absolute; opacity: 0;">
                                <span class="checkmark"></span>
					        </label>
                        </div>
					</div>

					<div class="form-group col-lg-3 col-md-4  col-sm-6 m-t-20">
                        <div class="input-group m-t-30">
                            <label for="qc_flag" class="custom-checkbox-report m-l-5">Quality Check
                                <input type="checkbox" name="qc_flag" id="qc_flag" class="lead_status_filters" value="<?= _STATUS_INSTALLATION_PROCESS_QUALITY_CHECK ?>" style="position: absolute; opacity: 0;">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row bg-title m-t-40">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Add Fields </h4>
                    </div>   
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <h3 class="box-title m-b-0 pull-right">
                        <button type="button" class="btn btn-info" id="add-section" ><i class="fa fa-plus"></i> Add Field</button>
                        </h3>
                    </div>                 
                    </div>
                    <div id="add-section-body">
                        <div class="well">
                        <?= $this->Form->create(null, ['id' => 'addInstallationFormField', 'enctype' => 'multipart/form-data']) ?>
                        <div class="row">
                            <div class="col-lg-12">
                            <div id="input-field-message"></div>
                            <?php
                            $section_options = getEntityOptions(_INSTALLATION_FORM_NAME); 
							$system_data_options = getEntityOptions(_ID_SYSTEM_DATA, 'System Data');

                            ?>
                            <div class="form-group col-lg-3  col-md-4  col-sm-6 col-xs-6" style="min-height: 70px;">
                                <label for="inputName1" class="control-label col-md-12"> Form Name <span class="text-danger"> *</span></label>
                                <?= $this->Form->select('form_id', $section_options, ['id' => 'form_id', 'class' => 'form-control', 'data-placeholder' => 'Select Form', 'required' => true, 'label' => false, 'default' => '']); ?>
                            </div>
                            <div class="form-group col-lg-3  col-md-4  col-sm-6 col-xs-6" style="min-height: 70px;">
                                <label for="inputName1" class="control-label col-md-12"> Input Type <span class="text-danger"> *</span></label>
                                <?= $this->Form->select('input_type', $input_type_options, ['id' => 'input_type_id', 'class' => 'form-control', 'data-placeholder' => 'Select Input Type', 'required' => true, 'label' => false, 'default' => '']); ?>
                            </div>
                            <input type="hidden" name="product_id" value='<?= $id ?>'>
							<div class="form-group col-lg-3  col-md-4  col-sm-6 col-xs-6" style="min-height: 70px;">
								<label>Map Data </label>
								<?= $this->Form->select('system_data_id', $system_data_options, [ 'class' => 'form-control', 'required' => false, 'label' => false, 'default' => 0]); ?>
							</div>
                            <div  id="input-html"></div>
                            <div class="col-md-12 text-right">
                                <button type="button" class="btn btn-info m-r-10" id="save-section-field">Save Field</button> 
                                <button type="button" class="btn btn-info" id="cancle-section-field">Cancel</button>
                            </div>
                            </div>
                        </div>
                        <?= $this->Form->end(); ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                <div class="row" id="append-section-and-field">
                    <div class="col-lg-12">
                    <?php
                        $section_options = getEntityOptions(_INSTALLATION_FORM_NAME); 
                        foreach ($section_options as $key => $value)://pr($lead_work_flow_section);
                        ?>
                        <div class="clearfix"></div>
                        <h4 class="page-title leadworkflow-h4"><?= ucwords($value); ?></h4><hr class="leadworkflow-hr">
                        <div class="row sortable-field  section_<?= $key ?>">
                            <?php
            
                            if (!empty($allFormField)):
                            foreach ($allFormField as $lead_work_flow_section_field):
                                if($lead_work_flow_section_field['product_id'] == $id){
                                    if($lead_work_flow_section_field['installation_form_id'] == $key){
                                if ($lead_work_flow_section_field['input_type_id'] == _INPUT_TYPE):
                                ?>
                                    <div class="form-group col-lg-4  col-md-4 col-sm-6 col-xs-6 height-label form-field-action-new" id="<?= $key . "_" . $lead_work_flow_section_field['id'] ?>">
                                    <div class="form-field-body-new">
                                        <a href="javascript:void(0);" class="delete-field delete-section-field" data-value="<?= $lead_work_flow_section_field['id']; ?>"><i class="m-l-10 text-danger fa fa-trash"></i></a>
                                        <a href="javascript:void(0);" class="edit-field edit-section-field" data-section_id="<?= $lead_work_flow_section_field['installation_form_id']; ?>" data-value="<?= $lead_work_flow_section_field['id']; ?>" data-section_id="<?= $lead_work_flow_section_field['installation_form_id']; ?>" data-input_id="<?= $lead_work_flow_section_field['input_type_id']; ?>" data-system_data_id="<?= $lead_work_flow_section_field['system_data_id']; ?>"><i class="m-l-10 fa fa-pencil"></i></a>
                                    </div>
                                    <label for="inputName1" class="control-label col-md-12"><?= ((isset($lead_work_flow_section_field['label_name'])) ? $lead_work_flow_section_field['label_name'] : ''); ?><?= (($lead_work_flow_section_field['required_field'] == _ID_FLAG_YES) ? '<span class="text-danger"> *</span>' : ''); ?> </label>
                                    <?php echo $this->Form->control('name', ['type' => 'text', 'required' => true, 'disabled' => 'disabled', 'escape' => false, 'label' => false, 'class' => 'form-control']); ?>
                                    </div>
                                <?php
								elseif($lead_work_flow_section_field['input_type_id'] == _INPUT_TYPE_CHECKBOX):
								?>
								 <div class="form-group col-lg-4  col-md-4 col-sm-6 col-xs-6 height-label form-field-action-new" id="<?= $key . "_" . $lead_work_flow_section_field['id'] ?>">
                                    <div class="form-field-body-new">
                                        <a href="javascript:void(0);" class="delete-field delete-section-field" data-value="<?= $lead_work_flow_section_field['id']; ?>"><i class="m-l-10 text-danger fa fa-trash"></i></a>
                                        <a href="javascript:void(0);" class="edit-field edit-section-field" data-section_id="<?= $lead_work_flow_section_field['installation_form_id']; ?>" data-value="<?= $lead_work_flow_section_field['id']; ?>" data-section_id="<?= $lead_work_flow_section_field['installation_form_id']; ?>" data-input_id="<?= $lead_work_flow_section_field['input_type_id']; ?>" data-system_data_id="<?= $lead_work_flow_section_field['system_data_id']; ?>"><i class="m-l-10 fa fa-pencil"></i></a>
                                    </div>
                                    <label for="inputName1" class="control-label col-md-12"><?= ((isset($lead_work_flow_section_field['label_name'])) ? $lead_work_flow_section_field['label_name'] : ''); ?> </label>
									
                                    <?php echo $this->Form->control('name', ['type' => 'radio', 'required' => true, 'disabled' => 'disabled', 'escape' => false, 'label' => false, 'class' => 'form-control']); ?>
                                    </div>
									<?php
								elseif($lead_work_flow_section_field['input_type_id'] == _INPUT_TYPE_NOTES):
								?>
								 <div class="form-group col-lg-4  col-md-4 col-sm-6 col-xs-6 height-label form-field-action-new" id="<?= $key . "_" . $lead_work_flow_section_field['id'] ?>">
                                    <div class="form-field-body-new">
                                        <a href="javascript:void(0);" class="delete-field delete-section-field" data-value="<?= $lead_work_flow_section_field['id']; ?>"><i class="m-l-10 text-danger fa fa-trash"></i></a>
                                        <a href="javascript:void(0);" class="edit-field edit-section-field" data-section_id="<?= $lead_work_flow_section_field['installation_form_id']; ?>" data-value="<?= $lead_work_flow_section_field['id']; ?>" data-section_id="<?= $lead_work_flow_section_field['installation_form_id']; ?>" data-input_id="<?= $lead_work_flow_section_field['input_type_id']; ?>" data-system_data_id="<?= $lead_work_flow_section_field['system_data_id']; ?>"><i class="m-l-10 fa fa-pencil"></i></a>
                                    </div>
                                    <label for="inputName1" class="control-label col-md-12"><?= ((isset($lead_work_flow_section_field['label_name'])) ? $lead_work_flow_section_field['label_name'] : ''); ?><?= (($lead_work_flow_section_field['required_field'] == _ID_FLAG_YES) ? '<span class="text-danger"> *</span>' : ''); ?> </label>
                                    <?php echo $this->Form->control('name', ['type' => 'textarea', 'required' => true, 'disabled' => 'disabled', 'escape' => false, 'label' => false, 'class' => 'form-control']); ?>
                                    </div>
								<?php
                                elseif ($lead_work_flow_section_field['input_type_id'] == _FILE) :
                                ?>
                                    <div class="form-group col-lg-4  col-md-4 col-sm-6 col-xs-6 height-label form-field-action-new" id="<?= $key. "_" . $lead_work_flow_section_field['id'] ?>">
                                    <div class="form-field-body-new">
                                        <a href="javascript:void(0);" class="delete-field delete-section-field" data-value="<?= $lead_work_flow_section_field['id']; ?>"><i class="m-l-10 text-danger fa fa-trash"></i></a>
                                        <a href="javascript:void(0);" class="edit-field edit-section-field" data-section_id="<?= $lead_work_flow_section_field['installation_form_id']; ?>" data-value="<?= $lead_work_flow_section_field['id']; ?>" data-section_id="<?= $lead_work_flow_section_field['installation_form_id']; ?>" data-input_id="<?= $lead_work_flow_section_field['input_type_id']; ?>" data-system_data_id="<?= $lead_work_flow_section_field['system_data_id']; ?>" ><i class="m-l-10 fa fa-pencil"></i></a>
                                    </div>
                                    <label for="inputName1" class="control-label col-md-12"><?= ((isset($lead_work_flow_section_field['label_name'])) ? $lead_work_flow_section_field['label_name'] : ''); ?><?= (($lead_work_flow_section_field['required_field'] == _ID_FLAG_YES) ? '<span class="text-danger"> *</span>' : ''); ?> </label>
                                    <div class="col-sm-12">
                                        <label class="custom-file">
                                        <input type="file" id="file" disabled="disabled" class="custom-file-input">
                                        <span class="custom-file-control"></span>
                                        </label>
                                    </div>
                                    </div>
									<?php
                                elseif ($lead_work_flow_section_field['input_type_id'] == _INPUT_TYPE_SIGNATURE) :
                                ?>
                                    <div class="form-group col-lg-4  col-md-4 col-sm-6 col-xs-6 height-label form-field-action-new" id="<?= $key. "_" . $lead_work_flow_section_field['id'] ?>">
                                    <div class="form-field-body-new">
                                        <a href="javascript:void(0);" class="delete-field delete-section-field" data-value="<?= $lead_work_flow_section_field['id']; ?>"><i class="m-l-10 text-danger fa fa-trash"></i></a>
                                        <a href="javascript:void(0);" class="edit-field edit-section-field" data-section_id="<?= $lead_work_flow_section_field['installation_form_id']; ?>" data-value="<?= $lead_work_flow_section_field['id']; ?>" data-section_id="<?= $lead_work_flow_section_field['installation_form_id']; ?>" data-input_id="<?= $lead_work_flow_section_field['input_type_id']; ?>" data-system_data_id="<?= $lead_work_flow_section_field['system_data_id']; ?>"><i class="m-l-10 fa fa-pencil"></i></a>
                                    </div>
                                    <label for="inputName1" class="control-label col-md-12"><?= ((isset($lead_work_flow_section_field['label_name'])) ? $lead_work_flow_section_field['label_name'] : ''); ?><?= (($lead_work_flow_section_field['required_field'] == _ID_FLAG_YES) ? '<span class="text-danger"> *</span>' : ''); ?> </label>
                                    <div class="col-sm-12">
                                        <label class="custom-file">
                                        <input type="file" id="file" disabled="disabled" class="custom-file-input">
                                        <span class="custom-file-control"></span>
                                        </label>
                                    </div>
                                    </div>
                                <?php
                                elseif ($lead_work_flow_section_field['input_type_id'] == _INPUT_TYPE_RADIO) :
                                ?>
                                <?php
                                $options = [];
                                if (!empty($allFormFieldOptions)) {
                                    foreach ($allFormFieldOptions as $form_field_option) {
									if($lead_work_flow_section_field['id'] == $form_field_option['installation_form_field_id']){
                                    $options[$form_field_option['option_value']] = ' ' . $form_field_option['option_name'];
                                    }
									}
                                }
                                ?>
                                    <div class="form-group col-lg-4  col-md-4 col-sm-6 col-xs-6 height-label form-field-action-new" id="<?= $key. "_" . $lead_work_flow_section_field['id'] ?>">
                                    <div class="form-field-body-new">
                                        <a href="javascript:void(0);" class="delete-field delete-section-field"  data-value="<?= $lead_work_flow_section_field['id']; ?>"><i class="m-l-10 text-danger fa fa-trash"></i></a>
                                        <a href="javascript:void(0);" class="edit-field edit-section-field" data-section_id="<?= $lead_work_flow_section_field['installation_form_id']; ?>" data-value="<?= $lead_work_flow_section_field['id']; ?>" data-input_id="<?= $lead_work_flow_section_field['input_type_id']; ?>" data-system_data_id="<?= $lead_work_flow_section_field['system_data_id']; ?>" ><i class="m-l-10 fa fa-pencil"></i></a>
                                    </div>
                                    <label for="inputName1" class="control-label col-md-12"><?= (((isset($lead_work_flow_section_field['label_name'])) ? $lead_work_flow_section_field['label_name'] : '')); ?><?= (($lead_work_flow_section_field['required_field'] == _ID_FLAG_YES) ? '<span class="text-danger"> *</span>' : ''); ?></label>
                                    <div class="radio-list">
                                    <?= $this->Form->radio('radio' . $lead_work_flow_section_field['id'], $options, [ 'class' => 'm-l-10']); ?>
                                    </div>
                                    <!--<? $this->Form->select('', $options, [ 'class' => 'form-control', 'required' => false, 'label' => false, 'default' => '']); ?>-->
                                    </div>
                                <?php elseif ($lead_work_flow_section_field['input_type_id'] == _SINGLE_CHOICE) :
                                ?>
                                <?php
                                $options = [];
                                $options[''] = '-- Select ' . ((isset($lead_work_flow_section_field['label_name'])) ? $lead_work_flow_section_field['label_name'] : '') . ' --';
                                if (!empty($allFormFieldOptions)) {
                                    foreach ($allFormFieldOptions as $form_field_option) {
									if($lead_work_flow_section_field['id'] == $form_field_option['installation_form_field_id']){
                                    $options[$form_field_option['option_value']] = $form_field_option['option_name'];
                                    }
									}
                                }
                                ?>
                                    <div class="form-group col-lg-4  col-md-4 col-sm-6 col-xs-6 height-label form-field-action-new" id="<?= $key. "_" . $lead_work_flow_section_field['id'] ?>">
                                    <div class="form-field-body-new">
                                        <a href="javascript:void(0);" class="delete-field delete-section-field"  data-value="<?= $lead_work_flow_section_field['id']; ?>"><i class="m-l-10 text-danger fa fa-trash"></i></a>
                                        <a href="javascript:void(0);" class="edit-field edit-section-field" data-section_id="<?= $lead_work_flow_section_field['installation_form_id']; ?>" data-value="<?= $lead_work_flow_section_field['id']; ?>" data-input_id="<?= $lead_work_flow_section_field['input_type_id']; ?>" data-system_data_id="<?= $lead_work_flow_section_field['system_data_id']; ?>" ><i class="m-l-10 fa fa-pencil"></i></a>
                                    </div>
                                    <label for="inputName1" class="control-label col-md-12"><?= (((isset($lead_work_flow_section_field['label_name'])) ? $lead_work_flow_section_field['label_name'] : '')); ?><?= (($lead_work_flow_section_field['required_field'] == _ID_FLAG_YES) ? '<span class="text-danger"> *</span>' : ''); ?></label>
                                    <?= $this->Form->select('', $options, [ 'class' => 'form-control', 'required' => false, 'label' => false, 'default' => '']); ?>
                                    </div>
                                <?php elseif ($lead_work_flow_section_field['input_type_id'] == _MULTIPLE_CHOICE) :
                                ?>
                                <?php
                                $options = [];
                                if (!empty($allFormFieldOptions)) {
                                    foreach ($allFormFieldOptions as $form_field_option) {
									if($lead_work_flow_section_field['id'] == $form_field_option['installation_form_field_id']){
                                    $options[$form_field_option['option_value']] = $form_field_option['option_name'];
                                    }
									}
                                }
                                ?>
                                    <div class="form-group col-lg-4  col-md-4 col-sm-6 col-xs-6 height-label form-field-action-new" id="<?= $key. "_" . $lead_work_flow_section_field['id'] ?>">
                                    <div class="form-field-body-new">
                                        <a href="javascript:void(0);" class="delete-field delete-section-field" data-value="<?= $lead_work_flow_section_field['id']; ?>"><i class="m-l-10 text-danger fa fa-trash"></i></a>
                                        <a href="javascript:void(0);" class="edit-field edit-section-field" data-section_id="<?= $lead_work_flow_section_field['installation_form_id']; ?>" data-value="<?= $lead_work_flow_section_field['id']; ?>" data-input_id="<?= $lead_work_flow_section_field['input_type_id'] ?>" ><i class="m-l-10 fa fa-pencil"></i></a>
                                    </div>
                                    <label for="inputName1" class="control-label col-md-12"><?= ((isset($lead_work_flow_section_field['label_name'])) ? $lead_work_flow_section_field['label_name'] : ''); ?><?= (($lead_work_flow_section_field['required_field'] == _ID_FLAG_YES) ? '<span class="text-danger"> *</span>' : ''); ?> </label>
                                    <?= $this->Form->select('', $options, [ 'class' => 'selectpicker', 'multiple' => true, 'data-style' => "form-control", 'required' => false, 'label' => false, 'default' => '']); ?>
                                    </div>
                                <?php elseif ($lead_work_flow_section_field['input_type_id'] == _INPUT_TYPE_DATE) :
                                ?>
                                    <div class="form-group col-lg-4  col-md-4 col-sm-6 col-xs-6 height-label form-field-action-new" id="<?= $key. "_" . $lead_work_flow_section_field['id'] ?>">
                                        <div class="form-field-body-new">
                                            <a href="javascript:void(0);" class="delete-field delete-section-field" data-value="<?= $lead_work_flow_section_field['id']; ?>"><i class="m-l-10 text-danger fa fa-trash"></i></a>
                                            <a href="javascript:void(0);" class="edit-field edit-section-field" data-section_id="<?= $lead_work_flow_section_field['installation_form_id']; ?>" data-value="<?= $lead_work_flow_section_field['id']; ?>" data-section_id="<?= $lead_work_flow_section_field['installation_form_id']; ?>" data-input_id="<?= $lead_work_flow_section_field['input_type_id']; ?>" data-system_data_id="<?= $lead_work_flow_section_field['system_data_id']; ?>" ><i class="m-l-10 fa fa-pencil"></i></a>
                                        </div>
                                        <label for="inputName1" class="control-label col-md-12"><?= ((isset($lead_work_flow_section_field['label_name'])) ? $lead_work_flow_section_field['label_name'] : ''); ?><?= (($lead_work_flow_section_field['required_field'] == _ID_FLAG_YES) ? '<span class="text-danger"> *</span>' : ''); ?> </label>
                                        <?php echo $this->Form->control('name', ['type' => 'text', 'required' => true, 'disabled' => 'disabled', 'escape' => false, 'label' => false, 'class' => 'form-control custom_datepicker']); ?>
                                    </div>
                                <?php elseif ($lead_work_flow_section_field['input_type_id'] == _INPUT_TYPE_ONLY_DATE) :
                                    ?>
                                    <div class="form-group col-lg-4  col-md-4 col-sm-6 col-xs-6 height-label form-field-action-new" id="<?= $key. "_" . $lead_work_flow_section_field['id'] ?>">
                                        <div class="form-field-body-new">
                                            <a href="javascript:void(0);" class="delete-field delete-section-field" data-value="<?= $lead_work_flow_section_field['id']; ?>"><i class="m-l-10 text-danger fa fa-trash"></i></a>
                                            <a href="javascript:void(0);" class="edit-field edit-section-field" data-section_id="<?= $lead_work_flow_section_field['lead_work_flow_section_id']; ?>" data-value="<?= $lead_work_flow_section_field['id']; ?>" data-section_id="<?= $lead_work_flow_section_field['lead_work_flow_section_id']; ?>" data-input_id="<?= $lead_work_flow_section_field['input_type_id']; ?>"  data-system_data_id="<?= $lead_work_flow_section_field['system_data_id']; ?>"><i class="m-l-10 fa fa-pencil"></i></a>
                                        </div>
                                        <label for="inputName1" class="control-label col-md-12"><?= ((isset($lead_work_flow_section_field['label_name'])) ? $lead_work_flow_section_field['label_name'] : ''); ?><?= (($lead_work_flow_section_field['required_field'] == _ID_FLAG_YES) ? '<span class="text-danger"> *</span>' : ''); ?> </label>
                                        <?php echo $this->Form->control('name', ['type' => 'text', 'required' => true, 'disabled' => 'disabled', 'escape' => false, 'label' => false, 'class' => 'form-control custom_datepicker']); ?>
                                    </div>
                                    <?php
                                endif;
                            }
                            }
                            endforeach;
                            endif;
                            ?>
                        </div>
                        <?php
                        endforeach;
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Input fields modal -->
<div id="fields-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; margin-top: 3% !important;">
    <div class="modal-dialog modal-lg" style="margin-top: 10% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-model" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Edit Field</h4>
            </div>
            <div class="modal-body">
		<?= $this->Form->create(null, ['id' => 'input_field_form']) ?>
                <div id="edit-input-field-message"></div>

                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label for="drop_reason" class="control-label">Form Name :</label>
                    <div class="">
			<?= $this->Form->select('form_id', $section_options, [ 'class' => 'form-control', "disabled" => "disabled", 'id' => 'edit-section-id', 'required' => true, 'label' => false, 'default' => '', 'empty' => '-- Select Section --']); ?>
                    </div>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label for="drop_reason" class="control-label">Input Type :</label>
                    <div class="">
			<?= $this->Form->select('input_type', $input_type_options, [ 'class' => 'form-control', 'id' => 'input-type-id', 'required' => true, 'label' => false, 'default' => '', 'empty' => '-- Select Input Type --']); ?>
                    </div>
                </div>
				<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label for="drop_reason" class="control-label">Map Data :</label>
                    <div class="">
					<?= $this->Form->select('system_data_id', $system_data_options, [ 'class' => 'form-control', 'id' => 'system_data_id', 'required' => false, 'label' => false, 'default' => 0]); ?>
                    </div>
                </div>
                <div id="ajax_input_form"></div>
		<?= $this->Form->end(); ?>
            </div>
            <div class="modal-footer">
                <button type="button"  class="btn btn-default waves-effect waves-light close-model" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info waves-effect waves-light" id="edit_input_field">Save</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">

$(document).ready(function () {
    $('#installation_type').prop('disabled',true);
	//Hide Add field form
	$('#add-section-body').hide();
    });

    $(document).on('click', '#add-section', function () {
	//Add disable attribute in add field button
	$(this).attr("disabled", "disabled");
	//Chance add field button color
	$(this).removeClass("btn-success");
	//Show Add field form
	$('#add-section-body').show();
    });

    //Cancle Section Field
    $(document).on('click', '#cancle-section-field', function () {
	//Reset Form
	$('#addInstallationFormField')[0].reset();
	//Remove Append Html
	$('#input-html').html('');
	//Hide Add field form
	$('#add-section-body').hide();
	//Remove disable attribute in add field button
	$('#add-section').removeAttr("disabled");
	//Chance add field button color
	$('#add-section').addClass("btn-success");
    });

    //Save Field
    $(document).on('click', '#save-section-field', function () {
//	alert('save field');
	$('#addInstallationFormField').validate();
	if ($('#addInstallationFormField').valid()) {
	    $.ajax({
		url: base_url + 'InstallationForms/saveInputField',
		type: "POST",
		data: new FormData($('#addInstallationFormField')[0]),
		processData: false,
		contentType: false,
		success: function (results) {
		    var result = jQuery.parseJSON(results);
//                    console.log(result);
		    if (result.status == 1) {
			var html = '<div class="alert alert-success alert-dismissable">';
			html += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
			html += result.message;
			html += '</div>';
			$('#input-field-message').html(html);
			setTimeout(function () {
			    $('#input-field-message').html('');
			    location.reload();
			}, 1000);
		    } else {
			var html = '<div class="alert alert-danger alert-dismissable">';
			html += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
			html += result.message;
			if (!jQuery.isEmptyObject(result.error_msg)) {
			    html += '</br></br> Validation : </br>';
			    for (key in result.error_msg) {
				if (result.error_msg.hasOwnProperty(key)) {
				    html += '</br>' + key + ' : ' + result.error_msg[key];
				}
			    }
			}
			html += '</div>';
			$('#input-field-message').html(html);
			setTimeout(function () {
			    $('#input-field-message').html('');
			}, 3000);
		    }
		}
	    });
	}
	$('#add-section').removeAttr("disabled");
	$('#add-section').addClass("btn-success");
    });

	//Delete Field
    $(document).on('click', '.delete-section-field', function () {
	var form_field_id = $(this).data('value');
	swal({
	    title: "Are you sure?",
	    text: "Once deleted, you will not be able to recover this input field!",
	    type: "warning",
	    showCancelButton: true,
	    confirmButtonColor: "#DD6B55",
	    confirmButtonText: "Yes, Delete it!",
	    closeOnConfirm: false
	}, function () {
	    $.ajax({
		url: base_url + 'InstallationForms/deleteInputField',
		type: "POST",
		dataType: 'json',
		data: {'form_field_id': form_field_id},
		success: function (result) {
		    if (result.status == <?= _ID_STATUS_ACTIVE; ?>) {
			swal("Done!", result.message, "success");
			$('.confirm').hide();
			setTimeout(function () {
			    location.reload();
			}, 1000);
		    } else {
			msg = result.message;
			errorMessage(msg);
		    }
		},
		error: function (jqXHR, exception) {
		    msg = "Something went wrong.";
		    errorMessage(msg);
		}
	    });
	});
    });

    //Save Field
    $(document).on('click', '#edit_input_field', function () {
	$('#addLeadWorkFlowField').validate();
	if ($('#input_field_form').valid()) {
	    $.ajax({
		url: base_url + 'InstallationForms/saveInputField',
		type: "POST",
		data: new FormData($('#input_field_form')[0]),
		processData: false,
		contentType: false,
		success: function (results) {
		    var result = jQuery.parseJSON(results);
		    console.log(result);
		    if (result.status == 1) {
			var html = '<div class="alert alert-success alert-dismissable">';
			html += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
			html += result.message;
			html += '</div>';
			$('#edit-input-field-message').html(html);
			setTimeout(function () {
			    $('#input-field-message').html('');
			    location.reload();
			}, 1000);
		    } else {
			var html = '<div class="alert alert-danger alert-dismissable">';
			html += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
			html += result.message;
			if (!jQuery.isEmptyObject(result.error_msg)) {
			    html += '</br></br> Validation : </br>';
			    for (key in result.error_msg) {
				if (result.error_msg.hasOwnProperty(key)) {
				    html += '</br>' + key + ' : ' + result.error_msg[key];
				}
			    }
			}
			html += '</div>';
			$('#edit-input-field-message').html(html);
			setTimeout(function () {
			    $('#input-field-message').html('');
			}, 3000);
		    }
		}
	    });
	}
    });
    //Edit Field
    $(document).on('click', '.edit-section-field', function () {
	var form_field_id = $(this).data('value');
	var input_id = $(this).data('input_id');
	var section_id = $(this).data('section_id');
	var system_data_id = $(this).data('system_data_id');
	system_data_id = (system_data_id > 0) ? system_data_id : "";
	if (parseInt(form_field_id) > 0) {
	    $.ajax({
		url: base_url + 'InstallationForms/editField',
		type: "POST",
		data: {'edit_id': parseInt(form_field_id)},
		dataType: 'json',
		success: function (result) {//alert(result.html);
		    if (result.status == 1) {
			var html = result.html;
			$('#ajax_input_form').html(html);
			} else {
			var html = '<div class="alert alert-danger alert-dismissable">';
			html += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
			html += result.message;
			html += '</div>';
			$('#input-field-message').html(html);
			setTimeout(function () {
			    $('#escalate-modal').modal('toggle');
			    $('#input-field-message').html('');
			}, 1000);
		    }
		}
	    });
	} else {
	    $('#ajax_input_form').html('');
	}

	$('.modal-title').text('Edit Field');
	$('#input-type-id').val(input_id);
	$('#input-type-id').attr("disabled", "disabled");
	$('#edit-section-id').val(section_id);
	$('#edit-section-id').attr("disabled", "disabled");
	$('#system_data_id').val(system_data_id);

	var options = {
	    'backdrop': 'static',
	    'show': true,
	}
	$("#fields-modal").modal(options);
    });


$(document).on('change', '#input_type_id', function () {
	switch (parseInt($(this).val())) {
	    case <?= _INPUT_TYPE; ?>:
		// Append input type html
		var html = inputTypeHtml();
		$('#input-html').html(html);
		break;
		case <?= _INPUT_TYPE_NOTES; ?>:
		// Append single choice type html
		var html = notesHtml();
        $('#input-html').html(html);
		break;
		case <?= _INPUT_TYPE_CHECKBOX; ?>:
		// Append single choice type html
		var html = checkboxHtml();
		$('#input-html').html(html);
		break;
	    case <?= _SINGLE_CHOICE; ?>:
		// Append single choice type html
		var html = singleChoiceTypeHtml();
		$('#input-html').html(html);
		break;
	    case <?= _MULTIPLE_CHOICE; ?>:
		// Append multiple choice type html
		var html = multipleChoiceTypeHtml();
		$('#input-html').html(html);
		break;
	    case <?= _FILE; ?>:
		// Append multiple choice type html
		var html = uploadTypeHtml();
		$('#input-html').html(html);
		break;
	    case <?= _INPUT_TYPE_SIGNATURE; ?>:
		// Append multiple choice type html
		var html = signatureTypeHtml();
		$('#input-html').html(html);
		break;
	    case <?= _INPUT_TYPE_RADIO; ?>:
		// Append multiple choice type html
		var html = radioTypeHtml();
		$('#input-html').html(html);
		break;
		case <?= _INPUT_TYPE_DATE; ?>:
			// Append input type html
			var html = dateTypeHtml();
			$('#input-html').html(html);
		break;
		case <?= _INPUT_TYPE_ONLY_DATE; ?>:
			// Append input type html
			var html = onlyDateTypeHtml();
			$('#input-html').html(html);
		break;
	    default:
		//Append Blank html
		$('#input-html').html('');
	}
    });
    //Input type Html
    var inputTypeHtml = function () {
	var html = '';
	var html = '<div class="form-group col-lg-3 col-md-4  col-sm-6">';
	html += '<label for="inputName1" class="control-label col-md-12"> Label Name <span class="text-danger"> *</span></label>';
	html += '<input type="text" name="label_name" class="form-control" placeholder="Label Name" required="required" >';
	html += '</div>';
	
    html += '<div class="form-group col-lg-3 col-md-4  col-sm-6"><div class="input-group m-t-30">';
    html += '<label for="attachment_multiple" class="custom-checkbox-report m-l-5">Image Attachment<input type="checkbox" name="attachment_multiple" class="lead_status_filters" id="attachment_multiple" value="1" style="position: absolute; opacity: 0;">';
    html += '<span class="checkmark"></span></label></div></div>';
    html += '<div class="clearfix"></div>';

    html += '<div class="form-group col-lg-3 col-md-4  col-sm-6"><div class="input-group m-t-30">';
    html += '<label for="required_field" class="custom-checkbox-report m-l-5">Required<input type="checkbox" name="required_field" class="lead_status_filters" id="required_field" value="1" style="position: absolute; opacity: 0;">';
    html += '<span class="checkmark"></span></label></div></div>';

	return html;
    }

	//Notes  Html
    var notesHtml = function () {
	var html = '';
	var html = '<div class="form-group col-lg-3 col-md-4  col-sm-6">';
	html += '<label for="inputName1" class="control-label col-md-12"> Label Name <span class="text-danger"> *</span></label>';
	html += '<input type="text" name="label_name" class="form-control" placeholder="Label Name" required="required" >';
	html += '</div>';

	html += '<div class="form-group col-lg-3 col-md-4  col-sm-6"><div class="input-group m-t-30">';
    html += '<label for="required_field" class="custom-checkbox-report m-l-5">Required<input type="checkbox" name="required_field" id="required_field" class="custom-checkbox-report" value="1" style="position: absolute; opacity: 0;">';
    html += '<span class="checkmark"></span></label></div></div>';


	return html;
    }

	//Checkbox  Html
    var checkboxHtml = function () {
	var html = '';
	var html = '<div class="form-group col-lg-3 col-md-4  col-sm-6">';
	html += '<label for="inputName1" class="control-label col-md-12"> Label Name <span class="text-danger"> *</span></label>';
	html += '<input type="textarea" name="label_name" class="form-control" placeholder="Label Name" required="required" >';
	html += '</div>';

	return html;
    }
    //Upload type Html
    var uploadTypeHtml = function () {
	var html = '';
	var html = '<div class="form-group col-lg-3 col-md-4  col-sm-6">';
	html += '<label for="inputName1" class="control-label col-md-12"> Label Name <span class="text-danger"> *</span></label>';
	html += '<input type="text" name="label_name" class="form-control" placeholder="Label Name" required="required" >';
	html += '</div>';
	
    html += '<div class="form-group col-lg-3 col-md-4  col-sm-6"><div class="input-group m-t-30">';
    html += '<label for="required_field" class="custom-checkbox-report m-l-5">Required<input type="checkbox" name="required_field" class="lead_status_filters" id="required_field" value="1" style="position: absolute; opacity: 0;">';
    html += '<span class="checkmark"></span></label></div></div>';

	return html;
    }

	 //Signature type Html
	 var signatureTypeHtml = function () {
	var html = '';
	var html = '<div class="form-group col-lg-3 col-md-4  col-sm-6">';
	html += '<label for="inputName1" class="control-label col-md-12"> Label Name <span class="text-danger"> *</span></label>';
	html += '<input type="text" name="label_name" class="form-control" placeholder="Label Name" required="required" >';
	html += '</div>';
	
    html += '<div class="form-group col-lg-3 col-md-4  col-sm-6"><div class="input-group m-t-30">';
    html += '<label for="required_field" class="custom-checkbox-report m-l-5">Required<input type="checkbox" name="required_field" class="lead_status_filters" id="required_field" value="1" style="position: absolute; opacity: 0;">';
    html += '<span class="checkmark"></span></label></div></div>';

	return html;
    }
    //Radio Type Html
    var radioTypeHtml = function () {
	var html = '';
	var html = '<div class="form-group col-lg-3 col-md-4  col-sm-6">';
	html += '<label for="inputName1" class="control-label col-md-12"> Label Name <span class="text-danger"> *</span></label>';
	html += '<input type="text" name="label_name" class="form-control" placeholder="Label Name" required="required" >';
	html += '</div>';
	
    html += '<div class="form-group col-lg-3 col-md-4  col-sm-6"><div class="input-group m-t-30">';
    html += '<label for="attachment_multiple" class="custom-checkbox-report m-l-5">Image Attachment<input type="checkbox" name="attachment_multiple" class="lead_status_filters" id="attachment_multiple" value="1" style="position: absolute; opacity: 0;">';
    html += '<span class="checkmark"></span></label></div></div>';
    
    html += '<div class="clearfix"></div>';
	
    html += '<div class="form-group col-lg-3 col-md-4  col-sm-6"><div class="input-group m-t-30">';
    html += '<label for="required_field" class="custom-checkbox-report m-l-5">Required<input type="checkbox" name="required_field" class="lead_status_filters" id="required_field" value="1" style="position: absolute; opacity: 0;">';
    html += '<span class="checkmark"></span></label></div></div>';

	html += '<div class="clearfix"></div>';

	html += '<div class="col-lg-12 parent-option-form">';
	html += '<div class="row"><label class="col-sm-4">Option Name</label><label class="col-sm-4">Value</label><label class="col-sm-4"></label></div>';
	html += '<div class ="option-form"><div class="row"><div class="col-sm-4"><input type="text" class="form-control" name="option_name[]" required="required"></div>';
	html += '<div class="col-sm-4"><input type="text" class="form-control" name="option_value[]" required="required"></div>';
	html += '<div class="col-sm-4 add-more-button"> <button type="button" class="btn-success add-button-reduction add-more-options" ><i class="fa fa-plus"></i></button></div>';
	html += '</div></div>';
	return html;
    }
    //Single Choice Type Html
    var singleChoiceTypeHtml = function () {
	var html = '';
	var html = '<div class="form-group col-lg-3 col-md-4  col-sm-6">';
	html += '<label for="inputName1" class="control-label col-md-12"> Label Name <span class="text-danger"> *</span></label>';
	html += '<input type="text" name="label_name" class="form-control" placeholder="Label Name" required="required" >';
	html += '</div>';
	
    html += '<div class="form-group col-lg-3 col-md-4  col-sm-6"><div class="input-group m-t-30">';
    html += '<label for="attachment_multiple" class="custom-checkbox-report m-l-5">Image Attachment<input type="checkbox" name="attachment_multiple" class="lead_status_filters" id="attachment_multiple" value="1" style="position: absolute; opacity: 0;">';
    html += '<span class="checkmark"></span></label></div></div>';


    html += '<div class="clearfix"></div>';
	
    html += '<div class="form-group col-lg-3 col-md-4  col-sm-6"><div class="input-group m-t-30">';
    html += '<label for="required_field" class="custom-checkbox-report m-l-5">Required<input type="checkbox" name="required_field" class="lead_status_filters" id="required_field" value="1" style="position: absolute; opacity: 0;">';
    html += '<span class="checkmark"></span></label></div></div>';

	html += '<div class="clearfix"></div>';

	html += '<div class="col-lg-12 parent-option-form">';
	html += '<div class="row"><label class="col-sm-4">Option Name</label><label class="col-sm-4">Value</label><label class="col-sm-4"></label></div>';
	html += '<div class ="option-form"><div class="row"><div class="col-sm-4"><input type="text" class="form-control" name="option_name[]" required="required"></div>';
	html += '<div class="col-sm-4"><input type="text" class="form-control" name="option_value[]" required="required"></div>';
	html += '<div class="col-sm-4 add-more-button"> <button type="button" class="btn-success add-button-reduction add-more-options" ><i class="fa fa-plus"></i></button></div>';
	html += '</div></div>';
	return html;
    }
    //Multiple Choice type html
    var multipleChoiceTypeHtml = function () {
	var html = '';
	var html = '<div class="form-group col-lg-3 col-md-4  col-sm-6">';
	html += '<label for="inputName1" class="control-label col-md-12"> Label Name <span class="text-danger"> *</span></label>';
	html += '<input type="text" name="label_name" class="form-control" placeholder="Label Name" required="required" >';
	html += '</div>';
	
    html += '<div class="form-group col-lg-3 col-md-4  col-sm-6"><div class="input-group m-t-30">';
    html += '<label for="attachment_multiple" class="custom-checkbox-report m-l-5">Image Attachment<input type="checkbox" name="attachment_multiple" class="lead_status_filters" id="attachment_multiple" value="1" style="position: absolute; opacity: 0;">';
    html += '<span class="checkmark"></span></label></div></div>';
	
    html += '<div class="clearfix"></div>';
	
    html += '<div class="form-group col-lg-3 col-md-4  col-sm-6"><div class="input-group m-t-30">';
    html += '<label for="required_field" class="custom-checkbox-report m-l-5">Required<input type="checkbox" name="required_field" class="lead_status_filters" id="required_field" value="1" style="position: absolute; opacity: 0;">';
    html += '<span class="checkmark"></span></label></div></div>';

	html += '<div class="clearfix"></div>';

	html += '<div class="col-lg-12 parent-option-form">';
	html += '<div class="row"><label class="col-sm-4">Option Name</label><label class="col-sm-4">Value</label><label class="col-sm-4"></label></div>';
	html += '<div class ="option-form"><div class="row"><div class="col-sm-4"><input type="text" class="form-control" name="option_name[]" required="required"></div>';
	html += '<div class="col-sm-4"><input type="text" class="form-control" name="option_value[]" required="required"></div>';
	html += '<div class="col-sm-4 add-more-button"> <button type="button" class="btn-success add-button-reduction add-more-options" ><i class="fa fa-plus"></i></button></div>';
	html += '</div></div>';
	return html;
    }
    //Date type Html
    var dateTypeHtml = function () {
	var html = '';
	var html = '<div class="form-group col-lg-3 col-md-4  col-sm-6" style="min-height: 70px;">';
	html += '<label for="inputName1" class="control-label col-md-12"> Label Name <span class="text-danger"> *</span></label>';
	html += '<input type="text" name="label_name" class="form-control" placeholder="Label Name" required="required" >';
	html += '</div>';
	
    html += '<div class="form-group col-lg-3 col-md-4  col-sm-6"><div class="input-group m-t-30">';
    html += '<label for="required_field" class="custom-checkbox-report m-l-5">Required<input type="checkbox" name="required_field" class="lead_status_filters" id="required_field" value="1" style="position: absolute; opacity: 0;">';
    html += '<span class="checkmark"></span></label></div></div>';

	return html;
    }

    //Only Date type Html
    var onlyDateTypeHtml = function () {
	var html = '';
	var html = '<div class="form-group col-lg-3 col-md-4  col-sm-6" style="min-height: 70px;">';
	html += '<label for="inputName1" class="control-label col-md-12"> Label Name <span class="text-danger"> *</span></label>';
	html += '<input type="text" name="label_name" class="form-control" placeholder="Label Name" required="required" >';
	html += '</div>';
	
    html += '<div class="form-group col-lg-3 col-md-4  col-sm-6"><div class="input-group m-t-30">';
    html += '<label for="required_field" class="custom-checkbox-report m-l-5">Required<input type="checkbox" name="required_field" class="lead_status_filters" id="required_field" value="1" style="position: absolute; opacity: 0;">';
    html += '<span class="checkmark"></span></label></div></div>';

	return html;
    }

     //Add More options
     $(document).on('click', '.add-more-options', function () {
	var closest_add_more_button = $(this).closest('.add-more-button');
	var closest_option_form = $(this).closest('.option-form');
	var closest_parent_option_form = closest_option_form.closest('.parent-option-form');
	var total_option_form = 1;
	$(closest_parent_option_form).find('.option-form').each(function () {
	    total_option_form++;
	});

	var inner_html = '<div class ="m-t-10 option-form">';
	inner_html += closest_option_form.html();
	inner_html += '</div>';
	inner_html = inner_html.replace(/value\=/g, ' ');
	$(closest_parent_option_form).append(inner_html);
	if (total_option_form > 1) {
	    $(closest_add_more_button).html('<a href="javascript:void(0);" class="remove_button remove-button-reduction" title="Remove field"><i class="fa fa-remove"></i></a>');
	}
    });
    //Remove option
    $(document).on('click', '.remove_button', function () {
	var reduction_price_sub_parent = $(this).closest('.option-form');
	$(reduction_price_sub_parent).remove();
    });

    //Sortable Field
    $(function () {
	$(".draggable").draggable({
	    connectToSortable: "#sortable-section",
	    helper: "clone",
	    revert: "invalid",
	});
	$(".sortable-field").sortable({
	    start: function (event, ui) {
		// creates a temporary attribute on the element with the old index
		$(this).attr('data-previndex', ui.item.index());
	    },
	    update: function (event, ui) {
		//Get section_id
		var element_id = ui.item.attr('id');
		var element_id_in_array = element_id.split("_");
		var section_id = element_id_in_array[0];
		var itemOrder = $('.section_' + section_id).sortable("toArray");
		$.ajax({
		    url: base_url + 'InstallationForms/changeFieldOrder',
		    type: "POST",
		    dataType: 'json',
		    data: {'form_field_order': itemOrder},
		    success: function (result) {
		    }
		});
	    },
	    revert: true
	});
	$("#sortable-section").sortable({
	    revert: true
	});

	$(".sortable-field").disableSelection();
    });

	// This below code is for saving the flags
	// to the installation_product_types table
	// by calling ajax call on the every checkbox
	// of addproduct row.
	$(document).ready(function(){
		var site_check = "<?= $check_marks['site_audit_flag'] ?>";
		var installation_check = "<?= $check_marks['installation_flag'] ?>";
		var qc_check = "<?= $check_marks['qc_flag'] ?>";
		if(site_check == <?=_ID_FLAG_YES?>){
		$('#site_audit_flag').iCheck('check');
		}

		if(installation_check == <?=_ID_FLAG_YES?>){
		$('#installation_flag').iCheck('check');
		}

		if(qc_check == <?=_ID_FLAG_YES?>){
		$('#qc_flag').iCheck('check');
		}
		
	});
	
	function checkFlagAjax(check_flag,check_value){
		var installation_type = $('#installation_type').val();
		$.ajax({
		url: base_url + 'InstallationForms/skipFunction',
		type: "POST",
		data: {'check_flag':check_flag ,'check_value':check_value,'installation_type':installation_type},
	    });

	}

	$('.addproduct :input[type="checkbox"]').on('change',function(){
		var check_flag = $(this).val();
		if(this.checked) {
            var check_value = "<?= _ID_FLAG_YES ?>";
        }else{
            var check_value = "<?= _ID_FLAG_NO ?>";    
        }
		checkFlagAjax(check_flag,check_value);
	}); 
</script>