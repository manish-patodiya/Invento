<?php

namespace App\Models;

use CodeIgniter\Model;

class AddressModel extends Model
{
    public $db;
    public $builder;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->session->start();

        $this->db = db_connect();
        $this->builder = $this->db->table('address_book');
    }
    // function to insert data in the table
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
    public function getAll()
    {
        $cid = $this->session->get('user_details')['company_id'];
        $bid = $this->session->get('user_details')['branch_id'];
        $builder = $this->builder;
        $builder->select("*")->where("parent_id=0");
        if ($cid) {
            $builder->where("company_id= $cid");
        }
        $builder->where("deleted_at is NULL");
        return $builder->get()->getResult();
    }
    public function get_branch_address($term)
    {

        $cid = $this->session->get('user_details')['company_id'];
        $bid = $this->session->get('user_details')['branch_id'];
        $builder = $this->builder;
        $builder->select("*")->where("parent_id!=0");
        if ($cid) {
            $builder->where("company_id= $cid");
        }
        if ($term != "") {
            $builder->like('address_book.name', $term);
        };
        $builder->where("deleted_at is NULL");
        return $builder->get()->getResult();
    }
    //view branch  getAll details
    public function view_branch_getAll($address_id)
    {
        $builder = $this->builder;
        $builder->select("*")
            ->where("parent_id", $address_id);
        $builder->where("deleted_at is NULL");
        return $builder->get()->getResult();

    }
    public function deleteRow($id)
    {
        $builder = $this->builder;
        $builder->set(['deleted_at' => date('Y-m-d')]);
        $builder->where('id', $id);
        $builder->update();
    }
    public function get_address($id)
    {
        return $this->builder->select('*')
            ->where('id', $id)
            ->get()->getRow();
    }
    public function get_view_branch_id($id)
    {
        return $this->builder->select('*')
            ->where('id', $id)
            ->get()->getRow();
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

    public function get_branch_list($company_address_id)
    {
        $cid = $this->session->get('user_details')['company_id'];
        return $this->builder->select('*')->where('parent_id', $company_address_id)->where('company_id', $cid)->get()->getResult();
    }
}