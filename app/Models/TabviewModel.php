<?php

namespace App\Models;

use CodeIgniter\Model;

class TabviewModel extends Model
{
    protected $table      = 'tabviews';
    protected $primaryKey = 'idtab';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['idview', 'idtaxonomy', 'icon', 'title', 'content', 'level', 'type', 'action', 'view_link', 'view_photo', 'view_title', 'view_content', 'view_filter', 'view_author', 'view_created_at', 'enabled'];

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
            'title'=>[
                'rules'=>'required|min_length[3]|max_length[24]',
                'errors'=>[
                    'required' => _('Nombre es requerido.'),
                    'min_length' => _('Nombre debe tener al menos 3 caracteres de longitud.'),
                    'max_length' => _('Nombre no debe tener más de 24 caracteres de longitud.')
                ]
            ],
            'type'=>[
                'rules'=>'required',
                'errors'=>[
                    'required' => _('Tipo es requerido.')
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
                case 'idview': 
                    $AllFields[] = (Object) array(
                        'name' => 'idview',
                        'type' => 'hidden'
                    );
                    break;
                case 'idtaxonomy': 
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Taxonomía',
                        'type' => 'select',
                        'options'=> $Taxonomys,
                        'placeholder'=> 'Seleccione'
                    );
                    break;
                case 'icon':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Icono',
                        'type' => 'select',
                        'options'=> \App\Libraries\Feather::getNames(),
                        'placeholder'=> 'Seleccione'
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
                        'label' => 'Nivel del rol',
                        'type' => 'number',
                        'placeholder'=> 'Ingresa nivel del rol',
                        'required' => true
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
                        'type' => 'text',
                        'placeholder'=> 'Ingresa acción nuevo',
                    );
                    break;
                case 'view_photo':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Columna medio',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa el nombre'
                    );
                    break;
                case 'view_title':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Columna título',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa el nombre',
                        'required' => true
                    );
                    break;
                case 'view_content':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Columna descripción',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa el nombre'
                    );
                    break;
                case 'view_filter':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Filtro',
                        'type' => 'textarea',
                        'placeholder'=> 'Ingresa el filtro'
                    );
                    break;
                case 'view_author':
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Vér autor',
                        'type' => 'switch'                    
                    );
                    break;
                case 'view_created_at':
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Vér fecha',
                        'type' => 'switch'                    
                    );
                    break;
                case 'view_link':
                    # code...
                    $AllFields[] = (Object) array(
                        'name' => $field,
                        'label' => 'Enlace',
                        'type' => 'text',
                        'placeholder'=> 'Ingresa el nombre'
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

    public function getTabView($IdTab, $Id){
        $Tab = $this->Exists($IdTab);
        if(!IS_NULL($Tab)){
            $Taxonomy = (Object) (new TaxonomyModel())->where('idtaxonomy', $Tab['idtaxonomy'])->first();
            $Fields = (new TaxonomyModel())->getFieldsExtras($Tab['idtaxonomy']);
            if(!IS_NULL($Taxonomy)){
                $Taxonomy->fields = $Fields;
                $Tab['taxonomy'] = (Object) $Taxonomy;
                $Tab['idViewModel'] = $Id;

                if($Taxonomy->type!=="terms"){
                    $Model = new EntityModel();
                    $Lists = $Model->where('idtaxonomy', $Tab['idtaxonomy'])->findAll();
                    
                    $Filters = [];

                    $Models = [];
                    
                    if($Tab['view_filter'] !== ""){
                        foreach (explode("&", $Tab['view_filter']) as $filter) {
                            $param = explode("=", $filter);
                            
                            if(isset($param[0]) && isset($param[1])){
                                if($param[1] === 'Id'){
                                    $Filters[$param[0]] = $Id;
                                }else{
                                    $Filters[$param[0]] = $param[1];
                                }
                            }
                        }
                    }
                    
                    foreach ($Lists as $index => $list) {
                        //$Lists[
                        $metas = $Model->getMeta($list['identity']);
                        //var_dump($metas);
                        //var_dump($Fields);
                        $Exists = false;
                        foreach ($Filters as $key => $filter) {
                            # code...
                            if(isset($list[$key])){
                                if($list[$key].""===$filter.""){
                                    $Exists = true;
                                }
                            }

                            if(isset($metas[$key])){
                                //var_dump($metas[$key]."===".$filter."");
                                if($metas[$key].""===$filter.""){
                                    $Exists = true;
                                }
                            }
                        }

                        if($Exists){
                            $list['metas'] = $metas;
                            $Models[] = $list;
                        }
                    }
                    
                    $Tab['model'] = $Models;
                }else{
                    $Model = new TermModel();
                    $Lists = $Model->where('idtaxonomy', $Tab['idtaxonomy'])->findAll();
                    
                    $Filters = [];

                    $Models = [];
                    
                    if($Tab['view_filter'] !== ""){
                        foreach (explode("&", $Tab['view_filter']) as $filter) {
                            $param = explode("=", $filter);
                            
                            if(isset($param[0]) && isset($param[1])){
                                if($param[1] === 'Id'){
                                    $Filters[$param[0]] = $Id;
                                }else{
                                    $Filters[$param[0]] = $param[1];
                                }
                            }
                        }
                    }
                    
                    foreach ($Lists as $index => $list) {
                        //$Lists[
                        $metas = $Model->getMeta($list['idterm']);
                        //var_dump($metas);
                        //var_dump($Fields);
                        $Exists = false;
                        foreach ($Filters as $key => $filter) {
                            # code...
                            if(isset($list[$key])){
                                if($list[$key].""===$filter.""){
                                    $Exists = true;
                                }
                            }

                            if(isset($metas[$key])){
                                //var_dump($metas[$key]."===".$filter."");
                                if($metas[$key].""===$filter.""){
                                    $Exists = true;
                                }
                            }
                        }

                        if($Exists){
                            $list['metas'] = $metas;
                            $Models[] = $list;
                        }
                    }
                    
                    $Tab['model'] = $Models;
                }
            }
            return (Object) $Tab;
        }

        return NULL;
    }
}