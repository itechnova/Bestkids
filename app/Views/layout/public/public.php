<!doctype html>
<html lang="<?=$lang;?>">
<head>

    <?=view('layout/head');?>
    <style>
        .error .form-control {
            border-color: #dc3545 !important;
            padding-right: calc(1.5em + .75rem) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23dc3545' viewBox='-2 -2 7 7'%3e%3cpath stroke='%23dc3545' d='M0 0l3 3m0-3L0 3'/%3e%3ccircle r='.5'/%3e%3ccircle cx='3' r='.5'/%3e%3ccircle cy='3' r='.5'/%3e%3ccircle cx='3' cy='3' r='.5'/%3e%3c/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: center right calc(.375em + .1875rem) !important;
            background-size: calc(.75em + .375rem) calc(.75em + .375rem) !important;
        }

        body.form-membership .form-wrapper form .form-control{
            margin-bottom: 0.5rem;
        }
    </style>

    <?=(isset($head)?$head():"")?>
</head>
<body class="form-membership">

<?=view('layout/dashboard/tools/preloader');?>

<!-- begin::main -->
<?=view('layout/public/'.$view);?>
<!-- end::main -->

<!-- begin::global scripts -->
<script src="<?=base_url('public/vendors/bundle.js');?>"></script>
<!-- end::global scripts -->

<!-- begin::custom scripts -->
<script src="<?=base_url('public/assets/js/app.js');?>"></script>
<!-- end::custom scripts -->

<!-- Javascript -->
<script src="<?=base_url('public/vendors/select2/js/select2.min.js');?>"></script>
<script type="text/javascript">
    'use strict';

    const initSelects = () => {
        $('.select-actived').select2({
            placeholder: '<?=_('Seleccione');?>'
        });
    }
    $(document).ready(function () { 
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
</body>
</html>