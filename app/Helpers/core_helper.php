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
if (!function_exists('logo_html')) {
    function logo_html($full = false) {
        $logo = 'public/assets/media/image/logo.png';
        $logosm ='public/assets/media/image/logo-sm.png';
        $logodark='public/assets/media/image/logo-dark.png';
        ob_start(); ?><div id="logo"><a href="<?=site_url();?>"><img class="logo" src="<?=site_url($logo);?>" alt="logo"><?php if($full){ ?><img class="logo-sm" src="<?=site_url($logosm);?>" alt="small logo"><?php } ?><img class="logo-dark" src="<?=site_url($logodark);?>" alt="dark logo"></a></div><?php return ob_get_clean();
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

if (!function_exists('proccess_options')) {
    function proccess_options($field) {
        $options = property_exists($field, 'options')?$field->options:"";

        if($options === ""){
            return [];
        }

        if(count(explode(':', $options))>1){
            $dynamics = explode(':', $options);
            $ModelLoad = "\App\Models"."\\".$dynamics[0];
            try {
                //code...
                $Model = new $ModelLoad();

                foreach (explode('&', $dynamics[1]) as $filter) {
                    if(count(explode('=', $filter))>1){
                        $where = explode('=', $filter);
                        $column=$where[0];
                        $value=$where[1];
                        $Model->where($column, $value);
                    }
                }
                $rows = $Model->findAll();

                $OptionList = [];
                foreach ($rows as $row) {
                    $OptionList[$row[$Model->primaryKey()]] = $row[$Model->description()];
                }
                return $OptionList;
            } catch (\Throwable $th) {
                //throw $th;
            }

            return [];
        }

        if(count(explode('|', $options))>1){
            $OptionList = [];
            $dynamics = explode('|', $options);
            foreach ($dynamics as $key => $option) {
                $value="";
                $text="";
                if(count(explode('=', $option))>1){
                    $option = explode('=', $option);
                    $value=$option[0];
                    $text=$option[1];
                }else{
                    $value=$option;
                    $text=$option;
                }

                $OptionList[$value] = $text;
            }

            return $OptionList;
        }
    }
}

if (!function_exists('proccess_cols_html')) {
    function proccess_cols_html($field, $html) {
        $cols = property_exists($field, 'cols')?$field->cols:"";

        if($cols === ""){
            ob_start();
            ?><div class="col-12"><?=$html;?></div><?php
            return ob_get_clean();
        }else{
            $class = "";
            foreach (explode('&', $cols) as $col) {
                $classCol = explode(':', $col);
                $class .= "col-".$classCol[0]."-".$classCol[1]." ";
            }
            ob_start();
            ?><div class="<?=$class;?>"><?=$html;?></div><?php
            return ob_get_clean();
        }
    }
}

if (!function_exists('field_dynamic_html')) {
    function field_dynamic_html($field, $values = [], $validator = NULL) {

        if(!isset($values[$field->name])){
            $values[$field->name] = $field->default_value;
        }

        ob_start();

        echo field_html(((Object) array(
            'name' => 'field_dynamic_'.$field->name,
            'type' => 'hidden',
        )), ['field_dynamic_'.$field->name=>$field->idfield]);

        $field_html = array(
            'name' => $field->name,
            'label' => $field->label,
            'type' => $field->typefield,
            'placeholder'=> $field->placeholder,
            'class'=> $field->class,
            'options' => proccess_options($field)
        );

        if($field->required."" === '1'){
            $field_html['required'] =  true;
        }

        echo field_html(((Object) $field_html), $values, $validator);
        
        return proccess_cols_html($field, ob_get_clean());
    }
}

if (!function_exists('field_html')) {
    function field_html($field, $values = [], $validator = NULL) {
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
            
            $options = "";
            if(property_exists($field, 'options')){
                ob_start();
                if(property_exists($field, 'placeholder')){
                    ?><option value=""><?=$field->placeholder;?></option><?php
                }
                foreach ($field->options as $value_option => $option_text) {
                    ?><option value="<?=$value_option;?>" <?=($value.""===$value_option."")?"selected":"";?>><?=$option_text;?></option><?php
                }
                $options = ob_get_clean();
            }

            $required = "";
            if(property_exists($field, 'required')){
                $required = " required=\"required\"";
            }

            $attrs = "";
            if(property_exists($field, 'attrs')){
                if(is_array($field->attrs)){
                    foreach ($field->attrs as $attr_key => $attr_value) {
                        $attrs .= ' '.$attr_key.'="'.$attr_value.'"';
                    }
                }
            }

            $context = "";
            if(property_exists($field, 'type')){
                if($field->type !== 'select' && $field->type !== 'textarea' && $field->type !== 'view'){
                    $currentpassword = "";
                    if($field->type === 'password'){
                        $currentpassword = ' autocomplete="current-password"';
                    }
                    ob_start(); ?><input id="<?=$id;?>" name="<?=$name;?>" type="<?=$field->type;?>" value="<?=$value;?>" class="form-control"<?=$placeholder;?><?=$required;?><?=$currentpassword;?><?=$attrs;?>><?php
                    $context = ob_get_clean();
                    
                    if($field->type === 'hidden'){
                        return $context;
                    }
                }

                if($field->type === 'textarea'){
                    ob_start(); ?><textarea id="<?=$id;?>" name="<?=$name;?>" type="<?=$field->type;?>" class="form-control"<?=$placeholder;?><?=$required;?> rows="6"<?=$attrs;?>><?=$value;?></textarea><?php
                    $context = ob_get_clean();                    
                }

                if($field->type === 'select'){
                    ob_start(); ?><select id="<?=$id;?>" name="<?=$name;?>" class="form-control select-actived"<?=$placeholder;?><?=$required;?><?=$attrs;?>><?=$options;?></select><?php
                    $context = ob_get_clean();                    
                }

                if($field->type === 'slug'){
                    ob_start(); ?><div class="d-flex mb-0 mx-0"><label class="align-items-center col-sm-2 d-flex mb-0 mr-0 pr-0 text-linkedin text-monospace" style="z-index: 1;height: 36px;border-radius: .25rem 0 0 .25rem;border: 1px solid #e1e1e1;border-right: none;background: #f7f7f7;white-space: nowrap; overflow: hidden;text-overflow: ellipsis;"><?=site_url();?></label><input id="<?=$id;?>" name="<?=$name;?>" type="<?=$field->type;?>" value="<?=$value;?>" style="border-radius: 0px .25rem .25rem 0px;" class="form-control"<?=$placeholder;?><?=$required;?><?=$attrs;?>></div><?php
                    $context = ob_get_clean();                    
                }

                if($field->type === 'switch'){
                    $checked = "";
                    if($value !== ""){
                        if(intval($value) === 1){
                            $checked = " checked";
                        }
                    }
                    ob_start(); ?><div class="custom-control custom-switch"><input id="<?=$id;?>" name="<?=$name;?>" type="checkbox" <?=$checked;?> class="custom-control-input"<?=$placeholder;?><?=$required;?><?=$attrs;?>><?=$label;?></div><?php
                    $context = ob_get_clean();
                    $label = "";
                }

                if($value !== '' && $field->type === 'view'){
                    ob_start(); ?><input id="<?=$id;?>" name="<?=$name;?>" type="text" value="<?=$value;?>" class="form-control"<?=$placeholder;?><?=$attrs;?>><?php
                    $context = ob_get_clean();                    
                }else{
                    if($field->type === 'view' && $value === ''){
                        $label = "";
                        $helper = "";    
                    }
                }
            }

            ob_start(); ?><div class="<?=$class;?>"><?=$label;?><?=$context;?><?=$helper;?></div><?php return ob_get_clean();            
        }
    }
}

if (!function_exists('field_view_html')) {
    function field_view_html($field, $values, $validator) {
        //var_dump($values);exit();

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
                }else{
                    $LabelClass = " class=\"calign-content-center align-items-center col-sm-2 d-flex font-weight-800 mb-0\"";
                }
                ob_start(); ?><label for="<?=$id?>"<?=$LabelClass;?> style="background: #f1f1f1;"><?=_($field->label)?></label><?php
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

            $options = "";
            if(property_exists($field, 'options')){
                ob_start();
                if(property_exists($field, 'placeholder')){
                    ?><option value=""><?=$field->placeholder;?></option><?php
                }
                foreach ($field->options as $value_option => $option_text) {
                    ?><option value="<?=$value_option;?>" <?=($value.""===$value_option."")?"selected":"";?>><?=$option_text;?></option><?php
                }
                $options = ob_get_clean();
            }

            $required = "";
            if(property_exists($field, 'required')){
                $required = " required=\"required\"";
            }

            $context = "";
            if(property_exists($field, 'type')){
                if($field->type !== 'select' && $field->type !== 'textarea' && $field->type !== 'view' && $field->type !== 'slug'){
                    ob_start(); ?><input readonly id="<?=$id;?>" name="<?=$name;?>" type="<?=$field->type;?>" value="<?=$value;?>" class="col-sm-10 form-control-plaintext"<?=$placeholder;?><?=$required;?>><?php
                    $context = ob_get_clean();   
                    
                    if($field->type === 'hidden'){
                        return $context;
                    }
                }

                if($field->type === 'textarea'){
                    ob_start(); ?><textarea readonly id="<?=$id;?>" name="<?=$name;?>" type="<?=$field->type;?>" class="col-sm-10 form-control-plaintext"<?=$placeholder;?><?=$required;?> rows="6"><?=$value;?></textarea><?php
                    $context = ob_get_clean();                    
                }

                if($field->type === 'select'){
                    ob_start(); ?><select id="<?=$id;?>" name="<?=$name;?>" disabled class="col-sm-10 form-control-plaintext"<?=$placeholder;?><?=$required;?>><?=$options;?></select><?php
                    $context = ob_get_clean();                    
                }

                if($field->type === 'switch'){
                    $checked = "";
                    if($value !== ""){
                        if(intval($value) === 1){
                            $checked = " checked";
                        }
                    }
                    ob_start(); ?><label class="col-sm-2 d-flex align-content-center align-items-center font-weight-800 mb-0" style="background: #f1f1f1;"><?=_($field->label)?></label><div class="cols-sm-10 px-3"><div class="custom-control custom-switch disabled"><input disabled id="<?=$id;?>" name="<?=$name;?>" type="checkbox" <?=$checked;?> class="custom-control-input"<?=$placeholder;?><?=$required;?>><?=$label;?></div></div><?php
                    $context = ob_get_clean();
                    $label = "";
                }

                if($value !== '' && $field->type === 'view'){
                    ob_start(); ?><input readonly id="<?=$id;?>" name="<?=$name;?>" type="text" value="<?=$value;?>" class="col-sm-10 form-control-plaintext"<?=$placeholder;?>><?php
                    $context = ob_get_clean();                    
                }else{
                    if($field->type === 'view' && $value === ''){
                        $label = "";
                        $helper = "";    
                    }
                }
            }

            ob_start(); ?>
            <div class="<?=$class;?> row mx-0 border mb-0 border-bottom-0" style="border-bottom: none !important;">
                <?=$label;?>
                <?=$context;?>
                <?=$helper;?>
            </div>
            <?php return ob_get_clean();            
        }
    }
}

if (!function_exists('form_button_view_html')) {
    function form_button_view_html($model, $values, $action) {
        $ModelId = $model->getID($values);
        ob_start(); ?>
        <div style="display: flex;flex-direction: row;justify-content: flex-end;align-items: center;">
            <?php if($ModelId){ ?>
            <a  href="<?=site_url($action.'/edit/'.$ModelId);?>" class="btn btn-primary mx-1" style="color: #fff"><i class="fa fa-pencil-square-o mr-2"></i><?=_('Editar');?></a>
            <?php } ?>
            <a href="javascript:history.back()" class="btn btn-secondary mx-1" style="color: #fff"><i class="ti-arrow-left mr-2"></i><?=_('Volver');?></a>
        </div>
        <?php return ob_get_clean();
    }
}

if (!function_exists('form_button_html')) {
    function form_button_html($model, $values, $action) {
        $ModelId = $model->getID($values);
        ob_start(); ?>
        <div style="display: flex;flex-direction: row;justify-content: flex-end;align-items: center;">
            <button type="submit" class="btn btn-success mx-1"><i class="ti-save mr-2"></i><?=_('Guardar');?></button>
            <?php if($model->isDeleted($values)){ ?>
            <a href="<?=site_url($action.'/trash/'.$ModelId);?>" class="btn btn-danger mx-1" style="color: #fff"><i class="ti-trash mr-2"></i><?=_('Eliminar');?></a>
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

if (!function_exists('table_html')) {
    function table_html($tabled) {
        $id = uniqid("tabled-");
        if(property_exists($tabled, 'id')){
            $id = $tabled->id;
        }

        $class = "Datatable table table-striped table-bordered";
        if(property_exists($tabled, 'class')){
            $class .= " ".$tabled->class;
        }

        $columns = [];
        if(property_exists($tabled, 'columns')){
            $columns = $tabled->columns;
        }

        $datas = [];
        if(property_exists($tabled, 'datas')){
            $datas = $tabled->datas;
        }

        $td = false;
        if(property_exists($tabled, 'td')){
            $td = $tabled->td;
        }

        ob_start(); ?>
        <div class="table-responsive"> <!-- Required for Responsive -->
            <table id="<?=$id;?>" class="<?=$class;?>" style="width: 100%;">
                <thead>
                    <tr>
                        <?php foreach ($columns as $column) { ?>
                            <th><?=$column->label;?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datas as $data) { ?>
                        <tr>
                            <?php foreach ($columns as $column) { ?>
                                <?php
                                    $_DATA = isset($data[$column->key]) ? $data[$column->key]: $data;
                                    if(!isset($data[$column->key]) && $column->key !== 'ACTION'){
                                        $_DATA = "";
                                    }
                                ?>
                                <td><?=$td?$td($data, $_DATA, $column->key):$_DATA;?></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <?php foreach ($columns as $column) { ?>
                            <th><?=$column->label;?></th>
                        <?php } ?>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php return ob_get_clean();
    }
}

if (!function_exists('div_html')) {
    function div_html($class) {
        ob_start(); ?>
        <div class="<?=$class?>">
        <?php return ob_get_clean();
    }
}

if (!function_exists('divEnd_html')) {
    function divEnd_html() {
        ob_start(); ?>
        </div>
        <?php return ob_get_clean();
    }
}