<?php
namespace App\Models;

use CodeIgniter\Model;

class Modules extends Model
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->builder = $this->db->table('module');
    }

    public function getAll()
    {
        return $this->builder->select('*')->get()->getResult();
    }

    public function getModules()
    {
        return $this->builder->select('*')->where('pid', 0)->orderBy('sort_order', 'asc')->get()->getResult();
    }

    public function getSubModulesByID($id)
    {
        return $this->builder->select('*')->where('pid', $id)->get()->getResult();
    }

    public function getPermissions($role_id)
    {
        return $this->db->table('module_access')->select('*')->where('role_id', $role_id)->get()->getResult();
    }

    public function deletePermissions($role_id)
    {
        return $this->db->table('module_access')->where('role_id', $role_id)->delete();
    }

    public function setPermissions($data)
    {
        return $this->db->table('module_access')->insertBatch($data);
    }

    public function getPermissionsForSession($role_id)
    {
        return $this->db->table('module_access')->select('operation,controller,title')->join('module', 'module.id = module_access.module_id', 'right')->where('role_id', $role_id)->get()->getResult();
    }

}