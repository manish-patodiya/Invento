<?php

namespace App\Controllers\profile;

use App\Controllers\BaseController;

class Profile extends BaseController
{
    public $usermodel;
    public function __construct()
    {
        check_auth();
        $this->usermodel = model('UserModel');
    }

    public function index()
    {
        $id = base64_encode($this->session->user_details['user_id']);
        $id = base64_decode($id);
        $details = $this->usermodel->get_profile_id($id);

        $data = [
            'session' => $this->session,
            'info' => $details,
            'admin_roles' => $this->session->user_details['user_roles'][0],
        ];
        return view('profile/profile', $data);
    }

    public function update_User_Profile()
    {
        $id = $this->request->getPost('user_id');
        $data = array(
            'first_name' => format_name($this->request->getPost('firat_name')),
            'last_name' => format_name($this->request->getPost('last_name')),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
        );
        if (!empty($_FILES['logo']['name'])) {
            $logo_path = $this->_upload_logo();
            $data = array_merge($data, ['user_img' => $logo_path]);
            $user_details = $this->session->get('user_details');
            $user_details['user_img'] = $logo_path;
            $this->session->set('user_details', $user_details);
        }
        $res = $this->usermodel->updateRow($data, "id=$id");

        $act_rol_id = $this->session->get('user_details')['active_role_id'];
        if ($act_rol_id == 7) {
            $staff_id = $this->session->get('user_details')['staff_id'];
            $res = model('Staffmanager')->updateRow($data, "id=$staff_id");
        }

        if ($res) {
            echo json_encode([
                "status" => 1,
                "msg" => "User profile updated successfully.",
            ]);
        }

    }
    private function _upload_logo()
    {
        $logo = $this->request->getFile('logo');
        $file_path = '';
        if ($logo->isValid()) {
            $upload_path = 'public/uploads/user_img/';
            $logo_name = $logo->getRandomName();
            $res = $logo->move($upload_path, $logo_name);
            if ($res) {
                $file_path = base_url($upload_path . $logo_name);
            }
        }
        return $file_path;
    }
    public function changePass()
    {
        $id = $this->session->user_details['user_id'];
        $model = $this->usermodel;
        $password = md5($this->request->getPost('old_password'));
        $exist = $model->get("password = '$password' AND id='$id'");
        if ($exist) {
            $new_pass = md5($this->request->getPost('new_password'));
            $data = [
                'password' => $new_pass,
            ];
            $model->updateRow($data, "id='$id'");
            echo json_encode([
                "status" => 1,
                "msg" => "Successfully updated.",
            ]);
        } else {
            echo json_encode([
                "status" => 0,
                "msg" => "Old password is wrong.",
            ]);
        }
    }
}