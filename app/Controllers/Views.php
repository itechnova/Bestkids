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

    protected function head(): string
    {
        ob_start(); ?>
        <style>
            .row.dropzones {
                border: none;
                min-height: 60vh;
            }

            .row.dropzones > div {
                height: 400px;
            }

            .row.dropzones {
                border-width: 2px;
                border-radius: 20px;
                padding: 15px;
            }
            
            .row.dropzones.dragover {
                border: 2px dashed #d1d1d1;
                background: #f1f1f1;
                box-shadow: 1px 1px 14px rgb(26 26 26 / 14%);
            }

            .row.dropzones.dragover .hidden {
                display: 'none';
            }
        </style>
        <?php return ob_get_clean();
    }
    
    protected function script(): string
    {
        ob_start(); ?>
        <script type="text/javascript">
            'use strict';
            document.addEventListener('DOMContentLoaded', (event) => {
                const draggables = document.querySelectorAll('.draggable');
                const dropzones = document.querySelectorAll('.dropzones');

                draggables.forEach(draggable => {
                    draggable.addEventListener('dragstart', dragStart);
                    //draggable.addEventListener('dragend', dragEnd);
                });

                dropzones.forEach(dropzone => {
                    dropzone.addEventListener('dragover', dragOver);
                    dropzone.addEventListener('drop', drop);
                    //dropzone.addEventListener('dragleave', dragLeave);
                });

                function dragStart(event) {
                    event.dataTransfer.setData('text', event.target.id);
                    setTimeout(() => {
                        event.target.classList.add('hidden');
                    }, 0);
                }

                function dragEnd(event) {
                    event.target.classList.remove('hidden');
                    const dropzones = document.querySelectorAll('.dropzones');
                    dropzones.forEach(dropzone => {
                        dropzone.classList.remove('dragover');
                    });
                }


                function dragOver(event) {
                    event.preventDefault();
                    if(event.target.classList.contains('draggable')){
                        const parent = event.target.parentElement;
                        if (parent.classList.contains('dropzones') && parent.dataset.allowDrop === 'true') {
                            
                            parent.classList.add('dragover');
                        }
                    }
                }

                function drop(event) {
                    event.preventDefault();
                    const id = event.dataTransfer.getData('text');
                    const draggable = document.getElementById(id);

                    if(event.target.dataset.allowDrop === 'true'){
                        event.target.appendChild(draggable);
                        event.target.classList.remove('dragover');

                        
                        const children = event.target.children;

                        let typed = '';
                        let content = {order: []};

                        for (let i = 0; i < children.length; i++) {
                            if(children[i]?.id){
                                const childrenId = (children[i].id).split("-");
                                typed = childrenId[0];
                                content.order.push(childrenId[1]);
                            }
                        }

                        $.ajax({
                            url: '<?=site_url("dashboard/taxonomy/move");?>',   // URL a la que enviar la solicitud
                            method: 'POST',      // Método HTTP (POST, GET, etc.)
                            data: {
                                typed: typed,
                                content: JSON.stringify(content)
                            },          // Datos a enviar en la solicitud (opcional)
                            dataType: 'json'     // Tipo de datos esperados en la respuesta (opcional)
                        }).done((response)=>{
                            console.log(response)
                        }).fail(function(jqXHR, textStatus, errorThrown) {
                            //Callback para manejar errores
                            console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
                        });
                    }        console.log(event.target);
                    // sendToServer(id, event.target.id);
                }

                function dragLeave(event) {
                    if(event.target.classList.contains('draggable')){
                        const parent = event.target.parentElement;
                        if (parent.classList.contains('dropzones') && parent.dataset.allowDrop === 'true') {
                            //parent.classList.remove('dragover');
                        }
                    }
                }
            });

        </script>
        <?php return ob_get_clean();
    }
}
