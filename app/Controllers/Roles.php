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
    protected $slug = 'role';

    public function index(): string
    {
        return view('welcome_message');
    }

    public function new(): string
    {
        $this->titlePage = "Nuevo rol";
        $this->description = "test casa";

        $this->setContent('Crear nuevo rol', 'texto de relleno');

        $this->addBreadcrumb($this->titlePage);
        return $this->View('role/edit');
    }

    public function edit(): string
    {
        return view('welcome_message');
    }

    protected function getModel(){
        return new \App\Models\RoleModel();
    }
}
