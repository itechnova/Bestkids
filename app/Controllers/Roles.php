<?php

namespace App\Controllers;

class Roles extends BaseController
{

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $title = 'Roles';
        
    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $slug = 'dashboard/role';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewEdit = 'role/edit';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $viewList = 'role/list';

    /**
     * An array of helpers to be loaded automatically upon
     *
     * @var array
     */
    protected function viewContent(): Object
    {
        return (Object) array(
            'new' => ((Object) array(
                'titlePage' => 'Nuevo rol',
                'description' => 'Crear nuevo rol',
                'title' => 'Crear nuevo rol',
                'content' => 'Rellena los datos del formulario.'
            )),
            'edit' => ((Object) array(
                'titlePage' => 'Editar rol',
                'description' => 'Editar rol',
                'title' => 'Editar rol',
                'content' => 'Cambia los datos del formulario.'
            )),
            'list' => ((Object) array(
                'titlePage' => 'Roles',
                'description' => 'Lista de roles',
                'title' => 'Lista de roles',
                'content' => 'Roles disponibles'
            ))
        );
    }

    protected function getModel(){
        return new \App\Models\RoleModel();
    }

}
