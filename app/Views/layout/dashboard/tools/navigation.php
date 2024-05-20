<?php $User = User(); ?>
<!-- begin::navigation -->
<div class="navigation">

    <!-- begin::logo -->
    <?=logo_html(true);?>
    <!-- end::logo -->

    <!-- begin::navigation header -->
    <header class="navigation-header">
        <figure class="avatar avatar-state-success">
            <img src="https://via.placeholder.com/128X128" class="rounded-circle" alt="image">
        </figure>
        <div>

            <?php //var_dump(User()); ?>
            <?php if($User){ ?>
                <h5><?=$User->fullname;?></h5>
                <p class="text-muted" style="color: #fab814 !important;"><?=$User->role->title;?></p>
            <?php } ?>
            <ul class="nav">
                <li class="nav-item">
                    <a href="profile.html" class="btn nav-link bg-info-bright" title="Profile" data-toggle="tooltip">
                        <i data-feather="user"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="btn nav-link bg-success-bright" title="Settings" data-toggle="tooltip">
                        <i data-feather="settings"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="login.html" class="btn nav-link bg-danger-bright" title="Logout" data-toggle="tooltip">
                        <i data-feather="log-out"></i>
                    </a>
                </li>
            </ul>
        </div>
    </header>
    <!-- end::navigation header -->

    <?php if($User){ ?>
        <?php 
            $Setting_menu = SETTING('menu_order', 'menu');
            $Setting_order = [];
            try {
                //code...
                $Setting_order = json_decode($Setting_menu->value);
            } catch (\Throwable $th) {
                //throw $th;
                $Setting_order = [];
            }
        ?>
        <!-- begin::navigation menu -->
        <div class="navigation-menu-body">
            <ul>
                <?php 
                    foreach ($Setting_order as $item) {
                        echo li_menus($item, $User->menu);
                    } 
                ?>
            </ul>
        </div>
        <!-- end::navigation menu -->
    <?php } ?>
</div>
<!-- end::navigation -->