<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="card-body">
                <section class="card card-body border mb-0">

                    <h5 class="card-title mb-3"><?=_($content->title);?></h5>
                    <p><?=_($content->content);?></p>
                    <?php 
                        if($model){
                        ob_start(); 
                    ?>
                    <div class="row">
                        <div class="col-md-8">
                            <?php 
                                foreach ($model->getFields() as $field) {
                                    if($field->name !== 'content'){
                                        echo field_view_html($field, $values, $validator);
                                    }else{
                                        if(isset($values[$field->name])){
                                            ?>
                                            <div class="form-group wd-xs-300 row mx-0 border mb-0 border-bottom-0" style="border-bottom: none !important;">
                                                <label class="calign-content-center align-items-center col-sm-12 col-md-2 d-flex font-weight-800 mb-0" style="background: #f1f1f1;"><?=$field->label?></label>
                                                <div class="col-sm-12 col-md-10 form-control-plaintext" style="min-height: 34.04px;">
                                                    <?=$values[$field->name];?>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                }
                            ?>
                        </div>
                        <div class="col-md-4">
                            <?php 
                                echo '<h5 class="card-title mb-3">'._('Datos adicionales').'</h5>';
                                
                                echo div_html('row');
                                foreach ($extras as $field) {
                                    if($field['typefield'] !== 'file'){
                                        echo field_dynamic_view_html((Object) $field, $values, $validator);
                                    }
                                }
                                echo divEnd_html();
                                echo br_html();
                                echo br_html();
                                echo form_button_view_html($model, $values, $action.'/'.$vars["code"]);
                            ?>
                        </div>
                    </div>
                    <?=form_html(((Object) array(
                        'method' => "POST",
                        'enctype'=> true,
                        'action'=> $action,
                        'validator'=> $validator,
                        'context' => ob_get_clean()
                        )));?>
                    <?php } ?> 
                </section>
            </div>
        </div>

    </div>
</div>
