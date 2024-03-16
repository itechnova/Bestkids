<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=$title;?> - <?=_($description);?></title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?=base_url('public/assets/media/image/favicon.png');?>"/>

    <!-- Plugin styles -->
    <link rel="stylesheet" href="<?=base_url('public/vendors/bundle.css');?>" type="text/css">

    <!-- App styles -->
    <link rel="stylesheet" href="<?=base_url('public/assets/css/app.min.css');?>" type="text/css">

    <!-- DataTable -->
    <link rel="stylesheet" href="<?=base_url('public/vendors/dataTable/dataTables.min.css');?>" type="text/css">

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
    </style>
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
</div>
<!-- end::main -->

<!-- begin::global scripts -->
<script src="<?=base_url('public/vendors/bundle.js');?>"></script>
<!-- end::global scripts -->

<!-- DataTable -->
<script src="<?=base_url('public/vendors/dataTable/jquery.dataTables.min.js');?>"></script>
<script src="<?=base_url('public/vendors/dataTable/dataTables.bootstrap4.min.js');?>"></script>
<script src="<?=base_url('public/vendors/dataTable/dataTables.responsive.min.js');?>"></script>
<script src="<?=base_url('public/assets/js/examples/datatable.js?ver=1.0.2');?>"></script>

<!-- begin::custom scripts -->
<script src="<?=base_url('public/assets/js/custom.js');?>"></script>
<script src="<?=base_url('public/assets/js/app.min.js');?>"></script>
<!-- end::custom scripts -->
<script type="text/javascript">
    'use strict';
    $(document).ready(function () { 
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
</body>
</html>