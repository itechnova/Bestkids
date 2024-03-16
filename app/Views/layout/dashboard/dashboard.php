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

    <style>

        .error .form-control {
            border-color: #dc3545 !important;
            padding-right: calc(1.5em + .75rem) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23dc3545' viewBox='-2 -2 7 7'%3e%3cpath stroke='%23dc3545' d='M0 0l3 3m0-3L0 3'/%3e%3ccircle r='.5'/%3e%3ccircle cx='3' r='.5'/%3e%3ccircle cy='3' r='.5'/%3e%3ccircle cx='3' cy='3' r='.5'/%3e%3c/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: center right calc(.375em + .1875rem) !important;
            background-size: calc(.75em + .375rem) calc(.75em + .375rem) !important;
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

<!-- begin::custom scripts -->
<script src="<?=base_url('public/assets/js/custom.js');?>"></script>
<script src="<?=base_url('public/assets/js/app.min.js');?>"></script>
<!-- end::custom scripts -->

</body>
</html>