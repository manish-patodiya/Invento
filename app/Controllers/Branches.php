<?php

namespace App\Controllers;

use App\Libraries\Linvoice;

class Branches extends BaseController
{
    public $usermodel;
    public $statemodel;
    public $citymodel;
    public $companymodel;
    public $rolesmodel;
    public function __construct()
    {
        check_auth();
        check_access('branches');
        $this->usermodel = model('UserModel');
        $this->companymodel = model('CompanyModel');
        $this->branches = model('Branches');
        $this->rolesmodel = model('UserRoles');
        $this->statemodel = model('StateModel');
        $this->citymodel = model('CityModel');
        $this->general_setting = model('GeneralSetting');
        $this->countrymodel = model('CountryModel');

    }

    public function index()
    {
        check_method_access('branches', 'view');
        $data = [
            'session' => $this->session,
        ];
        return view('branches/branches_list', $data);
    }
    public function add_branches()
    {
        check_method_access('branches', 'add');
        $data = [
            'session' => $this->session,
            'state' => $this->statemodel->getStates(),
            'roles' => $this->rolesmodel->getRoles(),
            'country' => $this->countrymodel->getAll(),
        ];
        // ($data);
        return view('branches/add_branches', $data);
    }

    public function branches_List()
    {
        check_method_access('branches', 'view');
        $branches = $this->branches->getBranchesWithUsers();

        $arr = [];
        $i = 0;
        foreach ($branches as $k => $v) {
            $originalDate = $v->created_at;
            // $button = "<a onclick='users_list($v->id)' class='btn btn-sm btn-success'>$v->count</a>";
            $actions = '';
            // if (check_method_access('branches', 'view', true)) {
            //     $actions .= '<a title="View" style="font-size: 1.2rem;"  class="text-primary me-1" href="#"> <i class="fa fa-eye"></i></a>';
            // }
            if (check_method_access('branches', 'edit', true)) {
                $actions .= '<a title="Edit" style="font-size: 1.2rem;" class="update text-warning me-1" href="' . base_url('branches/edit/' . $v->branch_id) . '" uid="' . $v->branch_id . '" > <i class="fa fa-pencil-square-o"></i></a>';
            }
            if (check_method_access('branches', 'delete', true)) {
                $actions .= '<a title="Delete" style="font-size: 1.2rem;" class="text-danger sup_delete"  branch_id="' . $v->branch_id . '" href="#" title="Delete" > <i class="fa fa-trash-o"></i></a>';
            }
            $arr[] = [
                ++$i,
                '<h6 class="m-0">' . $v->name . "</h6><p class='m-0'><small>" . ucfirst($v->address) . ", " . ucfirst($v->city_name) . ", " . ucfirst($v->state_name) . "</small></p> ",
                '<h6 class="m-0">' . $v->first_name . " " . $v->last_name . "</h6><p class='m-0'><small>" . $v->email . "<br> + " . $v->country_code . ' ' . $v->phone . "</small></p>",
                '<h5 class="m-0">' . date('d M Y', strtotime($originalDate)) . '</h5>',
                $actions,
            ];
        }
        echo json_encode([
            "status" => 1,
            "details" => $arr,
        ]);
    }

    public function add()
    {
        check_method_access('branches', 'add');
        $check = $this->validate([
            'username' => 'required|is_unique[users.username]',
        ], [
            'username' => [
                'is_unique' => 'Username is already exist',
            ],
        ]);
        if ($check) {
            $company_id = $this->session->user_details['company_id'];
            $post_data = $this->request->getPost();
            // insert user
            $data = array(
                'username' => $this->request->getPost('username'),
                'first_name' => format_name($this->request->getPost('firstname')),
                'last_name' => format_name($this->request->getPost('lastname')),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('mobile_no'),
                'state_id' => $this->request->getPost('state'),
                'city_id' => $this->request->getPost('citie'),
                'address' => format_name($this->request->getPost('address')),
                'password' => md5($this->request->getPost('password')),
                'status' => 1,

            );
            $user_id = $this->usermodel->insertData($data);

            // Add branch with invoice concept (eg: mrp concept)
            $trans_concept_id = $this->companymodel->getFeilds_by_id('trans_concept_id', 'id=' . $company_id);
            $data1 = array(
                'name' => format_name($this->request->getPost('branches_name')),
                'iec_code' => $this->request->getPost('ieccode'),
                'state_id' => $this->request->getPost('state'),
                'city_id' => $this->request->getPost('citie'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('mobile_no'),
                'gst_no' => $this->request->getPost('gst_no'),
                'pincode' => $this->request->getPost('pincode'),
                'country' => $this->request->getPost('country'),
                'country_code' => $this->request->getPost('country_code'),
                'trans_concept_id' => 1,
                'address' => $this->request->getPost('address'),
                'trans_concept_id' => $trans_concept_id->trans_concept_id,
            );
            $branch_id = $this->branches->add_branches($data1);

            // Add start no as company default start no
            $data3 = [
                'company_id' => $this->session->get('user_details')['company_id'],
                'user_id' => $user_id,
                'branch_id' => $branch_id,
                'role_id' => 3,
            ];
            $cuid = $this->usermodel->insert_company_user_role($data3);

            $start_no = $this->companymodel->getFeilds_by_id('start_no', 'id=' . $company_id);
            $trans_type = $this->general_setting->getTransType($company_id);
            foreach ($trans_type as $k => $v) {
                $data4[] = [
                    'company_id' => $company_id,
                    'branch_id' => $branch_id,
                    'trans_type_id' => $v->id,
                    'prefix' => "",
                    'start_no' => $start_no->start_no,
                ];
            }
            $result = $this->general_setting->insertData($data4);

            // add header templates
            $linvoice = new Linvoice();
            $header = $linvoice->insert_default_header($branch_id);
            $footer = $linvoice->insert_default_footer($branch_id);

            echo json_encode([
                "status" => 1,
                "msg" => "New Branches inserted successfully",
            ]);
        } else {
            echo json_encode([
                "status" => 0,
                "msg" => 'Username is already exist.',
                // "msg" => $this->validation->getErrors(),
            ]);
        }
    }

    public function edit($id)
    {
        check_method_access('branches', 'edit');
        if ($this->request->getPost('submit')) {
            $check = $this->validate([
                'username' => 'required|is_unique[users.username,id,{user_id}]',
            ], [
                'username' => [
                    'is_unique' => 'Username is already exist',
                ],
            ]);
            if ($check) {
                $user_id = $this->request->getPost('user_id');
                $data = array(
                    'username' => $this->request->getPost('username'),
                    'first_name' => format_name($this->request->getPost('firstname')),
                    'last_name' => format_name($this->request->getPost('lastname')),
                    'email' => $this->request->getPost('email'),
                    'phone' => $this->request->getPost('mobile_no'),
                    'state_id' => $this->request->getPost('state'),
                    'city_id' => $this->request->getPost('citie'),
                    'address' => format_name($this->request->getPost('address')),
                );
                $user = $this->usermodel->updateRow($data, "id=$user_id");
                $data1 = array(
                    'name' => format_name($this->request->getPost('branches_name')),
                    'state_id' => $this->request->getPost('state'),
                    'iec_code' => $this->request->getPost('ieccode'),
                    'city_id' => $this->request->getPost('citie'),
                    'email' => $this->request->getPost('email'),
                    'phone' => $this->request->getPost('mobile_no'),
                    'address' => $this->request->getPost('address'),
                    'pincode' => $this->request->getPost('pincode'),
                    'country' => $this->request->getPost('country'),
                    'country_code' => $this->request->getPost('country_code'),
                );
                $gst_no = $this->request->getPost('gst_no');
                $data1 = array_merge($data1, ['gst_no' => $gst_no]);
                $user_details = $this->session->get('user_details');
                $user_details['gst_no'] = $gst_no;
                $this->session->set('user_details', $user_details);

                $branches_id = $this->branches->updateRow($data1, "id=$id");

                $role_id = 2;
                $this->usermodel->update_cuser_role($id, $user_id, $role_id);

                echo json_encode([
                    "status" => 1,
                    "msg" => "Branches details updated successfully",
                ]);
            } else {
                echo json_encode([
                    "status" => 0,
                    "msg" => 'Username is already exist.',
                ]);
            }
        } else {
            $data['data'] = $this->branches->get_branches_by_id($id);
            $state_id = $data['data']['state_id'];
            $data['city'] = $this->citymodel->get_citys_state_by_id($state_id);
            $data['session'] = $this->session;
            $data['state'] = $this->statemodel->getStates();
            $data['roles'] = $this->rolesmodel->getRoles();
            $data['country'] = $this->countrymodel->getAll();

            return view('branches/edit_branches', $data);
        }

    }

    public function deleted_branches()
    {
        check_method_access('branches', 'delete');
        $id = $this->request->getPost('id');
        $this->branches->deleteRow($id);
        echo json_encode([
            'status' => 1,
            'msg' => 'Branches was deleted successfully',
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

    public function getPincode()
    {
        $post_data = $this->request->getPost();
        $getState_and_City = $this->branches->get_state_and_city($post_data);

        $state_id = $getState_and_City->state_id;
        $city_id = $getState_and_City->city_id;

        $state = $this->branches->get_state_by_id($state_id);
        $cities = $this->branches->get_cities_id($state_id);

        if ($state & $cities) {
            echo json_encode([
                'status' => 1,
                'stateinfo' => $state,
                'cityinfo' => $cities,
                'city_id' => $city_id,
                'msg' => 'fetch data is successfully',
            ]);
        } else {
            echo json_encode([
                'status' => 0,
                'msg' => 'Data is not successfully fatch',
            ]);
        }

    }

}