<?php

namespace App\Models;

use CodeIgniter\Model;

class ViewsModel extends Model
{
    protected $table      = 'views';
    protected $primaryKey = 'idview';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['title', 'slug', 'content', 'idtaxonomy', 'type', 'action', 'view_photo', 'view_title', 'view_content', 'level', 'enabled'];

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

    public function ExistsBySlug($Id){
        return $this->where('slug', $Id)->first();

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
                    if($field === 'slug'){
                        $_VALUES_[$field] = permanentLink($values[$field]!==""?$values[$field]:$values['title']);
                    }else{
                        $_VALUES_[$field] = $values[$field];
                    }
                }
            }else{
                if($field === 'slug' && isset($values['title'])){
                    if($values['title']!==""){
                        $_VALUES_[$field] = permanentLink($values['title']);
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
                    'required' => _('Nombre es requerido.'),
                    'min_length' => _('Nombre debe tener al menos 3 caracteres de longitud.'),
                    'max_length' => _('Nombre no debe tener más de 24 caracteres de longitud.')
                ]
            ],
            'level'=>[
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

        $AllFields[] = (Object) array(
            'name' => $this->primaryKey,
            'type' => 'hidden'
        );

        $Taxonomys = [];
        foreach ((new TaxonomyModel())->where('enabled', 1)->findAll() as $Taxonomy) {
            # code...
            $Taxonomys[$Taxonomy['idtaxonomy']] = $Taxonomy['title'];
        }

        foreach ($this->allowedFields as $field) {
            # code...
            switch ($field) {
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
                case 'slug':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Enlace permanente',
                        'type' => 'slug',
                        'placeholder'=> 'Ingresa enlace permanente'                    
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
                case 'idtaxonomy': 
                    $AllFields[$field] = (Object) array(
                        'name' => $field,
                        'label' => 'Taxonomía',
                        'type' => 'select',
                        'options'=> $Taxonomys,
                        'placeholder'=> 'Seleccione'
                    );
                    break;
                case 'type':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Tipo',
                        'type' => 'select',
                        'options'=>[
                            'list'=>_('Lista'),
                            'grid'=>_('Cuadricula'),
                        ],
                        'placeholder'=> 'Seleccione',
                        'required' => true
                    );
                    break;
                case 'action':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Acción nuevo',
                        'type' => 'textarea',
                        'placeholder'=> 'Ingresa acción nuevo',
                    );
                    break;
                case 'view_photo':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Columna medio',
                        'type' => 'textarea',
                        'placeholder'=> 'Ingresa el nombre'
                    );
                    break;
                case 'view_title':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Columna título',
                        'type' => 'textarea',
                        'placeholder'=> 'Ingresa el nombre',
                        'required' => true
                    );
                    break;
                case 'view_content':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Columna descripción',
                        'type' => 'textarea',
                        'placeholder'=> 'Ingresa el nombre'
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

    public function getViewTabs($IdView){
        return (new TabviewModel())->where('idview', $IdView)->where('enabled', 1)->findAll();
    }
}