<?php

namespace App\Controllers;
use CodeIgniter\Files\File;

class Filemanager extends BaseController
{

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $title = 'Administrador de archivos';
        
    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $slug = 'dashboard/file-manager';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewView = 'filemanager/view';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewEdit = 'filemanager/edit';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewList = 'filemanager/list';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewFilter = 'filemanager/filter';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $root = '';
    protected $order = '';

    protected $Folder = null;
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
                'titlePage' => $this->title,
                'description' => 'Lista de archivos',
                'title' => 'Lista de archivos',
                'content' => '/'
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
                'key' => 'idrole',
                'label' => _('ID')
            )),
            ((Object) array(
                'key' => 'title',
                'label' => _('Rol')
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
        return new \App\Models\FileModel();
    }

    protected function FILTER($search = ""){

        $FolderModel = new \App\Models\FolderModel();
        $FileModel = $this->getModel();

        if(!IS_NULL($this->Folder)){
            $FolderModel->where('sub', $this->Folder['idfolder']);
            $FileModel->where('idfolder', $this->Folder['idfolder']);
        }else{
            $FolderModel->where('sub', 0);
            $FileModel->where('idfolder', NULL);
        }

        if($this->root === 'recents'){
            $today = date('Y-m-d');
            $FolderModel->where('DATE(created_at)', $today)->orderBy('created_at', 'DESC');
            $FileModel->where('DATE(created_at)', $today)->orderBy('created_at', 'DESC');
        }

        if($this->root === 'recycle'){
            $FolderModel->withDeleted()->where('deleted_at !=', '0000-00-00 00:00:00');
            $FileModel->withDeleted()->where('deleted_at !=', '0000-00-00 00:00:00');
        }

        if($this->order !== ''){
            if($this->order = 'order=date'){
                $FolderModel->orderBy('created_at', 'DESC');
                $FileModel->orderBy('created_at', 'DESC');
            }
            if($this->order = 'order=name'){
                $FolderModel->orderBy('title', 'DESC');
                $FileModel->orderBy('title', 'DESC');
            }
            if($this->order = 'order=size'){
                $FolderModel->orderBy('title', 'DESC');
                $FileModel->orderBy('size', 'DESC');
            }
        }

        if($search !== ""){
            // Busca coincidencias en la columna title del modelo FolderModel
            $FolderModel->like('title', $search);
            // Busca coincidencias en la columna title del modelo FileModel
            $FileModel->like('title', $search);
        }

        $Folders = $FolderModel->findAll();
        $Files = $FileModel->findAll();
        return array_merge($Folders, $Files);
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
                        <a class="dropdown-item" href="<?=site_url('dashboard/permissions/'.$ModelID);?>"><i class="fa fa-check-square-o mr-2"></i><?=_('Permisos');?></a>
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

    protected function manager(){
        

        $Settings = SETTING('upload_path');
        $Settings_disk = SETTING('disk_total');

        $disk_total = ((floatval($Settings_disk->value) * 1024) * 1024) * 1024;
        $totalUsed = folderSize($Settings->value);

        $this->vars = (Object) array(
            'root' => $this->root,
            'totalDiskSpace' => formatBytes($disk_total),
            'usedDiskSpace' => formatBytes($totalUsed),
            'usagePercentage' => (($totalUsed/$disk_total)*100),
            'all_files_count' => count($this->getModel()->findAll()),
            'Folder' => new \App\Models\FolderModel(),
            'FolderOpen' => $this->Folder
        );
    }

    public function views($slug="")
    {
        $File = $this->getModel()->ExistsBy('slug', $slug);

        if(!IS_NULL($File)){
            $File = (Object) $File;
            if (file_exists($File->path)) {
                $ContentType = mime_content_type($File->path);
                header('Content-Type: ' . $ContentType);
                exit(readfile($File->path));
                //return $this->response->download($File->path, null);
            }
        }
        return "Archivo no encontrado";
    }

    public function download($slug="")
    {
        $File = $this->getModel()->ExistsBy('slug', $slug);

        if(!IS_NULL($File)){
            $File = (Object) $File;
            if (file_exists($File->path)) {
                return $this->response->download($File->path, null);
            }
        }
        return "Archivo no encontrado";
    }

    public function index($slug="")
    {
        $this->root = '';
        $this->manager();
        return parent::index();
    }

    public function recents()
    {
        $this->root = 'recents';
        $this->manager();
        return parent::index();
    }

    public function recycle()
    {
        $this->root = 'recycle';
        $this->manager();
        return parent::index();
    }

    public function folder($idfolder="")
    {

        $Folder = new \App\Models\FolderModel();
        $this->Folder = ($Folder)->where($Folder->primaryKey(), $idfolder)->first();
        if(!IS_NULL($this->Folder)){

            $this->root = '';
            $this->manager();

            $this->titlePage = $this->viewContent()->list->titlePage;
            $this->description = $this->viewContent()->list->description;
            $this->setContent($this->viewContent()->list->title, '/'.$this->Folder['title']);
            unset($this->breadcrumbs[1]);
            $this->addBreadcrumb($this->titlePage);
            $this->withLayout = 'index';

            // Verificar si el usuario ya está autenticado
            if (!(session()->get('isLoggedIn'))) {
                return redirect()->to('/login');
            }

            return $this->View($this->viewList);
            return parent::index();            
        }

        return redirect()->to($this->slug)->with('warning', '¡La carpeta no existe!');

    }

    public function order($order="")
    {
        $this->order = 'order='.$order;
        $this->manager();
        return parent::index();
    }

    public function content()
    {
        if (!(session()->get('isLoggedIn'))) {
            return $this->response->setJSON([
                'data' => null,
                'status' => 'error',
                'message' => _('Necesitas iniciar sesión nuevamente.')
            ]);
        }
        
        $Model =($this->request->getPost('typed') === 'file') ? $this->getModel() : (new \App\Models\FolderModel());

        $Model = $Model->where($Model->primaryKey(), $this->request->getPost('id'))->first();
        if(!IS_NULL($Model)){
            $User = (new \App\Models\AccountModel())->where('idaccount', $Model['idaccount'])->first();
            $Model['idautor'] = $Model['idaccount'];
            $Model['idaccount'] = IS_NULL($User) ? '': ($User['name']." ".$User['surname']);
        }
        return $this->response->setJSON([
            'data' => $Model,
            'status' => 'success',
            'message' => null
        ]);
    }

    public function find()
    {
        if (!(session()->get('isLoggedIn'))) {
            return $this->response->setJSON([
                'data' => null,
                'status' => 'error',
                'message' => _('Necesitas iniciar sesión nuevamente.')
            ]);
        }
        
        $data = [];
        $data_view = [];
        foreach ($this->FILTER($this->request->getPost('search')) as $file) {
            $data[] = render_item_file((Object) $file);

            if(isset($file['idfile'])){
                $data_view[] = render_item_file_view((Object) $file);
            }
        }
        return $this->response->setJSON([
            'data' => $data,
            'view' => $data_view,
            'status' => 'success',
            'message' => null
        ]);
    }

    public function save(){
        if (!(session()->get('isLoggedIn'))) {
            return $this->response->setJSON([
                'data' => null,
                'validator' => null,
                'status' => 'error',
                'message' => _('Necesitas iniciar sesión nuevamente.')
            ]);
        }

        $data = ($this->getValues());

        if($this->validate($this->getModel()->getValidation()) || count($this->getModel()->getValidation()) === 0){
            
            $Model = $this->getModel();
            
            if(!$this->isNew() && $this->getID()){
                if(isset($data['slug'])){
                    $data['slug'] = permanentLink($data['slug']);
                }
                $ModelId = $this->getID();
                if($Model->update($ModelId, $data)){
                    $data = $Model->where($Model->primaryKey(), $ModelId)->first();

                    return $this->response->setJSON([
                        'data' => render_item_file((Object) $data),
                        'validator' => null,
                        'status' => 'success',
                        'message' => _('¡Los datos se han guardado correctamente!')
                    ]);
                }
            }
        }
        
        return json_encode(['data' => $data, 'validator'=> (\Config\Services::validation())->getErrors()]);
    }

    public function upload()
    {
        if (!(session()->get('isLoggedIn'))) {
            return $this->response->setJSON([
                'data' => null,
                'validator' => null,
                'status' => 'error',
                'message' => _('Necesitas iniciar sesión nuevamente.')
            ]);
        }
    
        if ($this->request->getFile('file')->isValid()) {
            // Obtiene el archivo subido
            $file = $this->request->getFile('file');
    
            $IdFolder = $this->request->getPost('idfolder');

            $Folder = null;

            if(!IS_NULL($IdFolder)){
                $Folder = (new \App\Models\FolderModel())->where('idfolder', $IdFolder)->first();
            }

            // Define la ruta de destino para guardar el archivo
            $path = IS_NULL($Folder) ? SETTING('upload_path')->value: $Folder['path'];
            $pathModel = $path . $file->getName(); // Ruta completa del archivo en el servidor
            $Model = $this->getModel();

            $data = $Model->setValues([
                'title' => $file->getName(), // Nombre del archivo
                'path' => $pathModel,
                'mimetype' => $file->getMimeType(), // Tipo MIME del archivo
                'size' => $file->getSize(), // Tamaño del archivo en bytes
                'slug' => permanentLink($file->getName())
            ]);
            // Mueve el archivo al directorio de destino
            if ($file->move($path)) {
                if(!IS_NULL($Folder)){
                    $data['idfolder'] = $Folder['idfolder'];
                }
                if ($Model->insert($data)) {
                    $data = $Model->where($Model->primaryKey(), $Model->getInsertID())->first();
                    // El archivo se ha cargado exitosamente
                    return $this->response->setJSON([
                        'file' => $data,
                        'view' => render_item_file_view((Object) $data),
                        'data' => render_item_file((Object) $data),
                        'validator' => null,
                        'status' => 'success',
                        'message' => _('¡Los datos se han guardado correctamente!')
                    ]);
                }else{

                    $file = new File($pathModel);

                    if ($file->exists()) {
                        $file->delete();
                    }
                    // El archivo se ha cargado exitosamente
                    return $this->response->setJSON([
                        'data' => null,
                        'validator' => null,
                        'status' => 'error',
                        'message' => _('Ha ocurrido un error al subir el archivo.')
                    ]);                    
                }

            } else {
                // Ocurrió un error al mover el archivo
                return $this->response->setJSON([
                    'data' => null,
                    'validator' => null,
                    'status' => 'error',
                    'message' => _('Ha ocurrido un error al subir el archivo.')
                ]);
            }
        } else {
            // No se ha enviado ningún archivo o el archivo es inválido
            return $this->response->setJSON([
                'data' => null,
                'validator' => null,
                'status' => 'error',
                'message' => _('No se ha enviado ningún archivo o el archivo es inválido.')
            ]);
        }
    }

    public function folderSaved(){
        if (!(session()->get('isLoggedIn'))) {
            return redirect()->to('/login');
        }


        $Model = new \App\Models\FolderModel();
        $data = ($this->getValues($Model));
        

        if($this->validate($Model->getValidation()) || count($Model->getValidation()) === 0){

            $isNew = !$Model->isDeleted($this->values);
            $FolderId = $Model->getID($this->values);

            if(($isNew && !$FolderId)){
                $data['path'] = isset($data['sub']) ? create_sub_folder($data['idaccount'], $data['sub']): create_folder($data['idaccount']);
            }

            $Saved = ($isNew && !$FolderId) ? $Model->insert($data) : $Model->update($FolderId, $data);
            
            if($Saved){
                $ModelId = $FolderId;
                if($isNew && !$FolderId){
                    $ModelId = $Model->getInsertID();
                }
                $data[$Model->primaryKey()] = $ModelId;
                return json_encode(['data' => render_item_file((Object) $data), 'validator'=> null, 'status' => 'success', 'message' => _('¡Los datos se han guardado correctamente!')]);
            }

        }
        
        return json_encode(['data' => $data, 'validator'=> (\Config\Services::validation())->getErrors()]);
    }

    protected function head(): string
    {
        ob_start(); ?>
        <style>
            div#details-manager-file .col-sm-12 {
                -ms-flex: 0 0 100%;
                flex: 0 0 100%;
                max-width: 100%;
            }
        </style>
        <?php return ob_get_clean();
    }

    protected function script(): string
    {
        $Model = $this->getModel();

        
        ob_start();
        foreach ($this->vars->Folder->getFields() as $field) {
            echo field_html($field, $this->values, null);
        }
        echo br_html();
        echo br_html();
        //echo form_button_html($this->vars->Folder, $this->values, $this->slug);
        $FormFolder = form_html(((Object) array(
            'method' => "POST",
            'enctype'=> true,
            'validator'=> null,
            'context' => ob_get_clean()
        )));

        ob_start();
        foreach ($this->getModel()->getFields() as $field) {
            echo field_html($field, $this->values, null);
        }
        echo br_html();
        echo br_html();
        //echo form_button_html($this->getModel(), $this->values, $this->slug);
        $FormFile = form_html(((Object) array(
            'method' => "POST",
            'enctype'=> true,
            'validator'=> null,
            'context' => ob_get_clean()
        )));

        ob_start();
        foreach ($this->vars->Folder->getFields() as $field) {
            echo field_view_html($field, $this->values, null, true);
        }
        echo br_html();
        echo br_html();
        //echo form_button_view_html($this->vars->Folder, $this->values, $this->slug);
        $FormFolderView = form_html(((Object) array(
            'method' => "POST",
            'enctype'=> true,
            'validator'=> null,
            'context' => ob_get_clean()
        )));

        ob_start();
        foreach ($this->getModel()->getFields() as $field) {
            echo field_view_html($field, $this->values, null, true);
        }
        echo br_html();
        echo br_html();
        //echo form_button_view_html($this->getModel(), $this->values, $this->slug);
        $FormFileView = form_html(((Object) array(
            'method' => "POST",
            'enctype'=> true,
            'validator'=> null,
            'context' => ob_get_clean()
        )));

        ob_start(); ?>
        <script type="text/javascript">
            'use strict';
            $(document).ready(function () {
                let $FormFolderHTML = '<?=$FormFolder;?>';
                let $FormFileHTML = '<?=$FormFile;?>';


                let $FormFolderViewHTML = '<?=$FormFolderView;?>';
                let $FormFileViewHTML = '<?=$FormFileView;?>';

                let AppDetails = $('#details-manager-file');
                let AppContent = $('.app-content');

                let ButtonAddonSearch = $('#button-addon-search');
                let InputAddonSearch = $('#input-addon-search');

                let PanelList = $('#panel-files');
                let PanelUpload = $('#panel-upload');
                let PanelUploadList = $($('#panel-upload').find('.panel-list'));
                let Folder = $('#formFolder');
                let Saved = $(Folder.find('.modal-footer button.btn-primary'));

                let formUpload = $('form#file-upload');

                let AppClose = $(AppDetails.find('#close-details-manager-file'));

                AppClose.on('click', ()=>{
                    AppContent.removeClass('col-md-3');
                    AppContent.addClass('col-md-9');
                    AppDetails.hide();
                });

                const handleContent = (data, callback) => {
                    $.ajax({
                        url: '<?=site_url($this->slug.'/content');?>',   // URL a la que enviar la solicitud
                        method: 'POST',      // Método HTTP (POST, GET, etc.)
                        data: data,          // Datos a enviar en la solicitud (opcional)
                        dataType: 'json'     // Tipo de datos esperados en la respuesta (opcional)
                    }).done(callback).fail(function(jqXHR, textStatus, errorThrown) {
                        //Callback para manejar errores
                        console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
                    });
                }

                const handleTrash = (data, callback, action) => {
                    $.ajax({
                        url: '<?=site_url($this->slug);?>'+action,   // URL a la que enviar la solicitud
                        method: 'POST',      // Método HTTP (POST, GET, etc.)
                        data: data,          // Datos a enviar en la solicitud (opcional)
                        dataType: 'json'     // Tipo de datos esperados en la respuesta (opcional)
                    }).done(callback).fail(function(jqXHR, textStatus, errorThrown) {
                        //Callback para manejar errores
                        console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
                    });
                }

                let handleInit = () => {
                    $('.app-render-item a[data-action="details"]').on('click', function () {

                        let id = $(this).attr('data-id');
                        let typed = $(this).attr('data-typed');
                        let action = $(this).attr('data-action');

                        let menu = $($(this).closest('.dropdown-menu'));
                        menu.removeClass('show');
                        //alert(action + ' typed ' + typed +' id '+id);

                        handleContent({typed: typed, id: id}, (response)=>{
                            if(response?.data !== null){
                                AppContent.removeClass('col-md-9');
                                AppContent.addClass('col-md-6');
                                AppDetails.show();

                                let Content = $(AppDetails.find('#details-content'));

                                Content.html('');
                                if(typed === 'file'){
                                    $(AppDetails.find('h6')).html('Detalles del archivo');
                                    Content.append($FormFileViewHTML);
                                }else{
                                    $(AppDetails.find('h6')).html('Detalles de la carpeta');
                                    Content.append($FormFolderViewHTML);
                                }
                                
                                for (const e in response?.data) {
                                    let input = $(Content.find('[name="'+e+'"]'));
                                    if(input){
                                        input.val(response?.data[e]);
                                    }
                                }
                            }

                        });
                    });

                    /*$('.app-render-item a[data-action="download"]').on('click', function () {
                        let id = $(this).attr('data-id');
                        let typed = $(this).attr('data-typed');
                        let action = $(this).attr('data-action');
                        alert(action + ' typed ' + typed +' id '+id);
                    });*/

                    $('.app-render-item a[data-action="rename"]').on('click', function () {
                        let id = $(this).attr('data-id');
                        let typed = $(this).attr('data-typed');
                        let action = $(this).attr('data-action');

                        let menu = $($(this).closest('.dropdown-menu'));

                        menu.removeClass('show');
                        handleContent({typed: typed, id: id}, (response)=>{
                            if(response?.data !== null){
                                AppContent.removeClass('col-md-9');
                                AppContent.addClass('col-md-6');
                                AppDetails.show();
                                let Content = $(AppDetails.find('#details-content'));


                                Content.html('');
                                if(typed === 'file'){
                                    $(AppDetails.find('h6')).html('Renombrar el archivo');
                                    Content.append($FormFileHTML);
                                    Content.append('<?=form_saved_and_cancel_html();?>');

                                    $(Content.find('a.btn-saved')).on('click', function(){
                                        $.ajax({
                                            url: '<?=site_url($this->slug.'/saved');?>',   // URL a la que enviar la solicitud
                                            method: 'POST',      // Método HTTP (POST, GET, etc.)
                                            data: {
                                                idfile: id,
                                                idaccount: response?.data?.idautor,
                                                title: $(Content.find('[name="title"]')).val(),
                                                slug: $(Content.find('[name="slug"]')).val(),
                                                content: $(Content.find('[name="content"]')).val(),
                                            },          // Datos a enviar en la solicitud (opcional)
                                            dataType: 'json'     // Tipo de datos esperados en la respuesta (opcional)
                                        }).done((response)=>{
                                            if(response?.status === 'success'){
                                                let $File = $(PanelList.find('div.app-render-item[data-file-id="'+id+'"]'));
                                                let $Container = $($File.closest('.col-xl-3.col-lg-4.col-md-6.col-sm-12'));

                                                $File.remove();
                                                $Container.append(response?.data);
                                                handleInit();
                                                AppClose.click();
                                                swal(response?.message, {icon: "success"});
                                            }else{
                                                let message = "";
                                                for (const e in response?.validator) {
                                                    let input = $(Content).find('input[name="'+e+'"]');
                                                    let label = $(Content).find('label[for="'+e+'"]');
                                                    message += (message === "" ? "": "\r") + response?.validator[e];
                                                    if(input){
                                                        label.attr('style', 'color:#E91E63');
                                                        input.attr('style', 'border-color: #E91E63;');
                                                    }
                                                }

                                                swal(message, {icon: "error"});
                                            }
                                        }).fail(function(jqXHR, textStatus, errorThrown) {
                                            //Callback para manejar errores
                                            console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
                                        });
                                    });
                                }else{
                                    $(AppDetails.find('h6')).html('Renombrar la carpeta');
                                    Content.append($FormFolderHTML);
                                    Content.append('<?=form_saved_and_cancel_html();?>');

                                    $(Content.find('a.btn-saved')).on('click', function(){
                                        $.ajax({
                                            url: '<?=site_url($this->slug.'/folder/saved');?>',   // URL a la que enviar la solicitud
                                            method: 'POST',      // Método HTTP (POST, GET, etc.)
                                            data: {
                                                idfolder: id,
                                                idaccount: response?.data?.idautor,
                                                title: $(Content.find('[name="title"]')).val(),
                                                content: $(Content.find('[name="content"]')).val(),
                                                path: response?.data?.path,
                                                sub: response?.data?.sub
                                            },          // Datos a enviar en la solicitud (opcional)
                                            dataType: 'json'     // Tipo de datos esperados en la respuesta (opcional)
                                        }).done((response)=>{
                                            if(response?.status === 'success'){
                                                let $Folder = $(PanelList.find('div.app-render-item[data-folder-id="'+id+'"]'));
                                                let $Container = $($Folder.closest('.col-xl-3.col-lg-4.col-md-6.col-sm-12'));

                                                $Folder.remove();
                                                $Container.append(response?.data);
                                                handleInit();
                                                AppClose.click();
                                                swal(response?.message, {icon: "success"});
                                            }else{
                                                let message = "";
                                                for (const e in response?.validator) {
                                                    let input = $(Content).find('input[name="'+e+'"]');
                                                    let label = $(Content).find('label[for="'+e+'"]');
                                                    message += (message === "" ? "": "\r") + response?.validator[e];
                                                    if(input){
                                                        label.attr('style', 'color:#E91E63');
                                                        input.attr('style', 'border-color: #E91E63;');
                                                    }
                                                }

                                                swal(message, {icon: "error"});
                                            }
                                        }).fail(function(jqXHR, textStatus, errorThrown) {
                                            //Callback para manejar errores
                                            console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
                                        });
                                    });
                                }

                                $(Content.find('a.btn-close')).on('click', function(){
                                    AppClose.click();
                                })
                                

                                for (const e in response?.data) {
                                    let input = $(Content.find('[name="'+e+'"]'));
                                    if(input){
                                        input.val(response?.data[e]);
                                    }
                                }
                            }
                        });
                    });

                    //handleTrash
                    $('.app-render-item a[data-action="trash"]').on('click', function () {
                        let id = $(this).attr('data-id');
                        let typed = $(this).attr('data-typed');
                        let action = $(this).attr('data-action');

                        let menu = $($(this).closest('.dropdown-menu'));
                        menu.removeClass('show');
                        //alert(action + ' typed ' + typed +' id '+id);

                        handleTrash({typed: typed, id: id}, (response)=>{
                            if(response?.status === 'success'){
                                if(response?.data !== null){
                                    let $Element = $(PanelList.find('div.app-render-item[data-'+typed+'-id="'+id+'"]'));
                                    let $Container = $($Element.closest('.col-xl-3.col-lg-4.col-md-6.col-sm-12'));

                                    $Container.remove();
                                    swal(response?.message, {icon: "success"});
                                }
                            }else{
                                swal(response?.message, {icon: "error"});
                            }
                        }, '/trash');
                    });

                    //handleRestore
                    $('.app-render-item a[data-action="restore"]').on('click', function () {
                        let id = $(this).attr('data-id');
                        let typed = $(this).attr('data-typed');
                        let action = $(this).attr('data-action');

                        let menu = $($(this).closest('.dropdown-menu'));
                        menu.removeClass('show');
                        //alert(action + ' typed ' + typed +' id '+id);

                        handleTrash({typed: typed, id: id}, (response)=>{
                            if(response?.status === 'success'){
                                if(response?.data !== null){
                                    let $Element = $(PanelList.find('div.app-render-item[data-'+typed+'-id="'+id+'"]'));
                                    let $Container = $($Element.closest('.col-xl-3.col-lg-4.col-md-6.col-sm-12'));

                                    $Container.remove();
                                    swal(response?.message, {icon: "success"});
                                }
                            }else{
                                swal(response?.message, {icon: "error"});
                            }
                        }, '/restore');
                    });

                    //handleDelete
                    $('.app-render-item a[data-action="delete"]').on('click', function () {
                        let id = $(this).attr('data-id');
                        let typed = $(this).attr('data-typed');
                        let action = $(this).attr('data-action');

                        let menu = $($(this).closest('.dropdown-menu'));
                        menu.removeClass('show');
                        //alert(action + ' typed ' + typed +' id '+id);

                        handleTrash({typed: typed, id: id}, (response)=>{
                            if(response?.status === 'success'){
                                if(response?.data !== null){
                                    let $Element = $(PanelList.find('div.app-render-item[data-'+typed+'-id="'+id+'"]'));
                                    let $Container = $($Element.closest('.col-xl-3.col-lg-4.col-md-6.col-sm-12'));

                                    $Container.remove();
                                    swal(response?.message, {icon: "success"});
                                }
                            }else{
                                swal(response?.message, {icon: "error"});
                            }
                        }, '/delete');
                    });
                }

                const ObjectUpload = (id, name) => {
                    return '<div id="file-upload-'+id+'" class="mb-2"><div class="mb-1 text-black-50">'+name+'</div><div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div></div></div>';
                }

                const handleChangeUploadProgress = (obj, progress) => {
                    let bar = $(obj.find('.progress-bar'));
                    bar.attr('style', 'width: '+progress+'%');
                    bar.attr('aria-valuenow', progress);
                    bar.html(progress+'%');

                    if(progress === 100){
                        bar.addClass('bg-success');
                    }
                }
                const handleSaved = (action, data, callback) => {
                    $.ajax({
                        url: action,   // URL a la que enviar la solicitud
                        method: 'POST',      // Método HTTP (POST, GET, etc.)
                        data: data,          // Datos a enviar en la solicitud (opcional)
                        dataType: 'json'     // Tipo de datos esperados en la respuesta (opcional)
                    }).done(callback).fail(function(jqXHR, textStatus, errorThrown) {
                        //Callback para manejar errores
                        console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
                    });
                }

                const handleUpload = (action, data, handleLoad, handleSuccess, handleError) => {
                    $.ajax({
                        url: action, // Reemplaza 'URL_DEL_CONTROLADOR' con la URL a tu controlador en CodeIgniter 4
                        type: 'POST',
                        data: data,
                        processData: false,
                        contentType: false,
                        xhr: function () {
                            var xhr = new XMLHttpRequest();
                            xhr.upload.addEventListener('progress', function (e) {
                                if (e.lengthComputable && handleLoad) {
                                    handleLoad(Math.round((e.loaded / e.total) * 100));
                                }
                            }, false);
                            return xhr;
                        },
                        success: handleSuccess,
                        error: handleError
                    });
                }

                Saved.on('click', function(){
                    let form = {path: '<?=$this->root;?>'};
                    
                    $(Folder.find('form input')).map(function() {
                        form[$(this).attr('name')] = $(this).val();
                    });

                    $(Folder.find('form select')).map(function() {
                        form[$(this).attr('name')] = $(this).val();
                    });

                    $(Folder.find('form textarea')).map(function() {
                        form[$(this).attr('name')] = $(this).val();
                    });

                    <?php if(!IS_NULL($this->Folder)){ ?>
                        form['sub'] = '<?=$this->Folder['idfolder'];?>';
                    <?php } ?>

                    handleSaved($(Folder.find('form')).attr('action'), form, (response)=>{
                        //console.log(response)
                        if(response?.status === 'success'){
                            if($('.folder-empty')){
                                $('.folder-empty').hide();
                            }
                            PanelList.append('<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">'+response?.data+'</div>');
                            handleInit();
                            $(Folder.find('button.close')).click();
                            swal(response?.message, {icon: "success"});
                        }else{
                            let message = "";
                            for (const e in response?.validator) {
                                let input = $(Folder).find('input[name="'+e+'"]');
                                let label = $(Folder).find('label[for="'+e+'"]');
                                message += (message === "" ? "": "\r") + response?.validator[e];
                                if(input){
                                    label.attr('style', 'color:#E91E63');
                                    input.attr('style', 'border-color: #E91E63;');
                                }
                            }

                            swal(message, {icon: "error"});
                        }
                    })
                })
                
                $('.file-upload-btn').on('click', function () {
                    $('form#file-upload input[type="file"]').trigger('click');
                });

                $('form#file-upload input[type="file"]').on('change', function () {
                    var files = $(this)[0].files;

                    for (var i = 0; i < files.length; i++) {
                        var formData = new FormData();
                        formData.append('file', files[i]);
                        <?php if(!IS_NULL($this->Folder)){ ?>
                            formData.append('idfolder', '<?=$this->Folder['idfolder'];?>');
                        <?php } ?>
                        PanelUpload.show();
                        PanelUploadList.append(ObjectUpload(i, files[i]?.name));
                        let ObjectPanelUpload = PanelUploadList.find('#file-upload-'+i);
                        handleUpload(formUpload.attr('action'), formData, (progress)=>{
                            handleChangeUploadProgress(ObjectPanelUpload, progress)
                        }, (response)=>{
                            if(response?.status === 'success'){
                                if($('.folder-empty')){
                                    $('.folder-empty').hide();
                                }
                                PanelList.append('<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">'+response?.data+'</div>');
                                handleInit();
                            }else{
                                swal(response?.message, {icon: "error"});
                            }
                        }, (xhr, status, error) => { 
                            console.log(error);
                        })
                    }
                });

                ButtonAddonSearch.on('click', function () {
                    $.ajax({
                        url: '<?=site_url($this->slug."/find");?>',   // URL a la que enviar la solicitud
                        method: 'POST',      // Método HTTP (POST, GET, etc.)
                        data: { search: InputAddonSearch.val() },          // Datos a enviar en la solicitud (opcional)
                        dataType: 'json'     // Tipo de datos esperados en la respuesta (opcional)
                    }).done((response)=>{
                        let result = (response?.data??[]);

                        PanelList.html('');
                        if(InputAddonSearch.val() !== ""){
                            PanelList.html('<div class="col-md-12 col-sm-12"><p class="text-black-50 text-left text-light">'+(result?.length === 0 ? '<?=_('No hay resultados disponibles para')?> <b>'+InputAddonSearch.val()+'</b>': '<b>'+result?.length+'</b> <?=_('resultados encontrados para')?> <b>'+InputAddonSearch.val()+'</b>')+'</p></div>');
                        }

                        result.forEach( (file) => {
                            PanelList.append('<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">'+file+'</div>');
                        });

                        handleInit();
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        //Callback para manejar errores
                        console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
                    });
                });

                handleInit();
            });
        </script>
        <?php return ob_get_clean();
    }

    public function restore()
    {
        if (!(session()->get('isLoggedIn'))) {
            return $this->response->setJSON([
                'data' => null,
                'validator' => null,
                'status' => 'error',
                'message' => _('Necesitas iniciar sesión nuevamente.')
            ]);
        }

        $Model =($this->request->getPost('typed') === 'file') ? $this->getModel() : (new \App\Models\FolderModel());

        //$Model->withDeleted();

        $Data = null;
        if($Model->recovery($this->request->getPost('id'))){

            $Data = $Model->where($Model->primaryKey(), $this->request->getPost('id'))->first();
            if(strlen(trim($this->request->getPost('id')))===0 || is_null($Data)){
                return $this->response->setJSON([
                    'data' => null,
                    'validator' => null,
                    'status' => 'error',
                    'message' => _('¡Este recurso no se pudo restaurar!')
                ]);
            }
        }

        return $this->response->setJSON([
            'data' => $Data,
            'validator' => null,
            'status' => 'success',
            'message' => _('¡El recurso fue restaurado con éxito.!')
        ]);
    }

    public function trash($id="")
    {
        if (!(session()->get('isLoggedIn'))) {
            return $this->response->setJSON([
                'data' => null,
                'validator' => null,
                'status' => 'error',
                'message' => _('Necesitas iniciar sesión nuevamente.')
            ]);
        }

        $Model =($this->request->getPost('typed') === 'file') ? $this->getModel() : (new \App\Models\FolderModel());

        $Data = $Model->where($Model->primaryKey(), $this->request->getPost('id'))->first();
        if(strlen(trim($this->request->getPost('id')))===0 || is_null($Data)){
            return $this->response->setJSON([
                'data' => null,
                'validator' => null,
                'status' => 'error',
                'message' => _('¡Este recurso no existe!')
            ]);
        }

        //$this->setValues($Data);
        $Model->delete($this->request->getPost('id'));

        return $this->response->setJSON([
            'data' => $Data,
            'validator' => null,
            'status' => 'success',
            'message' => _('¡El recurso ha sido enviado a la papelera!')
        ]);
    }

    public function delete()
    {
        if (!(session()->get('isLoggedIn'))) {
            return $this->response->setJSON([
                'data' => null,
                'validator' => null,
                'status' => 'error',
                'message' => _('Necesitas iniciar sesión nuevamente.')
            ]);
        }

        $Model =($this->request->getPost('typed') === 'file') ? $this->getModel() : (new \App\Models\FolderModel());
        $Model->withDeleted();

        $Data = $Model->where($Model->primaryKey(), $this->request->getPost('id'))->first();

        if(strlen(trim($this->request->getPost('id')))===0 || is_null($Data)){
            return $this->response->setJSON([
                'data' => null,
                'validator' => null,
                'status' => 'error',
                'message' => _('¡Este recurso no existe!')
            ]);
        }

        //$this->setValues($Data);
        
        if($Model->delete($this->request->getPost('id'), true)){
            if($this->request->getPost('typed') === 'file'){
                if (file_exists($Data['path'])) {
                    unlink($Data['path']);
                }
            }else{
                if($Model->deleteFolders($this->request->getPost('id'))){
                    $Model->deleteFiles($this->request->getPost('id'));
                    if (is_dir($Data['path'])) {
                        deleteAll($Data['path']);
                    }                    
                }
            }
        }

        return $this->response->setJSON([
            'data' => $Data,
            'validator' => null,
            'status' => 'success',
            'message' => _('¡El recurso ha sido eliminado exitosamente!')
        ]);
    }
}
