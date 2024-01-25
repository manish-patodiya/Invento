<?php

namespace App\Controllers;

class Users extends BaseController
{
    public $usermodel;
    public $rolesmodel;
    public $companymodel;
    public $deactiveModal;
    public function __construct()
    {
        check_auth();
        check_access('users');

        $this->usermodel = model('UserModel');
        $this->companymodel = model('Companymodel');
        $this->rolesmodel = model('UserRoles');
        $this->deactiveModal = model('DeactiveModel');
    }

    public function index()
    {
        check_method_access('users', 'view');
        $data = [
            'session' => $this->session,
        ];
        return view('users/users_list', $data);
    }

    public function users_list()
    {
        $details = $this->usermodel->getAllUsers();
        $arr = [];
        $i = 1;
        foreach ($details as $k => $val) {
            $user_img = '<img src="' . $val->user_img . '"  style="width: 50px;" onerror="error(this)">';
            $actions = '';

            if (check_method_access('users', 'edit', true)) {
                $actions .= '<a title="Edit" style="font-size: 1.2rem;"  class="update text-warning me-1" href="#" uid="' . $val->id . '" company_id="' . $val->company_id . '" branch_id="' . $val->branch_id . '" staff_id="' . $val->staff_id . '"> <i class="fa fa-pencil-square-o"></i></a>';

            }
            if (check_method_access('users', 'delete', true)) {
                $actions .= '<a title="Delete" style="font-size: 1.2rem;"  class="text-danger delete"  uid="' . $val->id . '" href="#" company_id="' . $val->company_id . '" branch_id="' . $val->branch_id . '" staff_id="' . $val->staff_id . '"> <i class="fa fa-trash-o"></i></a>';
            }
            $role = $val->role;

            if ($val->status == 1) {
                $status = '<a title="deactive" style="font-size: 1.2rem;"  class="text-danger deactive"  uid="' . $val->id . '" href="#">Deactive</a>';
            } else {
                $status = '<a title="deactive" style="font-size: 1.2rem;"  class="text-success active"  uid="' . $val->id . '" href="#">active</a>';
            }
            $arr[] = [
                $i++,
                '<span class="col-md-1">' . $user_img . '</span>',
                '<h5>' . $val->first_name . ' ' . $val->last_name . '</h5> <p> + ' . $val->country_code . ' ' . $val->phone . '<br>' . $val->email . '</p>',
                $role,
                $status,
                $actions,
            ];

        }
        echo json_encode([
            "status" => 1,
            "details" => $arr,
        ]);
    }

    public function delete_user()
    {
        $post_data = $this->request->getPost();
    }

    public function get_user()
    {

        $post_data = $this->request->getPost();
        $details = $this->usermodel->get_users($post_data);
        if ($details) {
            echo json_encode([
                "status" => 1,
                "img" => 'Details is fount',
                "details" => $details,
            ]);
        } else {
            echo json_encode([
                "status" => 0,
                "msg" => 'details are not found',
            ]);
        }
    }

    public function update()
    {
        check_method_access('users', 'edit');
        $post_data = $this->request->getPost();
        $uid = $post_data['uid'];
        $data = array(
            'first_name' => format_name($post_data['first_name']),
            'last_name' => format_name($post_data['last_name']),
            'email' => $post_data['email'],
            'phone' => $post_data["phone"],
            'country_code' => $post_data["country_code"],
            'status' => 1,
        );

        if (!empty($_FILES['logo']['name'])) {
            $data = array_merge($data, ['user_img' => $this->_upload_photo()]);
        }
        $res = $this->usermodel->update_user_row($data, "id=$uid");
        if ($res) {
            echo json_encode([
                "status" => 1,
                "msg" => 'Update is successfully',
            ]);
        } else {
            echo json_encode([
                "status" => 0,
                "msg" => 'Update is not successfully',
            ]);
        }

    }

    private function _upload_photo()
    {
        $logo = $this->request->getFile('logo');
        $file_path = '';
        if ($logo->isValid()) {
            $upload_path = 'public/uploads/companise_logo/';
            $logo_name = $logo->getRandomName();
            $res = $logo->move($upload_path, $logo_name);
            if ($res) {
                $file_path = base_url($upload_path . $logo_name);
            }
        }
        return $file_path;

    }

    public function user_deactive_status_updata()
    {
        $post_data = $this->request->getPost();
        $uid = $post_data['uid'];
        $res = $this->deactiveModal->deactivateUser($uid);

        $data = [
            'user_id' => $uid,
            'reason' => $post_data['deactive'],
        ];
        $res1 = $this->deactiveModal->insertData($data);
        if ($res1) {
            echo json_encode([
                "status" => 1,
                "msg" => 'User  deactivete is successfully',
            ]);
        } else {
            echo json_encode([
                "status" => 0,
                "msg" => 'User deactivete is not successfully',
            ]);
        }

    }

    public function uers_active()
    {
        $post_data = $this->request->getPost();

        $uid = $post_data['user_id'];
        $res = $this->deactiveModal->activateUser($uid);
        $res1 = $this->deactiveModal->deactive_user_is_delete_($uid);

        if ($res & $res1) {
            echo json_encode([
                "status" => 1,
                "msg" => 'User  activete is successfully',
            ]);
        } else {
            echo json_encode([
                "status" => 0,
                "msg" => 'User activete is not successfully',
            ]);
        }

    }
}