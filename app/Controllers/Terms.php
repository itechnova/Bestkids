<?php

namespace App\Controllers;

class Terms extends BaseController
{

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $title = 'Terms';
        
    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $slug = 'dashboard/term';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewView = 'term/view';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewEdit = 'term/edit';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewList = 'term/list';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewFilter = 'term/filter';

    protected $Taxonomy = null;

    protected $ColumnExtras = [];
    protected $ColumnFields = [];
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
                'titlePage' => 'Nueva ',
                'description' => 'Crear nueva ',
                'title' => 'Crear nueva ',
                'content' => 'Rellena los datos del formulario.'
            )),
            'view' => ((Object) array(
                'titlePage' => 'Taxonomía ',
                'description' => 'Detalles de la taxonomía ',
                'title' => 'Taxonomía ',
                'content' => 'Datos generales de la taxonomía '
            )),
            'edit' => ((Object) array(
                'titlePage' => 'Editar taxonomía',
                'description' => 'Editar taxonomía',
                'title' => 'Editar taxonomía',
                'content' => 'Cambia los datos del formulario.'
            )),
            'list' => ((Object) array(
                'titlePage' => '',
                'description' => 'Lista de ',
                'title' => 'Lista de ',
                'content' => ' disponibles'
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
        $Columns = [];
        
        $Columns[] = ((Object) array(
            'key' => 'idterm',
            'label' => _('ID')
        ));
        $Columns[] = ((Object) array(
            'key' => 'title', 
            'label' => _($this->Taxonomy['title'])
        ));

        foreach ($this->ColumnExtras as $extra) {
            # code...
            $Columns[] = ((Object) array(
                'key' => $extra['idfield'],
                'label' => _($extra['label'])
            ));
        }

        $Columns[] = ((Object) array(
            'key' => 'status',
            'label' => _('Estatus')
        ));
        $Columns[] = ((Object) array(
            'key' => 'enabled',
            'label' => _('Habilitado')
        ));
        $Columns[] = ((Object) array(
            'key' => 'created_at',
            'label' => _('Creado')
        ));
        $Columns[] = ((Object) array(
            'key' => 'updated_at',
            'label' => _('Actualizado')
        ));
        $Columns[] = ((Object) array(
            'key' => 'ACTION',
            'label' => '<div class="d-flex justify-content-end"><i class="fa fa-ellipsis-v"></i></div>'
        ));

        return $Columns;
    }

    protected function getModel(){
        return new \App\Models\TermModel();
    }

    protected function FILTER(){
        if(!$this->getModel()){
            return [];
        }

        $ListTerms = (($this->getModel())->where('idtaxonomy', $this->Taxonomy['idtaxonomy'])->findAll());
        
        foreach ($ListTerms as $key => $ListTerm) {
            foreach ($this->ColumnExtras as $extra) {
                $Meta = $this->getModel()->getMetas();
                $Value = $Meta->where('idterm', $ListTerm['idterm'])->where('idfield', $ListTerm['idterm'])->first();
                $ListTerms[$key][$extra['idfield']] = $Value;
            }            
        }
        return $ListTerms;
    }

    protected function td($tr, $td, $column){
        if($column === 'enabled'){
            return intval($td) === 1 ? _('Si'):_('No');
        }

        if($column === 'title'){
            ob_start(); ?>
                <a href="<?=site_url($this->slug.'/'.$this->Taxonomy['code'].'/view/'.$tr['slug']);?>"><?=$td;?></a>
            <?php 
            return ob_get_clean();
        }

        if($column === 'status'){
            $OPTION = [
                'publish'=>_('Público'),
                'private'=>_('Privado'),
            ];
            return $OPTION[$td];
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
                        <a class="dropdown-item" href="<?=site_url('dashboard/fields/'.$ModelID);?>"><i class="fa fa-cubes mr-2"></i><?=_('Campos');?></a>
                        
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

    protected function allowedTaxonomy($code){
        $Taxonomy = new \App\Models\TaxonomyModel();
        $this->Taxonomy = $Taxonomy->ExistsByCode($code, 'terms');
        $this->vars = $this->Taxonomy;
        if(!is_null($this->Taxonomy)){
            $this->ColumnExtras = $Taxonomy->getFieldsExtras($this->Taxonomy['idtaxonomy'], ['enabled'=>'1', 'tabled'=> '1']);
            $this->ColumnFields = $Taxonomy->getFieldsExtras($this->Taxonomy['idtaxonomy'], ['enabled'=>'1']);
        }
        return !is_null($this->Taxonomy);
    }


    public function index($code="")
    {
        if($this->allowedTaxonomy($code)){
            $this->titlePage = $this->viewContent()->list->titlePage.$this->Taxonomy['title'];
            $this->description = $this->viewContent()->list->description.$this->Taxonomy['title'];
            $this->setContent($this->viewContent()->list->title.$this->Taxonomy['title'], $this->Taxonomy['title'].$this->viewContent()->list->content);
            unset($this->breadcrumbs[1]);
            $this->addBreadcrumb($this->titlePage);
            $this->withLayout = 'index';
            
            return $this->View($this->viewList);            
        }

        return redirect()->to('dashboard/taxonomys')->with('warning', '¡La taxonomía no existe!');
    }

    public function new($code="")
    {
        if($this->allowedTaxonomy($code)){
            $this->titlePage = $this->viewContent()->new->titlePage.$this->Taxonomy['title'];
            $this->description = $this->viewContent()->new->description.$this->Taxonomy['title'];
            $this->setContent($this->viewContent()->new->title.$this->Taxonomy['title'], $this->viewContent()->new->content);
            unset($this->breadcrumbs[1]);
            
            $this->addBreadcrumb($this->viewContent()->list->titlePage.$this->Taxonomy['title'], site_url($this->slug.'s/'.$this->Taxonomy['code']));
            $this->addBreadcrumb($this->titlePage);
            $this->withLayout = 'new';
            return $this->View($this->viewEdit, [
                'extras' => $this->ColumnFields
            ]);
        }

        return redirect()->to('dashboard/taxonomys')->with('warning', '¡La taxonomía no existe!');
    }

    protected function head(): string
    {
        $Model = $this->getModel();
        ob_start(); ?>
        <script src="https://cdn.tiny.cloud/1/hr9an7nmm0jxbcon8v8nls1018pujtkdg81f1u033nqptupz/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
        <!--script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script-->
        <?php return ob_get_clean();
        //<script>tinymce.init({ selector:'textarea' });</script>
    }

    protected function script(): string
    {
        $Model = $this->getModel();
        ob_start(); ?>
        <script>tinymce.init({ selector:'textarea', language: 'es' });</script>
        <?php return ob_get_clean();
    }

}
