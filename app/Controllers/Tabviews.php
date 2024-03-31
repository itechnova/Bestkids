<?php

namespace App\Controllers;

class Tabviews extends BaseController
{

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $title = 'Enlaces';
        
    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $slug = 'dashboard/tabview';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewView = 'tabview/view';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewEdit = 'tabview/edit';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewList = 'tabview/list';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewFilter = 'tabview/filter';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var object
     */
    protected $ViewData = null;

    /**
     * An object of helpers to be loaded automatically upon
     *
     * @var object
     */
    protected function viewContent(): Object
    {
        return (Object) array(
            'new' => ((Object) array(
                'titlePage' => 'Nuevo enlace',
                'description' => 'Crear nuevo enlace',
                'title' => 'Crear nuevo enlace',
                'content' => 'Rellena los datos del formulario.'
            )),
            'view' => ((Object) array(
                'titlePage' => 'Enlace ',
                'description' => 'Detalles del enlace ',
                'title' => 'Enlace ',
                'content' => 'Datos generales del enlace '
            )),
            'edit' => ((Object) array(
                'titlePage' => 'Editar enlace',
                'description' => 'Editar enlace',
                'title' => 'Editar enlace',
                'content' => 'Cambia los datos del formulario.'
            )),
            'list' => ((Object) array(
                'titlePage' => 'Enlaces de ',
                'description' => 'Lista de enlaces',
                'title' => 'Lista de enlaces para ',
                'content' => 'Enlaces disponibles'
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
                'key' => 'idtab',
                'label' => _('ID')
            )),
            ((Object) array(
                'key' => 'idtaxonomy',
                'label' => _('Taxonomía')
            )),
            ((Object) array(
                'key' => 'title',
                'label' => _('Enlace')
            )),
            ((Object) array(
                'key' => 'level',
                'label' => _('Nivel')
            )),
            ((Object) array(
                'key' => 'enabled',
                'label' => _('Habilitado')
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
        return new \App\Models\TabviewModel();
    }

    protected function FILTER(){
        if(!$this->getModel()){
            return [];
        }
        //->where('idbusiness', getIdBussiness())
        return (($this->getModel())->findAll());

    }

    protected function td($tr, $td, $column){
        if($column === 'enabled'){
            return intval($td) === 1 ? _('Si'):_('No');
        }

        if($column === 'idtaxonomy'){
            $Taxonomy = (new \App\Models\TaxonomyModel())->Exists($td);
            ob_start(); ?>
                <a href="<?=site_url('dashboard/taxonomy/view/'.$td);?>"><?=$Taxonomy['title'];?></a>
            <?php 
            return ob_get_clean();
        }

        if($column === 'title'){
            $ModelID = $this->getModel()->getID($tr);
            ob_start(); ?>
                <a href="<?=site_url($this->slug.'/view/'.$ModelID);?>"><?=$td;?></a>
            <?php 
            return ob_get_clean();
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

    protected function setViews($IdView){
        $ViewsModel = new \App\Models\ViewsModel();
        $this->ViewData = $ViewsModel->Exists($IdView);
        $this->vars = $this->ViewData;
        /*if(!is_null($this->ViewData)){
            $this->ColumnExtras = $Taxonomy->getFieldsExtras($this->Taxonomy['idtaxonomy'], ['enabled'=>'1', 'tabled'=> '1']);
            $this->ColumnFields = $Taxonomy->getFieldsExtras($this->Taxonomy['idtaxonomy'], ['enabled'=>'1']);
        }*/
        return !is_null($this->ViewData);
    }

    public function index($IdView = "")
    {
        if($this->setViews($IdView)){
            $this->titlePage = $this->viewContent()->list->titlePage.$this->ViewData['title'];
            $this->description = $this->viewContent()->list->description;
            $this->setContent($this->viewContent()->list->title.$this->ViewData['title'], $this->viewContent()->list->content);
            unset($this->breadcrumbs[1]);
            $this->addBreadcrumb($this->titlePage);
            $this->withLayout = 'index';

            // Verificar si el usuario ya está autenticado
            if (!(session()->get('isLoggedIn'))) {
                return redirect()->to('/login');
            }

            return $this->View($this->viewList);            
        }

        return redirect()->to('dashboard/views')->with('warning', '¡La vista no existe!');

    }

    public function new($IdView = "")
    {
        if($this->setViews($IdView)){
            $this->titlePage = $this->viewContent()->new->titlePage;
            $this->description = $this->viewContent()->new->description;
            $this->setContent($this->viewContent()->new->title, $this->viewContent()->new->content);
            $this->addBreadcrumb($this->titlePage);
            $this->withLayout = 'new';

            if (!(session()->get('isLoggedIn'))) {
                return redirect()->to('/login');
            }

            $this->values['idview'] = $this->ViewData['idview'];
            return $this->View($this->viewEdit);
        }

        return redirect()->to('dashboard/views')->with('warning', '¡La vista no existe!');
    }

    public function edit($id)
    {
        if (!(session()->get('isLoggedIn'))) {
            return redirect()->to('/login');
        }

        $this->titlePage = $this->viewContent()->edit->titlePage;
        $this->description = $this->viewContent()->edit->description;
        $this->setContent($this->viewContent()->edit->title, $this->viewContent()->edit->content);
        $this->addBreadcrumb($this->titlePage);

        $Model = $this->getModel()->Exists($id);
            
        if(strlen(trim($id))===0 || is_null($Model)){
            return redirect()->to('dashboard/views')->with('warning', '¡Este registro no existe!');
        }

        if(!$this->setViews($Model['idview'])){
            return redirect()->to('dashboard/views')->with('warning', '¡La vista no existe!');
        }

        $this->setValues($Model);

        $this->withLayout = 'edit';
        return $this->View($this->viewEdit);
    }

    public function details($id)
    {
        if (!(session()->get('isLoggedIn'))) {
            return redirect()->to('/login');
        }

        $Model = $this->getModel()->Exists($id);
        
        if(strlen(trim($id))===0 || is_null($Model)){
            return redirect()->to($this->slug.'s')->with('warning', '¡Este registro no existe!');
        }

        $description = isset($Model[$this->getModel()->description()]) ? $Model[$this->getModel()->description()]: "";

        $this->titlePage = $this->viewContent()->view->titlePage.$description;
        $this->description = $this->viewContent()->view->description.$description;
        $this->setContent($this->viewContent()->view->title.$description, $this->viewContent()->view->content.$description);
        $this->addBreadcrumb($this->titlePage);

        if(!$this->setViews($Model['idview'])){
            return redirect()->to('dashboard/views')->with('warning', '¡La vista no existe!');
        }

        $this->setValues($Model);

        $this->withLayout = 'view';

        return $this->View($this->viewView);
    }

    public function trash($id)
    {
        if (!(session()->get('isLoggedIn'))) {
            return redirect()->to('/login');
        }

        $Model = $this->getModel();
        $Data = $Model->Exists($id);
        if(strlen(trim($id))===0 || is_null($Data)){
            return redirect()->to('dashboard/views')->with('warning', '¡Este registro no existe!');
        }

        $this->setValues($Data);
        $Model->delete($this->getID());

        return redirect()->to($this->slug.'s/'.$Data['idview'])->with('success', '¡El registro ha sido eliminado exitosamente!');
    }
}
