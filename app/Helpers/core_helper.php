<?php

if (!function_exists('_')) {
    function _($String) {
        return lang('App.'.$String);
    }
}

if (!function_exists('display_error')) {
    function display_error($validation, $field){
        return ($validation->hasError($field)) ? $validation->getError($field) : false;
    }
}

if (!function_exists('form_html')) {
    function form_html($form) {

        $context = "";
        if(property_exists($form, 'context')){
            $context = $form->context;
        }
        
        $method = "";
        if(property_exists($form, 'method')){
            $method =  " method=\"".$form->method."\"";
        }

        $enctype = "";
        if(property_exists($form, 'enctype')){
            $enctype = " enctype=\"multipart/form-data\"";
        }

        $action = "";
        if(property_exists($form, 'action')){
            $action = " action=\"".site_url($form->action)."\"";
        }

        $class = "form";
        if(property_exists($form, 'class')){
            $class = $form->class;
        }

        $validator = NULL;
        if(property_exists($form, 'validator')){
            $validator = $form->validator;

            if(!IS_NULL($validator)){
                $class .= " was-validated";
            }
        }

        ob_start(); ?>
        <form <?=$method;?><?=$action;?><?=$action;?> class="<?=$class?>" novalidate>
            <?=csrf_field();?>
            <?=$context;?>
        </form>
        <?php return ob_get_clean();
    }
}

if (!function_exists('field_html')) {
    function field_html($field, $values, $validator) {

        $name = "";
        if(property_exists($field, 'name')){
            $name = $field->name;
        }

        if($name!==""){
            $id = $name;
            if(property_exists($field, 'id')){
                $id = $field->id;
            }

            $label = "";
            if(property_exists($field, 'label')){
                $LabelClass = "";
                if($field->type === 'switch'){
                    $LabelClass = " class=\"custom-control-label\"";
                }
                ob_start(); ?><label for="<?=$id?>"<?=$LabelClass;?>><?=_($field->label)?></label><?php
                $label = ob_get_clean();
            }

            $class = "form-group wd-xs-300";
            if(property_exists($field, 'class')){
                $class .= " ".$field->class;
            }

            $helper = "";
            if(property_exists($field, 'helper')){
                ob_start(); ?><div class="valid-feedback"><?=_($field->helper);?></div><?php
                $helper = ob_get_clean();
            }

            if(!IS_NULL($validator)){
                if(IS_OBJECT($validator)){
                    $ErrText = display_error($validator, $name);
                    if($ErrText){
                        $helper = "";
                        $class .= " error";                        
                        ob_start(); ?><div class="invalid-feedback" style="display: block;"><?=$ErrText;?></div><?php
                        $helper = ob_get_clean();                        
                    }
                }
            }

            $placeholder = "";
            if(property_exists($field, 'placeholder')){
                $placeholder = " placeholder=\"".$field->placeholder."\"";
            }

            $value = "";
            if(isset($values[$name])){
                $value = $values[$name];
            }

            $required = "";
            if(property_exists($field, 'required')){
                $required = " required=\"required\"";
            }

            $context = "";
            if(property_exists($field, 'type')){
                if($field->type !== 'select' && $field->type !== 'textarea' && $field->type !== 'view'){
                    ob_start(); ?><input id="<?=$id;?>" name="<?=$name;?>" type="<?=$field->type;?>" value="<?=$value;?>" class="form-control"<?=$placeholder;?><?=$required;?>><?php
                    $context = ob_get_clean();                    
                }

                if($field->type === 'textarea'){
                    ob_start(); ?><textarea id="<?=$id;?>" name="<?=$name;?>" type="<?=$field->type;?>" class="form-control"<?=$placeholder;?><?=$required;?> rows="6"><?=$value;?></textarea><?php
                    $context = ob_get_clean();                    
                }

                if($field->type === 'switch'){
                    $checked = "";
                    if($value !== ""){
                        if(intval($value) === 1){
                            $checked = " checked";
                        }
                    }
                    ob_start(); ?><div class="custom-control custom-switch"><input id="<?=$id;?>" name="<?=$name;?>" type="checkbox" <?=$checked;?> class="custom-control-input"<?=$placeholder;?><?=$required;?>><?=$label;?></div><?php
                    $context = ob_get_clean();
                    $label = "";
                }

                if($value !== '' && $field->type === 'view'){
                    ob_start(); ?><input id="<?=$id;?>" name="<?=$name;?>" type="text" value="<?=$value;?>" class="form-control"<?=$placeholder;?>><?php
                    $context = ob_get_clean();                    
                }else{
                    if($field->type === 'view' && $value === ''){
                        $label = "";
                        $helper = "";    
                    }
                }
            }

            ob_start(); ?>
            <div class="<?=$class;?>">
                <?=$label;?>
                <?=$context;?>
                <?=$helper;?>
            </div>
            <?php return ob_get_clean();            
        }
    }
}

if (!function_exists('form_button_html')) {
    function form_button_html($isDeleted) {
        ob_start(); ?>
        <div style="display: flex;flex-direction: row;justify-content: flex-end;align-items: center;">
            <button type="submit" class="btn btn-success mx-1"><i class="ti-save mr-2"></i><?=_('Guardar');?></button>
            <?php if($isDeleted){ ?>
            <button type="button" class="btn btn-danger mx-1"><i class="ti-trash mr-2"></i><?=_('Eliminar');?></button>
            <?php } ?>
            <button type="reset" class="btn btn-secondary mx-1"><i class="ti-close mr-2"></i><?=_('Cancelar');?></button>
        </div>
        <?php return ob_get_clean();
    }
}

if (!function_exists('br_html')) {
    function br_html() {
        ob_start(); ?>
        <div>&nbsp;</div>
        <?php return ob_get_clean();
    }
}