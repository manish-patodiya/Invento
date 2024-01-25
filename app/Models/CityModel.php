<?php
namespace App\Models;

use CodeIgniter\Model;

class CityModel extends Model
{
    public $builder;
    public $db;
    public function __construct()
    {
        $this->db = db_connect();
        $this->builder = $this->db->table('master_cities');

    }
    public function insertData($data)
    {
        $builder = $this->builder;
        $builder->insert($data);
        return $this->db->insertID();
    }
    public function getAll()
    {
        $this->builder->select('*,master_states.*');
        $this->builder->join('master_states', 'master_states.state_id=master_cities.state_id');
        return $this->builder->get()->getResult();

    }
    public function get_city_by_id($id)
    {
        $this->builder->select('*')
            ->join('master_states', 'master_states.state_id=master_cities.state_id')
            ->where('city_id', $id);
        return $this->builder->get()->getRowArray();
    }
    public function deleteRow($id)
    {
        $builder = $this->builder;
        // $builder->select('*');
        // $builder->set(["deleted_at" => date('d-m-y')]);
        $builder->where('city_id', $id);
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
    // public function updateData($data, $id)
    // {
    //     $builder = $this->builder;
    //     $builder->set($data);
    //     $builder->where('city_id', $id);
    //     return $builder->update();
    // }
    public function get_cities_state_id($id)
    {
        $this->builder->select('*')
            ->where('state_id', $id);
        return $this->builder->get()->getResult();
    }
    public function get_citys_state_by_id($state_id)
    {
        $this->builder->select('*')
            ->where('state_id', $state_id);
        return $this->builder->get()->getResult();
    }
    public function get_cities($id)
    {
        return $this->builder->select('*')
            ->where('id', $id)
            ->get()->getRow();
    }

    public function get_cities_id($id)
    {
        $this->builder->select('*')
            ->where('state_id', $id);
        return $this->builder->get()->getResult();
    }
}