<?php

namespace App\Models;

use CodeIgniter\Model;

class EntityModel extends Model
{
    protected $table      = 'entitys';
    protected $primaryKey = 'identity';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['idtaxonomy', 'idaccount', 'title', 'slug', 'content', 'parent', 'status', 'enabled'];

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

        $User = User();

        foreach ($this->allowedFields as $field) {
            # code...
           
            if(isset($values[$field])){
                if($field === 'enabled'){
                    $_VALUES_[$field] = $values[$field] === "on" ? 1: 0;
                }else{
                    if($field === 'slug'){
                        $_VALUES_[$field] = permanentLink($values[$field]!==""?$values[$field]:$values['title']);
                    }else{
                        if($field === 'idaccount' && $User && $values[$field] === ""){
                            $_VALUES_[$field] = $User->idaccount;
                        }else{
                            $_VALUES_[$field] = $values[$field];
                        }
                    }
                }
            }else{
                if($field === 'slug' && isset($values['title'])){
                    if($values['title']!==""){
                        $_VALUES_[$field] = permanentLink($values['title']);
                    }
                }

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
                        'placeholder'=> 'Ingresa enlace permanente'                    
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

    public function getMetas(){
        return new EntityMetaModel();
    }

    public function getMeta($IdEntity){
        $Model = new EntityMetaModel();
        $ModelField = new FieldModel();
        $_VALUES_ = [];
        foreach ($Model->where('identity', $IdEntity)->findAll() as $Meta) {
            $Field = $ModelField->Exists($Meta['idfield']);

            if(!IS_NULL($Field)){
                $_VALUES_[$Field['name']] = $Meta['value'];
            }
        }

        return $_VALUES_;
    }

    public function saved($data, $IdEntity){
        $Model = $this->getMetas();
        foreach ($data as $key => $value) {
            if(isset($data["field_dynamic_".$key])){
                $IdField = $data["field_dynamic_".$key];
                $Meta = $Model->where('identity', $IdEntity)->where('idfield', $IdField)->first();
                if(IS_NULL($Meta)){
                    $User = User();
                    $Model->insert([
                        'identity' => $IdEntity,
                        'idaccount' => $User->idaccount,
                        'idfield' => $IdField,
                        'value' => $value
                    ]);
                }else{
                    $Model->update($Meta['idmeta'], ['value' => $value]);
                }
            }
        }
    }
}