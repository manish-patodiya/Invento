<?php
namespace App\Models;

use CodeIgniter\Model;

class CountryModel extends Model
{
    public $builder;
    public $db;
    public function __construct()
    {
        $this->db = db_connect();
        $this->builder = $this->db->table('master_countries');

    }
    public function insertData($data)
    {
        $builder = $this->builder;
        $builder->insert($data);
        return $this->db->insertID();
    }

    public function getAll()
    {
        $this->builder->select('*');
        return $this->builder->get()->getResult();
    }

    public function deleteRow($id)
    {
        $builder = $this->builder;
        $builder->delete(['id' => $id]);
        return $this->db->affectedRows();
    }

    public function updateRow($data, $where)
    {
        $builder = $this->builder;
        $builder->set($data);
        $builder->where($where);
        return $builder->update();
    }

    public function get_country_by_id($id)
    {
        $this->builder->select('*')
            ->where('id', $id);
        return $this->builder->get()->getRowArray();
    }
}