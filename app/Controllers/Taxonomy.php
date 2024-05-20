<?php

namespace App\Controllers;

class Taxonomy extends BaseController
{

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $title = 'Taxonomías';
        
    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $slug = 'dashboard/taxonomy';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewView = 'taxonomy/view';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewEdit = 'taxonomy/edit';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewList = 'taxonomy/list';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewFilter = 'taxonomy/filter';

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
                'titlePage' => 'Nueva taxonomía',
                'description' => 'Crear nueva taxonomía',
                'title' => 'Crear nueva taxonomía',
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
                'titlePage' => 'Taxonomías',
                'description' => 'Lista de taxonomías',
                'title' => 'Lista de taxonomías',
                'content' => 'Taxonomía disponibles'
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
                'key' => 'idtaxonomy',
                'label' => _('ID')
            )),
            ((Object) array(
                'key' => 'code',
                'label' => _('Código')
            )),
            ((Object) array(
                'key' => 'type',
                'label' => _('Tipo')
            )),
            ((Object) array(
                'key' => 'title',
                'label' => _('Taxonomía')
            )),
            ((Object) array(
                'key' => 'view',
                'label' => _('Vista')
            )),
            ((Object) array(
                'key' => 'level',
                'label' => _('Nivel')
            )),
            ((Object) array(
                'key' => 'status',
                'label' => _('Estatus')
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
        return new \App\Models\TaxonomyModel();
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

        if($column === 'status'){
            $OPTION = [
                'publish'=>_('Público'),
                'private'=>_('Privado'),
            ];
            return $OPTION[$td];
        }

        if($column === 'type'){
            $OPTION = [
                'terms'=>_('Categoría'),
                'entity'=>_('Entidad'),
            ];
            return $OPTION[$td];
        }

        if($column === 'view'){
            $OPTION = [
                'list'=>_('Lista'),
                'grid'=>_('Cuadriculas'),
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

    public function move(){
        if (!(session()->get('isLoggedIn'))) {
            return redirect()->to('/login');
        }

        try {
            //code...
            $Typed = $this->request->getPost('typed');
            $Content = json_decode($this->request->getPost('content'));

            $Model = ($Typed === 'entity' ?  new \App\Models\EntityModel() : new \App\Models\TermModel());
            $ID = isset($Content->order[0])?$Content->order[0]:null;
            if(!IS_NULL($ID)){
                $Object = $Model->Exists($ID);
                if(!IS_NULL($Object)){
                    //['idtaxonomy']
                    $ModelOrder = new \App\Models\OrderTaxonomyModel();

                    $OrderGrouped = $ModelOrder->ExistsBy($Typed, $Object['idtaxonomy']);

                    if(IS_NULL($OrderGrouped)){
                        $ModelOrder->insert([
                            'idtaxonomy'=>$Object['idtaxonomy'],
                            'typed'=>$Typed,
                            'content'=>$this->request->getPost('content')
                        ]);
                    }else{
                        $ModelOrder->update($OrderGrouped['idorder'], ['content'=>$this->request->getPost('content')]);
                    }
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        
        return json_encode([]);
    }
}
