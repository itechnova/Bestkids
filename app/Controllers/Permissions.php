<?php

namespace App\Controllers;

class Permissions extends BaseController
{

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $title = 'Permisos';
        
    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $slug = 'dashboard/permission';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewView = 'permission/view';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewEdit = 'permission/edit';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewList = 'permission/list';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewFilter = 'permission/filter';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $roleId = "";

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $permissionType = "";

    /*public function getName(): string
    {
        return 'permissions';
    }*/

    /**
     * An object of helpers to be loaded automatically upon
     *
     * @var object
     */
    protected function viewContent(): Object
    {
        return (Object) array(
            'new' => ((Object) array(
                'titlePage' => 'Nuevo permiso',
                'description' => 'Crear nuevo permiso',
                'title' => 'Crear nuevo permiso',
                'content' => 'Rellena los datos del formulario.'
            )),
            'view' => ((Object) array(
                'titlePage' => 'Permiso ',
                'description' => 'Detalles del permiso ',
                'title' => 'Permiso ',
                'content' => 'Datos generales del permiso '
            )),
            'edit' => ((Object) array(
                'titlePage' => 'Editar permiso',
                'description' => 'Editar permiso',
                'title' => 'Editar permiso',
                'content' => 'Cambia los datos del formulario.'
            )),
            'list' => ((Object) array(
                'titlePage' => 'Permisos de ',
                'description' => 'Lista de permisos disponibles para ',
                'title' => 'Lista de permisos',
                'content' => 'Permisos disponibles para '
            ))
        );
    }

    /**
     * An array of helpers to be loaded automatically upon
     *
     * @var array
     */
    protected function getColumns(): array
    {
        return array(
            ((Object) array(
                'key' => 'idpermission',
                'label' => _('ID')
            )),
            ((Object) array(
                'key' => 'name',
                'label' => _('Permiso')
            )),
            ((Object) array(
                'key' => 'granted',
                'label' => _('Todos')
            )),
            ((Object) array(
                'key' => 'access_index',
                'label' => _('Visualizar')
            )),
            ((Object) array(
                'key' => 'access_view',
                'label' => _('Detalles')
            )),
            ((Object) array(
                'key' => 'access_new',
                'label' => _('Agregar')
            )),
            ((Object) array(
                'key' => 'access_edit',
                'label' => _('Editar')
            )),
            ((Object) array(
                'key' => 'access_trash',
                'label' => _('Eliminar')
            ))/*,
            ((Object) array(
                'key' => 'created_at',
                'label' => _('Creado')
            )),
            ((Object) array(
                'key' => 'updated_at',
                'label' => _('Actualizado')
            )),
            ((Object) array(
                'key' => 'ACTION',
                'label' => '<div class="d-flex justify-content-end"><i class="fa fa-ellipsis-v"></i></div>'
            )),*/
        );
    }

    public function index($id="")
    {
        $this->roleId = $id;

        if($this->roleId !== ""){
            $Role = new \App\Models\RoleModel();
            $RoleData = $Role->Exists($this->roleId);

            $Label = "";
            if(!IS_NULL($RoleData)){    
                $Label = ' rol <b>'.strtolower($RoleData['title']).'</b>';
                $this->permissionType = "role";
            }  
            
            $this->titlePage = $this->viewContent()->list->titlePage.$Label;
            $this->description = $this->viewContent()->list->description.$Label;
            $this->setContent($this->viewContent()->list->title, $this->viewContent()->list->content.$Label);
            $this->addBreadcrumb($this->titlePage);
            $this->withLayout = 'index';
            return $this->View($this->viewList);
        }

        return redirect()->to($this->slug.'s')->with('warning', '¡Este rol no existe!');
        //return parent::index();
    }

    protected function getModel(){
        return new \App\Models\PermissionModel();
    }

    protected function FILTER(){

        $pathController = APPPATH."Controllers";
        $controllerFiles = scandir($pathController);
        $ControllerAllowed = array_filter($controllerFiles, function($file) {
            if($file === 'BaseController.php'){
                return false;
            }
            return pathinfo($file, PATHINFO_EXTENSION) === 'php';
        });
        
        foreach ($ControllerAllowed as $Controller) {
            # code...
            $ControllerName = pathinfo($Controller, PATHINFO_FILENAME);
            $ControllerClassPath = 'App\Controllers\\'.$ControllerName;
            if(!class_exists($ControllerClassPath)){
                require_once $pathController.'/'.$ControllerName.'.php';
            }
            //$controllerClass = 'App\Controllers\\'.$controllerName;
            
            if(class_exists($ControllerClassPath)){
                $ControllerClass = new $ControllerClassPath();
                if($this->getModel() && $this->roleId!==""){
                    $Role = new \App\Models\RoleModel();

                    $RoleData = $Role->Exists($this->roleId);
                    if(!IS_NULL($RoleData)){
                        $Permission = $this->getModel()
                        ->where('idjoin', $this->roleId)
                        ->where('permission', 'role')
                        ->where('name', strtolower($ControllerName))
                        ->first();
                        if(IS_NULL($Permission)){
                            $this->getModel()->insert([
                                'idjoin' => $this->roleId,
                                'permission' => 'role',
                                'name' => strtolower($ControllerName),
                                'access_index' => 1,
                                'access_new' => 0,
                                'access_view' => 0,
                                'access_edit' => 0,
                                'access_trash' => 0
                            ]);
                        }
                    }
                }
            }
            
        }
        if(!$this->getModel()){
            return [];
        }
        $permissionType = $this->permissionType!==""?$this->permissionType: "role";
        
        return $this->getModel()
        ->where('idjoin', $this->roleId)
        ->where('permission', $permissionType)
        ->findAll();

    }

    protected function td($tr, $td, $column){
        if($column === 'access_index' || $column === 'access_new' || $column === 'access_view' || $column === 'access_edit' || $column === 'access_trash' || $column === 'granted'){
            $Values = [];
            if($column !== 'granted'){
                $Values[$column] = $td;
            }else{
                $Values[$column] = (($tr['access_index'] === '1') && ($tr['access_new'] === '1') && ($tr['access_view'] === '1') && ($tr['access_edit'] === '1') && ($tr['access_trash'] === '1')) ? 1: 0;
            }
            
            return field_html((Object) array(
                'id' => 'permission_'.$column.'_'.$tr['idpermission'],
                'name' => $column,
                'label' => _('Permitir'),
                'type' => 'switch',
                'class' => 'mb-0 switch-permissions '.$column.' switch-access-'.$tr['idpermission']
            ), $Values);
        }

        if($column === 'name'){
            /*$ModelID = $this->getModel()->getID($tr);
            ob_start(); ?>
                <a href="<?=site_url($this->slug.'/view/'.$ModelID);?>"><?=$td;?></a>
            <?php ob_get_clean();*/
            return _($td);
        }

        if($column === 'ACTION'){
            $ModelID = $this->getModel()->getID($tr);

            ob_start(); ?>
            <div class="d-flex justify-content-end">
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2"><?=_('Opción');?></span>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?=site_url($this->slug.'/view/'.$ModelID);?>"><i class="fa fa-eye mr-2"></i><?=_('Vér');?></a>
                        <a class="dropdown-item" href="<?=site_url($this->slug.'/edit/'.$ModelID);?>"><i class="fa fa-pencil-square-o mr-2"></i><?=_('Editar');?></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?=site_url($this->slug.'/trash/'.$ModelID);?>"><i class="fa fa-trash mr-2"></i><?=_('Eliminar');?></a>
                    </div>
                </div>
            </div>
            <?php
            return ob_get_clean();
        }
        return $td;
    }

    public function saved(){
        if($this->getID()){
            $data = $this->getValues();

            $enabled = $this->request->getPost('enabled') === 'on' ? 1: 0;

            $Updated = [];
            if($data['name'] === 'granted'){
                $Updated['access_index'] = $enabled;
                $Updated['access_new'] = $enabled;
                $Updated['access_view'] = $enabled;
                $Updated['access_edit'] = $enabled;
                $Updated['access_trash'] = $enabled;                
            }else{
                if($data['name'] === 'access_index'){
                    $Updated['access_index'] = $enabled;
                    if($enabled === 0){
                        $Updated['access_new'] = 0;
                        $Updated['access_view'] = 0;
                        $Updated['access_edit'] = 0;
                        $Updated['access_trash'] = 0;                        
                    }
                }else{
                    $Updated[$data['name']] = $enabled;
                }
            }

            $Model = $this->getModel();

            $Model->update($this->getID(), $Updated);
        }
        return '';
    }

    protected function script(): string
    {
        ob_start(); ?>
        <script type="text/javascript">
            'use strict';
            const roleId = '<?=$this->roleId;?>';
            const permissionType = '<?=$this->permissionType;?>';
            $(document).ready(function () { 

                const ChangeSwitch = (permissionId, Name, checked) => {
                    $.ajax({
                        url: '<?=site_url($this->slug."/saved");?>',   // URL a la que enviar la solicitud
                        method: 'POST',      // Método HTTP (POST, GET, etc.)
                        data: {
                            idpermission: permissionId,
                            idjoin: roleId,
                            permission: permissionType,
                            name: Name,
                            enabled: (checked ? 'on': 'off')
                        },          // Datos a enviar en la solicitud (opcional)
                        dataType: 'json'     // Tipo de datos esperados en la respuesta (opcional)
                    }).done((response) => {
                        // Callback para manejar la respuesta exitosa
                        console.log('Respuesta exitosa:', response);
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        // Callback para manejar errores
                        console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
                    });
                }


                $('.switch-permissions input[name="granted"]').change(function() {
                    const $switch = $(this);
                    const permissionName = $switch.attr('name');
                    const permissionId = $switch.attr('id').replace("permission_"+permissionName+"_", "");
                    const checked = $switch.prop('checked');

                    $('.switch-access-'+permissionId+' input[type="checkbox"]').prop('checked', checked);

                    if(checked){
                        $('.switch-access-'+permissionId+' input[name="access_view"]').prop('disabled', false);
                        $('.switch-access-'+permissionId+' input[name="access_new"]').prop('disabled', false);
                        $('.switch-access-'+permissionId+' input[name="access_edit"]').prop('disabled', false);
                        $('.switch-access-'+permissionId+' input[name="access_trash"]').prop('disabled', false);
                    }else{
                        $('.switch-access-'+permissionId+' input[name="access_view"]').prop('disabled', true);
                        $('.switch-access-'+permissionId+' input[name="access_new"]').prop('disabled', true);
                        $('.switch-access-'+permissionId+' input[name="access_edit"]').prop('disabled', true);
                        $('.switch-access-'+permissionId+' input[name="access_trash"]').prop('disabled', true);
                    }

                    ChangeSwitch(permissionId, permissionName, checked);
                });

                $('.switch-permissions input[name="access_index"]').change(function() {
                    const $switch = $(this);
                    const permissionName = $switch.attr('name');
                    const permissionId = $switch.attr('id').replace("permission_"+permissionName+"_", "");
                    const checked = $switch.prop('checked');

                    if(!checked){
                        $('.switch-access-'+permissionId+' input[name="access_view"]').prop('checked', false);
                        $('.switch-access-'+permissionId+' input[name="access_new"]').prop('checked', false);
                        $('.switch-access-'+permissionId+' input[name="access_edit"]').prop('checked', false);
                        $('.switch-access-'+permissionId+' input[name="access_trash"]').prop('checked', false);

                        $('.switch-access-'+permissionId+' input[name="access_view"]').prop('disabled', true);
                        $('.switch-access-'+permissionId+' input[name="access_new"]').prop('disabled', true);
                        $('.switch-access-'+permissionId+' input[name="access_edit"]').prop('disabled', true);
                        $('.switch-access-'+permissionId+' input[name="access_trash"]').prop('disabled', true);

                        $('.switch-access-'+permissionId+' input[name="granted"]').prop('checked', false);

                    }else{
                        $('.switch-access-'+permissionId+' input[name="access_view"]').prop('disabled', false);
                        $('.switch-access-'+permissionId+' input[name="access_new"]').prop('disabled', false);
                        $('.switch-access-'+permissionId+' input[name="access_edit"]').prop('disabled', false);
                        $('.switch-access-'+permissionId+' input[name="access_trash"]').prop('disabled', false);
                    }
                    ChangeSwitch(permissionId, permissionName, checked);
                });

                $('.switch-permissions input[type="checkbox"]').change(function() {
                    const $switch = $(this);
                    const permissionName = $switch.attr('name');
                    const permissionId = $switch.attr('id').replace("permission_"+permissionName+"_", "");
                    const checked = $switch.prop('checked');
                    const checkedGranted = $('.switch-access-'+permissionId+' input[name="access_index"]').prop('checked') && $('.switch-access-'+permissionId+' input[name="access_view"]').prop('checked') && $('.switch-access-'+permissionId+' input[name="access_new"]').prop('checked') && $('.switch-access-'+permissionId+' input[name="access_edit"]').prop('checked') && $('.switch-access-'+permissionId+' input[name="access_trash"]').prop('checked');

                    if(permissionName !== 'granted' && permissionName !== 'access_index'){
                        $('.switch-access-'+permissionId+' input[name="granted"]').prop('checked', checkedGranted);
                        ChangeSwitch(permissionId, permissionName, checked);
                    }
                });
            });
        </script>
        <?php return ob_get_clean();
    }
}
