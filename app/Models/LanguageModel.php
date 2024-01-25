<?php
namespace App\Models;

use CodeIgniter\Model;

class LanguageModel extends Model
{
    public $builder;
    public $db;
    public function __construct()
    {
        $this->db = db_connect();
        $this->builder = $this->db->table('master_languages');

    }
    public function getAll()
    {
        $this->builder->select('*');
        return $this->builder->get()->getResult();

    }
    public function insertData($data)
    {
        $builder = $this->builder;
        $data1 = [
            "created_at" => date('Y-m-d'),
        ];
        $data = array_merge($data, $data1);
        $builder->insert($data);
        return $this->db->insertID();
    }
    public function get_language_by_id($id)
    {
        $this->builder->select('*')
            ->where('id', $id);
        return $this->builder->get()->getRowArray();
    }
    public function deleteRow($id)
    {
        $builder = $this->builder;
        // $builder->select('*');
        // $builder->set(["deleted_at" => date('d-m-y')]);
        $result = $builder->where('id', $id);
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