<?php

namespace App\Controllers;

use App\Libraries\Linvoice;
use App\Libraries\SendEmail;

class Companies extends BaseController
{
    public $usermodel;
    public $rolesmodel;
    public $companymodel;
    public $statemodel;
    public $citymodel;
    public function __construct()
    {
        check_auth();
        check_access('companies');

        $this->parser = service('parser');

        $this->usermodel = model('UserModel');
        $this->companymodel = model('CompanyModel');
        $this->rolesmodel = model('UserRoles');
        $this->statemodel = model('StateModel');
        $this->citymodel = model('CityModel');
        $this->countrymodel = model('CountryModel');
    }

    public function index()
    {
        check_method_access('companies', 'view');
        $data = [
            'session' => $this->session,
            'roles' => $this->rolesmodel->getRoles(),
        ];
        return view('company/company_list', $data);
    }
    public function add_company()
    {
        check_method_access('companies', 'add');
        $data = [
            'session' => $this->session,
            'roles' => $this->rolesmodel->getRoles(),
            'state' => $this->statemodel->getStates(),
            'country' => $this->countrymodel->getAll(),
        ];
        return view('company/add_company', $data);
    }

    public function companysList()
    {
        check_method_access('companies', 'view');
        $company = $this->companymodel->getCompaniesWithUsers();
        $arr = [];
        $i = 0;

        foreach ($company as $k => $v) {
            $originalDate = $v->license_date;
            $cid = base64_encode($v->id);
            // $view_branch_url = base_url("companies/viewBranch/$cid");
            $actions = '';
            $branch = '';

            if (check_method_access('companies', 'view', true)) {
                $branch .=
                    '<a title="View branch" cid="' . $cid . '" class="btn btn-sm btn-info me-1 viewbranch" href="#">View Branch</a>';
            }
            if (check_method_access('companies', 'edit', true)) {
                $actions .= '<a title="Edit" style="font-size: 1.2rem;"  class="update text-warning me-1" href="' . base_url('companies/edit/' . $v->id) . '" uid="' . $v->id . '" > <i class="fa fa-pencil-square-o"></i></a>';
            }
            if (check_method_access('companies', 'delete', true)) {
                $actions .= '<a title="Delete" style="font-size: 1.2rem;"  class="text-danger sup_delete"  company_id="' . $v->id . '" href="#" title="Delete" > <i class="fa fa-trash-o"></i></a>';
            }

            $company_log = '<img src="' . $v->logo . '"  style="width: 70px;" onerror="error(this)">';

            $arr[] = [
                '<span class="col-md-1">' . $company_log . '</span>',
                '<h6 class="m-0">' . $v->name . "</h6><p class='m-0'><small>" . ucfirst($v->address) . ", " . ucfirst($v->city_name) . ",<br> " . ucfirst($v->state_name) . ", " . $v->pincode . "" . "</small></p> ",
                '<h6 class="m-0">' . $v->first_name . " " . $v->last_name . "</h6><p class='m-0'><small>" . $v->email . "<br> +" . $v->phonecode . ' ' . $v->mobile . "</small></p>",
                '<h5 class="m-0">' . date('d M Y', strtotime($originalDate)) . '</h5>',
                $branch,
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
        check_method_access('companies', 'add');
        $check = $this->validate([
            'username' => 'required|is_unique[users.username]',
        ], [
            'username' => [
                'is_unique' => 'Username is already exist',
            ],
        ]);
        if ($check) {
            $post_data = $this->request->getPost();
            $data = array(
                'username' => $this->request->getPost('username'),
                'first_name' => format_name($this->request->getPost('firstname')),
                'last_name' => format_name($this->request->getPost('lastname')),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('mobile_no'),
                'country_code' => $this->request->getPost('phonecode'),
                'address' => format_name($this->request->getPost('address')),
                'password' => md5($this->request->getPost('password')),
                'state_id' => $this->request->getPost('state_id'),
                'city_id' => $this->request->getPost('citie_id'),
                'status' => 1,
            );
            $user_id = $this->usermodel->insertData($data);

            $data1 = array(
                'name' => format_name($this->request->getPost('companyname')),
                'license_date' => $this->request->getPost('license_date'),
                'iec_code' => $this->request->getPost('ieccode'),
                'website_url' => $this->request->getPost('website'),
                'state_id' => $this->request->getPost('state_id'),
                'city_id' => $this->request->getPost('citie_id'),
                'logo' => $this->_upload_logo(),
                'email' => $this->request->getPost('email'),
                'cin' => $this->request->getPost('cin'),
                'mobile' => $this->request->getPost('mobile_no'),
                'pincode' => $this->request->getPost('pincode'),
                'country' => $this->request->getPost('country'),
                'phonecode' => $this->request->getPost('phonecode'),
                'trans_concept_id' => 1,
                'address' => format_name($this->request->getPost('address')),
            );
            $company_id = $this->companymodel->add_company($data1);

            $data3 = [
                'company_id' => $company_id,
                'user_id' => $user_id,
                'role_id' => 2,
            ];
            $cuid = $this->usermodel->insert_company_user_role($data3);

            $SendEmail = $this->_Send_Welcome_Mail($user_id);

            echo json_encode([
                "status" => 1,
                "msg" => "New Company inserted successfully",
            ]);
        } else {
            echo json_encode([
                "status" => 0,
                "msg" => 'Username is already exist.',
                // "msg" => $this->validation->getErrors(),
            ]);
        }
    }

    private function _upload_logo()
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

    public function edit($id)
    {
        check_method_access('companies', 'edit');
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
                    'address' => format_name($this->request->getPost('address')),
                    'status' => 1,
                );
                $res = $this->usermodel->updateRow($data, "id=$user_id");

                $data1 = array(
                    'name' => format_name($this->request->getPost('companyname')),
                    'license_date' => $this->request->getPost('license_date'),
                    'iec_code' => $this->request->getPost('ieccode'),
                    'website_url' => $this->request->getPost('website'),
                    'state_id' => $this->request->getPost('state_id'),
                    'city_id' => $this->request->getPost('citie_id'),
                    'email' => $this->request->getPost('email'),
                    'cin' => $this->request->getPost('cin'),
                    'mobile' => $this->request->getPost('mobile_no'),
                    'pincode' => $this->request->getPost('pincode'),
                    'country' => $this->request->getPost('country'),
                    'phonecode' => $this->request->getPost('phonecode'),
                    'address' => format_name($this->request->getPost('address')),

                );

                if (!empty($_FILES['logo']['name'])) {
                    $data1 = array_merge($data1, ['logo' => $this->_upload_logo()]);
                }
                $res1 = $this->companymodel->updateRow($data1, "id=$id");

                $role_id = 2;
                $this->usermodel->update_cuser_role($id, $user_id, $role_id);

                if ($res && $res1) {
                    echo json_encode([
                        "status" => 1,
                        "msg" => "Company details updated successfully",
                    ]);
                }
            } else {
                echo json_encode([
                    "status" => 0,
                    "msg" => 'Username is already exist.',
                    // "msg" => $this->validation->getErrors(),
                ]);
            }
        } else {
            $data['data'] = $this->companymodel->get_company_by_id($id);
            $state_id = $data['data']['state_id'];
            $data['city'] = $this->citymodel->get_citys_state_by_id($state_id);
            $data['session'] = $this->session;
            $data['roles'] = $this->rolesmodel->getRoles();
            $data['state'] = $this->statemodel->getStates();
            $data['country'] = $this->countrymodel->getAll();
            return view('company/edit_company', $data);
        }
    }

    public function delete_company()
    {
        check_method_access('companies', 'delete');
        $id = $this->request->getPost('id');
        $this->companymodel->deleteRow($id);
        echo json_encode([
            'status' => 1,
            'msg' => 'Company was deleted successfully',
        ]);

    }

    public function get_company_users($cid)
    {
        check_method_access('companies', 'view');
        $res = $this->usermodel->get_company_users($cid);
        if (!empty($res)) {
            json_response(1, 'Successfully Fetched', $res);
        } else {
            json_response(0, 'Users not found', $res);
        }
    }

    public function _Send_Welcome_Mail($uid)
    {
        $info = $this->usermodel->getUserMail($uid);

        $comp_logo = base_url('/public/uploads/image_found/comp_logo.jpg');
        $email = $info->email;
        $data = [
            'comp_logo' => $comp_logo,
            'username' => $info->username,
            'password' => $info->password,
        ];

        $EmailBody = base_url('/public/SendEmail/mail.htm');
        $htm = file_get_contents($EmailBody);
        $body = $parse = $this->parser->setData($data)->renderString($htm);

        $email_obj = new SendEmail();
        $res = $email_obj->send($email, 'Welcome', $body);
        return 1;

    }

    public function getPincode()
    {
        $post_data = $this->request->getPost();
        $getState_and_City = $this->companymodel->get_state_and_city($post_data);

        $state_id = $getState_and_City->state_id;
        $city_id = $getState_and_City->city_id;

        $state = $this->statemodel->get_state_by_id($state_id);
        $cities = $this->citymodel->get_cities_id($state_id);

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

    // Inside the list, click on the button View branch picture to display the list of branches which appear in the company's behavior
    //We get to show the list of branch per company id basis
    //add, Edit, Delete, functionality form company id basis

    public function viewBranch($cid)
    {
        check_method_access('companies', 'view');
        $data = [
            'cid' => $cid,
            'session' => $this->session,
        ];
        return view('company/viewbranch/branch_list', $data);
    }

    public function branches_List()
    {
        check_method_access('companies', 'view');
        $cid = $this->request->getGet('cid');
        $branches = $this->companymodel->getBranch_by_company_id($cid);

        $arr = [];
        $i = 0;
        foreach ($branches as $k => $v) {
            $originalDate = $v->created_at;

            $actions = '';

            if (check_method_access('companies', 'edit', true)) {
                $actions .= '<a title="Edit" style="font-size: 1.2rem;" class="update text-warning me-1" href="' . base_url('companies/edit_view_branch/' . $v->branch_id) . '" uid="' . $v->branch_id . '" > <i class="fa fa-pencil-square-o"></i></a>';
            }
            if (check_method_access('companies', 'delete', true)) {
                $actions .= '<a title="Delete" style="font-size: 1.2rem;" class="text-danger sup_delete"  branch_id="' . $v->branch_id . '" uid="' . $v->company_id . '" href="#" title="Delete" > <i class="fa fa-trash-o"></i></a>';
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

    public function ADDVIEWBRANCH($cid)
    {

        check_method_access('companies', 'add');
        $data = [
            'cid' => $cid,
            'session' => $this->session,
            'state' => $this->statemodel->getStates(),
            'roles' => $this->rolesmodel->getRoles(),
            'country' => $this->countrymodel->getAll(),
        ];
        return view('company/viewbranch/add_view_branch', $data);

    }
    public function add_view_branch()
    {
        check_method_access('companies', 'add');
        $check = $this->validate([
            'username' => 'required|is_unique[users.username]',
        ], [
            'username' => [
                'is_unique' => 'Username is already exist',
            ],
        ]);
        if ($check) {
            $post_data = $this->request->getPost();
            // prd(base64_decode($post_data['cid']));
            $company_id = base64_decode($post_data['cid']);
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
            // prd($data1);
            $branch_id = $this->companymodel->addview_branches($data1);

            // Add start no as company default start no
            $data3 = [
                'company_id' => $company_id,
                'user_id' => $user_id,
                'branch_id' => $branch_id,
                'role_id' => 3,
            ];
            $cuid = $this->usermodel->insert_company_user_role($data3);

            $start_no = $this->companymodel->getFeilds_by_id('start_no', 'id=' . $company_id);
            $trans_type = $this->companymodel->getTransType($company_id);
            foreach ($trans_type as $k => $v) {
                $data4[] = [
                    'company_id' => $company_id,
                    'branch_id' => $branch_id,
                    'trans_type_id' => $v->id,
                    'prefix' => "",
                    'start_no' => $start_no->start_no,
                ];
            }
            $result = $this->companymodel->insertData($data4);

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

    public function edit_view_branch($id)
    {
        check_method_access('companies', 'edit');
        if ($this->request->getPost('submit')) {
            $check = $this->validate([
                'username' => 'required|is_unique[users.username,id,{user_id}]',
            ], [
                'username' => [
                    'is_unique' => 'Username is already exist',
                ],
            ]);
            if ($check) {
                // prd($this->request->getPost());
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

                $branches_id = $this->companymodel->updateRow_branch($data1, "id=$id");

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
            $data['data'] = $this->companymodel->get_branches_by_id($id);
            $state_id = $data['data']['state_id'];
            $data['city'] = $this->citymodel->get_citys_state_by_id($state_id);
            $data['session'] = $this->session;
            $data['state'] = $this->statemodel->getStates();
            $data['roles'] = $this->rolesmodel->getRoles();
            $data['country'] = $this->countrymodel->getAll();

            return view('company/viewbranch/edit_view_branch', $data);
        }

    }

    public function deleted_branches()
    {
        check_method_access('companies', 'delete');
        $id = $this->request->getPost('id');
        $this->companymodel->deleteRow_branch($id);
        echo json_encode([
            'status' => 1,
            'msg' => 'Branches was deleted successfully',
        ]);

    }
}