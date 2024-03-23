<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table      = 'menus';
    protected $primaryKey = 'idmenu';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['sub', 'menu', 'icon', 'title', 'href', 'target', 'level', 'enabled'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function primaryKey() {
        return $this->primaryKey;
    }
    
    public function description() {
        return 'title';
    }

    public function Exists($Id){
        return $this->where($this->primaryKey, $Id)->first();

    }

    public function getID($values){
        if(isset($values[$this->primaryKey])){
            if($values[$this->primaryKey] !== ""){
                return $values[$this->primaryKey];
            }
        }
        return false;
    }

    public function setValues($values){
        $_VALUES_ = [];
        if(isset($values[$this->primaryKey])){
            $_VALUES_[$this->primaryKey] = $values[$this->primaryKey];
        }

        foreach ($this->allowedFields as $field) {
            # code...
            if(isset($values[$field])){
                if($field === 'enabled'){
                    $_VALUES_[$field] = $values[$field] === "on" ? 1: 0;
                }else{
                    $_VALUES_[$field] = $values[$field];
                }
            }
        }

        return $_VALUES_;
    }

    public function getValidation(){
        return [
            'menu'=>[
                'rules'=>'required',
                'errors'=>[
                    'required' => _('Tipo es requerido.')
                ]
            ],
            'title'=>[
                'rules'=>'required|min_length[2]|max_length[24]',
                'errors'=>[
                    'required' => _('Título es requerido.'),
                    'min_length' => _('Título debe tener al menos 2 caracteres de longitud.'),
                    'max_length' => _('Título no debe tener más de 24 caracteres de longitud.')
                ]
            ],
            'level' => [
                'rules' => 'required|numeric|greater_than_equal_to[1]|less_than_equal_to[100]',
                'errors' => [
                    'required' => _('El nivel es requerido.'),
                    'numeric' => _('El nivel debe ser un número.'),
                    'greater_than_equal_to' => _('El nivel debe ser igual o mayor que 1.'),
                    'less_than_equal_to' => _('El nivel debe ser igual o menor que 100.')
                ]
            ]
        ];
    }

    public function getFields(){
        $fields = [];
        
        $AllFields = [];

        $AllFields[$this->primaryKey] = (Object) array(
            'name' => $this->primaryKey,
            'type' => 'hidden'
        );

        $AllFields['sub'] = (Object) array(
            'name' => 'sub',
            'type' => 'hidden'
        );

        foreach ($this->allowedFields as $field) {
            # code...
            switch ($field) {
                case 'menu':
                    # code...
                    $AllFields[$field] = (Object) array(
                        'class' => 'new-field-menu menu-required',
                        'name' => $field,
                        'label' => 'Tipo',
                        'type' => 'select',
                        'options'=>[
                            'menu'=>'Menú',
                            'divider'=>'Separador'
                        ],
                        'placeholder'=> 'Seleccione',
                        'required' => true
                    );
                    break;
                case 'icon':
                    # code...
                    $AllFields[$field] = (Object) array(
                        'class' => 'new-field-menu',
                        'name' => $field,
                        'label' => 'Icono',
                        'type' => 'select',
                        'options'=> \App\Libraries\Feather::getNames(),
                        'placeholder'=> 'Seleccione'
                    );
                    break;
                case 'title':
                    # code...
                    $AllFields[$field] = (Object) array(
                        'class' => 'new-field-menu',
                        'name' => $field,
                        'label' => 'Título',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa título',
                        'required' => true
                    );
                    break;
                case 'href':
                    # code...
                    $AllFields[$field] = (Object) array(
                        'class' => 'new-field-menu',
                        'name' => $field,
                        'label' => 'Ruta',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa ruta',
                    );
                    break;
                case 'target':
                    # code...
                    $AllFields[$field] = (Object) array(
                        'class' => 'new-field-menu',
                        'name' => $field,
                        'label' => 'Abrir',
                        'type' => 'select',
                        'options'=>[
                            '_self'=>_('Misma ventana'),
                            '_blank'=>_('Nueva ventana'),
                        ],
                        'placeholder'=> 'Seleccione',
                    );
                    break;
                case 'level':
                    # code...
                    $AllFields[$field] = (Object) array(
                        'class' => 'new-field-menu',
                        'name' => $field,
                        'label' => 'Nivel de permisos',
                        'type' => 'number',
                        'placeholder'=> 'Ingresa nivel',
                        'required' => true
                    );
                    break;
                case 'enabled':
                    $AllFields[$field] = (Object) array(
                        'class' => 'mt-4 mb-0',
                        'name' => $field,
                        'label' => 'Habilitar',
                        'type' => 'switch'                    
                    );
                    break;
                default:
                    # code...
                    $AllFields[$field] = (Object) array(
                        'class' => 'new-field-menu',
                        'name' => $field,
                        'type' => 'text'
                    );
                    break;
            }
            
        }

        $AllFields[$this->createdField] = (Object) array(
            'class' => 'new-field-menu',
            'name' => $this->createdField,
            'label' => 'Creado',
            'type' => 'view',
            'required' => false
        );

        $AllFields[$this->updatedField] = (Object) array(
            'class' => 'new-field-menu',
            'name' => $this->updatedField,
            'label' => 'Actualizado',
            'type' => 'view',
            'required' => false
        );

        return $AllFields;
    }

    public function isDeleted($values){
        if(!isset($values[$this->primaryKey])){
            return false;
        }

        if($values[$this->primaryKey]==="" || !$values[$this->primaryKey] || IS_NULL($values[$this->primaryKey])){
            return false;
        }

        return true;
    }
}