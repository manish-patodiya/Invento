<?php
namespace App\Models;

use CodeIgniter\Model;

class UnitModel extends Model
{
    public $builder;
    public $db;
    public function __construct()
    {
        $this->db = db_connect();
        $this->builder = $this->db->table('master_units');

    }

    public function insertData($data)
    {
        $builder = $this->builder;
        $data1 = [
            "updated_at" => date('Y-m-d'),
            "created_at" => date('Y-m-d'),
        ];
        $data = array_merge($data, $data1);
        $builder->insert($data);
        return $this->db->insertID();
    }

    public function getAll_unit_by_pro()
    {
        $builder = $this->builder;
        $builder->select("*");
        $builder->where("deleted_at is NULL");
        return $builder->get()->getResult();
        // echo $this->db->getLastQuery();die;
    }

    public function getAll()
    {
        $builder = $this->builder;
        $builder->select("* ,(select title from master_units as b where master_units.base_unit = b.id ) as unit ");
        $builder->where("deleted_at is NULL");
        return $builder->get()->getResult();
        // echo $this->db->getLastQuery();die;
    }

    public function getAll_unit()
    {
        $builder = $this->builder;
        $builder->select("*")
            ->where("conversion_rate = 0");
        $builder->where("deleted_at is NULL");
        return $builder->get()->getResult();
    }
    public function updateRow($data, $where)
    {
        $data1 = [
            "updated_at" => date('Y-m-d'),
        ];
        $data = array_merge($data, $data1);
        $builder = $this->builder;
        $builder->set($data);
        $builder->where($where);
        return $builder->update();
    }
    public function deleteRow($id)
    {
        $builder = $this->builder;
        $builder->set(['deleted_at' => date('Y-m-d')]);
        $builder->where('id', $id);
        $builder->update();
    }
    public function get_unit_by_id($id)
    {
        $this->builder->select('*')
            ->where('id', $id);
        // echo $this->db->getLastQuery();die;
        return $this->builder->get()->getRowArray();
    }
    public function get_unit($id)
    {
        return $this->builder->select('*')
            ->where('id', $id)
            ->get()->getRow();
    }
    public function getAll_base_unit($id)
    {
        return $this->builder->select('master_units.*,b.*')->join('master_units as b ', ' master_units.base_unit=b.base_unit')->where('master_units.id', $id)->where("master_units.deleted_at is NULL")->get()->getResult();
    }
}