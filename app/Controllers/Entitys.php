<?php

namespace App\Controllers;

class Entitys extends BaseController
{

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $title = 'Entitys';
        
    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $slug = 'dashboard/entity';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewView = 'entity/view';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewEdit = 'entity/edit';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewList = 'entity/list';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewFilter = 'entity/filter';

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
                'titlePage' => '',
                'description' => 'Detalles de la ',
                'title' => 'Taxonomía ',
                'content' => 'Datos generales de la '
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
            'key' => 'identity',
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
            'key' => 'idaccount',
            'label' => _('Autor')
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
        return new \App\Models\EntityModel();
    }

    protected function FILTER(){
        if(!$this->getModel()){
            return [];
        }

        $ListTerms = (($this->getModel())->where('idtaxonomy', $this->Taxonomy['idtaxonomy'])->findAll());
        
        foreach ($ListTerms as $key => $ListTerm) {
            foreach ($this->ColumnExtras as $extra) {
                $Meta = $this->getModel()->getMetas();
                $Value = $Meta->where('identity', $ListTerm['identity'])->where('idfield', $extra['idfield'])->first();
                $ListTerms[$key]['th'.$extra['idfield']] = $Value;
            }            
        }
        return $ListTerms;
    }

    protected function td($tr, $td, $column){


        if(isset($tr['th'.$column])){
            if(!IS_NULL($tr['th'.$column])){
                return $tr['th'.$column]['value'];
            }
        }

        if($column === 'enabled'){
            return intval($td) === 1 ? _('Si'):_('No');
        }

        if($column === 'title' && !IS_NULL($this->Taxonomy['viewname'])){
            ob_start(); ?>
                <a href="<?=site_url('dashboard/view/'.$this->Taxonomy['viewname'].'/'.$tr['identity']);?>"><?=$td;?></a>
            <?php 
            return ob_get_clean();
        }

        if($column === 'idaccount'){


            $Account = new \App\Models\AccountModel();
            $Account = $Account->Exists($td);
            
            return $Account['name'].' '.$Account['surname'];
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
                        <a class="dropdown-item" href="<?=site_url($this->slug.'/'.$this->Taxonomy['code'].'/view/'.$ModelID);?>"><i class="fa fa-eye mr-2"></i><?=_('Vér');?></a>
                        <a class="dropdown-item" href="<?=site_url($this->slug.'/'.$this->Taxonomy['code'].'/edit/'.$ModelID);?>"><i class="fa fa-pencil-square-o mr-2"></i><?=_('Editar');?></a>
                        
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?=site_url($this->slug.'/'.$this->Taxonomy['code'].'/trash/'.$ModelID);?>"><i class="fa fa-trash mr-2"></i><?=_('Eliminar');?></a>
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
        $this->Taxonomy = $Taxonomy->ExistsByCode($code, 'entity');
        $this->vars = $this->Taxonomy;
        if(!is_null($this->Taxonomy)){
            $this->ColumnExtras = $Taxonomy->getFieldsExtras($this->Taxonomy['idtaxonomy'], ['enabled'=>'1', 'tabled'=> '1']);
            $this->ColumnFields = $Taxonomy->getFieldsExtras($this->Taxonomy['idtaxonomy'], ['enabled'=>'1']);
        }
        return !is_null($this->Taxonomy);
    }

    public function index($code="")
    {
        if (!(session()->get('isLoggedIn'))) {
            return redirect()->to('/login');
        }

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

    public function new($code="", $Id="")
    {
        if (!(session()->get('isLoggedIn'))) {
            return redirect()->to('/login');
        }
        
        if($this->allowedTaxonomy($code)){
            $this->titlePage = $this->viewContent()->new->titlePage.$this->Taxonomy['title'];
            $this->description = $this->viewContent()->new->description.$this->Taxonomy['title'];
            $this->setContent($this->viewContent()->new->title.$this->Taxonomy['title'], $this->viewContent()->new->content);
            unset($this->breadcrumbs[1]);
            
            $this->addBreadcrumb($this->viewContent()->list->titlePage.$this->Taxonomy['title'], site_url($this->slug.'s/'.$this->Taxonomy['code']));
            $this->addBreadcrumb($this->titlePage);
            $this->withLayout = 'new';
            $this->values['idtaxonomy'] = $this->Taxonomy['idtaxonomy'];
            $this->values['_GET_ID'] = $Id;
            return $this->View($this->viewEdit, [
                'extras' => $this->ColumnFields
            ]);
        }

        return redirect()->to('dashboard/taxonomys')->with('warning', '¡La taxonomía no existe!');
    }

    public function edit($code, $id="")
    {
        if (!(session()->get('isLoggedIn'))) {
            return redirect()->to('/login');
        }

        if($this->allowedTaxonomy($code)){
            $this->titlePage = $this->viewContent()->edit->titlePage.$this->Taxonomy['title'];
            $this->description = $this->viewContent()->edit->description.$this->Taxonomy['title'];
            $this->setContent($this->viewContent()->edit->title.$this->Taxonomy['title'], $this->viewContent()->edit->content);
            unset($this->breadcrumbs[1]);
            
            $this->addBreadcrumb($this->viewContent()->list->titlePage.$this->Taxonomy['title'], site_url($this->slug.'s/'.$this->Taxonomy['code']));
            $this->addBreadcrumb($this->titlePage);

            $Model = $this->getModel()->Exists($id);
            
            if(strlen(trim($id))===0 || is_null($Model)){
                return redirect()->to($this->slug.'s/'.$this->Taxonomy['code'])->with('warning', '¡Este registro no existe!');
            }

            $this->setValues($Model);
            $this->addValues($this->getModel()->getMeta($Model['identity']));
            $this->withLayout = 'edit';
            return $this->View($this->viewEdit, [
                'extras' => $this->ColumnFields
            ]);
        }

        return redirect()->to('dashboard/taxonomys')->with('warning', '¡La taxonomía no existe!');
    }

    public function details($code, $id="")
    {
        if (!(session()->get('isLoggedIn'))) {
            return redirect()->to('/login');
        }

        if($this->allowedTaxonomy($code)){
            $Model = $this->getModel()->Exists($id);
            
            if(strlen(trim($id))===0 || is_null($Model)){
                return redirect()->to($this->slug.'s/'.$this->Taxonomy['code'])->with('warning', '¡Este registro no existe!');
            }

            $description = isset($Model[$this->getModel()->description()]) ? $Model[$this->getModel()->description()]: "";

            $this->titlePage = $this->viewContent()->view->titlePage.$this->Taxonomy['title'].' '.$description;
            $this->description = $this->viewContent()->view->description.$this->Taxonomy['title'].' '.$description;
            $this->setContent($this->viewContent()->view->title.$this->Taxonomy['title'].' '.$description, $this->viewContent()->view->content.$description);
            unset($this->breadcrumbs[1]);
            
            $this->addBreadcrumb($this->viewContent()->list->titlePage.$this->Taxonomy['title'], site_url($this->slug.'s/'.$this->Taxonomy['code']));
            $this->addBreadcrumb($this->titlePage);

            $this->setValues($Model);
            $this->addValues($this->getModel()->getMeta($Model['identity']));

            $this->withLayout = 'view';

            return $this->View($this->viewView, [
                'extras' => $this->ColumnFields
            ]);
        }

        return redirect()->to('dashboard/taxonomys')->with('warning', '¡La taxonomía no existe!');
    }

    public function trash($code, $id="")
    {
        if (!(session()->get('isLoggedIn'))) {
            return redirect()->to('/login');
        }

        if($this->allowedTaxonomy($code)){
            $Model = $this->getModel();
            $Data = $Model->Exists($id);
            if(strlen(trim($id))===0 || is_null($Data)){
                return redirect()->to($this->slug.'s/'.$this->Taxonomy['code'])->with('warning', '¡Este registro no existe!');
            }

            $this->setValues($Data);
            $Model->delete($this->getID());
            return redirect()->to($this->slug.'s/'.$this->Taxonomy['code'])->with('success', _('¡El registro ha sido eliminado exitosamente!'));
        }

        return redirect()->to('dashboard/taxonomys')->with('warning', '¡La taxonomía no existe!');
    }

    protected function head(): string
    {
        $Model = $this->getModel();
        ob_start(); ?>
        <!--script src="https://cdn.tiny.cloud/1/hr9an7nmm0jxbcon8v8nls1018pujtkdg81f1u033nqptupz/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script-->
        <script src="<?= base_url('public/vendors/ckeditor/ckeditor.js'); ?>"></script>
        <!--script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script-->
        <?php return ob_get_clean();
        //<script>tinymce.init({ selector:'textarea' });</script>
    }

    protected function script(): string
    {
        $Model = $this->getModel();
        ob_start(); ?>
        <script type="text/javascript">
            'use strict';
            $(document).ready(function () { 
                let editores = document.querySelectorAll('textarea#content');
                if (editores.length > 0) {
                    let handlerError = (err) => {
                        console.log(err);
                    };

                    editores.forEach(editorElement => {
                        ClassicEditor.create(editorElement, {})
                            .then(editor => {
                                // Opcionalmente, puedes almacenar una referencia al editor
                                editorElement._editorInstance = editor;
                            })
                            .catch(handlerError);
                    });
                }

            });
        </script>
        <?php return ob_get_clean();
    }

    public function saved(){
        if (!(session()->get('isLoggedIn'))) {
            return redirect()->to('/login');
        }

        $Taxonomy = (new \App\Models\TaxonomyModel())->Exists($this->request->getPost('idtaxonomy'));
        if(!IS_NULL($Taxonomy)){
            if($this->validate($this->getModel()->getValidation())){
                $data = ($this->getValues());

                $Model = $this->getModel();
                $Saved = ($this->isNew() && !$this->getID()) ? $Model->insert($data) : $Model->update($this->getID(), $data);
                if($Saved){
                    $ModelId = $this->getID();
                    if($this->isNew() && !$this->getID()){
                        $ModelId = $Model->getInsertID();
                    }
                    $Model->saved($this->request->getPost(), $ModelId);
                    return redirect()->to($this->slug.'/'.$Taxonomy['code'].'/edit/'.$ModelId)->with('success', _('¡Los datos se han guardado correctamente!'));
                }
            }
        
            return ($this->isNew() && !$this->getID()) ? $this->new($Taxonomy['code']): $this->edit($Taxonomy['code'], $this->getID());
        }
        return redirect()->to('dashboard/taxonomys')->with('warning', '¡La taxonomía no existe!');
    }
}
