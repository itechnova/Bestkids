<?php

namespace App\Models;

use CodeIgniter\Model;

class FileModel extends Model
{
    protected $table      = 'files';
    protected $primaryKey = 'idfile';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['idaccount', 'idfolder', 'title', 'slug', 'content', 'path', 'mimetype', 'size'];

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

    public function recovery($id) {
        $this->allowedFields[] = $this->deletedField;
        $data = [];
        $data[$this->deletedField] = '0000-00-00 00:00:00';
        return $this->update($id, $data);
    }

    public function primaryKey() {
        return $this->primaryKey;
    }

    public function description() {
        return 'title';
    }

    public function Exists($Id){
        return $this->where($this->primaryKey, $Id)->first();

    }

    public function ExistsBy($key, $value){
        return $this->where($key, $value)->first();

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

        $User = User();

        foreach ($this->allowedFields as $field) {
            # code...
            if(isset($values[$field])){
                $_VALUES_[$field] = $values[$field];
                if($values[$field] === ""){
                    if($field === 'idaccount'){
                        if($User){
                            $_VALUES_[$field] = $User->idaccount;
                        }
                    }
                }

            }else{
                if($field === 'idaccount'){
                    if($User){
                        $_VALUES_[$field] = $User->idaccount;
                    }
                }
            }
        }

        return $_VALUES_;
    }

    public function getValidation(){
        return [
            'title'=>[
                'rules'=>'required|min_length[1]|max_length[24]',
                'errors'=>[
                    'required' => _('Nombre es requerido.'),
                    'min_length' => _('Nombre debe tener al menos 1 caracteres de longitud.'),
                    'max_length' => _('Nombre no debe tener más de 24 caracteres de longitud.')
                ]
            ],
            'slug'=>[
                'rules'=>'required',
                'errors'=>[
                    'required' => _('El enlace permanente es requerido.')
                ]
            ],
        ];
    }

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
                        'label' => 'Nombre del archivo',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa nombre',
                        'required' => true
                    );
                    break;
                case 'slug':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Enlace permanente',
                        'type' => 'slug',
                        'placeholder'=> 'Ingresa enlace permanente',
                        'required' => true
                    );
                    break;                                                              
                case 'content':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Descripción del archivo',
                        'type' => 'textarea',
                        'placeholder'=> 'Ingresa descripción',
                    );
                    break;
                default:
                    # code...
                    /*$AllFields[] = (Object) array(
                        'name' => $field,
                        'type' => 'text'
                    );*/
                    break;
            }
            
        }

        $AllFields[] = (Object) array(
            'name' => 'idaccount',
            'label' => 'Autor',
            'type' => 'view',
            'required' => false
        );

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