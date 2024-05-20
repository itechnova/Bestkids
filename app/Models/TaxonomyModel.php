<?php

namespace App\Models;

use CodeIgniter\Model;

class TaxonomyModel extends Model
{
    protected $table      = 'taxonomys';
    protected $primaryKey = 'idtaxonomy';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['code', 'type', 'title', 'content', 'viewname', 'view', 'level', 'status', 'enabled'];

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

    public function ExistsByCode($code, $type){
        return $this->where('code', $code)->where('type', $type)->first();
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
            'type'=>[
                'rules'=>'required',
                'errors'=>[
                    'required' => _('Tipo es requerido.')
                ]
            ],
            'view'=>[
                'rules'=>'required',
                'errors'=>[
                    'required' => _('Vista es requerido.')
                ]
            ],
            'title'=>[
                'rules'=>'required|min_length[3]|max_length[24]',
                'errors'=>[
                    'required' => _('Nombre es requerido.'),
                    'min_length' => _('Nombre debe tener al menos 3 caracteres de longitud.'),
                    'max_length' => _('Nombre no debe tener más de 24 caracteres de longitud.')
                ]
            ],
            'code'=>[
                'rules'=>'required|min_length[3]|max_length[24]',//is_unique[taxonomys.code]
                'errors'=>[
                    'required' => _('Código es requerido.'),
                    'min_length' => _('Código debe tener al menos 3 caracteres de longitud.'),
                    'max_length' => _('Código no debe tener más de 24 caracteres de longitud.'),
                    //'is_unique' => _('El correo ya está registrado.')
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
                case 'type':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Tipo',
                        'type' => 'select',
                        'options'=>[
                            'terms'=>_('Categoría'),
                            'entity'=>_('Entidad'),
                        ],
                        'placeholder'=> 'Seleccione',
                        'required' => true
                    );
                    break;
                case 'view':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Vista',
                        'type' => 'select',
                        'options'=>[
                            'list'=>_('Lista'),
                            'grid'=>_('Cuadriculas'),
                        ],
                        'placeholder'=> 'Seleccione',
                        'required' => true
                    );
                    break;
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
                        'label' => 'Nombre',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa nombre',
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
                case 'level':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Nivel de acceso',
                        'type' => 'number',
                        'placeholder'=> 'Ingresa nivel de acceso',
                        'required' => true
                    );
                    break;
                case 'viewname': 
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Enlace vista',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa enlace de la vista'
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

    public function getFieldsExtras($idtaxonomy, $Filters = []){
        $Fields = new FieldModel();

        $Fields->where('idtaxonomy', $idtaxonomy);
        foreach ($Filters as $key => $filter) {
            # code...
            $Fields->where($key, $filter);
        }

        return $Fields->findAll();
    }
}