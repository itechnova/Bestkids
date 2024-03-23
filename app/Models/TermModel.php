<?php

namespace App\Models;

use CodeIgniter\Model;

class TermModel extends Model
{
    protected $table      = 'terms';
    protected $primaryKey = 'idterm';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['idtaxonomy', 'title', 'slug', 'content', 'parent', 'status', 'enabled'];

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

    public function ExistsBySLug($slug){
        return $this->where('slug', $slug)->first();

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
            'slug'=>[
                'rules'=>'required',
                'errors'=>[
                    'required' => _('El enlace permanente es requerido.')
                ]
            ],
            'title'=>[
                'rules'=>'required|min_length[3]|max_length[24]',
                'errors'=>[
                    'required' => _('Título es requerido.'),
                    'min_length' => _('Título debe tener al menos 3 caracteres de longitud.'),
                    'max_length' => _('Título no debe tener más de 24 caracteres de longitud.')
                ]
            ],
            'status' => [
                'rules' => 'required',
                'errors' => [
                    'required' => _('El estatus es requerido.')
                ]
            ]
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
                case 'idtaxonomy': 
                    $AllFields[$field] = (Object) array(
                        'name' => 'idtaxonomy',
                        'type' => 'hidden'
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
                case 'title':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Título',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa título',
                        'required' => true
                    );
                    break;
                case 'content':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Descripción',
                        'type' => 'textarea',
                        'placeholder'=> 'Ingresa descripción',
                    );
                    break;
                case 'status':
                    # code...
                    $AllFields[$field] = (Object) array(
                        'name' => $field,
                        'label' => 'Estatus',
                        'type' => 'select',
                        'options'=>[
                            'publish'=>_('Público'),
                            'private'=>_('Privado'),
                        ],
                        'placeholder'=> 'Seleccione',
                        'required' => true
                    );
                    break;
                case 'enabled':
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Habilitar',
                        'type' => 'switch'                    
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

    public function getMetas(){
        return new TermMetaModel();
    }
}