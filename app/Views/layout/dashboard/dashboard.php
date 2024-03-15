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
</head>
<body>

<?=view('layout/dashboard/tools/preloader');?>

<?=view('layout/dashboard/tools/navigation');?>

<!-- begin::main -->
<div id="main">

    <?=view('layout/dashboard/header');?>

    <!-- begin::main-content -->
    <main class="main-content">

        <div class="container">

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