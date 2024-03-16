<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="card-body">
                <section class="card card-body border mb-0">

                    <h5 class="card-title mb-3"><?=_($content->title);?></h5>
                    <p><?=_($content->content);?></p>

                    <?php 
                        echo table_html((Object) array(
                            'columns' => $columns,
                            'datas' => $all,
                            'td' => $td
                        ));
                    ?>
                </section>
            </div>
        </div>

    </div>
</div>
