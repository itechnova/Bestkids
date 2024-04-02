<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <section class="card card-body border mb-0">
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <?php foreach ($tabPanels as $index => $panel) { ?>
                            <li class="nav-item">
                                <a id="<?="tab-view-".$panel->idtab;?>"
                                    class="nav-link<?=$index===0? " active":""?>"
                                    data-toggle="tab" 
                                    href="#<?="view-".$panel->idtab;?>" 
                                    role="tab"
                                    aria-controls="<?="view-".$panel->idtab;?>" 
                                    aria-selected="true">
                                    <?php if($panel->icon!==""){ ?>
                                        <i class="mr-2" data-feather="<?=$panel->icon;?>" style="width: 16px;height: 16px;margin-right: 2px;"></i>
                                    <?php } ?>
                                    <?=$panel->title;?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                    <div class="tab-content">
                        <?php foreach ($tabPanels as $index => $panel) { ?>
                            <div class="tab-pane fade <?=$index===0? " show active":""?>" id="<?="view-".$panel->idtab;?>" role="tabpanel" aria-labelledby="<?="tab-view-".$panel->idtab;?>">
                                <div class="card card-body border">
                                    <h5 class="card-title mb-3"><?=$panel->title;?></h5>
                                    <p class="mb-4"><?=$panel->content;?></p>
                                    <?php if($panel->type === 'grid'){ 
                                        echo div_html('row');
                                        echo div_html('col-xl-3 col-lg-4 col-md-6 col-sm-12');
                                            echo view_card_new($panel);
                                        echo divEnd_html();
                                        foreach ($panel->model as $model) {
                                            $model['taxonomy'] = $panel->taxonomy;
                                            echo div_html('col-xl-3 col-lg-4 col-md-6 col-sm-12');
                                            echo view_card((Object) $model);
                                            echo divEnd_html();
                                        }
                                        echo divEnd_html();
                                    } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <?php 
                        /*if($model){
                            ob_start();
                            foreach ($model->getFields() as $field) {
                                echo field_view_html($field, $values, $validator);
                            }

                            echo br_html();
                            echo br_html();
                            echo form_button_view_html($model, $values, $action);
                            echo form_html(((Object) array(
                                'method' => "POST",
                                'enctype'=> true,
                                'action'=> $action,
                                'validator'=> $validator,
                                'context' => ob_get_clean()
                            )));
                        }*/
                    ?>
                </section>
            </div>
        </div>

    </div>
</div>
