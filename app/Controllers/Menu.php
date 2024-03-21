<?php

namespace App\Controllers;

class Menu extends BaseController
{

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $title = 'Menús';
        
    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $slug = 'dashboard/settings/menu';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewView = 'settings/menu/view';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewEdit = 'settings/menu/edit';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewList = 'settings/menu/list';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewFilter = 'settings/menu/filter';

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
                'titlePage' => 'Opciones de menú',
                'description' => 'Lista de opciones',
                'title' => 'Lista de opciones',
                'content' => 'Lista de menús disponibles'
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
                'key' => 'idmenu',
                'label' => _('ID')
            )),
            ((Object) array(
                'key' => 'menu',
                'label' => _('Tipo')
            )),
            ((Object) array(
                'key' => 'icon',
                'label' => _('Icono')
            )),
            ((Object) array(
                'key' => 'title',
                'label' => _('Título')
            )),
            ((Object) array(
                'key' => 'href',
                'label' => _('Ruta')
            )),
            ((Object) array(
                'key' => 'target',
                'label' => _('Abrir')
            )),
            ((Object) array(
                'key' => 'level',
                'label' => _('Nivel')
            )),
            ((Object) array(
                'key' => 'enabled',
                'label' => _('Habilitado')
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
        return new \App\Models\MenuModel();
    }

    protected function FILTER(){
        if(!$this->getModel()){
            return [];
        }
        //->where('idbusiness', getIdBussiness())
        return (($this->getModel())->findAll());

    }

    protected function td($tr, $td, $column){
        $ModelID = $this->getModel()->getID($tr);

        if($column === 'icon'){
            return '<div class="menu_'.$ModelID.' '.$column.'" data-value="'.$td.'"><i data-feather="'.$td.'"></i></div>';
        }

        if($column === 'enabled'){
            return '<div class="menu_'.$ModelID.' '.$column.'" data-value="'.$td.'">'.(intval($td) === 1 ? _('Si'):_('No')).'</div>';
        }

        if($column === 'menu'){
            $OPTION = [
                'menu'=>'Menú',
                'divider'=>'Separador'
            ];
            return '<div class="menu_'.$ModelID.' '.$column.'" data-value="'.$td.'">'.$OPTION[$td].'</div>';
        }

        if($column === 'target'){
            $OPTION = [
                '_self'=>_('Misma ventana'),
                '_blank'=>_('Nueva ventana'),
            ];
            return '<div class="menu_'.$ModelID.' '.$column.'" data-value="'.$td.'">'.$OPTION[$td].'</div>';
        }

        if($column === 'ACTION'){
            return '<div class="d-flex justify-content-end"><div class="btn-group"><button type="button" class="btn btn-sm btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="mr-2">'._('Opción').'</span></button><div class="dropdown-menu"><a class="dropdown-item menu-edited" data-id="'.$ModelID.'" href="javascript:void(0)"><i class="fa fa-pencil-square-o mr-2"></i>'._('Editar').'</a><a class="dropdown-item menu-trashed" data-id="'.$ModelID.'" href="javascript:void(0)"><i class="fa fa-trash mr-2"></i>'._('Eliminar').'</a></div></div></div>';
        }
        return '<div class="menu_'.$ModelID.' '.$column.'" data-value="'.$td.'">'.$td.'</div>';
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

                const trashMenu = (idmenu, callback) => {
                    $.ajax({
                        url: '<?=site_url($this->slug."/trash/");?>'+idmenu,   // URL a la que enviar la solicitud
                        method: 'POST',      // Método HTTP (POST, GET, etc.)
                        dataType: 'json'     // Tipo de datos esperados en la respuesta (opcional)
                    }).done(callback).fail(function(jqXHR, textStatus, errorThrown) {
                        //Callback para manejar errores
                        console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
                    });
                }

                const savedMenu = ($tr, callback, idmenu) => {
                    $.ajax({
                        url: '<?=site_url($this->slug."/saved");?>',   // URL a la que enviar la solicitud
                        method: 'POST',      // Método HTTP (POST, GET, etc.)
                        data: {
                            idmenu: idmenu,
                            menu: $tr.find('select[name="menu"]').val(),
                            icon: $tr.find('select[name="icon"]').val(),
                            title: $tr.find('input[name="title"]').val(),
                            href: $tr.find('input[name="href"]').val(),
                            target: $tr.find('select[name="target"]').val(),
                            level: $tr.find('input[name="level"]').val(),
                            enabled: $tr.find('input[name="enabled"]').prop('checked')?'on': 'off',
                        },          // Datos a enviar en la solicitud (opcional)
                        dataType: 'json'     // Tipo de datos esperados en la respuesta (opcional)
                    }).done(callback).fail(function(jqXHR, textStatus, errorThrown) {
                        //Callback para manejar errores
                        console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
                    });
                }

                const actionTrash = function() {
                    const menuEdited = $(this);
                    const MenuID = menuEdited.attr('data-id');

                    let $ColID = $('.tabled-menu').find('tr td .menu_'+MenuID+'.idmenu');

                    let tabled = $('.tabled-menu').DataTable();
                    let $tr = $ColID.closest('tr');
                    // let row = tabled.row($tr);
                    // let tr = row.data();
                    
                    let title_value = $($tr).find('.menu_'+MenuID+'.title').attr('data-value');
                    swal({
                        title: "<?=_('¿Estás seguro?');?>",
                        text: "<?=_('¿Deseas eliminar el menú?');?> "+title_value+" <?=_('¡Una vez eliminado, no podrás recuperar el menú!');?>",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((willDelete) => {
                        if (willDelete) {
                            trashMenu(MenuID, (response)=>{
                                tabled.row($tr).remove().draw();
                                swal("¡El menú ha sido eliminado con éxito!", {icon: "success"});
                            });
                        }
                    });

                }

                const restore = (tr) => {
                    $(tr).find('.menu-edited').on('click', actionEdited);
                    $(tr).find('.menu-trashed').on('click', actionTrash);
                }

                const actionEdited = function() {
                    const menuEdited = $(this);
                    const MenuID = menuEdited.attr('data-id');

                    menuEdited.closest('.dropdown-menu').remove();

                    let $ColID = $('.tabled-menu').find('tr td .menu_'+MenuID+'.idmenu');
                    let tabled = $('.tabled-menu').DataTable();
                    let $tr = $ColID.closest('tr');
                    let row = tabled.row($tr);
                    let tr = row.data();

                    let menu_value = ($($tr).find('.menu_'+MenuID+'.menu')).attr('data-value');
                    let icon_value = ($($tr).find('.menu_'+MenuID+'.icon')).attr('data-value');
                    let title_value = ($($tr).find('.menu_'+MenuID+'.title')).attr('data-value');
                    let href_value = ($($tr).find('.menu_'+MenuID+'.href')).attr('data-value');
                    let target_value = ($($tr).find('.menu_'+MenuID+'.target')).attr('data-value');
                    let level_value = ($($tr).find('.menu_'+MenuID+'.level')).attr('data-value');
                    let enabled_value = ($($tr).find('.menu_'+MenuID+'.enabled')).attr('data-value');

                    let content = {
                        0: '<div class="mt-4"><?=_('Editando');?></div>',
                        1: '<?=field_html($Model->getFields()['menu']);?>',
                        2: '<?=field_html($Model->getFields()['icon']);?>',
                        3: '<?=field_html($Model->getFields()['title']);?>',
                        4: '<?=field_html($Model->getFields()['href']);?>',
                        5: '<?=field_html($Model->getFields()['target']);?>',
                        6: '<?=field_html($Model->getFields()['level']);?>',
                        7: '<?=field_html($Model->getFields()['enabled']);?>',
                        8: '<a href="javascript:void(0)" class="btn btn-info btn-sm mb-1 text-center w-100 saved-menu" style="color:#fff"><i class="ti-save mr-2"></i> <?=_('Guardar');?></a> <a href="javascript:void(0)" class="btn btn-secondary btn-sm mb-1 text-center w-100 cancel-edited" style="color:#fff"><i class="ti-close mr-2"></i> <?=_('Cancelar');?></a>'
                    };

                    let trEdited = row.data(content).draw().node();

                    $(trEdited).find('select[name="menu"]').val(menu_value);
                    $(trEdited).find('select[name="icon"]').val(icon_value);

                    $(trEdited).find('input[name="title"]').val(title_value);
                    $(trEdited).find('input[name="href"]').val(href_value);

                    $(trEdited).find('select[name="target"]').val(target_value);
                    $(trEdited).find('input[name="level"]').val(level_value);

                    $(trEdited).find('input[name="enabled"]').prop('checked', enabled_value === '1');

                    $(trEdited).find('select').select2({
                        placeholder: '<?=_('Seleccione');?>'
                    });

                    $(trEdited).find('.saved-menu').click((evt)=>{
                        //tabled.row(tr).remove().draw();
                        savedMenu($(trEdited), (response)=>{
                            if(response?.row?.idmenu){
                                restore(row.data(Object.values(response?.row)).draw().node());
                                feather.replace();
                                swal("<?=_('¡El menú ha sido guardado con éxito!');?>", {icon: "success"});
                            }
                        }, MenuID)
                    });

                    $(trEdited).find('.cancel-edited').click((evt)=>{
                        restore(row.data(tr).draw().node());
                    });
                    // Tu código para manejar el evento click aquí
                    // Por ejemplo, puedes acceder a $(this) para referirte al elemento que disparó el evento
                }

                $('.new_menu').click((evt)=>{
                    let tabled = $('.tabled-menu').DataTable();

                    let newMenu = {
                        'ID': '<div class="mt-4"><?=_('Nuevo');?></div>',
                        'Tipo': '<?=field_html($Model->getFields()['menu']);?>',
                        'Icono': '<?=field_html($Model->getFields()['icon']);?>',
                        'Titulo': '<?=field_html($Model->getFields()['title']);?>',
                        'Ruta': '<?=field_html($Model->getFields()['href']);?>',
                        'Abrir': '<?=field_html($Model->getFields()['target']);?>',
                        'Nivel': '<?=field_html($Model->getFields()['level']);?>',
                        'Habilitado': '<?=field_html($Model->getFields()['enabled']);?>',
                        'Action': '<a href="javascript:void(0)" class="btn btn-info btn-sm mb-1 text-center w-100 saved-menu" style="color:#fff"><i class="ti-save mr-2"></i> <?=_('Guardar');?></a> <a href="javascript:void(0)" class="btn btn-secondary btn-sm mb-1 text-center w-100 cancel-new" style="color:#fff"><i class="ti-close mr-2"></i> <?=_('Cancelar');?></a>'
                    };
                    //console.log(newMenu.Icono);
                    let tr = tabled.row.add(Object.values(newMenu)).draw(false).node();
                    $(tr).addClass('new-menu-item'); // Agregar la clase 'mi-clase' al tr
                    $(tr).find('select').select2({
                        placeholder: '<?=_('Seleccione');?>'
                    });

                    $(tr).find('.saved-menu').click((evt)=>{
                        //tabled.row(tr).remove().draw();
                        savedMenu($(tr), (response)=>{
                            if(response?.row?.idmenu){
                                restore(tabled.row.add(Object.values(response?.row)).draw().node());
                                $(tr).find('.cancel-new').click();
                                feather.replace();
                                swal("<?=_('¡El menú ha sido guardado con éxito!');?>", {icon: "success"});
                            }else{
                                let message = "";
                                for (const e in response?.validator) {
                                    let input = (e === 'menu') ? $(tr).find('.new-field-menu.menu-required span.select2-selection.select2-selection--single'): $(tr).find('input[name="'+e+'"]');
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

                $('.menu-edited').on('click', actionEdited);
                $('.menu-trashed').on('click', actionTrash);
            });
        </script>
        <?php return ob_get_clean();
    }
}
