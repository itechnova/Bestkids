<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table      = 'roles';
    protected $primaryKey = 'idrole';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['title', 'description', 'level', 'enabled'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
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

    public function getFields(){
        $fields = [];
        
        $AllFields = [];

        $AllFields[] = (Object) array(
            'name' => $this->primaryKey,
            'type' => 'hidden'
        );

        foreach ($this->allowedFields as $field) {
            # code...
            switch ($field) {
                case 'title':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Nombre del rol',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa nombre del rol',
                        'required' => true
                    );
                    break;
                case 'description':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Descripción del rol',
                        'type' => 'textarea',
                        'placeholder'=> 'Ingresa descripción del rol',
                    );
                    break;
                case 'level':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Nivel del rol',
                        'type' => 'number',
                        'placeholder'=> 'Ingresa nivel del rol',
                        'required' => true
                    );
                    break;
                case 'enabled':
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Habilitar rol',
                        'type' => 'switch'                    
                    );
                    break;
                default:
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'type' => 'text'
                    );
                    break;
            }
            
        }

        $AllFields[] = (Object) array(
            'name' => $this->createdField,
            'label' => 'Creado',
            'type' => 'view',
            'required' => false
        );

        $AllFields[] = (Object) array(
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