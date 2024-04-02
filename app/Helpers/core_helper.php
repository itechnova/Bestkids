<?php

if (!function_exists('_')) {
    function _($String) {
        return lang('App.'.$String);
    }
}

if (!function_exists('get_menu')) {
    function get_menu($Id, $menus) {
        $returnMenu = false;
        foreach ($menus as $menu) {
            if(intval($menu['idmenu']) === intval($Id)){
                $returnMenu = $menu;
            }
        }

        return $returnMenu;
    }
}

if (!function_exists('dd_item_menu')) {
    function dd_item_menu($lists = [], $rows = []) {
        ob_start(); ?>
        <ol class="dd-list">
            <?php foreach ($lists as $key => $menu) { ?>
                <?php 
                    $Id = "";
                    $menuType ="";
                    $Title = "";
                    if(!is_array($menu) && property_exists($menu, 'id')){
                        $Id = $menu->id;
                        $MenuFind = get_menu($Id, $rows);
                        if($MenuFind){
                            $menuType = $MenuFind['menu'];
                            $Title = $MenuFind['title'];
                        }
                    }else{
                        $Id = $menu['idmenu'];
                        $menuType = $menu['menu'];
                        $Title = $menu['title'];
                    }
                ?>
                <li class="dd-item dd3-item" data-id="<?=$Id;?>">
                    <div class="dd-handle dd3-handle"></div>
                    <div class="dd3-content"><?=$menuType.' - '.$Title;?></div>
                    <?php
                        if(!is_array($menu) && property_exists($menu, 'children')){
                            echo dd_item_menu($menu->children, $rows);
                        }
                    ?>
                </li>
            <?php } ?>
        </ol>
        <?php return ob_get_clean();
    }
}

if (!function_exists('dd_item_menu_new')) {
    function dd_item_menu_new($lists = [], $rows = []) {
        $filteredRows = array();
        
        foreach ($rows as $row) {
            $id = $row['idmenu'];
            $found = false;
        
            foreach ($lists as $item) {
                if(property_exists($item, 'id')){
                    if (intval($id) == intval($item->id) || in_array(intval($id), array_column((property_exists($item, 'children')?$item->children:[]), 'id'))) {
                        $found = true;
                        break;
                    }
                }
            }
        
            if (!$found) {
                $filteredRows[] = $row;
            }
        }
        ob_start(); 
        echo dd_item_menu($filteredRows, $rows);
        return ob_get_clean();
    }
}

if (!function_exists('display_error')) {
    function display_error($validation, $field){
        return ($validation->hasError($field)) ? $validation->getError($field) : false;
    }
}

if (!function_exists('logo_html')) {
    function logo_html($full = false) {
        $logo = '/public/assets/media/image/logo.png';
        $logosm ='/public/assets/media/image/logo-sm.png';
        $logodark='/public/assets/media/image/logo-dark.png';
        ob_start(); ?><div id="logo"><a href="<?=site_url();?>"><img class="logo" src="<?=site_url($logo);?>" alt="logo"><?php if($full){ ?><img class="logo-sm" src="<?=site_url($logosm);?>" alt="small logo"><?php } ?><img class="logo-dark" src="<?=site_url($logodark);?>" alt="dark logo"></a></div><?php return ob_get_clean();
    }
}

if (!function_exists('logo_clean_html')) {
    function logo_clean_html() {
        $logo = '/public/assets/media/image/logo.png';
        $logosm ='/public/assets/media/image/logo-sm.png';
        $logodark='/public/assets/media/image/logo-dark.png';
        ob_start(); ?><a href="<?=site_url();?>"><img class="logo" src="<?=site_url($logo);?>" alt="logo"><img class="logo-sm" src="<?=site_url($logosm);?>" alt="small logo"><img class="logo-dark" src="<?=site_url($logodark);?>" alt="dark logo"></a><?php return ob_get_clean();
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

        $id = "";
        if(property_exists($form, 'id')){
            $id = ' id="'.$form->id.'"';
        }

        $validator = NULL;
        if(property_exists($form, 'validator')){
            $validator = $form->validator;

            if(!IS_NULL($validator)){
                $class .= " was-validated";
            }
        }

        return '<form'.$id.$method.$action.' class="'.$class.'" novalidate>'.csrf_field().$context.'</form>';
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

if (!function_exists('field_dynamic_view_html')) {
    function field_dynamic_view_html($field, $values = [], $validator = NULL) {

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

        echo field_view_html(((Object) $field_html), $values, $validator);
        
        return '<div class="col-sm-12">'.ob_get_clean().'</div>';
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

                    if($field->type === 'file'){
                        $label = "";
                        if(property_exists($field, 'label')){
                            ob_start(); ?><h6 <?=$LabelClass;?>><?=_($field->label)?></h6><?php
                            $label = ob_get_clean();
                        }

                        $control = '';
                        $control = '<div style="display: flex;flex-direction: row;justify-content: center;align-items: center;align-content: center;">';
                        $control .= '<a href="javascript:void(0)" class="btn-saved btn btn-sm btn-gradient-primary mx-1" style="color: #fff" data-name="'.$name.'" data-toggle="modal" data-target="#uploadMedia"><i class="fa fa-folder-open mr-2"></i><small>'._('Galería').'</small></a>';
                        $control .= '<a href="javascript:void(0)" class="btn-quit-file btn-close btn btn-sm btn-gradient-dark mx-1" data-name="'.$name.'" style="color: #fff"><i class="ti-close mr-2"></i><small>'._('Cancelar').'</small></a>';
                        $control .= '</div>';

                        $contentValue = '';
                        if($value === ''){
                            $contentValue = '<p class="text-black-50">'._('Sin contenido.').'</p>'; 
                        }else{
                            $File = (new \App\Models\FileModel())->Exists($value);

                            if(!IS_NULL($File)){
                                $contentValue = '<img src="'.site_url('file/'.$File['slug']).'"/>';
                            }else{
                                $contentValue = '<p class="text-black-50">'._('Sin contenido.').'</p>'; 
                            }
                        }

                        $content = '<div class="file-content content-'.$name.'"><div  class="file-preview preview-'.$name.'">'.$contentValue.'</div><div class="file-control control-'.$name.'">'.$control.'</div></div>';
                        ob_start(); ?><input id="<?=$id;?>" name="<?=$name;?>" type="hidden" value="<?=$value;?>" <?=$placeholder;?><?=$attrs;?>><?php
                        $input = ob_get_clean();
                        return '<div class="card border"><div class="card-body">'.$label.$content.$input.'</div></div>';
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
    function field_view_html($field, $values, $validator, $full = false) {
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
                    $LabelClass = " class=\"calign-content-center align-items-center col-sm-12 col-md-2 d-flex font-weight-800 mb-0\"";
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
                if($field->type !== 'select' && $field->type !== 'textarea' && $field->type !== 'view' && $field->type !== 'slug'){
                    ob_start(); ?><input readonly id="<?=$id;?>" name="<?=$name;?>" type="<?=$field->type;?>" value="<?=$value;?>" class="col-sm-12 col-md-10 form-control-plaintext"<?=$placeholder;?><?=$required;?>><?php
                    $context = ob_get_clean();   
                    
                    if($field->type === 'hidden'){
                        return $context;
                    }
                }

                if($field->type === 'textarea'){
                    ob_start(); ?><textarea readonly id="<?=$id;?>" name="<?=$name;?>" type="<?=$field->type;?>" class="col-sm-12 col-md-10 form-control-plaintext"<?=$placeholder;?><?=$required;?> rows="6"><?=$value;?></textarea><?php
                    $context = ob_get_clean();                    
                }

                if($field->type === 'select'){
                    ob_start(); ?><select id="<?=$id;?>" name="<?=$name;?>" disabled class="col-sm-12 col-md-10 form-control-plaintext"<?=$placeholder;?><?=$required;?>><?=$options;?></select><?php
                    $context = ob_get_clean();                    
                }

                if($field->type === 'slug'){
                    ob_start(); ?><div class="d-flex mb-0 mx-0"><label class="align-items-center col-sm-12 d-flex mb-0 mr-0 pr-0 text-linkedin text-monospace" style="z-index: 1;height: 36px;border-radius: .25rem 0 0 .25rem;border: 0px solid #e1e1e1;border-right: none;white-space: nowrap; overflow: hidden;text-overflow: ellipsis;"><?=site_url($value);?></label><input readonly id="<?=$id;?>" name="<?=$name;?>" type="<?=$field->type;?>" value="<?=$value;?>" style="border-radius: 0px .25rem .25rem 0px;display:none;" class="form-control-plaintext"<?=$placeholder;?><?=$required;?><?=$attrs;?>></div><?php
                    $context = ob_get_clean();
                }

                if($field->type === 'switch'){
                    $checked = "";
                    if($value !== ""){
                        if(intval($value) === 1){
                            $checked = " checked";
                        }
                    }
                    ob_start(); ?><label class="col-sm-12 col-md-2 d-flex align-content-center align-items-center font-weight-800 mb-0" style="background: #f1f1f1;"><?=_($field->label)?></label><div class="cols-sm-10 px-3"><div class="custom-control custom-switch disabled"><input disabled id="<?=$id;?>" name="<?=$name;?>" type="checkbox" <?=$checked;?> class="custom-control-input"<?=$placeholder;?><?=$required;?>><?=$label;?></div></div><?php
                    $context = ob_get_clean();
                    $label = "";
                }

                if(($value !== '' || $full) && $field->type === 'view'){
                    ob_start(); ?><input readonly id="<?=$id;?>" name="<?=$name;?>" type="text" value="<?=$value;?>" class="col-sm-12 col-md-10 form-control-plaintext"<?=$placeholder;?>><?php
                    $context = ob_get_clean();                    
                }else{
                    if($field->type === 'view' && $value === ''){
                        $label = "";
                        $helper = "";    
                    }
                }
            }

            
            $html = '<div class="'.$class.' row mx-0 border mb-0 border-bottom-0" style="border-bottom: none !important;">';
            $html .= $label;
            $html .= $context;
            $html .= $helper;
            $html .= '</div>';
            return $html;          
        }
    }
}

if (!function_exists('form_button_view_html')) {
    function form_button_view_html($model, $values, $action) {
        $ModelId = $model->getID($values);
        $html = '<div style="display: flex;flex-direction: row;justify-content: flex-end;align-items: center;">';
        if($ModelId){
            $html .= '<a  href="'.site_url($action.'/edit/'.$ModelId).'" class="btn btn-primary mx-1" style="color: #fff"><i class="fa fa-pencil-square-o mr-2"></i>'._('Editar').'</a>';
        }
        $html .= '<a href="javascript:history.back()" class="btn btn-secondary mx-1" style="color: #fff"><i class="ti-arrow-left mr-2"></i>'._('Volver').'</a></div>';
        return $html;
    }
}

if (!function_exists('form_saved_and_cancel_html')) {
    function form_saved_and_cancel_html() {
        $html = '<div style="display: flex;flex-direction: row;justify-content: flex-end;align-items: center;">';
        $html .= '<a href="javascript:void(0)" class="btn-saved btn btn-success mx-1" style="color: #fff"><i class="ti-save mr-2"></i>'._('Guardar').'</a>';
        $html .= '<a href="javascript:void(0)" class="btn-close btn btn-secondary mx-1" style="color: #fff"><i class="ti-close mr-2"></i>'._('Cancelar').'</a>';
        $html .= '</div>';
        return $html;
    }
}

if (!function_exists('form_button_html')) {
    function form_button_html($model, $values, $action) {
        $ModelId = $model->getID($values);

        $html = '<div style="display: flex;flex-direction: row;justify-content: flex-end;align-items: center;">';
        $html .= '<button type="submit" class="btn btn-success mx-1"><i class="ti-save mr-2"></i>'._('Guardar').'</button>';
        if($model->isDeleted($values)){
            $html .= '<a href="'.site_url($action.'/trash/'.$ModelId).'" class="btn btn-danger mx-1" style="color: #fff"><i class="ti-trash mr-2"></i>'._('Eliminar').'</a>';
        } 
        $html .= '<button type="reset" class="btn btn-secondary mx-1"><i class="ti-close mr-2"></i>'._('Cancelar').'</button>';
        $html .= '</div>';
        return $html;
    }
}

if (!function_exists('br_html')) {
    function br_html() {
        return '<div>&nbsp;</div>';
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

        $responsive = ' responsive="1"';
        if(property_exists($tabled, 'responsive')){
            $responsive = ' responsive="'.$tabled->responsive.'"';
        }

        ob_start(); ?>
        <div class="table-responsive"> <!-- Required for Responsive -->
            <table id="<?=$id;?>" class="<?=$class;?>" style="width: 100%;"<?=$responsive;?>>
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

if (!function_exists('li_submenu')) {
    function li_submenu($subItem, $subMenu, $menus) {
        ob_start();?>
        <li>
            <a href="<?=(property_exists($subItem, 'children')?'javascript:void(0)':$subMenu->href);?>" target="<?=$subMenu->target?>"><?=$subMenu->title;?></a>
            <?php if(property_exists($subItem, 'children')){ ?>
                <ul>
                    <?php 
                        foreach ($subItem->children as $subSubItem) {
                            $subSubMenu = get_menu($subSubItem->id, $menus);
                            if($subSubMenu) {
                                $subSubMenu = (Object) $subSubMenu;
                                if($subSubmenu->menu !== 'divider'){
                                    echo li_submenu($subSubItem, $subSubmenu, $menus);
                                }
                            }
                        } 
                    ?>
                </ul>
            <?php } ?>
        </li>
        <?php return ob_get_clean();
    }
}

if (!function_exists('li_menu')) {
    function li_menu($item, $menu, $menus) {
        ob_start(); ?>
        <li>
            <a href="<?=(property_exists($item, 'children')?'javascript:void(0)':$menu->href);?>" target="<?=$menu->target?>">
                <?php 
                    if($menu->icon!==""){
                        echo '<i class="nav-link-icon" data-feather="'.$menu->icon.'"></i>';
                    }
                ?>
                <span><?=$menu->title;?></span>
            </a>
            <?php if(property_exists($item, 'children')){ ?>
                <ul>
                    <?php 
                        foreach ($item->children as $subItem) { 
                            $subMenu = get_menu($subItem->id, $menus); 
                            if($subMenu){ 
                                $subMenu = (Object) $subMenu; 
                                if($subMenu->menu !== 'divider'){
                                    echo li_submenu($subItem, $subMenu, $menus);
                                }
                            } 
                        } 
                    ?>
                </ul>
            <?php } ?>
        </li>
        <?php return ob_get_clean();
    }
}

if (!function_exists('li_menus')) {
    function li_menus($item, $menus) {
        ob_start();
        $menu = get_menu($item->id, $menus);
        if($menu){
            $menu = (Object) $menu;
            if($menu->menu === 'divider'){
                echo '<li class="navigation-divider">'.$menu->title.'</li>';
                if(property_exists($item, 'children')){
                    foreach ($item->children as $subItem) {
                        $subMenu = get_menu($subItem->id, $menus);
                        if($subMenu){
                            $subMenu = (Object) $subMenu;
                            echo li_menu($subItem, $subMenu, $menus);
                        }
                    }
                }
            }else{
                echo li_menu($item, $menu, $menus);
            }
        }

        return ob_get_clean();
    }
}

if (!function_exists('modal_html')) {
    function modal_html($modal) {
        ob_start(); ?>
        <div class="modal fade"<?=(property_exists($modal, 'id')?' id="'.$modal->id.'"': '')?> tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered <?=(property_exists($modal, 'class')?$modal->class: '')?>" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?=(property_exists($modal, 'title')?$modal->title: '')?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="ti-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?=(property_exists($modal, 'content')?$modal->content: '')?>
                    </div>
                    <?=(property_exists($modal, 'footer')?'<div class="modal-footer">'.$modal->footer.'</div>': '')?>
                </div>
            </div>
        </div>
        <?php return ob_get_clean();
    }
}

if (!function_exists('modal_button_html')) {
    function modal_button_html($button) {
        if(!property_exists($button, 'close')){
            $button->close = (Object) array();
        }
        ob_start(); ?>
        <button type="button" class="btn <?=(property_exists($button->close, 'class')?$button->close->class:'btn-secondary')?>" data-dismiss="modal"><?=(property_exists($button->close, 'content')?$button->close->content:'<i class="ti-close mr-2"></i>'._('Cancelar'))?></button>
        <button type="button" class="btn <?=(property_exists($button->ok, 'class')?$button->ok->class:'btn-primary')?>"><?=(property_exists($button->ok, 'content')?$button->ok->content:_('Aceptar'))?></button>
        <?php return ob_get_clean();
    }
}

if (!function_exists('permanentLink')) {
    function permanentLink($string) {
        // Reemplazar espacios con guiones
        $slug = str_replace(' ', '-', $string);
        // Convertir a minúsculas
        $slug = strtolower($slug);
        // Eliminar caracteres especiales
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
        return $slug;
    }
}

if (!function_exists('create_folder')) {
    function create_folder($idaccount) {
        $path = ""; // Inicializa la variable de ruta

        $Setting = SETTING('upload_path');
        // Obtén el año, mes, día y hora actual
        $current_year = date("Y");
        $current_month = date("m");
        $current_day = date("d");
        $current_hour = date("H");
        $current_minute = date("i");
        $current_second = date("s");
        
        // Itera hasta encontrar un nombre de directorio único
        do {
            // Construye la ruta del directorio
            $path = $Setting->value . "Y$current_year/M$current_month/D$current_day/U$idaccount/F$current_year$current_month$current_day$current_hour$current_minute$current_second-$idaccount-" . rand(1, 100) . "/";
        
            // Verifica si el directorio existe
            if (!is_dir($path)) {
                // Si el directorio no existe, crea el directorio y termina el bucle
                mkdir($path, 0777, true);
                break;
            } else {
                // Si el directorio existe, genera un nuevo nombre de directorio único y repite el bucle
                $current_second++;
            }
        } while (true);

        return $path;
    }
}

if (!function_exists('create_sub_folder')) {
    function create_sub_folder($idaccount, $sub) {
        $path = ""; // Inicializa la variable de ruta

        $Model = new \App\Models\FolderModel();
        
        $Folder = $Model->where('idfolder', $sub)->first();

        if(!IS_NULL($Folder)){
            $current_year = date("Y");
            $current_month = date("m");
            $current_day = date("d");
            $current_hour = date("H");
            $current_minute = date("i");
            $current_second = date("s");
            do {
                // Construye la ruta del directorio
                $path = $Folder['path'] . "F$current_year$current_month$current_day$current_hour$current_minute$current_second-$idaccount-" . rand(1, 100) . "/";
            
                // Verifica si el directorio existe
                if (!is_dir($path)) {
                    // Si el directorio no existe, crea el directorio y termina el bucle
                    mkdir($path, 0777, true);
                    break;
                } else {
                    // Si el directorio existe, genera un nuevo nombre de directorio único y repite el bucle
                    $current_second++;
                }
            } while (true);

            return $path;
        }

        return create_folder($idaccount);
    }
}

if (!function_exists('deleteAll')) {
    function deleteAll($directorio) {
        // Obtener una lista de archivos y subdirectorios en el directorio
        $archivos = glob($directorio . '/*');
    
        // Eliminar cada archivo en el directorio
        foreach ($archivos as $archivo) {
            if (is_file($archivo)) {
                unlink($archivo);
            } elseif (is_dir($archivo)) {
                // Llamar recursivamente a la función para eliminar subdirectorios
                deleteAll($archivo);
            }
        }
    
        // Una vez que se eliminan todos los archivos y subdirectorios, eliminar el directorio principal
        rmdir($directorio);
    }
}

if(!function_exists('view_card')){
    function view_card($item){
        $File = NULL;
        foreach ($item->taxonomy->fields as $field) {
            if(isset($item->metas[$field['name']])){
                if($field['typefield'] === "file"){
                    $File = (new \App\Models\FileModel())->Exists($item->metas[$field['name']]);
                    if(!IS_NULL($File)){
                        $File = (Object) $File;
                    }
                }
            }
        }
        ob_start();
        ?>
        <a href="">
            <div class="card cursor-pointer border">
                <?php if(!IS_NULL($File)){ ?>
                    <img src="<?=!IS_NULL($File) ? site_url('/file/'.$File->slug): "";?>" class="card-img-top" alt="image" style="max-height: 297px;object-fit: cover;">
                <?php } else { ?>
                    <div style="background: #1a1a1aa3;border-radius: 8px 8px 0 0;" class="p-50 text-center"><i class="fa fa-image" style="font-size: 7rem;color: #ffff;"></i></div>
                <?php } ?>
                <div class="card-body">
                    <h6 class="card-title text-primary mb-2"><?=$item->title?></h6>
                    <!--p class="card-text">This is a wider card with supporting text below as a natural lead-in to
                                        additional content. This content is a little bit longer.</p-->
                    <p class="card-text">
                        <small class="text-muted"><?=agoDate($item->updated_at);?></small>
                    </p>
                </div>
            </div>
        </a>
        <?php return ob_get_clean();
    }
}

if(!function_exists('view_card_new')){
    function view_card_new($panel){
        //var_dump($panel);
        ob_start();
        ?>
        <a href="<?=site_url($panel->action);?>">
            <div class="card cursor-pointer border">
                <div style="display: flex;background: #1a1a1a59;border-radius: 8px 8px 0 0;height: 297px;align-content: center;justify-content: center;align-items: center;" class="p-50 text-center"><i class="fa fa-plus-circle" style="font-size: 12rem;color: #ffff;"></i></div>
                <div class="card-body">
                    <h6 class="card-title text-center text-primary mb-2"><?=_('Nuevo');?></h6>
                    <!--p class="card-text">This is a wider card with supporting text below as a natural lead-in to
                                        additional content. This content is a little bit longer.</p-->
                    <p class="card-text text-center">
                        <small class="text-muted"><?= $panel->taxonomy->title;?></small>
                    </p>
                </div>
            </div>
        </a>
        <?php return ob_get_clean();
    }
}

if(!function_exists('agoDate')){
    function agoDate($updated_at){
        // Fecha y hora actual
        $current_time = new DateTime();

        // Fecha y hora de la última actualización
        $updated_at_datetime = new DateTime($updated_at);

        // Calcula la diferencia entre la fecha actual y la fecha de la última actualización
        $time_diff = $current_time->diff($updated_at_datetime);

        // Genera el mensaje de tiempo transcurrido
        $time_message = '';

        if ($time_diff->s > 0) {
            $time_message = $time_diff->s . ' segundo' . ($time_diff->s > 1 ? 's' : '') . '.';
        }
        if ($time_diff->i > 0) {
            $time_message = $time_diff->i . ' minuto' . ($time_diff->i > 1 ? 's' : '') . '.';
        }
        if ($time_diff->h > 0) {
            $time_message = $time_diff->h . ' hora' . ($time_diff->h > 1 ? 's' : '') . '.';
        }
        if ($time_diff->d > 0) {
            $time_message = $time_diff->d . ' día' . ($time_diff->d > 1 ? 's' : '') . '.';
        }
        if ($time_diff->m > 0) {
            $time_message = $time_diff->m . ' mes' . ($time_diff->m > 1 ? 'es' : '') . '.';
        }
        if ($time_diff->y > 0) {
            $time_message = $time_diff->y . ' año' . ($time_diff->y > 1 ? 's' : '') . '.';
        }

        // Elimina la coma y el espacio extra al final
        $time_message = rtrim($time_message, ', ');
        // Muestra el mensaje
        return 'Hace ' . $time_message;
    }
}