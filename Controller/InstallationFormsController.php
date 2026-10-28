<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\Exception\Exception;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\Mailer\Email;
use Cake\Routing\Router;
use \Cake\Log\Log;


class InstallationFormsController extends AppController
{
     public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->viewBuilder()->layout('dashboard');
        $this->loadModel('InputTypes');
	    $this->loadModel('InstallationFormFields');
	    $this->loadModel('InstallationFormOptions');
	    $this->loadModel('InstallationProductTypes');
    }    
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {   
        $installation_type = getEntitySet(_ID_INSTALLATION_PRODUCT_TYPE);
        $installationType = [];
        foreach($installation_type as $type){
            $installationType[$type[3]] = $type[1];
        }
        // pr($installationType);die;
        $this->set(compact('installationType'));

    }

    public function edit($id = null)
    {   
        $inputTypes = $this->InputTypes->getInputTypes();
        $allFormField = $this->InstallationFormFields->getInstallationFields();
        $allFormFieldOptions = $this->InstallationFormOptions->getInstallationFieldOptions();
        $installation_type = getEntitySet(_ID_INSTALLATION_PRODUCT_TYPE);
        $installationType = [];
        foreach($installation_type as $type){
            $installationType[$type[3]] = $type[1];
        }

        $check_marks = $this->InstallationProductTypes->find()->where(['id'=>$id])->first();
        //Add Css File
        $css_links = [
            '/plugins/bower_components/sweetalert/sweetalert.css'
        ];
        //Add Js file
        $js_links = [
            '/plugins/bower_components/sweetalert/sweetalert.min.js',
            '/plugins/bower_components/sweetalert/jquery.sweet-alert.custom.js'
        ];
            // pr($installationType);die;
            $this->set(compact('installationType','id','inputTypes','allFormField','allFormFieldOptions','css_links','js_links','check_marks'));
        }

    /**
     * Svae Input Field
     */
    public function saveInputField() {
        if ($this->request->is('ajax')) {
            $json = [];
            $message = '';
            $status = _ID_FLAG_NO;
            $html = '';
            try { //pr($this->request->data);die;
            if ((!isset($this->request->data['form_id'])) || (empty($this->request->data['form_id']))):
                throw new Exception("Please select Form.");
            endif;
    
            if ((!isset($this->request->data['input_type'])) || (empty($this->request->data['input_type']))):
                throw new Exception("Please select input type");
            endif;
    
            if ((!isset($this->request->data['label_name'])) || (empty($this->request->data['label_name']))):
                throw new Exception("Please enter label name");
            endif;
            //Validate input type
            $inputType = $this->InputTypes->getInputType($this->request->data['input_type']);
            if (empty($inputType)):
                Log::error('Installation Forms : Input type id not found.');
                throw new Exception("Input type is not found. Please try again.");
            endif;
            if ((isset($this->request->data['edit_id'])) || (!empty($this->request->data['edit_id']))){
            $edit_id = $this->request->data['edit_id'];
            $this->request->data['edit_id'] = $edit_id;
            }
            $this->request->data['status'] = _ID_STATUS_ACTIVE;
            $this->request->data['input_type_id'] = $this->request->data['input_type'];
            $this->request->data['installation_form_id'] = $this->request->data['form_id'];
            // pr($this->request->data);die;
            switch ($inputType) {
                case ($inputType['id'] == _INPUT_TYPE):
                $json = $this->saveInputTypeField($this->request->data);
                break;
                case ($inputType['id'] == _INPUT_TYPE_NOTES):
                $json = $this->saveInputTypeField($this->request->data);
                break;
                case ($inputType['id'] == _INPUT_TYPE_CHECKBOX):
                $json = $this->saveInputTypeField($this->request->data);
                break;
                case ($inputType['id'] == _SINGLE_CHOICE):
                $json = $this->saveMultipleOrSingleChoiceField($this->request->data);
                break;
                case ($inputType['id'] == _MULTIPLE_CHOICE):
                $json = $this->saveMultipleOrSingleChoiceField($this->request->data);
                break;
                case ($inputType['id'] == _FILE):
                $json = $this->saveInputTypeField($this->request->data);
                $status = _ID_FLAG_YES;
                break;
                case ($inputType['id'] == _INPUT_TYPE_SIGNATURE):
                $json = $this->saveInputTypeField($this->request->data);
                $status = _ID_FLAG_YES;
                break;
                case ($inputType['id'] == _INPUT_TYPE_RADIO):
                $json = $this->saveMultipleOrSingleChoiceField($this->request->data);
                $status = _ID_FLAG_YES;
                break;
                case ($inputType['id'] == _INPUT_TYPE_HEADING):
                $json = $this->saveInputTypeField($this->request->data);
                break;
                case ($inputType['id'] == _INPUT_TYPE_DATE):
                    $json = $this->saveInputTypeField($this->request->data);
                break;
                case ($inputType['id'] == _INPUT_TYPE_ONLY_DATE):
                    $json = $this->saveInputTypeField($this->request->data);
                break;
                default:
                throw new Exception("Input type not found.");
            }
            } catch (Exception $e) {
            $json['message'] = $e->getMessage();
            $json['status'] = _ID_FLAG_NO;
            }
            echo json_encode($json);
            die;
        }
        }
    
    /**
     * Delete Input field
     */
    public function deleteInputField() {
        if ($this->request->is('ajax')) {
            $json = [];
            $message = '';
            $status = _ID_FLAG_NO;
            try {
            if ((!isset($this->request->data['form_field_id'])) || (empty($this->request->data['form_field_id']))):
                Log::error('Installation Form Delete: installation_form_field_id is not found in post data.');
                throw new Exception("Something went wrong. Please try again.");
            endif;
            $formFields = $this->InstallationFormFields->getInstallationFieldByIdObject($this->request->data['form_field_id']);
            //Validate Installation forms fields
            if (empty($formFields)):
                Log::error('Instllation Form Delete Delete: record not found.');
                throw new Exception("Input type is not found. Please try again.");
            endif;
            //Update status
            $formFields->status = _ID_STATUS_DELETED;
            if ($this->InstallationFormFields->save($formFields)) {
                $json['message'] = 'The form field has been deleted.';
                $json['status'] = _ID_FLAG_YES;
            } else {
                Log::error('Instllation Form Delete: record not updated.');
                throw new Exception("The form field could not be deleted. Please, try again.");
            }
            } catch (Exception $ex) {
            $json['message'] = $ex->getMessage();
            $json['status'] = _ID_FLAG_NO;
            }
            echo json_encode($json);
            die;
        }
        }
       /**
     * Save Input type
     */
    protected function saveInputTypeField($data = []) {
        $json = [];
        $message = '';
        $status = _ID_FLAG_NO;
        $field_id = 0;
        if (!empty($data)) {
            if (isset($data['edit_id']) && !empty($data['edit_id'])):
		    $formFields = $this->InstallationFormFields->getInstallationFieldByIdObject($data['edit_id']);
                if (!empty($formFields)):
                    $field_id = $formFields['id'];
                    $info = [];      
                    $info['label_name'] = $data['label_name'];
                    $info['required_field'] = ((isset($data['required_field'])) && ($data['required_field'] == _ID_FLAG_YES)) ? _ID_FLAG_YES : _ID_FLAG_NO;
                    $info['attachment_multiple'] = ((isset($data['attachment_multiple'])) && ($data['attachment_multiple'] == _ID_FLAG_YES)) ? _ID_FLAG_YES : _ID_FLAG_NO;
                    $info['system_data_id'] = ((isset($data['system_data_id'])) && (!empty($data['system_data_id']))) ? $data['system_data_id'] : _ID_FLAG_NO;
                    $formFields = $this->InstallationFormFields->patchEntity($formFields, $info);
                else:
                    $formFields = $this->InstallationFormFields->newEntity();
                    $formFields = $this->InstallationFormFields->patchEntity($formFields, $data);
                endif;
            else:
            $formFields = $this->InstallationFormFields->newEntity();
            $formFields = $this->InstallationFormFields->patchEntity($formFields, $data);
            endif;
            //Backend Validation
            if ($formFields->errors()) {
            $error_msg = [];
            foreach ($formFields->errors() as $key => $errors) {
                if (is_array($errors)) {
                foreach ($errors as $error) {
                    $error_msg[$key] = $error;
                }
                } else {
                $error_msg[] = $errors;
                }
            }
            $json['error_msg'] = $error_msg;
            }
            if ($formField = $this->InstallationFormFields->save($formFields)) {
            $status = _ID_FLAG_YES;
            if ($field_id != 0):
                $message = 'The input field has been updated.';
            else:
                $message = 'The input field has been created.';
            endif;
            } else {
            $message = 'The input field could not be created. Please, try again.';
            }
        } else {
            $message = 'Something went wrong. Please, try again.';
        }
        $json['message'] = $message;
        $json['status'] = $status;
        return $json;
        }
    /**
     * Save Multiple/Single Choice field
     */
    protected function saveMultipleOrSingleChoiceField($data = []) {
        $json = [];
        $message = '';
        $status = _ID_FLAG_NO;
        $field_id = 0;
        if (!empty($data)) {
            if (isset($data['edit_id']) && !empty($data['edit_id'])):
		    $formFields = $this->InstallationFormFields->getInstallationFieldByIdObject($data['edit_id']);
            if (!empty($formFields)):
                $field_id = $formFields['id'];
                    $info = [];      
                    $info['label_name'] = $data['label_name'];
                    $info['required_field'] = ((isset($data['required_field'])) && ($data['required_field'] == _ID_FLAG_YES)) ? _ID_FLAG_YES : _ID_FLAG_NO;
                    $info['attachment_multiple'] = ((isset($data['attachment_multiple'])) && ($data['attachment_multiple'] == _ID_FLAG_YES)) ? _ID_FLAG_YES : _ID_FLAG_NO;
                    $info['system_data_id'] = ((isset($data['system_data_id'])) && (!empty($data['system_data_id']))) ? $data['system_data_id'] : _ID_FLAG_NO;
                    $formFields = $this->InstallationFormFields->patchEntity($formFields, $info);
            else:
                $formFields = $this->InstallationFormFields->newEntity();
                $formFields = $this->InstallationFormFields->patchEntity($formFields, $data);
            endif;
            else:
                $formFields = $this->InstallationFormFields->newEntity();
                $formFields = $this->InstallationFormFields->patchEntity($formFields, $data);
            endif;
            //Backend Validation
            if ($formFields->errors()) {
            $error_msg = [];
            foreach ($formFields->errors() as $key => $errors) {
                if (is_array($errors)) {
                foreach ($errors as $error) {
                    $error_msg[$key] = $error;
                }
                } else {
                $error_msg[] = $errors;
                }
            }
            $json['error_msg'] = $error_msg;
            }
            if ($formField = $this->InstallationFormFields->save($formFields)) {
            if (!empty($data['option_name'])) {
                //Delete previous options
		        if ($field_id != 0):
                 $this->InstallationFormOptions->deleteAll(['installation_form_field_id' => $field_id]);
                endif;
                foreach ($data['option_name'] as $key => $option_name) {
                if ((!empty($option_name)) && (isset($data['option_value'][$key])) && ((!empty($data['option_value'][$key])) || (($data['input_type_id'] == _INPUT_TYPE_RADIO)))) {
                    $info = [];
                    $info['installation_form_field_id'] = $formField->id;
                    $info['product_id'] = $formField->product_id;
                    $info['option_name'] = $option_name;
                    $info['option_value'] = $data['option_value'][$key];
                    $info['status'] = _ID_STATUS_ACTIVE;
                    
                    $formFieldOptions = $this->InstallationFormOptions->newEntity();
                    $formFieldOptions = $this->InstallationFormOptions->patchEntity($formFieldOptions, $info);
                    $this->InstallationFormOptions->save($formFieldOptions);
                }
                }
            }
            $status = _ID_FLAG_YES;
            if ($field_id != 0):
                $message = 'The input field has been updated.';
            else:
                $message = 'The input field has been created.';
            endif;
            } else {
            $message = 'The input field could not be created. Please, try again.';
            }
        } else {
            $message = 'Something went wrong. Please, try again.';
        }
        $json['message'] = $message;
        $json['status'] = $status;
        return $json;
        }

    /**
     * Change Display field order
     */
    public function changeFieldOrder() {
        if ($this->request->is('ajax')) {
            $json = [];
            $message = '';
            $status = _ID_FLAG_NO;
            try {
            if ((!isset($this->request->data['form_field_order'])) || (empty($this->request->data['form_field_order']))):
                Log::error('Installation Change Field Order: form_field_order is not found in post data.');
                throw new Exception("Something went wrong. Please try again.");
            endif;
            foreach ($this->request->data['form_field_order'] as $key => $val):
                if (!empty($val)):
                $section_id = 0;
                $field_id = 0;
                $val_in_array = explode('_', $val);
                if (array_key_exists(0, $val_in_array)):
                    $section_id = $val_in_array[0];
                else:
                    Log::error('Installation Change Field Order: form_id is not found in explode data.');
                    throw new Exception("Something went wrong. Please try again.");
                endif;
                if (array_key_exists(0, $val_in_array)):
                    $field_id = $val_in_array[1];
                else:
                    Log::error('Installation Change Field Order: form_id is not found in explode data.');
                    throw new Exception("Something went wrong. Please try again.");
                endif;
                if (($section_id != 0) && ($field_id != 0)):
                    //Update lead_work_flow_section_field order
                    $field_order = $key + 1;
                    echo $field_order;
                    if (!$this->InstallationFormFields->updateAll(['field_order' => $field_order], ['id' => $field_id])):
                    Log::error('Installation Change Field Order: field order is not updated.');
                    endif;
                endif;
                endif;
            endforeach;
            $json['message'] = 'The form field has been deleted.';
            $json['status'] = _ID_FLAG_YES;
            } catch (Exception $ex) {
            $json['message'] = $ex->getMessage();
            $json['status'] = _ID_FLAG_NO;
            }
            echo json_encode($json);
            die;
        }
        }

     /**
     * Edit Lead Work Flow Field
     */
    public function editField() {
        $json = [];
        $message = '';
        $status = _ID_FLAG_NO;
        $html = '';
        try {
            //validate form
            if ((!isset($this->request->data['edit_id'])) && (empty($this->request->data['edit_id']))):
            Log::error('WorkFlow Edit Field: edit_id is not found in post data.');
            throw new Exception("Something went wrong. Please try again.");
            endif;
            //Get form field  details
            $formField = $this->InstallationFormFields->getInstallationFieldById($this->request->data['edit_id']);
                // pr($formField);die();
            if (empty($formField)):
            Log::error('Installation Edit Field: form_id is not found in installation_form_fields table.');
            throw new Exception("form_id is not found. Please try again.");
            endif;
    
            switch ($formField):
            case ($formField['input_type_id'] == _INPUT_TYPE):
                $html = $this->getInputTextHtml($formField);
                $status = _ID_FLAG_YES;
                break;
            case ($formField['input_type_id'] == _INPUT_TYPE_NOTES):
                $html = $this->getNotestHtml($formField);
                $status = _ID_FLAG_YES;
                break;
            case ($formField['input_type_id'] == _INPUT_TYPE_CHECKBOX):
                $html = $this->getCheckboxHtml($formField);
                $status = _ID_FLAG_YES;
                break;
            case ($formField['input_type_id'] == _FILE):
                $html = $this->getInputTextHtml($formField);
                $status = _ID_FLAG_YES;
                break;
            case ($formField['input_type_id'] == _INPUT_TYPE_RADIO):
                $html = $this->getSingleChoiceHtml($formField);
                $status = _ID_FLAG_YES;
                break;
            case ($formField['input_type_id'] == _SINGLE_CHOICE):
                $html = $this->getSingleChoiceHtml($formField);
                $status = _ID_FLAG_YES;
                break;
            case ($formField['input_type_id'] == _MULTIPLE_CHOICE):
                $html = $this->getSingleChoiceHtml($formField);
                $status = _ID_FLAG_YES;
                break;
            case ($formField['input_type_id'] == _INPUT_TYPE_SIGNATURE):
                $html = $this->getNotestHtml($formField);
                $status = _ID_FLAG_YES;
                break;
            case ($formField['input_type_id'] == _INPUT_TYPE_DATE):
                $html = $this->getInputDateHtml($formField);
                $status = _ID_FLAG_YES;
            break;
            case ($formField['input_type_id'] == _INPUT_TYPE_ONLY_DATE):
                $html = $this->getInputDateOnlyHtml($formField);
                $status = _ID_FLAG_YES;
            break;
            default:
                throw new Exception("Input type not found.");
            endswitch;
            $html .='<input type="hidden" name="input_type" class="form-control"  value ="' . ((isset($formField['input_type_id'])) ? $formField['input_type_id'] : 0) . '">';
            $html .='<input type="hidden" name="installation_form_field_id" class="form-control"  value ="' . $formField['id'] . '">';
            $html .='<input type="hidden" name="form_id" class="form-control"  value ="' . $formField['id'] . '">';
            $html .='<input type="hidden" name="product_id" class="form-control"  value ="' . $formField['product_id'] . '">';
                
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        $json['message'] = $message;
        $json['status'] = $status;
        $json['html'] = $html;
    
        echo json_encode($json);
        die;
        }

    /**
     * Get Input Text html
     */
    protected function getInputTextHtml($data = []) {

        $html = '';
        $html .= '<div class="form-group col-lg-5 col-md-6  col-sm-6">';
        $html .= '<label for="inputName1" class="control-label col-md-12"> Label Name <span class="text-danger"> *</span></label>';
        $html .= '<input type="text" name="label_name" value="' . ((isset($data['label_name'])) ? $data['label_name'] : '') . '" class="form-control" placeholder="Label Name" required="required" >';
        $html .= '</div>';
        
        $html .= '<div class="form-group col-lg-3 col-md-6  col-sm-6 custom-top"><div class="input-group m-t-10">';
        $html .= '<label for="attachment_multiple" class="custom-checkbox-report m-l-5">Image Attachment<input type="checkbox" name="attachment_multiple" id="attachment_multiple" class="lead_status_filters" value="1" style="position: absolute; opacity: 0;"' . ((isset($data['attachment_multiple']) && ($data['attachment_multiple'] == _ID_FLAG_YES)) ? 'checked' : '' ) . '>';
        $html .= '<span class="checkmark"></span></label></div></div>';

        $html .= '<div class="form-group col-lg-3 col-md-6  col-sm-6 custom-top"><div class="input-group m-t-10">';
        $html .= '<label for="required_field" class="custom-checkbox-report m-l-5">Required<input type="hidden" name="edit_id" value = "'.((isset($data['id'])) ? $data['id'] : '').'"><input type="checkbox" name="required_field" id="required_field" class="lead_status_filters" value="1" style="position: absolute; opacity: 0;"' . ((isset($data['required_field']) && ($data['required_field'] == _ID_FLAG_YES)) ? 'checked' : '' ) . '>';
        $html .= '<span class="checkmark"></span></label></div></div>';

        return $html;
        }

        protected function getNotestHtml($data = []) {

            $html = '';
            $html .= '<div class="form-group col-lg-5 col-md-6  col-sm-6">';
            $html .= '<label for="inputName1" class="control-label col-md-12"> Label Name <span class="text-danger"> *</span></label>';
            $html .= '<input type="text" name="label_name" value="' . ((isset($data['label_name'])) ? $data['label_name'] : '') . '" class="form-control" placeholder="Label Name" required="required" >';
            $html .= '</div>';
    
            $html .= '<div class="form-group col-lg-3 col-md-6  col-sm-6 custom-top"><div class="input-group m-t-10">';
            $html .= '<label for="required_field" class="custom-checkbox-report m-l-5">Required<input type="hidden" name="edit_id" value = "'.((isset($data['id'])) ? $data['id'] : '').'"><input type="checkbox" name="required_field" id="required_field" class="lead_status_filters" value="1" style="position: absolute; opacity: 0;"' . ((isset($data['required_field']) && ($data['required_field'] == _ID_FLAG_YES)) ? 'checked' : '' ) . '>';
            $html .= '<span class="checkmark"></span></label></div></div>';
            
            return $html;
            }

            protected function getCheckboxHtml($data = []) {

                $html = '';
                $html .= '<div class="form-group col-lg-5 col-md-6  col-sm-6">';
                $html .= '<label for="inputName1" class="control-label col-md-12"> Label Name <span class="text-danger"> *</span></label>';
                $html .= '<input type="text" name="label_name" value="' . ((isset($data['label_name'])) ? $data['label_name'] : '') . '" class="form-control" placeholder="Label Name" required="required" >';
                $html .= '</div>';
                
                $html .= '<input type="hidden" name="edit_id" value = "'.((isset($data['id'])) ? $data['id'] : '').'">';
                return $html;
                }
    
        /**
         * Get Single Choice html
         */
        protected function getSingleChoiceHtml($data = []) {
    
        $html = '';
        $html .= '<div class="form-group col-lg-5 col-md-6  col-sm-6">';
        $html .= '<label for="inputName1" class="control-label col-md-12"> Label Name <span class="text-danger"> *</span></label>';
        $html .= '<input type="text" name="label_name" value="' . ((isset($data['label_name'])) ? $data['label_name'] : '') . '" class="form-control" placeholder="Label Name" required="required" >';
        $html .= '</div>';
        
        $html .= '<div class="form-group col-lg-6 col-md-3  col-sm-6 custom-top"><div class="input-group m-t-10">';
        $html .= '<label for="attachment_multiple" class="custom-checkbox-report m-l-5">Image Attachment<input type="checkbox" name="attachment_multiple" id="attachment_multiple" class="lead_status_filters" value="1" style="position: absolute; opacity: 0;"' . ((isset($data['attachment_multiple']) && ($data['attachment_multiple'] == _ID_FLAG_YES)) ? 'checked' : '' ) . '>';
        $html .= '<span class="checkmark"></span></label></div></div>';

        $html .= '<div class="form-group col-lg-6 col-md-3  col-sm-6 custom-top"><div class="input-group m-t-10">';
        $html .= '<label for="required_field" class="custom-checkbox-report m-l-5">Required<input type="hidden" name="edit_id" value = "'.((isset($data['id'])) ? $data['id'] : '').'"><input type="checkbox" name="required_field" id="required_field" class="lead_status_filters" value="1" style="position: absolute; opacity: 0;"' . ((isset($data['required_field']) && ($data['required_field'] == _ID_FLAG_YES)) ? 'checked' : '' ) . '>';
        $html .= '<span class="checkmark"></span></label></div></div>';

    
        $html .= '<div class="clearfix"></div>';
    
        $html .= '<div class="col-lg-12 parent-option-form">';
        $html .= '<div class="row"><label class="col-sm-4">Option Name</label><label class="col-sm-4">Value</label><label class="col-sm-4"></label></div>';
        if ((isset($data['installation_form_options']) ) && (!empty($data['installation_form_options'])) && (is_array($data['installation_form_options']))):
            $total_option = count($data['installation_form_options']);
            foreach ($data['installation_form_options'] as $option):
            $html .= '<div class ="option-form m-t-10"><div class="row"><div class="col-sm-4">';
            $html .= '<input type="text" class="form-control" name="option_name[]" required="required" value="' . $option['option_name'] . '"></div>';
            $html .= '<div class="col-sm-4">';
            $html .= '<input type="text" class="form-control" name="option_value[]" required="required" value="' . $option['option_value'] . '"></div>';
            $html .='<div class="col-sm-4 add-more-button">' . (($total_option != 1) ? '
                                            <a href="javascript:void(0);" class="remove_button remove-button-reduction" title="Remove field"><i class="fa fa-remove"></i></a>' :
                    '<button type="button" class="btn-success add-button-reduction add-more-options" ><i class="fa fa-plus"></i></button>' ) . '
                                        </div>';
            $html .= '</div></div>';
            $total_option --;
            endforeach;
        else:
            $html .= '<div class ="option-form"><div class="row"><div class="col-sm-4"><input type="text" class="form-control" name="option_name[]" required="required"></div>';
            $html .= '<div class="col-sm-4"><input type="text" class="form-control" name="option_value[]" required="required"></div>';
            $html .= '<div class="col-sm-4 add-more-button"> <button type="button" class="btn-success add-button-reduction add-more-options" ><i class="fa fa-plus"></i></button></div>';
            $html .= '</div></div>';
        endif;
        return $html;
        }
    
        /**
         * Get Input Date html
         */
        protected function getInputDateHtml($data = []) {
    
            $html = '';
            $html .= '<div class="form-group col-lg-6 col-md-6  col-sm-6" style="height: 70px;">';
            $html .= '<label for="inputName1" class="control-label col-md-12"> Label Name <span class="text-danger"> *</span></label>';
            $html .= '<input type="text" name="label_name" value="' . ((isset($data['label_name'])) ? $data['label_name'] : '') . '" class="form-control" placeholder="Label Name" required="required" >';
            $html .= '</div>';
    
            $html .= '<div class="form-group col-lg-6 col-md-6  col-sm-6">';
                $html .= '<label for="installation_type" class="control-label col-md-12"> Installation Type </label>';
                $html .= '<select name="installation_type" id="installation_type" class="form-control">';
                    $html .= '<option value="">-- Select Installation Type --</option>';
                    $installation_data = getEntityOptions(_ID_INSTALLATION_PRODUCT_TYPE);
                    foreach($installation_data as $key => $value){
                        if($data['installation_type'] == $key){
                            $html .= '<option value="'.$key.'" selected="selected">'.$value.'</option>';
                        } else {
                            $html .= '<option value="'.$key.'">'.$value.'</option>';
                        }
                    }
                $html .= '<select>';
            $html .= '</div>';
    
            $html .= '<div class="form-group col-lg-12 col-md-12 col-sm-12 height"><div class="row m-t-20"><div class="col-md-2 input-group">';
            $html .= '<label for="required_field" class="custom-checkbox-report m-l-5">Required<input type="hidden" name="edit_id" value = "'.((isset($data['id'])) ? $data['id'] : '').'"><input type="checkbox" name="required_field" id="required_field" class="lead_status_filters" value="1" style="position: absolute; opacity: 0;"' . ((isset($data['required_field']) && ($data['required_field'] == _ID_FLAG_YES)) ? 'checked' : '' ) . '>';
            $html .= '<span class="checkmark"></span></label></div>';

            $html .= '<div class="col-md-3 input-group"><label for="cancel_field" class="custom-checkbox-report m-l-5">Cancel field<input type="checkbox" name="cancel_field" id="cancel_field" class="lead_status_filters"  value="1" style="position: absolute; opacity: 0;"' . ((isset($data['cancel_field']) && ($data['cancel_field'] == _ID_FLAG_YES)) ? 'checked' : '' ) . '><span class="checkmark"></span></label></div>';

            $html .= '<div class="col-md-4 input-group">';
            $html .= '<label for="add_task" class="custom-checkbox-report m-l-5">Add Installation Task<input type="checkbox" name="add_task" id="add_task" class="lead_status_filters" value="1" style="position: absolute; opacity: 0;"' . ((isset($data['add_task']) && ($data['add_task'] == _ID_FLAG_YES)) ? 'checked' : '' ) . '><span class="checkmark"></span></label></div>';

            $html .= '<div class="col-md-3 input-group"><input type="hidden" name="edit_id" value = "'.((isset($data['id'])) ? $data['id'] : '').'">';
            $html .= '<label for="show_on_calender" class="custom-checkbox-report m-l-5">Show On Calender<input type="checkbox" id="show_on_calender" name="show_on_calender" class="lead_status_filters" value="1" style="position: absolute; opacity: 0;"' . ((isset($data['show_on_calender']) && ($data['show_on_calender'] == _ID_FLAG_YES)) ? 'checked' : '' ) . '><span class="checkmark"></span></label></div>';
            $html .= '</div></div>';
            return $html;
        }
    
        /**
         * Get Input Date html
         */
        protected function getInputDateOnlyHtml($data = []) {
    
            $html = '';
            $html .= '<div class="form-group col-lg-6 col-md-6  col-sm-6" style="height: 70px;">';
            $html .= '<label for="inputName1" class="control-label col-md-12"> Label Name <span class="text-danger"> *</span></label>';
            $html .= '<input type="text" name="label_name" value="' . ((isset($data['label_name'])) ? $data['label_name'] : '') . '" class="form-control" placeholder="Label Name" required="required" >';
            $html .= '</div>';
    
            $html .= '<div class="form-group col-lg-12 col-md-12 col-sm-12 height"><label>';
            $html .= '<div class="input-group m-t-30"><div class="icheckbox_square-red" style="position: relative;">';
            $html .= '<input type="hidden" name="edit_id" value = "'.((isset($data['id'])) ? $data['id'] : '').'">';
            $html .= '<input type="checkbox" name="required_field" class="check "  data-checkbox="icheckbox_square-red" value="1" style="position: absolute; opacity: 0;"' . ((isset($data['required_field']) && ($data['required_field'] == _ID_FLAG_YES)) ? 'checked' : '' ) . '></div>';
            $html .= '<label for="required" class="m-l-5">Required</label></div></label>';
            return $html;
        }

        public function skipFunction(){
            $installation_type = $this->request->data('installation_type');
            if($this->request->data('check_flag')){
                if($this->request->data('check_flag') == _STATUS_INSTALLATION_PROCESS_SITE_AUDIT){
                    if($this->request->data('check_value') == _ID_FLAG_YES){
                        $this->InstallationProductTypes->updateAll(['site_audit_flag'=>_ID_FLAG_YES],['id'=>(int)$installation_type]);
                    }else{
                        $this->InstallationProductTypes->updateAll(['site_audit_flag' => _ID_FLAG_NO ],['id'=>(int)$installation_type]);
                    }
                }
                if($this->request->data('check_flag') == _STATUS_INSTALLATION_PROCESS_INSTALLATION){
                    if($this->request->data('check_value') == _ID_FLAG_YES){
                        $this->InstallationProductTypes->updateAll(['installation_flag'=>_ID_FLAG_YES],['id'=>(int)$installation_type]);
                    }else{
                        $this->InstallationProductTypes->updateAll(['installation_flag' => _ID_FLAG_NO ],['id'=>(int)$installation_type]);
                    }
                }
                if($this->request->data('check_flag') == _STATUS_INSTALLATION_PROCESS_QUALITY_CHECK){
                    if($this->request->data('check_value') == _ID_FLAG_YES){
                        $this->InstallationProductTypes->updateAll(['qc_flag'=>_ID_FLAG_YES],['id'=>(int)$installation_type]);
                    }else{
                        $this->InstallationProductTypes->updateAll(['qc_flag' => _ID_FLAG_NO ],['id'=>(int)$installation_type]);
                    }
                }
            }
            die;
        }

}
