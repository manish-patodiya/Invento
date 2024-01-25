<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    public $db;
    public $builder;

    public function __construct()
    {
        $this->db = db_connect();
        $this->builder = $this->db->table('users');
    }

    //funtion to get rows on the basis of condition
    public function get($where)
    {
        $builder = $this->builder;
        $builder->select('*');
        $builder->where($where);
        $builder->where('deleted_at is NULL');
        return $builder->get()->getResultArray();
    }

    public function getUserByIdentifier($where)
    {
        $builder = $this->builder;
        $builder->select('users.id, users.first_name, users.last_name, users.username, users.email');
        $builder->where($where);
        $builder->where('deleted_at is NULL');
        return $builder->get()->getRow();
    }
    public function getUser($where)
    {
        $builder = $this->builder;
        $builder->select('*');
        $builder->where($where);
        $builder->where('deleted_at is NULL');
        return $builder->get()->getRow();
    }

    public function getFeilds($select, $where = "")
    {
        $builder = $this->builder;
        $builder->select($select);
        if ($where) {
            $builder->where($where);
        }
        $builder->where('deleted_at is NULL');
        return $builder->get()->getResultArray();
    }

    //function to get all row in the table
    public function getAll()
    {
        $builder = $this->builder;
        $builder->select("*");
        $builder->where('deleted_at is NULL');
        return $builder->get()->getResultArray();
    }

    // function to get fields for join rows on the basis of the condition
    public function getFieldsForJoin($column, $otherTable, $cond, $where = '', $type = null)
    {
        $builder = $this->builder;
        $builder->select($column);
        if ($where) {
            $builder->where($where);
        }
        $builder->where('users.deleted_at is NULL');
        if ($type) {
            $builder->join($otherTable, $cond, $type);
        } else {
            $builder->join($otherTable, $cond);
        }

        return $builder->get()->getResultArray();
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

    // function to update any row
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

    public function login($username, $pwd)
    {
        $pwd = md5($pwd);
        $this->builder->where("username='$username'");
        $this->builder->where("password", $pwd);
        $this->builder->where('deleted_at is Null');

        $result = $this->builder->get()->getRow();
        // echo $this->db->getLastQuery();die;
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function activateUser($uid = false)
    {
        if ($uid) {
            $builder = $this->builder;
            $builder->set("status", 2);
            $builder->where("id", $uid);
            $builder->update();
            return true;
        } else {
            return false;
        }
    }

    public function deleteUser($id)
    {
        $builder = $this->builder;
        $builder->set(['deleted_at' => date('Y-m-d')]);
        $builder->where('id', $id);
        $builder->update();
    }

    public function getCount($where)
    {
        $builder = $this->builder;
        $builder->where($where);
        $builder->where('deleted_at is NULL');
        return $builder->countAllResults();
    }

    public function getPaginate($limit, $offset, $where)
    {
        $builder = $this->builder;
        $builder->select('users.id,users.phone,status,users.first_name,users.last_name,profile_photo');
        $builder->join('members_profile mp', 'mp.user_id=users.id', 'left');
        $builder->where('users.deleted_at is NULL');
        $builder->where($where);
        $builder->limit($limit, $offset);
        return $builder->get()->getResult();
    }

    public function insert_company_user_role($data)
    {
        // ($data);
        $this->db
            ->table('company_users')
            ->insert($data);
        return $this->db->insertID();
    }
    public function getFeilds_by_id($select, $where)
    {
        // ($where);
        $builder_com_users = $this->db->table('company_users')->select($select);
        if ($where) {
            $builder_com_users->where('user_id', $where);
        }
        return $builder_com_users->get()->getRow();
        // echo $this->db->getLastQuery();die;
    }

    public function update_cuser_role($cid, $user_id, $role_id)
    {
        return $this->db
            ->table('company_users')
            ->where("company_id=$cid AND user_id = $user_id")
            ->set(['role_id' => $role_id])
            ->update();
    }

    public function get_company_users($cid)
    {
        return $this->db->table('company_users')
            ->select('*')
            ->join('users', "company_users.user_id=users.id")
            ->where('company_id', $cid)
            ->get()->getResult();
    }

    public function delete_company_user($cid, $uid)
    {
        $this->db->table('company_users')->delete(['user_id' => $uid, 'company_id' => $cid]);
        $this->db->table('users')->delete(['id' => $uid]);
    }

    public function get_company_user_by_id($cid, $uid)
    {
        return $this->db->table('company_users')
            ->select('*')
            ->join('users', "company_users.user_id=users.id")
            ->where(['company_id' => $cid, 'user_id' => $uid])
            ->get()->getRow();
    }

    public function get_profile_id($id)
    {
        return $this->builder->select('*')
            ->join('master_states', 'users.state_id=master_states.state_id', 'left')
            ->join('master_cities', 'users.state_id=master_cities.city_id', 'left')
            ->where('id', $id)
            ->get()->getRow();

    }

    // function to run query to select field as per condition
    public function getFields($select, $where = "")
    {
        $builder = $this->builder;
        $builder->select($select);
        if ($where) {
            $builder->where($where);
        }
        $builder->where('deleted_at is NULL');
        return $builder->get()->getRow();
    }

    // user...............

    public function getAllUsers()
    {

        $builder = $this->builder;
        $builder->select("users.*,cu.company_id,cu.branch_id,cu.staff_id,r.role")
            ->join('company_users cu', 'cu.user_id=users.id')->join('roles r', 'r.id=cu.role_id')
            ->where('cu.role_id != 1')->where('users.deleted_at is NULL');
        return $builder->get()->getResult();
    }

    public function update_user_row($data, $uid)
    {
        $data1 = [
            "updated_at" => date('Y-m-d'),
        ];
        $data = array_merge($data, $data1);
        $builder = $this->builder;
        $builder->set($data);
        $builder->where($uid);
        return $builder->update();
    }

    public function get_users($post_data)
    {
        $uid = $post_data['uid'];
        $builder = $this->builder;
        $builder->select("users.*,cu.company_id,cu.branch_id,cu.staff_id,r.role")
            ->join('company_users cu', 'cu.user_id=users.id')->join('roles r', 'r.id=cu.role_id')
            ->where('cu.role_id != 1')->where('users.id', $uid);
        return $builder->get()->getRow();
    }

    public function getUserMail($where)
    {
        $builder = $this->builder;
        $builder->select('*');
        $builder->where('users.id', $where);
        $builder->where('deleted_at is NULL');
        return $builder->get()->getRow();
    }

}