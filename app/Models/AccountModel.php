<?php

namespace App\Models;

use CodeIgniter\Model;

class AccountModel extends Model
{
    protected $table      = 'accounts';
    protected $primaryKey = 'idaccount';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['username', 'password', 'idrole', 'name', 'surname', 'phone', 'birthday', 'lastlogin', 'lastip', 'langcode', 'enabled'];

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
        return 'username';
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
                    if($field === 'password'){
                        $_VALUES_[$field] = \App\Libraries\Hash::encrypt($values[$field]);
                    }else{
                        $_VALUES_[$field] = $values[$field];
                    }
                }
            }
        }

        return $_VALUES_;
    }

    public function getValidation(){
        return [
            'username'=>[
                'rules'=>'required|valid_email|is_unique[accounts.username]',
                'errors'=>[
                    'required' => _('El correo es requerido.'),
                    'valid_email' => _('El correo no es válido.'),
                    'is_unique' => _('El correo ya está registrado.')
                ]
            ],
            'password'=>[
                'rules'=>'required',
                'errors'=>[
                    'required' => _('La contraseña es requerido.')
                ]
            ],
            'name'=>[
                'rules'=>'required|min_length[3]|max_length[30]',
                'errors'=>[
                    'required' => _('El nombre es requerido.'),
                    'min_length' => _('El nombre debe tener al menos 3 caracteres de longitud.'),
                    'max_length' => _('El nombre no debe tener más de 30 caracteres de longitud.')
                ]
            ],
            'surname'=>[
                'rules'=>'required|min_length[3]|max_length[30]',
                'errors'=>[
                    'required' => _('El apellido es requerido.'),
                    'min_length' => _('El apellido debe tener al menos 3 caracteres de longitud.'),
                    'max_length' => _('El apellido no debe tener más de 30 caracteres de longitud.')
                ]
            ]/*,
            /*'birthday'=>[
                'rules'=>'required',
                'errors'=>[
                    'required' => _('La fecha de cumpleaños es requerido.')
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

        

        foreach ($this->allowedFields as $field) {
            # code...
            switch ($field) {
                case 'idrole': 
                    $AllFields[] = (Object) array(
                        'name' => 'idrole',
                        'type' => 'hidden'
                    );
                    break;
                case 'name':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Nombre',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa nombre',
                        'required' => true
                    );
                    break;
                case 'surname':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Apellido',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa apellido',
                        'required' => true
                    );
                    break;
                case 'username':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Correo',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa correo',
                        'required' => true
                    );
                    break;
                case 'password':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Contraseña',
                        'type' => 'password',
                        'placeholder'=> 'Ingresa contraseña',
                    );
                    break;
                case 'phone':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Móvil',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa móvil',
                    );
                    break;
                case 'birthday':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Fecha de nacimiento',
                        'type' => 'date',
                        'placeholder'=> 'Ingresa fecha',
                    );
                    break;
                case 'enabled':
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Habilitar cuenta',
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

        $AllFields[] = (Object) array(
            'name' => 'lastlogin',
            'label' => 'Último acceso',
            'type' => 'view',
            'required' => false
        );

        $AllFields[] = (Object) array(
            'name' => 'lastip',
            'label' => 'Último ip',
            'type' => 'view',
            'required' => false
        );

        $AllFields[] = (Object) array(
            'name' => 'langcode',
            'label' => 'Idioma',
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