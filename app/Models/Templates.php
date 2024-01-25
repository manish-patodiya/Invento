<?php
namespace App\Models;

use CodeIgniter\Model;

class Templates extends Model
{
    public $builder;
    public $db;
    public function __construct()
    {
        $this->db = db_connect();
        $this->builder = $this->db->table('templates');
        $this->session = service('session');
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

    public function getAll()
    {
        $branch_id = $this->session->get('user_details')['branch_id'];
        $builder = $this->builder;
        $builder->select("*")
            ->where('deleted_at is NULL')->where(['branch_id' => $branch_id])->orderBy('trans_type_id', 'asc');
        return $builder->get()->getResult();
        // echo $this->db->getLastQuery();die;
    }

    public function deleteRow($id)
    {
        $builder = $this->builder;
        $builder->set(['deleted_at' => date('Y-m-d')]);
        $builder->where('id', $id);
        $builder->update();
    }

    public function get_temp_by_id($id)
    {
        $this->builder->select('*')
            ->where('templates.id', $id);
        // echo $this->db->getLastQuery();die;
        return $this->builder->get()->getRowArray();
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

    public function get_header_of_report($trans_id)
    {
        $branch_id = $this->session->get('user_details')['branch_id'];
        return $this->builder->select('content')->where(['trans_type_id' => $trans_id, 'branch_id' => $branch_id, 'sub_type_id' => 1])->get()->getRow();
    }
    public function get_footer_of_report($trans_id)
    {
        $branch_id = $this->session->get('user_details')['branch_id'];
        return $this->builder->select('content')->where(['trans_type_id' => $trans_id, 'branch_id' => $branch_id, 'sub_type_id' => 3])->get()->getRow();
    }

    public function insert_batch($data)
    {
        return $this->builder->insertBatch($data);
    }
}