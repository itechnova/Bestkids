<?php

namespace App\Controllers;

class Accounts extends BaseController
{

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $title = 'Cuentas de usuarios';
        
    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $slug = 'dashboard/account';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewView = 'account/view';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewEdit = 'account/edit';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewList = 'account/list';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewFilter = 'account/filter';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $roleId = "";

    /*public function getName(): string
    {
        return 'roles';
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
                'titlePage' => 'Nuevo ',
                'description' => 'Crear ',
                'title' => 'Crear nuevo ',
                'content' => 'Rellena los datos del formulario.'
            )),
            'view' => ((Object) array(
                'titlePage' => '',
                'description' => 'Detalles de ',
                'title' => 'Rol ',
                'content' => 'Datos generales de '
            )),
            'edit' => ((Object) array(
                'titlePage' => 'Editar ',
                'description' => 'Editar ',
                'title' => 'Editar ',
                'content' => 'Cambia los datos del formulario.'
            )),
            'list' => ((Object) array(
                'titlePage' => '',
                'description' => 'Lista de ',
                'title' => 'Lista de ',
                'content' => ''
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
                'key' => 'idaccount',
                'label' => _('ID')
            )),
            ((Object) array(
                'key' => 'name',
                'label' => _('Nombre')
            )),
            ((Object) array(
                'key' => 'surname',
                'label' => _('Apellido')
            )),
            ((Object) array(
                'key' => 'username',
                'label' => _('Correo')
            )),
            ((Object) array(
                'key' => 'lastlogin',
                'label' => _('Último acceso')
            )),
            ((Object) array(
                'key' => 'lastip',
                'label' => _('Último ip')
            )),
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
            )),
        );
    }

    protected function getModel(){
        return new \App\Models\AccountModel();
    }

    protected function FILTER(){
        if(!$this->getModel()){
            return [];
        }
        //
        return (($this->getModel())->where('idrole', $this->roleId)->findAll());

    }

    protected function td($tr, $td, $column){
        if($column === 'enabled'){
            return intval($td) === 1 ? _('Si'):_('No');
        }

        /*if($column === 'title'){
            $ModelID = $this->getModel()->getID($tr);
            ob_start(); ?>
                <a href="<?=site_url($this->slug.'/view/'.$ModelID);?>"><?=$td;?></a>
            <?php 
            return ob_get_clean();
        }*/

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

    protected function withRole($id) {
        $this->roleId = $id;

        if($this->roleId !== ""){
            $Role = new \App\Models\RoleModel();
            $RoleData = $Role->Exists($this->roleId);

            if(!IS_NULL($RoleData)){
                $this->values['idrole'] = $this->roleId;
                $this->vars = (Object) $RoleData;
                $this->breadcrumbs[1] = (Object) array('title'=>$this->title, 'slug'=>site_url($this->slug.'s').'/'.$this->roleId);
                return true;
            }
        }

        return false;
    }

    public function saved(){
        $this->withRole($this->request->getPost('idrole'));
        return parent::saved();
    }

    public function index($id="")
    {

        if($this->withRole($id)){
            $Label = 'Usuarios <b>'.strtolower($this->vars->title).'</b>';

            $this->titlePage = $this->viewContent()->list->titlePage.$Label;
            $this->description = $this->viewContent()->list->description.$Label;
            $this->setContent($this->viewContent()->list->title.$Label, $this->viewContent()->list->content.$Label);
            $this->addBreadcrumb($this->titlePage);
            $this->withLayout = 'index';
            return $this->View($this->viewList);
        }

        return redirect()->to('dashboard/roles/')->with('warning', '¡Este rol no existe!');
        //return parent::index();
    }

    public function new($id="")
    {
        if($this->withRole($id)){
            $Label = 'usuario <b>'.strtolower($this->vars->title).'</b>';
            $this->titlePage = $this->viewContent()->new->titlePage.$Label;
            $this->description = $this->viewContent()->new->description.$Label;
            $this->setContent($this->viewContent()->new->title.$Label, $this->viewContent()->new->content);
            $this->addBreadcrumb($this->titlePage);
            $this->withLayout = 'new';
            return $this->View($this->viewEdit);
        }
        return redirect()->to('dashboard/roles/')->with('warning', '¡Este rol no existe!');
    }

    public function edit($id)
    {
        $Model = $this->getModel()->Exists($id);
        
        if(strlen(trim($id))===0 || is_null($Model)){
            return redirect()->to('dashboard/roles/')->with('warning', '¡Este registro no existe!');
        }

        $this->setValues($Model);
        if($this->withRole($Model['idrole'])){
            $Label = 'usuario <b>'.strtolower($this->vars->title).'</b>';

            $this->titlePage = $this->viewContent()->edit->titlePage.$Label;
            $this->description = $this->viewContent()->edit->description.$Label;
            $this->setContent($this->viewContent()->edit->title.$Label, $this->viewContent()->edit->content);
            $this->addBreadcrumb($this->titlePage);


            $this->withLayout = 'edit';
            return $this->View($this->viewEdit);            
        }

        return redirect()->to('dashboard/roles/')->with('warning', '¡Este rol no existe!');
    }

    public function details($id)
    {

        $Model = $this->getModel()->Exists($id);
        
        if(strlen(trim($id))===0 || is_null($Model)){
            return redirect()->to($this->slug.'s')->with('warning', '¡Este registro no existe!');
        }

        if($this->withRole($Model['idrole'])){

            $description = isset($Model[$this->getModel()->description()]) ? $Model[$this->getModel()->description()]: "";

            $this->titlePage = $this->viewContent()->view->titlePage.$description;
            $this->description = $this->viewContent()->view->description.$description;
            $this->setContent($this->viewContent()->view->title.$description, $this->viewContent()->view->content.$description);
            $this->addBreadcrumb($this->titlePage);

            $this->setValues($Model);

            $this->withLayout = 'view';
            return $this->View($this->viewView);
        }

        return redirect()->to('dashboard/roles/')->with('warning', '¡Este rol no existe!');
    }
}
