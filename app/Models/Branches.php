<?php

namespace App\Models;

use CodeIgniter\Model;

class Branches extends Model
{
    public $db;
    public $builder;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->session->start();

        $this->db = db_connect();
        $this->builder = $this->db->table('company_branches');

    }
    // function to insert data in the table
    public function add_branches($data)
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
        $builder = $this->builder;
        $builder->select("* ,(select count(*) from company_users where branch_id = company_branches.id) as count");
        $builder->join('company_users', 'company_branches.id = company_users.branch_id');
        if ($cid) {
            $builder->where('company_users.company_id', $cid);
        }
        $builder->where("deleted_at is NULL");
        return $builder->get()->getResult();
    }

    public function getBranchesWithUsers()
    {
        $cid = $this->session->get('user_details')['company_id'];
        return $this->db->table('company_branches b')
            ->select('b.*,cu.*,u.first_name,u.last_name,ms.state_name,mc.city_name')
            ->join('company_users cu', 'b.id=cu.branch_id')
            ->join('users u', 'cu.user_id=u.id')
            ->join('master_states ms', 'ms.state_id=b.state_id')
            ->join('master_cities mc', 'mc.city_id =b.city_id')
            ->where('cu.company_id', $cid)
            ->where('b.deleted_at is NULL')->where('cu.staff_id=0')
            ->get()->getResult();
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
        $builder->update();
        return $this->db->affectedRows();
    }
    public function get_branches_by_id($id)
    {
        $this->builder->select('company_branches.*,company_branches.id as bid,cus.branch_id, cus.user_id,cus.company_id,u.*')
            ->join('company_users as cus', 'cus.branch_id=company_branches.id')
            ->join('users u', 'u.id=cus.user_id')
            ->where('company_branches.id', $id);
        //echo $this->db->getLastQuery();die;
        return $this->builder->get()->getRowArray();
    }

    public function get_branch_info($id = false)
    {
        if (!$id) {
            $id = $this->session->get('user_details')['branch_id'];
        }
        $this->builder->select('company_branches.*,c.name as company, c.logo, c.website_url, c.cin')
            ->join('company_users cu', 'cu.branch_id=company_branches.id')
            ->join('users u', 'u.id=cu.user_id')
            ->join('companies c', 'c.id = cu.company_id')
            ->where('company_branches.id', $id);
        //echo $this->db->getLastQuery();die;
        return $this->builder->get()->getRow();
    }

    public function deleteRow($id)
    {
        $date = date('Y-m-d');
        $res = $this->db->query("update users,company_users,company_branches  set company_branches.deleted_at=$date, users.deleted_at = '$date' WHERE users.id= company_users.user_id and company_branches.id = company_users.branch_id and company_branches.id=$id");
        return $res;
    }

    public function getField($fields, $where)
    {
        // ($where);
        $this->builder->select($fields);
        $this->builder->where("deleted_at is NULL");
        if ($where) {
            $this->builder->where($where);
        }
        return $this->builder->get()->getRowArray();
    }
    public function update_cuser_role($data, $where)
    {
        $builder1 = $this->builder = $this->db->table('company_users');
        $builder1->set($data);
        $builder1->where($where);
        return $builder1->update();

    }
    public function getFeilds_by_id($select, $where = "")
    {
        $builder = $this->builder;
        $builder->select($select);
        if ($where) {
            $builder->where($where);
        }
        $builder->where('deleted_at is NULL');
        return $builder->get()->getRow();
    }
    public function insertTransConceptId($data, $id)
    {
        $builder = $this->builder;
        $builder->set($data);
        $builder->where('id', $id);
        $builder->update();
        return $this->db->affectedRows();
    }
    public function check_is_updated($id)
    {
        $builder = $this->builder;
        $builder->select('id')
            ->where('id', $id)
            ->where('is_updated is NULL')
            ->where('deleted_at is NUll');
        return $this->builder->get()->getRow();
    }

    public function get_state_and_city($post_data)
    {

        $this->master_zipcode = $this->db->table('master_zipcode');
        $pincode = $post_data['pincode'];
        $this->master_zipcode->select('*');
        if ($pincode) {
            $this->master_zipcode->where("pincode=$pincode");
        }
        return $this->master_zipcode->get()->getRow();
    }
    public function get_state_by_id($id)
    {
        $this->master_builder = $this->db->table('master_states');
        $this->master_builder->select('*')
            ->join('master_countries', 'master_countries.id=master_states.country_id')
            ->where('state_id', $id);
        return $this->master_builder->get()->getRowArray();
    }
    public function get_cities_id($id)
    {
        $this->master_cities = $this->db->table('master_cities');
        $this->master_cities->select('*')
            ->where('state_id', $id);
        return $this->master_cities->get()->getResult();
    }
}