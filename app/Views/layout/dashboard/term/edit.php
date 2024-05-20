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

                            echo div_html('row');
                            echo div_html('col-xs-12 col-sm-8');
                            foreach ($model->getFields() as $field) {
                                if($field->name !== 'status' && $field->name !== 'enabled' && $field->type !== 'view'){
                                    echo field_html($field, $values, $validator);
                                }
                            }

                            if(count($extras)>0){
                                echo '<hr>';
                                //echo '<h5 class="card-title mb-3">'._('Datos adicionales').'</h5>';
                            }
                            
                            
                            echo div_html('row');
                            foreach ($extras as $field) {
                                if($field['typefield'] !== 'file'){
                                    echo field_dynamic_html((Object) $field, $values, $validator);
                                }
                            }
                            echo divEnd_html();
                            echo divEnd_html();
                            echo div_html('border card card-body col-sm-4 col-xs-12');
                            foreach ($model->getFields() as $field) {
                                if($field->name === 'status' || $field->name === 'enabled'){
                                    echo field_html($field, $values, $validator);
                                }
                            }
                    
                            echo '<hr>';                            
                            
                            echo div_html('row');
                            foreach ($extras as $field) {
                                if($field['typefield'] === 'file'){
                                    echo field_dynamic_html((Object) $field, $values, $validator);
                                }
                            }
                            echo divEnd_html();

                            echo br_html();
                            echo br_html();
                            echo form_button_html($model, $values, $action.'/'.$vars["code"]);

                            echo divEnd_html();
                            echo divEnd_html();


                            echo form_html(((Object) array(
                                'method' => "POST",
                                'enctype'=> true,
                                'action'=> $action."/saved",
                                'validator'=> $validator,
                                'context' => ob_get_clean()
                            )));
                        }
                    ?>
                </section>
            </div>
        </div>

    </div>
</div>
