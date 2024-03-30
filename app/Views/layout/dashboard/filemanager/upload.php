<?php 
    $all = all_file_manager();
    ob_start(); 
?>
<ul class="nav nav-tabs mb-3" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
       aria-controls="home" aria-selected="true"><i class="fa fa-upload mr-2"></i><?=_('Subir archivos');?></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
       aria-controls="profile" aria-selected="false"><i class="fa fa-folder-open-o mr-2"></i><?=_('Mis archivos');?></a>
  </li>
</ul>
<div class="tab-content">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
    <?=form_html(((Object) array(
        'method' => "POST",
        'enctype'=> true,
        'id'=> 'dropzone-file-manager',
        'action'=> "dashboard/file-manager/upload",
        'validator'=> null,
        'context' => '<p class="text-black-50">'._('Arrastra y suelta los archivos aquí.').'</p>'
    )));?>
    <hr>
    <div style="display: flex;flex-direction: row;justify-content: flex-end;align-items: center;">
        <?=modal_button_html((Object) array(
            'ok'=>(Object) array(
                'class' => 'ml-2 btn-success btn-file-manager disabled',
                'content' => '<i class="ti-check mr-2"></i>'._('Seleccionar')
            )
        ))?>
    </div>
  </div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
    <div class="row app-block">
        <div class="col-md-12 app-content">
            <div class="app-content-overlay"></div>
            <div class="app-action mb-0 pb-0">
                <div class="action-right">
                    <form class="d-flex mr-3">
                        <a href="#" class="app-sidebar-menu-button btn btn-outline-light">
                            <i data-feather="menu"></i>
                        </a>
                        <div class="input-group">
                            <input id="input-file-manager-search" type="text" class="form-control" placeholder="<?=_('Buscar archivo');?>" aria-describedby="button-file-manager-search">
                            <div class="input-group-append">
                                <button class="btn btn-outline-light" type="button" id="button-file-manager-search">
                                    <i data-feather="search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card app-content-body" style="max-height: 420px;overflow-y: scroll;">
                <div class="card-body">
                    <h6 class="font-size-11 text-uppercase mb-4">
                        <?='<b class="manager-count">('.count($all).')</b> '._('archivos disponibles');?>
                    </h6>

                    <div id="panel-file-managers" class="row">
                        <?php foreach ($all as $file) { ?>
                            <?php $file = (Object) $file;?>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <?=render_item_file_view($file);?>
                            </div>    
                        <?php } ?>
                    </div>

                    <?php if(count($all) === 0) { ?>
                        <div class="folder-file-manager-empty" style="min-height: 40vh;display: flex;align-content: center;justify-content: center;align-items: center;">
                            <p class="text-black-50 text-center text-light"><?=_('Carpeta vacía.')?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
<?=modal_html((Object) array(
    'id' => 'uploadMedia',
    'title' => _('Galería de archivos'),
    'class' => 'modal-lg',
    'content' => ob_get_clean(),
    /*'footer' => modal_button_html((Object) array(
        'ok'=>(Object) array(
            'content' => '<i class="ti-save mr-2"></i>'._('Guardar')
        )
    ))*/
))?>