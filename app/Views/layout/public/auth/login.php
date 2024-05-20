<?=view('layout/head');?>
<div style="display: flex;justify-content: center;align-items: center;align-content: center;width: 100%;">
    <div class="form-wrapper">

        <!-- logo -->
        <?=logo_html();?>
        <!-- ./ logo -->

        <h5><?=$content->title;?></h5>

        <?php 
            ob_start();
            foreach ([
                ((Object) array(
                    'class' => 'text-left',
                    'name' => 'username',
                    'label' => 'Correo',
                    'type' => 'text',
                    'placeholder'=> 'Ingresa correo',
                    'required' => true,
                    'attrs'=>[
                        'autocomplete'=>"username"
                    ]
                )),
                ((Object) array(
                    'class' => 'text-left',
                    'name' => 'password',
                    'label' => 'Contraseña',
                    'type' => 'password',
                    'required' => true,
                    'placeholder'=> 'Ingresa contraseña',
                ))
            ] as $field) {
                echo field_html($field, $values, $validator);
            }
            ?>
            <div class="form-group d-flex justify-content-between">
                <?=field_html((Object) array(
                    'name' => 'remember',
                    'label' => _('Recordarme'),
                    'type' => 'switch'                    
                ), $values, $validator);?>
                <a href="<?=site_url('/lost');?>"><?=_('Recuperar contraseña')?></a>
            </div>
            <button class="btn btn-primary btn-block"><?=_('Iniciar sesión');?></button>
            <!--hr>
            <--p class="text-muted">Login with your social media account.</p>
            <ul class="list-inline">
                <li class="list-inline-item">
                    <a href="#" class="btn btn-floating btn-facebook">
                        <i class="fa fa-facebook"></i>
                    </a>
                </li>
                <li class="list-inline-item">
                    <a href="#" class="btn btn-floating btn-twitter">
                        <i class="fa fa-twitter"></i>
                    </a>
                </li>
                <li class="list-inline-item">
                    <a href="#" class="btn btn-floating btn-dribbble">
                        <i class="fa fa-dribbble"></i>
                    </a>
                </li>
                <li class="list-inline-item">
                    <a href="#" class="btn btn-floating btn-linkedin">
                        <i class="fa fa-linkedin"></i>
                    </a>
                </li>
                <li class="list-inline-item">
                    <a href="#" class="btn btn-floating btn-google">
                        <i class="fa fa-google"></i>
                    </a>
                </li>
                <li class="list-inline-item">
                    <a href="#" class="btn btn-floating btn-behance">
                        <i class="fa fa-behance"></i>
                    </a>
                </li>
                <li class="list-inline-item">
                    <a href="#" class="btn btn-floating btn-instagram">
                        <i class="fa fa-instagram"></i>
                    </a>
                </li>
            </ul>
            <hr-->
            <!--p class="text-muted"><?=_('¿No tienes una cuenta?');?></p>
            <a href="<?=site_url('/register');?>" class="btn btn-outline-light btn-sm"><?=_('¡Regístrate ahora!');?></a-->
            <?php
            echo form_html(((Object) array(
                'method' => "POST",
                'enctype'=> true,
                'action'=> "/login",
                'validator'=> $validator,
                'context' => ob_get_clean()
            )));
                            
            ?>
        <!-- form -->

        <!-- ./ form -->

    </div>
</div>
<div class="leaf-top"></div>
<div class="leaf-left"></div>
<div class="child"></div>
<div class="leaf-right"></div>