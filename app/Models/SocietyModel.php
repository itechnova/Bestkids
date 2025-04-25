<?php

namespace App\Models;

use CodeIgniter\Model;

class SocietyModel extends Model
{
    protected $table      = 'societys';
    protected $primaryKey = 'idsocietys';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['code', 'modelmain', 'modelend', 'filtermain', 'filterend', 'title', 'subtitle', 'content', 'search', 'options', 'viewmedia', 'viewtitle', 'viewcontent', 'viewnew', 'selectedmain', 'selectedend'];

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
        return 'code';
    }

    public function Exists($Id){
        return $this->where($this->primaryKey, $Id)->first();

    }

    public function ExistsByCode($Id){
        return $this->where('code', $Id)->first();

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
                $_VALUES_[$field] = $values[$field];
            }        

        }

        return $_VALUES_;
    }

    public function getValidation(){
        return [
            'code'=>[
                'rules'=>'required|min_length[3]|max_length[24]',
                'errors'=>[
                    'required' => _('Código es requerido.'),
                    'min_length' => _('Código debe tener al menos 3 caracteres de longitud.'),
                    'max_length' => _('Código no debe tener más de 24 caracteres de longitud.')
                ]
            ],
            'title'=>[
                'rules'=>'required|min_length[3]',
                'errors'=>[
                    'required' => _('Título es requerido.'),
                    'min_length' => _('Título debe tener al menos 3 caracteres de longitud.')
                ]
            ],
            'modelmain'=>[
                'rules'=>'required|min_length[3]',
                'errors'=>[
                    'required' => _('Model main es requerido.'),
                    'min_length' => _('Model main debe tener al menos 3 caracteres de longitud.')
                ]
            ],
            'modelend'=>[
                'rules'=>'required|min_length[3]',
                'errors'=>[
                    'required' => _('Model end es requerido.'),
                    'min_length' => _('Model end debe tener al menos 3 caracteres de longitud.')
                ]
            ],
            'selectedmain'=>[
                'rules'=>'required|min_length[3]',
                'errors'=>[
                    'required' => _('Columna main es requerido.'),
                    'min_length' => _('Columna main debe tener al menos 3 caracteres de longitud.')
                ]
            ],
            'selectedend'=>[
                'rules'=>'required|min_length[3]',
                'errors'=>[
                    'required' => _('Columna end es requerido.'),
                    'min_length' => _('Columna end debe tener al menos 3 caracteres de longitud.')
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
                case 'code':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Código',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa código',
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
                case 'subtitle':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Subtítulo',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa subtítulo',
                        'required' => true
                    );
                    break;
                case 'modelmain':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Model main',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa model main',
                        'required' => true
                    );
                    break;
                case 'modelend':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Model end',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa model end',
                        'required' => true
                    );
                    break;
                case 'filtermain':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Filtro main',
                        'type' => 'textarea',
                        'placeholder'=> 'Ingresa filtro',
                    );
                    break;
                case 'filterend':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Filtro end',
                        'type' => 'textarea',
                        'placeholder'=> 'Ingresa filtro',
                    );
                    break;
                case 'title':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Título',
                        'type' => 'textarea',
                        'placeholder'=> 'Ingresa título',
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
                case 'search':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Busqueda',
                        'type' => 'textarea',
                        'placeholder'=> 'Ingresa busqueda',
                    );
                    break;

                case 'options':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Opciones',
                        'type' => 'textarea',
                        'placeholder'=> 'Ingresa opciones',
                    );
                    break;
                case 'viewnew':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Acción nuevo',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa acción nuevo',
                    );
                    break;
                case 'viewmedia':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Columna medio',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa el nombre'
                    );
                    break;
                case 'viewtitle':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Columna título',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa el nombre'
                    );
                    break;
                case 'viewcontent':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Columna descripción',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa el nombre'
                    );
                    break;
                case 'selectedmain':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Columna selección main',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa el nombre',
                        'required' => true
                    );
                    break;
                case 'selectedend':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Columna selección main',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa el nombre',
                        'required' => true
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

    public function getMeta($IdSocietys, $IdColumnMain){
        $Model = new SocietyMetaModel();
        return $Model->where('idsocietys', $IdSocietys)->where('idcolumnmain', $IdColumnMain)->findAll();
    }


    
}