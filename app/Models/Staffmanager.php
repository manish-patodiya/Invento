<?php

namespace App\Models;

use CodeIgniter\Model;

class Staffmanager extends Model
{
    public $db;
    public $builder;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->session->start();

        $this->db = db_connect();
        $this->builder = $this->db->table('staffmanager');
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
        $builder->select('staffmanager.*,branch.name as br_name,c.name as c_name,ms.state_name as s_name,mc.city_name as cy_name')
            ->join('company_branches as branch', 'branch.id=staffmanager.branch_id')
            ->join('companies as c', 'c.id=staffmanager.company_id')
            ->join('master_states as ms', 'ms.state_id=staffmanager.state_id')
            ->join('master_cities as mc', 'mc.city_id=staffmanager.city_id');
        if ($cid) {
            $builder->where("company_id= $cid");
            $builder->where("branch_id= $bid");
        }
        $builder->where("staffmanager.deleted_at is NULL");
        return $builder->get()->getResult();
    }
    public function get_staff_by_id($id)
    {
        return $this->builder->select('staffmanager.*,users.id as user_id,company_users.role_id as login_role')
            ->join('company_users', 'company_users.staff_id=staffmanager.id', 'left')
            ->join('users', 'users.id=company_users.user_id', 'left')
            ->where('staffmanager.id', $id)
            ->get()->getRowArray();
    }

    public function deleteRow($id)
    {
        $date = date('Y-m-d');
        $res = $this->db->query("update users,company_users,staffmanager  set staffmanager.deleted_at=$date, users.deleted_at = '$date' WHERE users.id= company_users.user_id and staffmanager.id = company_users.staff_id and staffmanager.id=$id");
        return $res;
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

    // public function get_branch_list($company_address_id)
    // {
    //     return $this->builder->select('*')->where('parent_id', $company_address_id)->get()->getResult();
    // }
}