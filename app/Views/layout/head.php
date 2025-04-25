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
    body.form-membership {position: relative; height: 100vh;background-color: #c0fcfe; color: #444444;}
    body.form-membership .form-wrapper {z-index: 999;max-height: 600px;border-radius: 30px; box-shadow: 1px 1px 20px rgb(26 26 26 / 14%);}
    .leaf-top{position: absolute;top: 0px;left: 10%;background-size: cover;width: 300px;height: 220px;background-image: url('<?=base_url('public/assets/resource/leaftop.png');?>');}
    .leaf-left{z-index: 0;position: absolute;bottom: 0px;left: 0px;background-size: cover;width: 740px;height: 740px;background-image: url('<?=base_url('public/assets/resource/leafleft.png');?>');}
    .child{z-index: 1;position: absolute;bottom: 0px;right: 2%;background-size: cover;filter: drop-shadow(10px 10px 15px rgba(0, 0, 0, 0.5));width: 640px;height: 640px;background-image: url('<?=base_url('public/assets/resource/children.png');?>');}
    .leaf-right{z-index: 0;position: absolute;bottom: 0px;right: 0px;background-size: cover;width: 340px;height: 420px;background-image: url('<?=base_url('public/assets/resource/leafright.png');?>');}
    .btn.btn-primary {
        background: rgb(173, 0, 111);
        border-color: rgb(173, 0, 111);
    }
    .btn.btn-primary:hover {
        background: rgb(149 0 96) !important;
        border-color: rgb(149 0 96) !important;
    }
    a{color: rgb(255 155 0) !important}
    a:hover{color: rgb(42 178 173) !important}

    .custom-switch .custom-control-input:checked~.custom-control-label::before {
        border-color: rgb(173, 0, 111);
        background-color: rgb(173, 0, 111);
    }
    .custom-switch .custom-control-input:not(:disabled):active~.custom-control-label::before {
        border-color: #ad086f;
        background-color: #ad086f;
    }
    .custom-switch .custom-control-input:focus~.custom-control-label::before {
        -webkit-box-shadow: 0 0 0 .2rem rgba(173, 0, 111, .3);
        -moz-box-shadow: 0 0 0 .2rem rgba(173, 0, 111, .3);
        box-shadow: 0 0 0 .2rem rgb(173, 0, 111, .3);
    }
    .custom-control-input:focus:not(:checked)~.custom-control-label::before {
        border-color: #b23282;
    }
    .form-control {
        color: #666;
        border-color: #dddddd;
        background: #fafafa;
    }
    body.form-membership .form-wrapper #logo img {
        max-width: 100%;
        width: 100%;
    }
    @media only screen and (max-width: 1300px) {
        .child {
            display: none;
        }
    }

    #main, .modal .modal-dialog .modal-content .modal-header {
        background: url('<?=base_url('public/assets/resource/bg.jpeg');?>');
    }

    .header {
        background: transparent;
    }

    .navigation #logo img {
        width: 100%;
        max-width: 100%;
    }

    body.small-navigation:not(.hidden-navigation) .navigation #logo .logo-sm {
        max-width: 60px;
    }
    .navigation #logo img {
        max-width: 210px;
    }
    .navigation {
        background-color: #fffbeb;
        box-shadow: 1px 1px 20px rgb(26 26 26 / 14%);
    }

    .card:not(.card-body) {
        border-radius: 30px;
        background: #ffffffb8;
        box-shadow: 1px 1px 20px rgb(26 26 26 / 14%);
    }

    .bg-info-bright {
        background: rgb(0 97 175) !important;
        color: #fff !important;
    }
    .bg-success-bright {
        background: rgb(42 178 173) !important;
        color: #fff !important;
    }
    .bg-danger-bright {
        background: rgb(214 16 117) !important;
        color: #fff !important;
    }
    .bg-info-bright:hover,
    .bg-success-bright:hover,
    .bg-danger-bright:hover{
        color: #fff !important;
    }
    .navigation .navigation-menu-body>ul li.navigation-divider {
        color: #444444;
    }

    .navigation .navigation-menu-body a, .navigation .navigation-menu-body svg {
        color: rgb(33 96 174) !important;
        stroke: rgb(33 96 174) !important;
    }
    .navigation .navigation-menu-body>ul li a:hover {
        background: rgb(250 156 10 / 27%);
    }
    .navigation .navigation-menu-body li:hover a, .navigation .navigation-menu-body li:hover svg {
        color: rgb(214 16 117) !important;
        stroke: rgb(214 16 117) !important;
    }
    .btn.btn-secondary {
        background: #d61075;
        border-color: #d61075;
        color: #fff !important;
    }
    .btn.btn-secondary:not(:disabled):not(.disabled):active, .btn.btn-secondary:not(:disabled):not(.disabled):focus, .btn.btn-secondary:not(:disabled):not(.disabled):hover {
        background: #ad055b !important;
        border-color: #ad055b !important;
    }
    .table thead, .table tfoot{
        position: relative;
    }

    .table thead::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 3px;
        bottom: 0px;
        left: 0px;
        right: 0px;
        z-index: 1;
        background: linear-gradient(to right, rgb(0, 97, 175), rgb(42, 178, 173), rgb(214, 16, 117), rgb(255, 155, 0));
    }

    .table tfoot::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 3px;
        top: 0px;
        left: 0px;
        right: 0px;
        z-index: 1;
        background: linear-gradient(to right, rgb(0, 97, 175), rgb(42, 178, 173), rgb(214, 16, 117), rgb(255, 155, 0));
    }


    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgb(250 156 10 / 11%);
    }

    .table td, .table th {
        border-top: none;
    }
    .table td{
        color: #444;
        font-weight: 100;
    }

    .table thead th, .table tfoot th {
        font-weight: 600 !important;
    }
    .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
        color: #2bb2ad;
    }

    .btn-success {
        color: #fff !important;
        background-color: #2bb2ad !important;
        border-color: #2bb2ad !important;
    }

    .btn-success:hover {
        color: #fff !important;
        background-color: #079c96 !important;
        border-color: #079c96 !important;
    }

    .header .navigation-toggler a{
        background: #2161af !important;
    }

    .header .navigation-toggler a svg {
        stroke: #fff;
    }

    body.small-navigation:not(.hidden-navigation) .navigation .navigation-menu-body ul li.navigation-divider:after {
        background-color: #f5bf25 !important;
    }

    .card.cursor-pointer {
        overflow: hidden;
    }

    .text-primary {
        color: #2161af !important;
    }

    #society .modal-body {
        overflow: hidden;
        background: #c0fcfe;
    }

    .society_btn{
        position: absolute;
        top: 0px;
        left: 0px;
        right: 0px;
        bottom: 0px;
        z-index: 1;
        display: flex;
        align-content: center;
        justify-content: center;
        align-items: center;
        background: rgb(26 26 26 / 88%);
        display: none;
    }


    .item-society:hover .society_btn{
        display: flex;
    }

    .alert_message{
        display: none;
        color: #fff;
        background: #2161af;
        border: 1px solid #2161af;
        box-shadow: 1px 1px 14px rgb(26 26 26 / 44%);
        padding: 10px 15px;
        border-radius: 8px;
        width: auto;
        max-width: 540px;
        margin: 20px auto -20px;
    }

    .form-group.wd-xs-300.slug {
        display: none;
    }
</style>
