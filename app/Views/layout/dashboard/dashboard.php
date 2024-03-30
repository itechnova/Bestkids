<!doctype html>
<html lang="<?=$lang;?>">
<head>

    <?=view('layout/head');?>
    <!-- DataTable -->
    <link rel="stylesheet" href="<?=base_url('public/vendors/dataTable/dataTables.min.css');?>" type="text/css">

    <!-- Style -->
    <link rel="stylesheet" href="<?=base_url('public/vendors/select2/css/select2.min.css');?>" type="text/css">

    <link rel="stylesheet" href="<?=base_url('public/vendors/dropzone/dropzone.css');?>" type="text/css">

    <style>

        .error .form-control {
            border-color: #dc3545 !important;
            padding-right: calc(1.5em + .75rem) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23dc3545' viewBox='-2 -2 7 7'%3e%3cpath stroke='%23dc3545' d='M0 0l3 3m0-3L0 3'/%3e%3ccircle r='.5'/%3e%3ccircle cx='3' r='.5'/%3e%3ccircle cy='3' r='.5'/%3e%3ccircle cx='3' cy='3' r='.5'/%3e%3c/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: center right calc(.375em + .1875rem) !important;
            background-size: calc(.75em + .375rem) calc(.75em + .375rem) !important;
        }
        .dataTables_wrapper .dataTables_length > label {
            display: flex;
            align-content: center;
            align-items: center;
        }

        .dataTables_wrapper .dataTables_length > label > select {
            max-width: 100px;
            margin-right: 7px;
        }

        table.Datatable.table.tabled-menu .new-field-menu label,
        table.Datatable.table.tabled-model label {
            font-size: 11px;
            margin-bottom: 0px;
        }

        table.Datatable.table.tabled-menu .new-field-menu,
        table.Datatable.table.tabled-model .form-group{
            margin-bottom: 0px;
        }

        tr.new-menu-item td:hover,
        tr.new-model-item td:hover {
            width: 100%;
            min-width: 280px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }

        .navigation {
            min-height: 100vh;
        }

        .file-content {
            position: relative;
        }

        .file-content .file-preview {
            border-radius: 8px;
            background: #f1f1f1;
            height: 180px;
            display: flex;
            align-content: center;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .file-content .file-preview img {
            max-width: 100%;
            object-fit: contain;
        }

        .file-control {
            position: absolute;
            top: 0px;
            right: 0px;
            left: 0px;
            bottom: 0px;
            display: none;
            z-index: 99;    
            align-content: center;
            justify-content: center;
            align-items: center;
            background: #1a1a1aa6;
            border-radius: 8px;
        }

        .file-content:hover .file-control{
            display: flex;
        }

        #uploadMedia .modal-content .modal-body {
            padding-top: 8px;
        }

        form#dropzone-file-manager{ 
            position: relative;
        }

        /*form#dropzone-file-manager > p {
            position: absolute;
            top: 0px;
            right: 0px;
            bottom: 0px;
            left: 0px;
            display: flex;
            align-items: center;
            justify-content: center;
            align-content: center;
        }*/
        .file-manager-item {
            position: absolute;
            top: 0px;
            left: 0px;
            right: 0px;
            bottom: 0px;
            flex-direction: row;
            align-content: center;
            justify-content: center;
            align-items: center;
            background: rgb(26 26 26 / 87%);
            border-radius: 8px;
            display: none;
        }

        .card.app-file-list.app-render-item:hover .file-manager-item{
            display: flex;
        }
    </style>

    <?=(isset($head)?$head():"")?>
</head>
<body>

<?=view('layout/dashboard/tools/preloader');?>

<?=view('layout/dashboard/tools/navigation');?>

<!-- begin::main -->
<div id="main">

    <?=view('layout/dashboard/header');?>

    <!-- begin::main-content -->
    <main class="main-content">

        <div class="container-fluid">

            <!-- begin::page-header -->
            <div class="page-header">
                <?php if(isset($filter)){ ?>    
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <?php } ?>
                        <h4><?=_($titlePage);?></h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <?php foreach ($breadcrumbs as $key => $breadcrumb) { ?>
                                    <li class="breadcrumb-item<?=((count($breadcrumbs)-1)===$key)?" active":""?>" <?=((count($breadcrumbs)-1)===$key)?"aria-current=\"page\"":""?>>
                                        <a href="<?=$breadcrumb->slug;?>"><?=_($breadcrumb->title);?></a>
                                    </li>
                                <?php } ?>
                            </ol>
                        </nav>
                    <?php if(isset($filter)){ ?>    
                    </div>
                    <div class="ccol-sm-12 col-md-6">
                        <?=view('layout/dashboard/'.$filter);?>
                    </div>
                </div>
                <?php } ?>
            </div>
            <!-- end::page-header -->
            <?=view('layout/dashboard/'.$view);?>

            
        </div>

    </main>
    <!-- end::main-content -->

    <?=view('layout/dashboard/footer');?>
    <?=view('layout/dashboard/filemanager/upload');?>
</div>
<!-- end::main -->

<!-- begin::global scripts -->
<script src="<?=base_url('public/vendors/bundle.js');?>"></script>
<!-- end::global scripts -->

<!-- DataTable -->
<script src="<?=base_url('public/vendors/dataTable/jquery.dataTables.min.js');?>"></script>
<script src="<?=base_url('public/vendors/dataTable/dataTables.bootstrap4.min.js');?>"></script>
<script src="<?=base_url('public/vendors/dataTable/dataTables.responsive.min.js');?>"></script>
<script src="<?=base_url('public/assets/js/examples/datatable.js?ver=1.0.4');?>"></script>

<!-- begin::custom scripts -->
<script src="<?=base_url('public/assets/js/custom.js');?>"></script>
<script src="<?=base_url('public/assets/js/app.js');?>"></script>
<!-- end::custom scripts -->

<!-- Javascript -->
<script src="<?=base_url('public/vendors/select2/js/select2.min.js');?>"></script>

<!-- Javascript -->
<script src="<?=base_url('public/vendors/dropzone/dropzone.js');?>"></script>
<script type="text/javascript">
    'use strict';
    const initSelects = () => {
        $('.select-actived').select2({
            placeholder: '<?=_('Seleccione');?>'
        });
    }
    $(document).ready(function () { 

        let FileSelectioned = null;
        let InputMediaFile = null;
        let FileContentPreview = null;
        let Filemanager = new Dropzone("#dropzone-file-manager", {});
        let BtnSelectFile = $('.btn-file-manager');

        let PanelListManager = $('#panel-file-managers');
        let InputSearch = $('input#input-file-manager-search')

        let initSelected = () => {
            $('.card.app-file-list.app-render-item .file-manager-item a').on('click', function(){
                let idfile = $(this).attr('data-id');
                if(InputMediaFile && idfile!=='none'){
                    InputMediaFile.val(idfile);
                    $.ajax({
                        url: '<?=site_url('dashboard/file-manager/content');?>',   // URL a la que enviar la solicitud
                        method: 'POST',      // Método HTTP (POST, GET, etc.)
                        data: {typed: 'file', id: idfile},          // Datos a enviar en la solicitud (opcional)
                        dataType: 'json'     // Tipo de datos esperados en la respuesta (opcional)
                    }).done((response)=>{
                        if(response?.data){
                            FileSelectioned = response?.data;
                            if(FileContentPreview){
                                FileContentPreview.html('');
                                FileContentPreview.append('<img src="<?=site_url("file/")?>'+FileSelectioned?.slug+'"/>');
                            }
                        }
                        $('#uploadMedia button.close[data-dismiss="modal"]').click();
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        //Callback para manejar errores
                        console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
                    });
                }
            });
        }

        BtnSelectFile.attr('disabled','disabled');
        $('#dropzone-file-manager').addClass('dropzone');

        $('.file-content .file-control a.btn-saved[data-target="#uploadMedia"]').on('click', function(){
            let InputName = $(this).attr('data-name');
            InputMediaFile = $('input[name="'+InputName+'"]');

            FileContentPreview = $('.file-content.content-'+InputName+' .file-preview.preview-'+InputName);
        })
        Filemanager.on("success", function(file, response) {
            if(response?.file){
                BtnSelectFile.removeClass('disabled');
                BtnSelectFile.removeAttr('disabled');
                FileSelectioned = response?.file;

                let count = $('#uploadMedia h6 b.manager-count').html();

                if($(PanelListManager.find('.folder-file-manager-empty'))){
                    $(PanelListManager.find('.folder-file-manager-empty')).remove();
                }
                PanelListManager.append('<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">'+response?.view+'</div>');

                count = parseInt((count.replace('(','')).replace(')',''));
                $('#uploadMedia h6 b.manager-count').html('('+(count++)+')');
                initSelected();
            }
        });

        BtnSelectFile.on('click', function(){
            if(FileSelectioned !== null && InputMediaFile !== null){
                InputMediaFile.val(FileSelectioned?.idfile);
                if(FileContentPreview){
                    FileContentPreview.html('');
                    FileContentPreview.append('<img src="<?=site_url("file/")?>'+FileSelectioned?.slug+'"/>');
                }
                $('#uploadMedia button.close[data-dismiss="modal"]').click();
            }
        });


        $('button#button-file-manager-search').on('click', function () {
            $.ajax({
                url: '<?=site_url("dashboard/file-manager/find");?>',   // URL a la que enviar la solicitud
                method: 'POST',      // Método HTTP (POST, GET, etc.)
                data: { search: InputSearch.val() },          // Datos a enviar en la solicitud (opcional)
                dataType: 'json'     // Tipo de datos esperados en la respuesta (opcional)
            }).done((response)=>{
                let result = (response?.view??[]);

                PanelListManager.html('');
                if(InputSearch.val() !== ""){
                    PanelListManager.html('<div class="col-md-12 col-sm-12"><p class="text-black-50 text-left text-light">'+(result?.length === 0 ? '<?=_('No hay resultados disponibles para')?> <b>'+InputSearch.val()+'</b>': '<b>'+result?.length+'</b> <?=_('resultados encontrados para')?> <b>'+InputSearch.val()+'</b>')+'</p></div>');
                }

                result.forEach( (file) => {
                    PanelListManager.append('<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">'+file+'</div>');
                });

                initSelected();
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
            });
        });

        $('.btn-quit-file').on('click', function(){
            let InputName = $(this).attr('data-name');
            $('input[name="'+InputName+'"]').val('');
            $('.file-content.content-'+InputName+' .file-preview.preview-'+InputName).html('<p class="text-black-50"><?=_('Sin contenido.');?></p>');
        });

        initSelected();
        initSelects();
        <?php if(!empty(session()->getFlashdata('fail'))){ ?>
            swal('<?= _('Se ha producido un error'); ?>', '<?= session()->getFlashdata('fail'); ?>', "error");
        <?php } ?>
        <?php if(!empty(session()->getFlashdata('success'))){ ?>
            swal('<?= _('Felicidades'); ?>', '<?=session()->getFlashdata('success'); ?>', "success");
        <?php } ?>
        <?php if(!empty(session()->getFlashdata('warning'))){ ?>
            swal('<?= _('Alerta'); ?>', '<?= session()->getFlashdata('warning'); ?>', "warning");
        <?php } ?>
        <?php if(!empty(session()->getFlashdata('info'))){ ?>
            swal('<?= _('Información'); ?>', '<?= session()->getFlashdata('info'); ?>', "info");
        <?php } ?>
    });
</script>

<?=(isset($script)?$script():"")?>

</body>
</html>