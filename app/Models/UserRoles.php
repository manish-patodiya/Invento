<?php
namespace App\Models;

use CodeIgniter\Model;

class UserRoles extends Model
{
    public $builder;
    public $db;
    public function __construct()
    {
        $this->db = db_connect();
        $this->builder = $this->db->table('company_users ');

    }
    public function insertData($data)
    {
        $builder = $this->builder();
        $this->builder->insert($data);
        return $this->db->insertID();
    }

    public function deleteData($id, $role_id = false)
    {
        $this->builder->where("user_id", $id);
        if ($role_id) {
            $this->builder->where("role_id", $role_id);
        }
        $this->builder->delete();
        return $this->db->affectedRows();
    }

    public function getRoles()
    {
        return $this->db->table('roles')->select('*')->get()->getResult();
    }

    public function getUserRoles($id)
    {
        $this->builder->select('role_id,role,company_id,name as company_name,branch_id,logo as company_logo');
        $this->builder->join('roles r', 'company_users.role_id=r.id', 'left');
        $this->builder->join('companies c', 'company_users.company_id=c.id', 'left');
        $this->builder->where('user_id ', $id);
        $roles = $this->builder->get()->getResult();
        // echo $this->db->getLastQuery();die;

        return $roles;
    }

    public function getCount($id)
    {
        $builder = $this->builder;
        $builder->where('role_id', $id);
        return $builder->countAllResults();
    }

    public function checkRole($user_id)
    {
        $builder = $this->builder;
        $builder->select('id');
        $builder->where("user_id = $user_id AND role_id = 2");
        return $builder->countAllResults();
    }
}