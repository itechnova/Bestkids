<?php

namespace App\Controllers;

class Societys extends BaseController
{

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $title = 'Sociedades';
        
    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $slug = 'dashboard/society';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewView = 'society/view';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewEdit = 'society/edit';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewList = 'society/list';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewFilter = 'society/filter';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewDetails = 'society/details';

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
                'titlePage' => 'Nueva sociedad',
                'description' => 'Crear nueva sociedad',
                'title' => 'Crear nueva sociedad',
                'content' => 'Rellena los datos del formulario.'
            )),
            'view' => ((Object) array(
                'titlePage' => 'Vista ',
                'description' => 'Detalles de la sociedad ',
                'title' => 'Vista ',
                'content' => 'Datos generales de la sociedad '
            )),
            'edit' => ((Object) array(
                'titlePage' => 'Editar sociedad',
                'description' => 'Editar sociedad',
                'title' => 'Editar sociedad',
                'content' => 'Cambia los datos del formulario.'
            )),
            'list' => ((Object) array(
                'titlePage' => 'Sociedades',
                'description' => 'Lista de sociedades',
                'title' => 'Lista de sociedades',
                'content' => 'Sociedades disponibles'
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
                'key' => 'idsocietys',
                'label' => _('ID')
            )),
            ((Object) array(
                'key' => 'code',
                'label' => _('Código')
            )),
            ((Object) array(
                'key' => 'modelmain',
                'label' => _('Main')
            )),
            ((Object) array(
                'key' => 'modelend',
                'label' => _('End')
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
        return new \App\Models\SocietyModel();
    }

    protected function FILTER(){
        if(!$this->getModel()){
            return [];
        }
        //->where('idbusiness', getIdBussiness())
        return (($this->getModel())->findAll());

    }

    protected function td($tr, $td, $column){


        if($column === 'code'){
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

    public function detail($slug, $Id)
    {
        if (!(session()->get('isLoggedIn'))) {
            return redirect()->to('/login');
        }

        $Model = $this->getModel()->ExistsByCode($slug);
        
        //var_dump($Model); 
        if(strlen(trim($slug))===0 || is_null($Model)){
            return redirect()->to($this->slug.'s')->with('warning', '¡Este registro no existe!');
        }

        $description = isset($Model[$this->getModel()->description()]) ? $Model[$this->getModel()->description()]: "";

        $Main = proccess_model_filter($Model["modelmain"],$Model["filtermain"], $Id);
        $ModelMain = isset($Main->Lists[0]) ? $Main->Lists[0]: null;


        $End = proccess_model_filter($Model["modelend"],$Model["filterend"], $Id);

        $subtitle = (prepare_filter_text($Model['subtitle'], $ModelMain));
        $content = (prepare_filter_text($Model['content'], $ModelMain));

        $this->titlePage = $subtitle;
        $this->description = $content;
        $this->setContent($subtitle, $content);
        unset($this->breadcrumbs[1]); 

        $this->addBreadcrumb($this->titlePage);

        $this->setValues($Model);

        $this->withLayout = 'details';

        $Object = list_society($Model["idsocietys"], $Id, $Model["search"], $Model["options"]);

        $out = $Object->out;

        if(count($Object->lists)>0){
            $out = '<div class="row">'.$out.'</div>';
        }else{
            $out = '<p>'._('Sin resultados disponibles.').'</p>';
        }

        $lists = $this->getModel()->getMeta($Model["idsocietys"], $Id);

        $rows = [];
        $render = "";

        foreach ($lists as $list) {
            foreach ($End->Lists as $j => $row) {
                if(isset($row[$End->Model->primaryKey()]) && isset($row[$Model['selectedend']])){
                    if($row[$Model['selectedend']]."" === $list['idcolumnend'].""){
                        $ID = $row[$End->Model->primaryKey()];
                        $row["metas"] = (property_exists($End->Model, "getMeta") ? $End->Model->getMeta($ID): []);//  $End->Model->getMeta($ID);
                        $rows[] = (Object) $row;

                        $render .=society_card_view((Object) $row);
                    }
                }
                
            }            
        }

        return $this->View($this->viewDetails,[
            'modelo' => $Model,
            'out' => $out,
            'lists'  => $lists,
            'Main' => $Main,
            'ModelMain' => $ModelMain,
            'End'=> $End,
            'societyMetas'=> $rows,
            'render' => $render,
            '_GET_ID'=> $Id
        ]);
    }

    public function data(){
        if (!(session()->get('isLoggedIn'))) {
            return redirect()->to('/login');
        }

        $data = ($this->request->getPOST());

        $Object = list_society($data["idsocietys"], $data["idcolumnmain"], $data["datasearch"], $data["dataoption"], $data["search"], $data["option"]);

        //var_dump($Object);

        $Lists = $Object->lists;
        $OutHTML = $Object->out;

        if(count($Lists)>0){
            $OutHTML = '<div class="row">'.$OutHTML.'</div>';
        }else{
            $OutHTML = '';
        }
        return json_encode(['data' => $Lists, 'out'=> $OutHTML]);
    }

    public function checked(){
        if (!(session()->get('isLoggedIn'))) {
            return redirect()->to('/login');
        }

        $ModelMeta = new \App\Models\SocietyMetaModel();
        $data = $this->request->getPOST();
        foreach ($data as $key => $value) {
            $ModelMeta->where($key, $value);
        }
        $MetaData = $ModelMeta->first();

        if(IS_NULL($MetaData)){
            $ModelMeta->insert($data);
            $data["idmeta"]= $ModelMeta->getInsertID();
        }

        return json_encode(['data' => $data]);
    }

    public function lists(){
        if (!(session()->get('isLoggedIn'))) {
            return redirect()->to('/login');
        }

        //$ModelMeta = new \App\Models\SocietyMetaModel();
        $data = $this->request->getPOST();

        $End = proccess_model_filter($data["modelend"],$data["filterend"], $data["getid"]);
        
        $lists = $this->getModel()->getMeta($data["idsocietys"], $data["getid"]);

        $rows = [];
        $render = '';

        foreach ($lists as $list) {
            foreach ($End->Lists as $j => $row) {
                if(isset($row[$End->Model->primaryKey()]) && isset($row[$data['selectedend']])){
                    if($row[$data['selectedend']]."" === $list['idcolumnend'].""){
                        $ID = $row[$End->Model->primaryKey()];
                        $row["metas"] = (property_exists($End->Model, "getMeta") ? $End->Model->getMeta($ID): []);
                        $rows[] = (Object) $row;

                        $render .=society_card_view((Object) $row);
                    }
                }
                
            }            
        }
        return json_encode(['data' => array('rows'=>$rows, 'render'=>$render)]);
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
            $(document).ready(()=>{

                const render_lists = () => {
                    const society_lists_render = $('#society_lists_render');

                    const idsocietys = society_lists_render.attr('data-idsocietys');
                    const getid = society_lists_render.attr('data-getid');
                    const modelend = society_lists_render.attr('data-modelend');
                    const filterend = society_lists_render.attr('data-filterend');
                    const selectedend = society_lists_render.attr('data-selectedend');
                    
                    $(society_lists_render.find('.col-cleans')).remove();
                    $.ajax({
                        url: '<?=site_url($this->slug."/lists");?>',
                        method: 'POST',
                        data: {
                            idsocietys: idsocietys,
                            getid: getid,
                            modelend: modelend,
                            filterend: filterend,
                            selectedend: selectedend
                        },          
                        dataType: 'json'
                    }).done((response)=>{
                        if(response?.data?.render){
                            society_lists_render.append(response?.data?.render);
                        }
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
                    });

                }

                const seleted_clicked = (evt) => {
                    const idcolumnend = $(evt.target).attr("data-id");
                    const idsocietys = $('#societyout').attr('data-idsociety');
                    const idcolumnmain = $('#societyout').attr('data-idcolumnmain');

                    $.ajax({
                        url: '<?=site_url($this->slug."/checked");?>',   // URL a la que enviar la solicitud
                        method: 'POST',      // Método HTTP (POST, GET, etc.)
                        data: {
                            idsocietys: idsocietys,
                            idcolumnmain: idcolumnmain,
                            idcolumnend: idcolumnend
                        },          
                        dataType: 'json'     // Tipo de datos esperados en la respuesta (opcional)
                    }).done((response)=>{
                        const sdata = response?.data;
                        if (sdata?.idmeta){
                            $('#society-'+sdata?.idcolumnend).remove();
                            render_lists();
                            $('.alert_message').show();
                            setTimeout(() => {
                                $('.alert_message').hide();
                            }, 5000);
                        }
                        //$('#societyout .society_btn a').on('click', seleted_clicked);
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
                    }); 
                }

                $('#societyout .society_btn a').on('click', seleted_clicked);

                $('#button-society-search').click(()=>{
                    let search_value = $('#input-society-search').val();
                    let option_value = $('#select-society-search').val();

                    if(search_value !== "" && option_value !== ""){
                        $('#societyout').html("<p><?=_('Por favor espere...')?></p>");
                        $.ajax({
                            url: '<?=site_url($this->slug."/data");?>',   // URL a la que enviar la solicitud
                            method: 'POST',      // Método HTTP (POST, GET, etc.)
                            data: {
                                idsocietys: $('#societyout').attr('data-idsociety'),
                                idcolumnmain: $('#societyout').attr('data-idcolumnmain'),
                                search: search_value,
                                option: option_value,
                                datasearch: $('#input-society-search').attr('data-info'),
                                dataoption: $('#select-society-search').attr('data-info')
                            },          
                            dataType: 'json'     // Tipo de datos esperados en la respuesta (opcional)
                        }).done((response)=>{
                            //console.log(response);
                            $('#societyout').html("<p><b>"+response?.data?.length+"</b> <?=_('resultados de la búsqueda.')?></p>");
                            $('#societyout').append(response?.out??"");

                            $('#societyout .society_btn a').on('click', seleted_clicked);
                        }).fail(function(jqXHR, textStatus, errorThrown) {
                            //Callback para manejar errores
                            console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
                        });                        
                    }

                });
            });
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
