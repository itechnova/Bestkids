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
                            foreach ($model->getFields() as $field) {
                                echo field_html($field, $values);
                            }

                            echo br_html();
                            echo br_html();
                            echo form_button_html($model->isDeleted($values));
                            echo form_html(ob_get_clean());
                        }
                    ?>
                </section>
            </div>
        </div>

    </div>
</div>
