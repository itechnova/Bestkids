<div class="d-flex justify-content-end mt-4">
    <div class="btn-group" role="group" aria-label="Basic example">
        <a href="<?=site_url($action.'/new');?>" class="btn btn-sm btn-success" style="color:#fff">
            <i class="ti-plus mr-2"></i> <?=_('Nuevo');?>
        </a>
        <?php if($layout === 'index') { ?>
            <!--a type="button" class="btn btn-sm btn-secondary" style="color:#fff">
                <i class="ti-filter mr-2"></i> <?=_('Fitro');?>
            </a-->
        <?php } else { ?>        
            <a href="javascript:history.back()" class="btn btn-sm btn-secondary" style="color:#fff">
                <i class="ti-arrow-left mr-2"></i> <?=_('Volver');?>
            </a>
        <?php } ?>
    </div>
</div>