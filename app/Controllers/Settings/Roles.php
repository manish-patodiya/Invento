<?php

namespace App\Controllers\settings;

use App\Controllers\BaseController;

class Roles extends BaseController
{
    public function __construct()
    {
        check_auth();
        check_access('roles');
        $this->UserRoles = model('UserRoles');
        $this->Modules = model('Modules');
    }

    public function index()
    {
        $roles = $this->UserRoles->getRoles();
        $data = [
            'session' => $this->session,
            'roles' => $roles,
        ];
        return view('roles/roleslist', $data);
    }

    public function permissions($role_id)
    {
        $modules = $this->Modules->getAll();
        $permissions = $this->Modules->getPermissions($role_id);
        $perm_arr = [];
        foreach ($permissions as $k => $v) {
            $perm_arr[$v->module_id][] = $v->operation;
        }
        $data = [
            'session' => $this->session,
            'role_id' => $role_id,
            'modules' => $modules,
            'permissions' => $perm_arr,
        ];
        return view('roles/permissions', $data);
    }

    public function update_permissions()
    {
        $post_data = $this->request->getPost();
        $role_id = $post_data['role_id'];
        $res = $this->Modules->deletePermissions($role_id);
        $modules = isset($post_data['module']) ? $post_data['module'] : [];
        $permissions = isset($post_data['permission']) ? $post_data['permission'] : [];
        $data = [];
        foreach ($permissions as $k => $p) {
            $data[] = [
                'role_id' => $role_id,
                'operation' => $p,
                'module_id' => $modules[$k],
            ];
        }
        if (!empty($data)) {
            $res = $this->Modules->setPermissions($data);
            if ($res) {
                if ($role_id == $this->session->get('user_details')['active_role_id']) {
                    set_permissions_in_session($role_id);
                }
                json_response(1, "Update Successfully");
            }
        } else {
            json_response(1, "Update Successfully");
        }
    }

}