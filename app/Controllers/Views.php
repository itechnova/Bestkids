<?php

namespace App\Controllers;

class Views extends BaseController
{

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $title = 'Vistas';
        
    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $slug = 'dashboard/view';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewView = 'view/view';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewEdit = 'view/edit';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewList = 'view/list';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewFilter = 'view/filter';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewDetails = 'view/details';

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
                'titlePage' => 'Nueva vista',
                'description' => 'Crear nueva vista',
                'title' => 'Crear nueva vista',
                'content' => 'Rellena los datos del formulario.'
            )),
            'view' => ((Object) array(
                'titlePage' => 'Vista ',
                'description' => 'Detalles de la vista ',
                'title' => 'Vista ',
                'content' => 'Datos generales de la vista '
            )),
            'edit' => ((Object) array(
                'titlePage' => 'Editar vista',
                'description' => 'Editar vista',
                'title' => 'Editar vista',
                'content' => 'Cambia los datos del formulario.'
            )),
            'list' => ((Object) array(
                'titlePage' => 'Vistas',
                'description' => 'Lista de vistas',
                'title' => 'Lista de vistas',
                'content' => 'Vistas disponibles'
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
                'key' => 'idview',
                'label' => _('ID')
            )),
            ((Object) array(
                'key' => 'title',
                'label' => _('Vista')
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
        return new \App\Models\ViewsModel();
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
                        <a class="dropdown-item" href="<?=site_url('dashboard/tabviews/'.$ModelID);?>"><i class="fa fa fa-link mr-2"></i><?=_('Enlance');?></a>
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

    public function detail($slug, $Id)
    {
        if (!(session()->get('isLoggedIn'))) {
            return redirect()->to('/login');
        }

        $Model = $this->getModel()->ExistsBySlug($slug);
        
        //var_dump($Model); exit();
        if(strlen(trim($slug))===0 || is_null($Model)){
            return redirect()->to($this->slug.'s')->with('warning', '¡Este registro no existe!');
        }

        $description = isset($Model[$this->getModel()->description()]) ? $Model[$this->getModel()->description()]: "";

        $this->titlePage = $this->viewContent()->view->titlePage.$description;
        $this->description = $this->viewContent()->view->description.$description;
        $this->setContent($this->viewContent()->view->title.$description, $this->viewContent()->view->content.$description);
        $this->addBreadcrumb($this->titlePage);

        $this->setValues($Model);

        $this->withLayout = 'details';

        $Tabpanels = [];

        foreach ($this->getModel()->getViewTabs($Model['idview']) as $Panel) {
            # code...
            $Tabpanels[] = (new \App\Models\TabviewModel())->getTabView($Panel['idtab'], $Id);
        }

        return $this->View($this->viewDetails,[
            'tabPanels' => $Tabpanels
        ]);
    }
}
