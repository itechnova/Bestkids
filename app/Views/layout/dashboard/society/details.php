<div class="row">
    <div class="col-md-12">
        <div class="card" style="overflow: hidden;">
            <div class="card-body">
                <section class="card card-body border mb-0">
                    <h5 class="card-title mb-3"><?=_($content->title);?></h5>
                    <p><?=_($content->content);?></p>
                    

                    <div id="society_lists_render" class="row" data-idsocietys="<?=$modelo["idsocietys"];?>" data-getid="<?=$_GET_ID;?>" data-modelend="<?=$modelo["modelend"];?>" data-filterend="<?=$modelo["filterend"];?>" data-selectedend="<?=$modelo["selectedend"]?>">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#society">
                                <div class="card cursor-pointer">
                                    <div style="display: flex;background: #2cb2ad;border-radius: 8px 8px 0 0;height: 297px;align-content: center;justify-content: center;align-items: center;" class="p-50 text-center"><i class="fa fa-plus-circle" style="font-size: 12rem;color: #ffff;"></i></div>
                                    <div class="card-body">
                                        <h6 class="card-title text-center text-primary mb-2"><?=_('Agregar');?></h6>
                                        <p class="card-text text-center">
                                            <small class="text-muted"><?= $modelo['title'];?></small>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?=$render;?>
                    </div>
                </section>
            </div>
        </div>

    </div>
</div>

<?php 
    ob_start();
?>
<?=div_html('" style="position: relative;z-index:1'); ?> 
<p><?=_('Seleccione uno o más elementos de <b>'.$content->title.'</b>.');?></p>
<?=div_html('row'); ?> 
<?=div_html('col-xl-6 col-lg-6 col-md-6 col-sm-12');?>
    <div class="input-group">
        <input id="input-society-search" type="text" class="form-control" placeholder="<?=_('Buscar '.$modelo['title']);?>" data-info="<?=$modelo['search']?>" aria-describedby="button-society-search">
        <div class="input-group-append">
            <button class="btn btn-outline-light btn-primary" type="button" id="button-society-search">
                <i data-feather="search" style="color: #fff"></i>
            </button>
        </div>
    </div>
<?=divEnd_html();?>
<?=div_html('col-xl-6 col-lg-6 col-md-6 col-sm-12');?>
<?php $options = proccess_select_html($modelo['options']); ?>
<select id="select-society-search" class="form-control select-actived" data-info="<?=$modelo['options']?>">
    <option><?=_('Seleccione');?></option>
    <?php foreach ($options as $option_value => $option_text) { ?> 
        <option value="<?=$option_value;?>"><?=$option_text;?></option>
    <?php } ?>
</select>
<?=divEnd_html();?>
<?=divEnd_html();?>
<div class="alert_message"><i data-feather="check" style="color: #fff"></i> <?=_('Se ha agregado el elemento a la lista.');?></div>
<div id="societyout" class="mt-5" data-idsociety="<?=$modelo['idsocietys'];?>" data-idcolumnmain="<?=$ModelMain[$modelo['selectedmain']];?>"><?=$out;?></div>
<?=divEnd_html();?>
<div class="leaf-right" style="z-index:0"></div>
<?=modal_html((Object) array(
    'id' => 'society',
    'title' => _($modelo['title']),
    'class' => 'modal-xl',
    'content' => ob_get_clean(),
    /*'footer' => modal_button_html((Object) array(
        'ok'=>(Object) array(
            'content' => '<i class="ti-save mr-2"></i>'._('Guardar')
        )
    ))*/
))?>