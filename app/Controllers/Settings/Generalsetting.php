<?php

namespace App\Controllers\settings;

use App\Controllers\BaseController;

class Generalsetting extends BaseController
{
    public $general_setting;
    public $companymodel;
    public $citymodel;
    public $countrymodel;
    public $rolesmodel;
    public $statemodel;
    public $usermodel;

    public function __construct()
    {
        check_auth();
        check_access('generalsetting');
        $this->general_setting = model('GeneralSetting');
        $this->companymodel = model('CompanyModel');
        $this->citymodel = model('CityModel');
        $this->countrymodel = model('CountryModel');
        $this->rolesmodel = model('UserRoles');
        $this->statemodel = model('StateModel');
        $this->usermodel = model('UserModel');
        $this->branches = model('Branches');
        $this->transactionmodel = model('TransactionModel');
    }
    public function index()
    {
        //this session  get to active role id and company id
        $active_role_id = $this->session->user_details['active_role_id'];
        $company_id = $this->session->user_details['company_id'];

        // active role id (2=comapny admin , 3= branch manager)

        //  this geeneral setting is load view for this role id is tow for comapny  admin(but compan login....)
        if ($active_role_id == 2) {
            $data['data'] = $this->companymodel->get_company_by_id($company_id);
            $state_id = $data['data']['state_id'];
            $data['city'] = $this->citymodel->get_citys_state_by_id($state_id);
            $data['session'] = $this->session;
            $data['trans_concept_master'] = $this->general_setting->getTransConceptMaster($company_id);
            $data['roles'] = $this->rolesmodel->getRoles();
            $data['state'] = $this->statemodel->getStates();
            $data['country'] = $this->countrymodel->getAll();
            return view('general_setting/company_setting/general_setting', $data);
        }

        //active role id  is 3 for branch manager (login with branch )
        // this load view for general setting -> branch setting (folder)-> general setting (file)

        if ($active_role_id == 3) {
            $company_id = $this->session->user_details['company_id'];
            $branch_id = $this->session->user_details['branch_id'];
            $data['trans_concept_master'] = $this->general_setting->getTransConceptMaster($company_id);
            $data['trans_type'] = $this->general_setting->getAll($branch_id);
            $data['start_no'] = $this->companymodel->getFeilds_by_id('start_no', 'id=' . $company_id);
            $data['data'] = $this->branches->get_branches_by_id($branch_id);
            $state_id = $data['data']['state_id'];
            $data['city'] = $this->citymodel->get_citys_state_by_id($state_id);
            $data['cid'] = $this->companymodel->getAll();
            $data['country'] = $this->countrymodel->getAll();
            $data['session'] = $this->session;
            $data['state'] = $this->statemodel->getStates();
            $data['roles'] = $this->rolesmodel->getRoles();
            return view('general_setting/branch_setting/general_setting', $data);
        }
    }

    // genernal setting update for company details are comapny admin done
    public function comp_update_details($id)
    {
        if ($this->request->getPost('submit')) {
            $user_id = $this->request->getPost('user_id');
            $data = array(
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('mobile_no'),
                'address' => format_name($this->request->getPost('address')),
                'status' => 1,
            );
            $res = $this->usermodel->updateRow($data, "id=$user_id");

            $data1 = array(
                'name' => format_name($this->request->getPost('companyname')),
                'iec_code' => $this->request->getPost('ieccode'),
                'website_url' => $this->request->getPost('website'),
                'state_id' => $this->request->getPost('state_id'),
                'city_id' => $this->request->getPost('citie_id'),
                'email' => $this->request->getPost('email'),
                'mobile' => $this->request->getPost('mobile_no'),
                'phonecode' => $this->request->getPost('phonecode'),
                'country' => $this->request->getPost('country'),
                'pincode' => $this->request->getPost('pincode'),
                'address' => format_name($this->request->getPost('address')),

            );

            if (!empty($_FILES['logo']['name'])) {
                $logo = $this->_upload_logo();

                //change log in session
                $user_details = $this->session->get('user_details');
                $user_details['comp_logo'] = $logo;
                $this->session->set('user_details', $user_details);

                $data1 = array_merge($data1, ['logo' => $logo]);
            }
            // $res1 = $this->companymodel->updateRow($data1, "id=$id");
            // $role_id = $this->session->get('user_details')['active_role_id'];

            // $this->usermodel->update_cuser_role($id, $user_id, $role_id);

            if ($res && $res1) {
                echo json_encode([
                    "status" => 1,
                    "msg" => "Company details updated successfully",
                ]);
            }
        }
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

    // genernal setting update for branch details
    public function branch_updata_details($id)
    {
        $user_id = $this->request->getPost('user_id');
        $data = array(
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
            'country' => $this->request->getPost('country'),
            'pincode' => $this->request->getPost('pincode'),
        );
        $gst_no = $this->request->getPost('gst_no');
        $data1 = array_merge($data1, ['gst_no' => $gst_no]);
        $user_details = $this->session->get('user_details');
        $user_details['gst_no'] = $gst_no;
        $this->session->set('user_details', $user_details);

        $branches_id = $this->branches->updateRow($data1, "id=$id");

        // no use for role  in general setting
        // $data2 = array(
        //     'company_id' => $this->session->get('user_details')['company_id'],
        //     'user_id' => $user_id,
        //     'branch_id' => $id,
        // );
        // $update = $this->branches->update_cuser_role($data2, "branch_id=$id");

        echo json_encode([
            "status" => 1,
            "msg" => "Branches details updated successfully",
        ]);
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

    public function getPincode()
    {

        $post_data = $this->request->getPost();
        $getState_and_City = $this->general_setting->get_state_and_city($post_data);

        $state_id = $getState_and_City->state_id;
        $city_id = $getState_and_City->city_id;

        $state = $this->general_setting->get_state_by_id($state_id);
        $cities = $this->general_setting->get_cities_id($state_id);

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