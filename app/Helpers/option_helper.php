<?php

if (!function_exists('SETTING')) {
    function SETTING($name = "", $groups = "") {
        $Model = new \App\Models\SettingModel();
        if($name !== ""){
            $Model->where('name', $name);
        }
        $Setting = $Model->first();

        if(is_null($Setting)){
            if($groups !== "" && $name !== ""){
                $Setting = [
                    'groups' => $groups, 
                    'name' => $name,
                ];
                if($Model->insert($Setting)){
                    $Setting['idsetting'] = $Model->getInsertID();
                    $Setting['value'] = null;
                    return (Object) $Setting;
                }
            }
        }else{
            return (Object) $Setting;
        }
        return false;
    }
}

if (!function_exists('setAuthentication')) {
    function setAuthentication($user = false) {
        if($user){
            $session = session();

            $Role = new \App\Models\RoleModel();
            $Permission = new \App\Models\PermissionModel();
            $Menu = new \App\Models\MenuModel();

            $user['fullname'] = $user['name']." ".$user['surname'];
            $user['role'] = (Object) $Role->where('idrole', $user['idrole'])->first();
            $user['access'] = (Object) $Permission->where('idjoin', $user['idrole'])->where('permission', 'role')->first();
            $user['menu'] = $Menu->where('enabled', 1)->where('level <=', $user['role']->level)->findAll();
            $session->set('isLoggedIn', true);
            $session->set('userData', $user);
        }
    }
}

if (!function_exists('User')) {
    function User() {
        if (!(session()->get('isLoggedIn'))) {
            return false;
        }
        return (Object) (session()->get('userData'));
    }
}

if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    
        $pow = floor(log($bytes, 1024));
        $pow = min($pow, count($units) - 1);
    
        $bytes /= (1 << (10 * $pow));
    
        return round($bytes, $precision) . ' ' . $units[$pow];
    
    }
}

if (!function_exists('folderSize')) {
    function folderSize($dir){
        $size = 0;
        // Abre el directorio y lee su contenido
        if($dir!=="" && !is_null($dir)){
            foreach(glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each){
                // Suma el tamaño si es un archivo
                if(is_file($each)) {
                    $size += filesize($each);
                } elseif(is_dir($each)) {
                    // Llama recursivamente si es un directorio
                    $size += folderSize($each);
                }
            }            
        }

        return $size;
    }
}

if (!function_exists('viewFile')) {
    function viewFile($file) {
        $view = (Object) array(
            'class' => 'fa fa-3x fa-file text-secondary',
            'target'=> 'icon',
            'size'=> formatBytes(0)
        );
        if(property_exists($file, 'mimetype')){
            $extension = explode('/', $file->mimetype);
            $extension = isset($extension[1]) ? $extension[1] : $extension[0];
            switch ($extension) {
                case 'pdf':
                    $view = (Object) array(
                        'class' => 'fa fa-3x fa-file-pdf-o text-danger',
                        'target'=> 'icon',
                        'size'=> formatBytes($file->size)
                    );
                    break;
                case 'xls':
                case 'xlsx':
                case 'vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                case 'xlsm':
                case 'xlsb':
                case 'xltx':
                case 'xltm':
                    $view = (Object) array(
                        'class' => 'fa fa-3x fa-file-excel-o text-success',
                        'target'=> 'icon',
                        'size'=> formatBytes($file->size)
                    );
                    break;
                case 'zip':
                case 'rar':
                    $view = (Object) array(
                        'class' => 'fa fa-3x fa-file-zip-o text-primary',
                        'target'=> 'icon',
                        'size'=> formatBytes($file->size)
                    );
                    break;
                case 'doc':
                case 'docx':
                case 'dotx':
                case 'docm':
                case 'dotm':
                case 'vnd.openxmlformats-officedocument.wordprocessingml.document':
                    $view = (Object) array(
                        'class' => 'fa fa-3x fa-file-word-o text-info',
                        'target'=> 'icon',
                        'size'=> formatBytes($file->size)
                    );
                    break;
                case 'txt':
                    $view = (Object) array(
                        'class' => 'fa fa-3x fa-file-text-o text-warning',
                        'target'=> 'icon',
                        'size'=> formatBytes($file->size)
                    );
                    break;
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'svg':
                    $view = (Object) array(
                        'class' => '',
                        'target'=> 'image',
                        'size'=> formatBytes($file->size)
                    );
                    break;
                case 'mp4':
                    $view = (Object) array(
                        'class' => 'fa fa-3x fa-video-camera text-linkedin',
                        'target'=> 'icon',
                        'size'=> formatBytes($file->size)
                    );
                    break;
                case 'ppt':
                case 'pptx':
                    $view = (Object) array(
                        'class' => 'fa fa-3x fa-file-powerpoint-o text-secondary',
                        'target'=> 'icon',
                        'size'=> formatBytes($file->size)
                    );
                    break;
                case 'mp3':
                    $view = (Object) array(
                        'class' => 'fa fa-3x fa-music text-danger',
                        'target'=> 'icon',
                        'size'=> formatBytes($file->size)
                    );
                    break;
                default:
                    break;
            }
        }else{
            if(property_exists($file, 'idfolder')){
                $view = (Object) array(
                    'class' => 'fa fa-3x fa-folder text-warning',
                    'target'=> 'folder',
                    'size'=> formatBytes(folderSize($file->path))
                );
            }            
        }
        return $view;
    }
}

if (!function_exists('render_item_file')) {
    function render_item_file($file, $recycle = false){
        $render = viewFile($file);
        
        $render_html ="";
        $render_html .= '<div class="card app-file-list app-render-item" data-'.(property_exists($file, 'idfile') ? 'file': 'folder').'-id="'.(property_exists($file, 'idfile') ? $file->idfile: $file->idfolder).'">';
        $render_html .= '<div class="app-file-icon'.($render->target === 'image'?' overflow-hidden':'').'"'.($render->target === 'image'?' style="background: url('.site_url('file/'.(property_exists($file, 'slug')? $file->slug: '')).');background-size: cover;background-position: center;background-repeat: no-repeat;"':'').'>';
        if($render->target === 'icon' || $render->target === 'folder') {
            if($render->target === 'folder'){
                $render_html .= '<a href="'.site_url('dashboard/file-manager/folder/'.$file->idfolder).'" class="cursor-pointer">';
                $render_html .= '<i class="'.$render->class.' cursor-pointer"></i>';
                $render_html .= '</a>';
            } else {
                $render_html .= '<i class="'.$render->class.'"></i>';
            }
        }
        if($render->target === 'image') {
            //$render_html .= '<img src="https://via.placeholder.com/512X512" class="w-100" alt="image">';
            $render_html .= '<i class="fa fa-3x fa-image" style="opacity: 0;"></i>';
        }
        $render_html .= '<div class="dropdown position-absolute top-0 right-0 mr-3">';
        $render_html .= '<a href="#" class="font-size-14" data-toggle="dropdown">';
        $render_html .= '<i class="fa fa-ellipsis-v"></i>';
        $render_html .= '</a>';
        $render_html .= '<div class="dropdown-menu dropdown-menu-right">';
        if(!$recycle){
            $render_html .= '<a href="javascript:void(0)" class="dropdown-item" data-action="details" data-typed="'.(property_exists($file, 'idfile') ? 'file': 'folder').'" data-id="'.(property_exists($file, 'idfile') ? $file->idfile: $file->idfolder).'"><i class="fa fa-eye mr-2"></i>'._('Vér detalles').'</a>';
            if($render->target !== 'folder'){
                $render_html .= '<a href="'.site_url('/download/'.$file->slug).'" target="_blank" class="dropdown-item" data-action="download" data-typed="'.(property_exists($file, 'idfile') ? 'file': 'folder').'" data-id="'.(property_exists($file, 'idfile') ? $file->idfile: $file->idfolder).'"><i class="fa fa-download mr-2"></i>'._('Descargar').'</a>';
            }
            //$render_html .= '<a href="javascript:void(0)" class="dropdown-item" data-action="details" data-typed="'.(property_exists($file, 'idfile') ? 'file': 'folder').'" data-id="'.(property_exists($file, 'idfile') ? $file->idfile: $file->idfolder).'">'._('Mover').'</a>';
            $render_html .= '<a href="javascript:void(0)" class="dropdown-item" data-action="rename" data-typed="'.(property_exists($file, 'idfile') ? 'file': 'folder').'" data-id="'.(property_exists($file, 'idfile') ? $file->idfile: $file->idfolder).'"><i class="fa fa-edit mr-2"></i>'._('Renombrar').'</a>';
            $render_html .= '<a href="javascript:void(0)" class="dropdown-item" data-action="trash" data-typed="'.(property_exists($file, 'idfile') ? 'file': 'folder').'" data-id="'.(property_exists($file, 'idfile') ? $file->idfile: $file->idfolder).'"><i class="fa fa-trash mr-2"></i>'._('Enviar a papelera').'</a>';
        }else{
            $render_html .= '<a href="javascript:void(0)" class="dropdown-item" data-action="restore" data-typed="'.(property_exists($file, 'idfile') ? 'file': 'folder').'" data-id="'.(property_exists($file, 'idfile') ? $file->idfile: $file->idfolder).'"><i class="fa fa-edit mr-2"></i>'._('Restaurar').'</a>';
            $render_html .= '<a href="javascript:void(0)" class="dropdown-item" data-action="delete" data-typed="'.(property_exists($file, 'idfile') ? 'file': 'folder').'" data-id="'.(property_exists($file, 'idfile') ? $file->idfile: $file->idfolder).'"><i class="fa fa-trash mr-2"></i>'._('Eliminar permanentemente').'</a>';
        }
        $render_html .= '</div>';
        $render_html .= '</div>';
        $render_html .= '</div>';
        $render_html .= '<div class="p-2 small">';
        if($render->target === 'folder'){
            $render_html .= '<div class="btn-link cursor-pointer"><a href="'.site_url('dashboard/file-manager/folder/'.$file->idfolder).'" class="text-primary">'.$file->title.'</a></div>';
        } else {
            $render_html .= '<div style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">'.$file->title.'</div>';
        }
        $render_html .= '<div class="text-muted">'.$render->size.'</div>';
        $render_html .= '</div>';
        $render_html .= '</div>';
        return $render_html;
    }
}

if (!function_exists('all_file_manager')) {
    function all_file_manager(){
        $Model = new \App\Models\FileModel();
        $User = User();

        if($User){
            return $Model->where('idaccount', $User->idaccount)->findAll();
        }

        return [];
    }
}

if (!function_exists('render_item_file_view')) {
    function render_item_file_view($file, $recycle = false){
        $render = viewFile($file);
        
        $render_html ="";
        $render_html .= '<div class="card app-file-list app-render-item" data-'.(property_exists($file, 'idfile') ? 'file': 'folder').'-id="'.(property_exists($file, 'idfile') ? $file->idfile: $file->idfolder).'" style="position: relative;">';
        $render_html .= '<div class="app-file-icon'.($render->target === 'image'?' overflow-hidden':'').'"'.($render->target === 'image'?' style="background: url('.site_url('file/'.(property_exists($file, 'slug')? $file->slug: '')).');background-size: cover;background-position: center;background-repeat: no-repeat;"':'').'>';
        if($render->target === 'icon' || $render->target === 'folder') {
            if($render->target === 'folder'){
                $render_html .= '<a href="'.site_url('dashboard/file-manager/folder/'.$file->idfolder).'" class="cursor-pointer">';
                $render_html .= '<i class="'.$render->class.' cursor-pointer"></i>';
                $render_html .= '</a>';
            } else {
                $render_html .= '<i class="'.$render->class.'"></i>';
            }
        }
        if($render->target === 'image') {
            //$render_html .= '<img src="https://via.placeholder.com/512X512" class="w-100" alt="image">';
            $render_html .= '<i class="fa fa-3x fa-image" style="opacity: 0;"></i>';
        }
        //$render_html .= '<div class="dropdown position-absolute top-0 right-0 mr-3">';
        //$render_html .= '<a href="#" class="font-size-14" data-toggle="dropdown">';
        //$render_html .= '<i class="fa fa-ellipsis-v"></i>';
        //$render_html .= '</a>';
        //$render_html .= '<div class="dropdown-menu dropdown-menu-right">';
        /*if(!$recycle){
            $render_html .= '<a href="javascript:void(0)" class="dropdown-item" data-action="details" data-typed="'.(property_exists($file, 'idfile') ? 'file': 'folder').'" data-id="'.(property_exists($file, 'idfile') ? $file->idfile: $file->idfolder).'"><i class="fa fa-eye mr-2"></i>'._('Vér detalles').'</a>';
            if($render->target !== 'folder'){
                $render_html .= '<a href="'.site_url('/download/'.$file->slug).'" target="_blank" class="dropdown-item" data-action="download" data-typed="'.(property_exists($file, 'idfile') ? 'file': 'folder').'" data-id="'.(property_exists($file, 'idfile') ? $file->idfile: $file->idfolder).'"><i class="fa fa-download mr-2"></i>'._('Descargar').'</a>';
            }
            $render_html .= '<a href="javascript:void(0)" class="dropdown-item" data-action="rename" data-typed="'.(property_exists($file, 'idfile') ? 'file': 'folder').'" data-id="'.(property_exists($file, 'idfile') ? $file->idfile: $file->idfolder).'"><i class="fa fa-edit mr-2"></i>'._('Renombrar').'</a>';
            $render_html .= '<a href="javascript:void(0)" class="dropdown-item" data-action="trash" data-typed="'.(property_exists($file, 'idfile') ? 'file': 'folder').'" data-id="'.(property_exists($file, 'idfile') ? $file->idfile: $file->idfolder).'"><i class="fa fa-trash mr-2"></i>'._('Enviar a papelera').'</a>';
        }else{
            $render_html .= '<a href="javascript:void(0)" class="dropdown-item" data-action="restore" data-typed="'.(property_exists($file, 'idfile') ? 'file': 'folder').'" data-id="'.(property_exists($file, 'idfile') ? $file->idfile: $file->idfolder).'"><i class="fa fa-edit mr-2"></i>'._('Restaurar').'</a>';
            $render_html .= '<a href="javascript:void(0)" class="dropdown-item" data-action="delete" data-typed="'.(property_exists($file, 'idfile') ? 'file': 'folder').'" data-id="'.(property_exists($file, 'idfile') ? $file->idfile: $file->idfolder).'"><i class="fa fa-trash mr-2"></i>'._('Eliminar permanentemente').'</a>';
        }*/
        //$render_html .= '</div>';
        //$render_html .= '</div>';
        $render_html .= '</div>';
        $render_html .= '<div class="p-2 small">';
        if($render->target === 'folder'){
            $render_html .= '<div class="btn-link cursor-pointer"><a href="'.site_url('dashboard/file-manager/folder/'.$file->idfolder).'" class="text-primary">'.$file->title.'</a></div>';
        } else {
            $render_html .= '<div style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">'.$file->title.'</div>';
        }
        $render_html .= '<div class="text-muted">'.$render->size.'</div>';
        $render_html .= '</div>';
        $render_html .= '<div class="file-manager-item"><a href="javascript:void(0)" class="btn btn-primary btn-sm" data-id="'.(property_exists($file, 'idfile') ? $file->idfile: 'none').'" style="color: #fff"><i class="ti-check mr-2"></i>'._('Seleccionar').'</a></div>';
        $render_html .= '</div>';
        return $render_html;
    }
}