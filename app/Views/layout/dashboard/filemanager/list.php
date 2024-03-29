<div class="row app-block">
    <div class="col-md-3 app-sidebar">
        <div class="card">
            <div class="card-body">
                <button class="btn btn-primary btn-block file-upload-btn" data-toggle="modal" data-target="#compose">
                    <i data-feather="plus" class="mr-2"></i>
                    <?=_('Subir archivos')?>
                </button>
                <form class="d-none" id="file-upload" action="<?=site_url($action."/upload");?>" method="POST" enctype="multipart/form-data">
                    <input type="file" multiple>
                </form>
            </div>
            <div class="app-sidebar-menu">
                <div class="list-group list-group-flush">
                    <a href="<?=site_url($action);?>" class="list-group-item <?=($vars->root === '' ?'active': '')?> d-flex align-items-center">
                        <i data-feather="folder" class="width-15 height-15 mr-2"></i>
                        <?=_('Todos los archivos')?>
                        <span class="small ml-auto"><?=$vars->all_files_count?></span>
                    </a>
                    <!--a href="" class="list-group-item">
                        <i data-feather="monitor" class="width-15 height-15 mr-2"></i>
                        My Devices
                    </a-->
                    <a href="<?=site_url($action.'/recents');?>" class="list-group-item <?=($vars->root === 'recents' ?'active': '')?>">
                        <i data-feather="upload-cloud" class="width-15 height-15 mr-2"></i>
                        <?=_('Recientes');?>
                     </a>
                    <!--a href="" class="list-group-item d-flex align-items-center">
                            <i data-feather="star" class="width-15 height-15 mr-2"></i>
                            Important
                        <span class="small ml-auto">10</span>
                    </a-->
                    <a href="<?=site_url($action.'/recycle');?>" class="list-group-item <?=($vars->root === 'recycle' ?'active': '')?>">
                        <i data-feather="trash" class="width-15 height-15 mr-2"></i>
                        <?=_('Papelera');?>
                    </a>
                </div>
                <div class="card-body">
                    <h6 class="mb-4"><?=_('Estado del disco');?></h6>
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <i data-feather="database" class="width-30 height-30"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="progress" style="height: 10px">
                                <div class="progress-bar progress-bar-striped" role="progressbar"
                                    style="width: <?=$vars->usagePercentage;?>%" aria-valuenow="10" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                            <div class="line-height-12 small text-muted mt-2"><?=$vars->usedDiskSpace;?> <?=_('usado de')?> <?=$vars->totalDiskSpace;?></div>
                        </div>
                    </div>
                </div>
                <div id="panel-upload" class="card-body" style="display:none">
                    <h6 class="mb-4"><?=_('Estado de subida');?></h6>
                    <div class="panel-list">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9 app-content">
        <div class="app-content-overlay"></div>
        <div class="app-action">
            <div class="action-left">
                <ul class="list-inline">
                    <li class="list-inline-item mb-0">
                        <a href="javascript:void(0)" class="btn btn-outline-light"  data-toggle="modal" data-target="#formFolder">
                            <i data-feather="plus" class="mr-2"></i>
                            <?=_('Nueva carpeta')?>
                        </a>
                    </li>

                    <li class="list-inline-item mb-0">
                        <a href="#" class="btn btn-outline-light dropdown-toggle" data-toggle="dropdown">
                            <?=_('Ordenar por')?>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?=site_url($action.'/order/date');?>"><?=_('Fecha');?></a>
                            <a class="dropdown-item" href="<?=site_url($action.'/order/name');?>"><?=_('Nombre');?></a>
                            <a class="dropdown-item" href="<?=site_url($action.'/order/size');?>"><?=_('Tamaño');?></a>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="action-right">
                <form class="d-flex mr-3">
                    <a href="#" class="app-sidebar-menu-button btn btn-outline-light">
                        <i data-feather="menu"></i>
                    </a>
                    <div class="input-group">
                        <input id="input-addon-search" type="text" class="form-control" placeholder="<?=_('Buscar archivo');?>" aria-describedby="button-addon-search">
                        <div class="input-group-append">
                            <button class="btn btn-outline-light" type="button" id="button-addon-search">
                                <i data-feather="search"></i>
                            </button>
                        </div>
                     </div>
                </form>
            </div>
        </div>
        <div class="card app-content-body">
            <div class="card-body">

                <?php if(!IS_NULL($vars->FolderOpen)){ ?>
                    <a href="<?=site_url($action.($vars->FolderOpen['sub'] !== '0' ? '/folder/'.$vars->FolderOpen['sub']: ''))?>" class="btn-link float-right text-black-50"><i class="fa fa-2x fa-arrow-circle-o-left"></i></a>
                <?php } ?>
                <h6 class="font-size-11 text-uppercase mb-4">
                    <?=$content->title;?>
                    <span class="ml-1 text-primary">
                        <?=$vars->root === 'recents' ? $content->content._('Recientes'): ($vars->root === 'recycle' ? $content->content._('Papelera'): $content->content)?>
                    </span>
                </h6>

                <div id="panel-files" class="row">
                    <?php foreach ($all as $file) { ?>
                        <?php $file = (Object) $file;?>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                            <?=render_item_file($file, ($vars->root === 'recycle'));?>
                        </div>    
                    <?php } ?>
                </div>

                <?php if(count($all) === 0) { ?>
                    <div class="folder-empty" style="min-height: 40vh;display: flex;align-content: center;justify-content: center;align-items: center;">
                        <p class="text-black-50 text-center text-light"><?=$vars->root === 'recents' ? _('No se han agregado archivos recientemente. '): ($vars->root === 'recycle' ? _('La papelera está vacía.'): _('Carpeta vacía.'))?></p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div id="details-manager-file" class="col-md-3 app-sidebar" style="display: none">
        <div class="card">
            <div class="card-body">
                <button id="close-details-manager-file" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="ti-close"></i>
                </button>
                <h6 class="mb-4"><?=_('Detalles');?></h6>
                <div id="details-content"></div>
            </div>
        </div>
    </div>
</div>
<?php
    ob_start();
    foreach ($vars->Folder->getFields() as $field) {
        echo field_html($field, $values, $validator);
    }

    echo br_html();
    //echo br_html();
    //echo form_button_html($vars->Folder, $values, $action);
?>
<?=modal_html((Object) array(
    'id' => 'formFolder',
    'title' => _('Nueva carpeta'),
    'content' => form_html(((Object) array(
        'method' => "POST",
        'enctype'=> true,
        'action'=> $action."/folder/saved",
        'validator'=> $validator,
        'context' => ob_get_clean()
    ))),
    'footer' => modal_button_html((Object) array(
        'ok'=>(Object) array(
            'content' => '<i class="ti-save mr-2"></i>'._('Guardar')
        )
    ))
))?>