<?php
namespace App\Models;

use CodeIgniter\Model;

class StateModel extends Model
{
    public $builder;
    public $db;
    public function __construct()
    {
        $this->db = db_connect();
        $this->builder = $this->db->table('master_states');

    }
    public function getStates()
    {
        $builder = $this->builder;
        $builder->select("*");
        return $builder->get()->getResult();
    }
    public function getAll()
    {
        $builder = $this->builder;
        $builder->select("*,master_countries.*");
        $builder->join('master_countries', 'master_countries.id=master_states.country_id');
        return $builder->get()->getResult();
    }
    public function insertData($data)
    {
        $builder = $this->builder;
        $builder->insert($data);
        return $this->db->insertID();
    }
    public function get_state_by_id($id)
    {
        $this->builder->select('*')
            ->join('master_countries', 'master_countries.id=master_states.country_id')
            ->where('state_id', $id);
        return $this->builder->get()->getRowArray();
    }
    public function deleteRow($id)
    {
        $builder = $this->builder;
        $result = $builder->where('state_id', $id);
        $builder->delete();
        return $this->db->affectedRows();
    }
    public function updateRow($data, $where)
    {
        $builder = $this->builder;
        $builder->set($data);
        $builder->where($where);
        return $builder->update();
    }
}