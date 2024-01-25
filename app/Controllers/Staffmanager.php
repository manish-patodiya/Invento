<?php

namespace App\Controllers;

class Staffmanager extends BaseController
{
    public $supportModel;
    public $branches;
    public function __construct()
    {
        check_auth();
        check_access('staffmanager');
        $this->staffmanager = model('Staffmanager');
        $this->branches = model('Branches');
        $this->rolesmodel = model('UserRoles');
        $this->statemodel = model('StateModel');
        $this->citymodel = model('CityModel');
        $this->usermodel = model('UserModel');
    }
    public function index()
    {
        check_method_access('staffmanager', 'view');
        $data = [
            'session' => $this->session,
        ];

        return view('staffmanager/staffmanager_list', $data);
    }

    public function add_staffmanager()
    {
        check_method_access('staffmanager', 'view');
        $data = [
            'session' => $this->session,
            'roles' => $this->rolesmodel->getRoles(),
            'state' => $this->statemodel->getStates(),
        ];
        return view('staffmanager/add_staff', $data);
    }

    public function add()
    {
        check_method_access('staffmanager', 'add');
        $post_data = $this->request->getPost();
        $data = array(
            'username' => $post_data['username'],
            'first_name' => format_name($post_data['firstname']),
            'last_name' => format_name($post_data['lastname']),
            'email' => $post_data['email'],
            'phone' => $post_data['mobile_no'],
            'address' => format_name($post_data['address']),
            'password' => md5($post_data['password']),
            'state_id' => $post_data['state'],
            'city_id' => $post_data['citie'],
            'status' => 1,
        );
        $user_id = $this->usermodel->insertData($data);

        $data1 = array(
            'first_name' => format_name($post_data['firstname']),
            'last_name' => format_name($post_data['lastname']),
            'email' => $post_data['email'],
            'phone' => $post_data['mobile_no'],
            'address' => format_name($post_data['address']),
            'state_id' => $post_data['state'],
            'city_id' => $post_data['citie'],
            'branch_id' => $this->session->get('user_details')['branch_id'],
            'company_id' => $this->session->get('user_details')['company_id'],
        );

        $staff_id = $this->staffmanager->insertData($data1);
        $data3 = [
            'company_id' => $this->session->get('user_details')['company_id'],
            'user_id' => $user_id,
            'staff_id' => $staff_id,
            'branch_id' => $this->session->get('user_details')['branch_id'],
            'role_id' => $post_data['role_id'],
        ];
        $cuid = $this->usermodel->insert_company_user_role($data3);

        echo json_encode([
            "status" => 1,
            "msg" => "New Staff inserted successfully",
        ]);
    }

    public function stafflist()
    {
        check_method_access('staffmanager', 'view');
        $staffmanager = $this->staffmanager->getAll();
        $arr = [];
        $i = 0;
        foreach ($staffmanager as $k => $v) {
            $id = base64_encode($v->id);
            $action = '';
            if (check_method_access('staffmanager', 'edit', true)) {
                $action .= '<a title="Edit" class="text-warning sup_update me-1"  href="' . base_url('staffmanager/edit/' . $id) . '"staff_id="' . $id . '" style="font-size: 1.2rem;"> <i class="fa fa-pencil-square-o"></i></a>';
            }
            if (check_method_access('staffmanager', 'delete', true)) {
                $action .= '<a title="Delete" class="delete  text-danger sup_delete me-1"  uid="' . $id . '" href="#" title="Delete"  > <i class="fa fa-trash-o"></i></a>';
            }
            $arr[] = [
                ++$i,
                $v->first_name . ' ' . $v->last_name,
                '<h6 class="m-0">' . $v->email . "</h6><h5 class='m-0'><small>" . $v->address . ', ' . $v->cy_name . ', ' . $v->s_name . "</small></h5> ",
                $v->phone,
                $action,
            ];
        }
        echo json_encode([
            "status" => 1,
            "details" => $arr,
        ]);
    }

    public function edit($id)
    {
        $id = base64_decode($id);
        check_method_access('staffmanager', 'edit');
        if ($this->request->getPost('submit')) {
            $post_data = $this->request->getPost();
            $user_id = $post_data['user_id'];
            $data = array(
                'first_name' => format_name($post_data['firstname']),
                'last_name' => format_name($post_data['lastname']),
                'email' => $post_data['email'],
                'phone' => $post_data['mobile_no'],
                'address' => format_name($post_data['address']),
                'state_id' => $post_data['state'],
                'city_id' => $post_data['citie'],
                'status' => 1,
            );
            $res = $this->usermodel->updateRow($data, "id=$user_id");

            $data1 = array(
                'first_name' => format_name($post_data['firstname']),
                'last_name' => format_name($post_data['lastname']),
                'email' => $post_data['email'],
                'phone' => $post_data['mobile_no'],
                'address' => format_name($post_data['address']),
                'state_id' => $post_data['state'],
                'city_id' => $post_data['citie'],
                'role_id' => $post_data['role_id'],
                'branch_id' => $this->session->get('user_details')['branch_id'],
            );

            $res1 = $this->staffmanager->updateRow($data1, "id=$id");

            $role_id = $post_data['role_id'];
            $this->usermodel->update_cuser_role($id, $user_id, $role_id);

            if ($res && $res1) {
                echo json_encode([
                    "status" => 1,
                    "msg" => "New Staff inserted successfully",
                ]);
            }
        } else {
            $details = $this->staffmanager->get_staff_by_id($id);
            $state_id = $details['state_id'];
            $city = $this->citymodel->get_citys_state_by_id($state_id);
            $data = [
                'session' => $this->session,
                'roles' => $this->rolesmodel->getRoles(),
                'details' => $details,
                'city' => $city,
                'state' => $this->statemodel->getStates(),
            ];
            return view('staffmanager/edit_staff', $data);
        }
    }

    public function delete()
    {
        check_method_access('staffmanager', 'delete');
        $post_data = $this->request->getPost('id');
        $id = base64_decode($post_data);
        $this->staffmanager->deleteRow($id);
        echo json_encode([
            'status' => 1,
            'msg' => 'Company was deleted successfully',
        ]);

    }
    public function getCities()
    {
        $id = $this->request->getPost('id');
        $cities = $this->citymodel->get_cities_state_id($id);
        echo json_encode([
            'status' => 1,
            'data' => $cities,
            'msg' => 'successfully Fetched',
        ]);

    }

}