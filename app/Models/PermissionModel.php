<?php

namespace App\Models;

use CodeIgniter\Model;

class PermissionModel extends Model
{
    protected $table      = 'permissions';
    protected $primaryKey = 'idpermission';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['idjoin', 'permission', 'name', 'access_index',	'access_new', 'access_view', 'access_edit', 'access_trash'];

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

    public function description() {
        return 'name';
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
                if($field === 'access_index' || $field === 'access_new' || $field === 'access_view' || $field === 'access_edit' ||	$field === 'access_trash'){
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
            'name'=>[
                'rules'=>'required|min_length[5]|max_length[24]',
                'errors'=>[
                    'required' => _('Nombre es requerido.'),
                    'min_length' => _('Nombre debe tener al menos 5 caracteres de longitud.'),
                    'max_length' => _('Nombre no debe tener más de 24 caracteres de longitud.')
                ]
            ]/*,
            'level' => [
                'rules' => 'required|numeric|greater_than_equal_to[1]|less_than_equal_to[100]',
                'errors' => [
                    'required' => _('El nivel es requerido.'),
                    'numeric' => _('El nivel debe ser un número.'),
                    'greater_than_equal_to' => _('El nivel debe ser igual o mayor que 1.'),
                    'less_than_equal_to' => _('El nivel debe ser igual o menor que 100.')
                ]
            ]*/
        ];
    }

    public function getFields(){
        $fields = [];
        
        $AllFields = [];

        $AllFields[] = (Object) array(
            'name' => $this->primaryKey,
            'type' => 'hidden'
        );

        $AllFields[] = (Object) array(
            'name' => 'idjoin',
            'type' => 'hidden'
        );

        $AllFields[] = (Object) array(
            'name' => 'permission',
            'type' => 'hidden'
        );

        foreach ($this->allowedFields as $field) {
            # code...
            switch ($field) {
                case 'name':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Permiso',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa nombre del permiso',
                        'required' => true
                    );
                    break;
                case 'access_index':
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Visualizar',
                        'type' => 'switch'
                    );
                    break;
                case 'access_new':
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Agregar',
                        'type' => 'switch'
                    );
                    break;
                case 'access_view':
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Detalles',
                        'type' => 'switch'
                    );
                    break;
                case 'access_edit':
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Editar',
                        'type' => 'switch'
                    );
                    break;
                case 'access_trash':
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Eliminar',
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