<?php

namespace App\Models;

use CodeIgniter\Model;

class EntityMetaModel extends Model
{
    protected $table      = 'entitymetas';
    protected $primaryKey = 'idmeta';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['identity', 'idaccount', 'idfield', 'value'];

    protected bool $allowEmptyInserts = false;

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
        return 'idfield';
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
    
    public function getValidation(){
        return [];
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