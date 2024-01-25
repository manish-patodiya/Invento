<?php
namespace App\Models;

use CodeIgniter\Model;

class DeactiveModel extends Model
{
    public $builder;
    public $db;
    public function __construct()
    {
        $this->db = db_connect();
        $this->builder = $this->db->table('deactive_reason');
        $this->userBuilder = $this->db->table('users');

    }
    public function insertData($data)
    {
        $builder = $this->builder;
        $builder->insert($data);
        return $this->db->insertID();
    }

    public function deactivateUser($uid = false)
    {
        if ($uid) {
            $userBuilder = $this->userBuilder;
            $userBuilder->set("status", 2);
            $userBuilder->where("id", $uid);
            $userBuilder->update();
            return true;
        } else {
            return false;
        }
    }
    public function activateUser($uid = false)
    {
        if ($uid) {
            $userBuilder = $this->userBuilder;
            $userBuilder->set("status", 1);
            $userBuilder->where("id", $uid);
            $userBuilder->update();
            return true;
        } else {
            return false;
        }
    }

    public function deactive_user_is_delete_($uid)
    {
        $builder = $this->builder;
        $builder->where('user_id', $uid);
        $builder->delete();
        return $this->db->affectedRows();
    }
}