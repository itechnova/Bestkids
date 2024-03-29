<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="card-body">
                <section class="card card-body border mb-0">

                    <h5 class="card-title mb-3"><?=_($content->title);?></h5>
                    <p><?=_($content->content);?></p>
                    <div class="mb-4" id="nestable-menu">
                        <button type="button" class="btn btn-primary mr-2" data-action="expand-all"><?=_('Expandir todo');?></button>
                        <button type="button" class="btn btn-primary mr-2" data-action="collapse-all"><?=_('Contraer todo');?></button>
                        <button type="button" class="btn btn-success" data-action="saved-all"><i class="ti-save mr-2"></i> <?=_('Guardar');?></button>
                    </div>
                    <?php 
                    
                    $OrderMenu = SETTING('menu_order', 'menu'); 
                    $Menus = ($OrderMenu->value==="") ? $all: $OrderMenu->value;
                    if($OrderMenu->value!==""){
                        try {
                            //code...
                            $Menus = json_decode($Menus);
                        } catch (\Throwable $th) {
                            //throw $th;
                            $Menus = $all;
                        }
                    }
                    ?>
                    <div class="dd" id="nestable1">
                        <?= dd_item_menu($Menus, $all); ?>
                        <?= dd_item_menu_new($Menus, $all);?>
                    </div>
                    
                    <div class="form-group">
                        <textarea id="nestable-output" class="form-control"></textarea>
                    </div>
                </section>
            </div>
        </div>

    </div>
</div>