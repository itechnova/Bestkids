<?php

namespace App\Controllers;

class Fields extends BaseController
{

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $title = 'Campos';
        
    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $slug = 'dashboard/field';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewView = 'field/view';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewEdit = 'field/edit';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewList = 'field/list';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewFilter = 'field/filter';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $taxonomyId = "";
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
            'list' => ((Object) array(
                'titlePage' => 'Campos de taxonomía',
                'description' => 'Lista de campos',
                'title' => 'Lista de campos',
                'content' => 'Lista de campos disponibles'
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
                'key' => 'idfield',
                'label' => _('ID')
            )),
            ((Object) array(
                'key' => 'typefield',
                'label' => _('Tipo')
            )),
            ((Object) array(
                'key' => 'name',
                'label' => _('Nombre')
            )),
            ((Object) array(
                'key' => 'label',
                'label' => _('Etiqueta')
            )),
            ((Object) array(
                'key' => 'placeholder',
                'label' => _('Placeholder')
            )),
            ((Object) array(
                'key' => 'default_value',
                'label' => _('Valor')
            )),
            ((Object) array(
                'key' => 'options',
                'label' => _('Opciones')
            )),
            ((Object) array(
                'key' => 'orderby',
                'label' => _('Orden')
            )),
            ((Object) array(
                'key' => 'class',
                'label' => _('Clase')
            )),
            ((Object) array(
                'key' => 'cols',
                'label' => _('Columnas')
            )),
            ((Object) array(
                'key' => 'required',
                'label' => _('Obligatorio')
            )),
            ((Object) array(
                'key' => 'enabled',
                'label' => _('Habilitado')
            )),
            ((Object) array(
                'key' => 'tabled',
                'label' => _('Columna')
            )),/*,
            ((Object) array(
                'key' => 'created_at',
                'label' => _('Creado')
            )),
            ((Object) array(
                'key' => 'updated_at',
                'label' => _('Actualizado')
            )),*/
            ((Object) array(
                'key' => 'ACTION',
                'label' => '<div class="d-flex justify-content-end"><i class="fa fa-ellipsis-v"></i></div>'
            )),
        );
    }

    protected function getModel(){
        return new \App\Models\FieldModel();
    }

    protected function FILTER(){
        if(!$this->getModel()){
            return [];
        }
        //
        return (($this->getModel())->where('idtaxonomy', $this->taxonomyId)->findAll());

    }

    protected function td($tr, $td, $column){
        $ModelID = $this->getModel()->getID($tr);

        if($column === 'enabled' || $column === 'tabled' || $column === 'required'){
            return '<div class="model_'.$ModelID.' '.$column.'" data-value="'.$td.'">'.(intval($td) === 1 ? _('Si'):_('No')).'</div>';
        }

        if($column === 'typefield'){
            $OPTION = [
                'text'=>_('Texto'),
                'number'=>_('Número'),
                'email'=>_('Correo'),
                'tel'=>_('Teléfono'),
                'password'=>_('Contraseña'),
                'select'=>_('Seleccionable'),
                'textarea'=>_('Área de texto'),
                'file'=>_('Archivos'),
                'date'=>_('Fecha'),
            ];
            return '<div class="model_'.$ModelID.' '.$column.'" data-value="'.$td.'">'.$OPTION[$td].'</div>';
        }

        if($column === 'ACTION'){
            return '<div class="d-flex justify-content-end"><div class="btn-group"><button type="button" class="btn btn-sm btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="mr-2">'._('Opción').'</span></button><div class="dropdown-menu"><a class="dropdown-item model-edited" data-id="'.$ModelID.'" href="javascript:void(0)"><i class="fa fa-pencil-square-o mr-2"></i>'._('Editar').'</a><a class="dropdown-item model-trashed" data-id="'.$ModelID.'" href="javascript:void(0)"><i class="fa fa-trash mr-2"></i>'._('Eliminar').'</a></div></div></div>';
        }
        return '<div class="model_'.$ModelID.' '.$column.'" data-value="'.$td.'">'.$td.'</div>';
    }

    public function index($id="")
    {
        $this->taxonomyId = $id;

        if($this->taxonomyId !== ""){
            $Taxonomy = new \App\Models\TaxonomyModel();
            $TaxonomyData = $Taxonomy->Exists($this->taxonomyId);

            $Label = "";
            if(!IS_NULL($TaxonomyData)){    
                $Label = ' de <b>'.strtolower($TaxonomyData['title']).'</b>';
                $this->titlePage = $this->viewContent()->list->titlePage.$Label;
                $this->description = $this->viewContent()->list->description.$Label;
                $this->setContent($this->viewContent()->list->title, $this->viewContent()->list->content.$Label);
                unset($this->breadcrumbs[1]);
                $this->addBreadcrumb(_('Taxonomías'), site_url('dashboard/taxonomys'));
                $this->addBreadcrumb($this->title, site_url('dashboard/fields/'.$this->taxonomyId));
                $this->addBreadcrumb($this->titlePage);
                $this->withLayout = 'index';
                return $this->View($this->viewList);                
            }
        }

        return redirect()->to('dashboard/taxonomys')->with('warning', '¡La taxonomía no existe!');
    }

    public function saved(){
        
        $row = [];
        if($this->validate($this->getModel()->getValidation())){
            $data = ($this->getValues());

            $Model = $this->getModel();
            $Saved = ($this->isNew() && !$this->getID()) ? $Model->insert($data) : $Model->update($this->getID(), $data);

            if($Saved){
                $ModelId = $this->getID();
                if($this->isNew() && !$this->getID()){
                    $ModelId = $Model->getInsertID();
                }
                $row  = $this->getModel()->Exists($ModelId);
                $tr = [];
                foreach ($this->getColumns() as $key => $column) {
                    if(isset($row[$column->key])){
                       $tr[$column->key] = str_replace(["\r", "\n"], '', $this->td($row, $row[$column->key], $column->key));
                    }else{
                        $tr[$column->key] = str_replace(["\r", "\n"], '', $this->td($row, $row, $column->key));
                    }
                }
                $row = $tr;
            }
        }
        return json_encode(['row' => $row, 'validator'=> (\Config\Services::validation())->getErrors()]);
    }

    public function trash($id)
    {
        $Model = $this->getModel();
        $Data = $Model->Exists($id);
        if(strlen(trim($id))===0 || is_null($Data)){
            return json_encode(['status' => 404, 'message'=> '¡Este registro no existe!']);
        }

        $this->setValues($Data);
        $Model->delete($this->getID());

        return json_encode(['status' => 200, 'message'=> '¡El registro ha sido eliminado exitosamente!']);
    }

    protected function script(): string
    {
        $Model = $this->getModel();
        ob_start(); ?>
        <script type="text/javascript">
            'use strict';
            $(document).ready(function () {
                const idtaxonomy = '<?=$this->taxonomyId;?>';
                <?php 
                    $string = "";
                    foreach ($this->getColumns() as $key => $column) {
                        # code...
                        $fields_html = 'null';
                        if($key !== 0 && $column->key !== 'ACTION'){
                            $fields_html = "'".field_html($Model->getFields()[$column->key])."'";
                        }else{
                            if($column->key === 'ACTION'){
                                $fields_html = '<a href="javascript:void(0)" class="btn btn-info btn-sm mb-1 text-center w-100 saved-model" style="color:#fff"><i class="ti-save mr-2"></i> '._('Guardar').'</a> <a href="javascript:void(0)" class="btn btn-secondary btn-sm mb-1 text-center w-100 cancel-new cancel-edited" style="color:#fff"><i class="ti-close mr-2"></i> '._('Cancelar').'</a>';
                                $fields_html = "'".$fields_html."'";
                            }
                        }
                        $string .= ($string!==""?",": "").$key.":".$fields_html;
                    }

                    echo "const ContentTr = {".$string."};"
                ?>

                const trash = (id, callback) => {
                    $.ajax({
                        url: '<?=site_url($this->slug."/trash/");?>'+id,   // URL a la que enviar la solicitud
                        method: 'POST',      // Método HTTP (POST, GET, etc.)
                        dataType: 'json'     // Tipo de datos esperados en la respuesta (opcional)
                    }).done(callback).fail(function(jqXHR, textStatus, errorThrown) {
                        //Callback para manejar errores
                        console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
                    });
                }

                const saved = ($tr, callback, id) => {
                    $.ajax({
                        url: '<?=site_url($this->slug."/saved");?>',   // URL a la que enviar la solicitud
                        method: 'POST',      // Método HTTP (POST, GET, etc.)
                        data: {
                            idfield: id,
                            idtaxonomy: idtaxonomy,
                            <?php 
                                $string = "";
                                foreach ($Model->getFields() as $key => $field) {
                                    if($key !== 'idfield' && $key !== 'idtaxonomy' && $key !== 'created_at' && $key !== 'updated_at'){
                                        if($field->type !== 'switch'){
                                            if($field->type !== 'select' && $field->type !== 'textarea'){
                                                $string .= $key.': $tr.find(\'input[name="'.$field->name.'"]\').val(),';
                                            }else{
                                                $string .= $key.': $tr.find(\''.$field->type.'[name="'.$field->name.'"]\').val(),';
                                            }
                                        }else{
                                            $string .= $key.': $tr.find(\'input[name="'.$field->name.'"]\').prop(\'checked\')?\'on\': \'off\',';
                                        }
                                        
                                    }
                                    
                                }
                                echo $string;
                            ?>
                        },          // Datos a enviar en la solicitud (opcional)
                        dataType: 'json'     // Tipo de datos esperados en la respuesta (opcional)
                    }).done(callback).fail(function(jqXHR, textStatus, errorThrown) {
                        //Callback para manejar errores
                        console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
                    });
                }

                const actionTrash = function() {
                    const modelEdited = $(this);
                    const ModelID = modelEdited.attr('data-id');

                    let $ColID = $('.tabled-model').find('tr td .model_'+ModelID+'.idfield');


                    let tabled = $('.tabled-model').DataTable();
                    let $tr = $ColID.closest('tr');
                    // let row = tabled.row($tr);
                    // let tr = row.data();
                    
                    let title_value = $($tr).find('.model_'+ModelID+'.name').attr('data-value');
                    swal({
                        title: "<?=_('¿Estás seguro?');?>",
                        text: "<?=_('¿Deseas eliminar el registro?');?> "+title_value+" <?=_('¡Una vez eliminado, no podrás recuperar el registro!');?>",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((willDelete) => {
                        if (willDelete) {
                            trash(ModelID, (response)=>{
                                tabled.row($tr).remove().draw();
                                swal("¡El registro ha sido eliminado con éxito!", {icon: "success"});
                            });
                        }
                    });

                }

                const restore = (tr) => {
                    $(tr).find('.model-edited').on('click', actionEdited);
                    $(tr).find('.model-trashed').on('click', actionTrash);
                }

                const actionEdited = function() {
                    const modelEdited = $(this);
                    const ModelID = modelEdited.attr('data-id');

                    modelEdited.closest('.dropdown-menu').remove();
                    let $ColID = $('.tabled-model').find('tr td .model_'+ModelID+'.idfield');
                    
                    let tabled = $('.tabled-model').DataTable();
                    let trow = $ColID.closest('tr');
                    let row = tabled.row(trow);
                    let tr = row.data();
                    //console.log(tr)
                    <?php 
                        $string = "";
                        foreach ($this->getColumns() as $key => $column) {
                            if($key !== 0 && $column->key !== 'ACTION'){
                                $string .= "let ".$column->key."_value = ($(trow).find('.model_'+ModelID+'.".$column->key."')).attr('data-value');";
                            }
                        }

                        echo $string;
                    ?>

                    let content = ContentTr;
                    content[0] = '<div class="mt-4"><?=_('Editando');?></div>';

                    let trEdited = row.data(content).draw().node();

                    <?php 
                        $string = "";
                        foreach ($this->getColumns() as $key => $column) {
                            if($key !== 0 && $column->key !== 'ACTION'){
                                $field = $Model->getFields()[$column->key];
                                if($field->type !== 'switch'){
                                    if($field->type !== 'select' && $field->type !== 'textarea'){
                                        $string .= '$(trEdited).find(\'input[name="'.$field->name.'"]\').val('.$column->key.'_value);';
                                    }else{
                                        $string .= '$(trEdited).find(\''.$field->type.'[name="'.$field->name.'"]\').val('.$column->key.'_value);';
                                    }
                                }else{
                                    $string .= '$(trEdited).find(\'input[name="'.$field->name.'"]\').prop(\'checked\', '.$column->key.'_value === \'1\');';
                                }
                            }
                        }

                        echo $string;
                    ?>

                    $(trEdited).find('select').select2({
                        placeholder: '<?=_('Seleccione');?>'
                    });

                    //console.log(ModelID)

                    $(trEdited).find('.saved-model').click((evt)=>{
                        //tabled.row(tr).remove().draw();
                        saved($(trEdited), (response)=>{
                            if(response?.row?.idfield){
                                restore(row.data(Object.values(response?.row)).draw().node());
                                feather.replace();
                                swal("<?=_('¡El registro ha sido guardado con éxito!');?>", {icon: "success"});
                            }
                        }, ModelID);
                    });

                    $(trEdited).find('.cancel-edited').click((evt)=>{
                        restore(row.data(tr).draw().node());
                    });
                    // Tu código para manejar el evento click aquí
                    // Por ejemplo, puedes acceder a $(this) para referirte al elemento que disparó el evento
                }

                $('.model-new').click((evt)=>{
                    let tabled = $('.tabled-model').DataTable();

                    let content = ContentTr;
                    content[0] = '<div class="mt-4"><?=_('Nuevo');?></div>'

                    let tr = tabled.row.add(Object.values(content)).draw(false).node();
                    $(tr).addClass('new-model-item'); // Agregar la clase 'mi-clase' al tr
                    $(tr).find('select').select2({
                        placeholder: '<?=_('Seleccione');?>'
                    });

                    $(tr).find('.saved-model').click((evt)=>{
                        //tabled.row(tr).remove().draw();
                        saved($(tr), (response)=>{
                            if(response?.row?.idfield){
                                restore(tabled.row.add(Object.values(response?.row)).draw().node());
                                $(tr).find('.cancel-new').click();
                                feather.replace();
                                swal("<?=_('¡El registro ha sido guardado con éxito!');?>", {icon: "success"});
                            }else{
                                let message = "";
                                for (const e in response?.validator) {
                                    let input = (e === 'typefield') ? $(tr).find('.model-required span.select2-selection.select2-selection--single'): $(tr).find('input[name="'+e+'"]');
                                    let label = $(tr).find('label[for="'+e+'"]');
                                    message += (message === "" ? "": "\r") + response?.validator[e];
                                    if(input){
                                        label.attr('style', 'color:#E91E63');
                                        input.attr('style', 'border-color: #E91E63;');
                                    }
                                }

                                swal(message, {icon: "error"});
                            }
                        }, '');
                    });
                    $(tr).find('.cancel-new').click((evt)=>{
                        tabled.row(tr).remove().draw();
                    });
                });

                $('.model-edited').on('click', actionEdited);
                $('.model-trashed').on('click', actionTrash);
            });
        </script>
        <?php return ob_get_clean();
    }
}
