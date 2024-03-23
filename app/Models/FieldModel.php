<?php

namespace App\Models;

use CodeIgniter\Model;

class FieldModel extends Model
{
    protected $table      = 'fields';
    protected $primaryKey = 'idfield';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['idtaxonomy', 'typefield', 'name', 'label', 'placeholder', 'default_value', 'options', 'orderby', 'class', 'cols', 'required', 'enabled', 'tabled'];

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
                if($field === 'enabled' || $field === 'tabled' || $field === 'required'){
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
                'rules'=>'required|min_length[3]|max_length[30]',
                'errors'=>[
                    'required' => _('El nombre es requerido.'),
                    'min_length' => _('El nombre debe tener al menos 3 caracteres de longitud.'),
                    'max_length' => _('El nombre no debe tener más de 30 caracteres de longitud.')
                ]
            ],            
            'typefield'=>[
                'rules'=>'required',
                'errors'=>[
                    'required' => _('El tipo es requerido.'),
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

        foreach ($this->allowedFields as $field) {
            # code...
            switch ($field) {
                case 'idtaxonomy': 
                    $AllFields[$field] = (Object) array(
                        'name' => 'idtaxonomy',
                        'type' => 'hidden'
                    );
                    break;
                case 'typefield':
                    $AllFields[$field] = (Object) array(
                        'name' => $field,
                        'label' => 'Tipo',
                        'type' => 'select',
                        'class' => 'model-required',
                        'options'=>[
                            'text'=>_('Texto'),
                            'number'=>_('Número'),
                            'email'=>_('Correo'),
                            'tel'=>_('Teléfono'),
                            'password'=>_('Contraseña'),
                            'select'=>_('Seleccionable'),
                            'textarea'=>_('Área de texto'),
                            'file'=>_('Archivos'),
                            'date'=>_('Fecha'),
                        ],
                        'placeholder'=> 'Seleccione',
                        'required' => true
                    );
                    break;
                case 'name':
                    # code...
                    $AllFields[$field] = (Object) array(
                        'name' => $field,
                        'label' => 'Nombre',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa nombre',
                        'required' => true
                    );
                    break;
                case 'label':
                    # code...
                    $AllFields[$field] = (Object) array(
                        'name' => $field,
                        'label' => 'Etiqueta',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa etiqueta',
                    );
                    break;
                case 'placeholder':
                    # code...
                    $AllFields[$field] = (Object) array(
                        'name' => $field,
                        'label' => 'Placeholder',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa placeholder',
                    );
                    break;
                case 'default_value':
                    # code...
                    $AllFields[$field] = (Object) array(
                        'name' => $field,
                        'label' => 'Valor',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa valor',
                    );
                    break;
                case 'options':
                    # code...
                    $AllFields[$field] = (Object) array(
                        'name' => $field,
                        'label' => 'Opciones',
                        'type' => 'textarea',
                        'placeholder'=> 'Ej: Modelo:column1=value&column2=value..',
                    );
                    break;
                case 'orderby':
                    # code...
                    $AllFields[$field] = (Object) array(
                        'name' => $field,
                        'label' => 'Orden',
                        'type' => 'number',
                        'placeholder'=> 'Ingresa orden',
                    );
                    break;
                case 'class':
                    # code...
                    $AllFields[$field] = (Object) array(
                        'name' => $field,
                        'label' => 'Clases',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa clases',
                    );
                    break;
                case 'cols':
                    # code...
                    $AllFields[$field] = (Object) array(
                        'name' => $field,
                        'label' => 'Columnas',
                        'type' => 'text',
                        'placeholder'=> 'Ej: sm:value&md:value&lg:value&xl:value',
                    );
                    break;
                case 'tabled':
                    $AllFields[$field] = (Object) array(
                        'name' => $field,
                        'label' => 'Columna',
                        'type' => 'switch'                    
                    );
                    break;
                case 'required':
                    $AllFields[$field] = (Object) array(
                        'name' => $field,
                        'label' => 'Obligatorio',
                        'type' => 'switch'                    
                    );
                    break;
                case 'enabled':
                    $AllFields[$field] = (Object) array(
                        'name' => $field,
                        'label' => 'Habilitar',
                        'type' => 'switch'                    
                    );
                    break;
                default:
                    # code...
                    /*$AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => $field,
                        'type' => 'text'
                    );*/
                    break;
            }
            
        }

        $AllFields[$this->createdField] = (Object) array(
            'name' => $this->createdField,
            'label' => 'Creado',
            'type' => 'view',
            'required' => false
        );

        $AllFields[$this->updatedField] = (Object) array(
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