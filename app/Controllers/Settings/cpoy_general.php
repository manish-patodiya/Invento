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
        if ($this->session->user_details['branch_id'] == "0") {
            $company_id = $this->session->user_details['company_id'];
            $data['data'] = $this->companymodel->get_company_by_id($company_id);
            $state_id = $data['data']['state_id'];
            $data['city'] = $this->citymodel->get_citys_state_by_id($state_id);
            $data['session'] = $this->session;
            $data['trans_concept_master'] = $this->general_setting->getTransConceptMaster($company_id);
            $data['roles'] = $this->rolesmodel->getRoles();
            $data['state'] = $this->statemodel->getStates();
            return view('general_setting/general_setting', $data);
        } else {
            $company_id = $this->session->user_details['company_id'];
            $branch_id = $this->session->user_details['branch_id'];
            $data['trans_concept_master'] = $this->general_setting->getTransConceptMaster($company_id);
            $data['trans_type'] = $this->general_setting->getAll($branch_id);
            $data['start_no'] = $this->companymodel->getFeilds_by_id('start_no', 'id=' . $company_id);
            $data['data'] = $this->branches->get_branches_by_id($branch_id);
            $state_id = $data['data']['state_id'];
            $data['city'] = $this->citymodel->get_citys_state_by_id($state_id);
            $data['cid'] = $this->companymodel->getAll();
            $data['session'] = $this->session;
            $data['state'] = $this->statemodel->getStates();
            $data['roles'] = $this->rolesmodel->getRoles();
            return view('general_setting/general_setting', $data);
        }
    }
    // genernal setting add for company details
    public function add()
    {
        $trans_type_id = $this->request->getPost('trans_type_id');
        $prefix = $this->request->getPost('prefix');
        $company_id = $this->session->get('user_details')['company_id'];
        $branch_id = $this->session->get('user_details')['branch_id'];
        $data = [];
        foreach ($prefix as $k => $v) {
            $data[] = [
                'company_id' => $company_id,
                'branch_id' => $branch_id,
                'trans_type_id' => $trans_type_id[$k],
                'prefix' => $v,
                'start_no' => $this->request->getPost('start_no[' . $k . ']'),
            ];
        }
        $result = $this->general_setting->insertData($data, $branch_id);
        if ($result) {
            echo json_encode([
                "status" => 1,
                "msg" => "Transaction setting inserted successfully",
            ]);
        }
    }

    // genernal setting update for company details
    public function edit($id)
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
            $res1 = $this->companymodel->updateRow($data1, "id=$id");
            $role_id = $this->session->get('user_details')['active_role_id'];

            $this->usermodel->update_cuser_role($id, $user_id, $role_id);

            if ($res && $res1) {
                echo json_encode([
                    "status" => 1,
                    "msg" => "Company details updated successfully",
                ]);
            }
        }
    }

    // genernal setting update for branch details
    public function edit_branch($id)
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
        );
        $gst_no = $this->request->getPost('gst_no');
        $data1 = array_merge($data1, ['gst_no' => $gst_no]);
        $user_details = $this->session->get('user_details');
        $user_details['gst_no'] = $gst_no;
        $this->session->set('user_details', $user_details);

        $branches_id = $this->branches->updateRow($data1, "id=$id");
        $data2 = array(
            'company_id' => $this->session->get('user_details')['company_id'],
            'user_id' => $user_id,
            'branch_id' => $id,
        );
        $update = $this->branches->update_cuser_role($data2, "branch_id=$id");

        echo json_encode([
            "status" => 1,
            "msg" => "Branches details updated successfully",
        ]);
    }

    public function add_invoice_concept()
    {
        $data['trans_concept_id'] = $this->request->getPost('group4');
        $company_id = $this->session->get('user_details')['company_id'];
        $result = $this->companymodel->insertTransConceptId($data, $company_id);
        $branches = $this->companymodel->getBranches($company_id);
        $non_updated_branch = [];
        $data['is_updated'] = "1";
        foreach ($branches as $k => $v) {
            $rows = $this->branches->check_is_updated($v->branch_id);
            // Getting branch id invoices

            if ($rows) {
                $non_updated_branch[] .= $this->branches->updateRow($data, 'id=' . $rows->id);
            }

        }
        ($non_updated_branch);
        if ($result) {
            echo json_encode([
                "status" => 1,
                "msg" => "Invoice concept updated successfully",
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
    public function add_branch_invoice_concept()
    {
        $data['trans_concept_id'] = $this->request->getPost('group4');
        $data['is_updated'] = "1";
        $branch_id = $this->session->get('user_details')['branch_id'];
        $result = $this->branches->insertTransConceptId($data, $branch_id);
        if ($result) {
            echo json_encode([
                "status" => 1,
                "msg" => "Invoice concept updated successfully",
            ]);
        }
    }

    public function start_no()
    {
        $data['start_no'] = $this->request->getPost('start_no');
        $company_id = $this->session->user_details['company_id'];
        $branches = $this->companymodel->getBranches($company_id);
        // Getting all branches of login company

        $non_trans_branch_id = [];
        // A array type variable to store non transaction branch ids.

        foreach ($branches as $k => $v) {
            $rows = $this->transactionmodel->getFeild($v->branch_id, $v->company_id);
            // Getting branch id invoices

            if ($rows == "") {
                $non_trans_branch_id[] .= $v->branch_id;
            }
        }
        $result = $this->general_setting->updateStartNo($data, $non_trans_branch_id);
        $result = $this->companymodel->insertTransConceptId($data, $company_id);
        if ($result) {
            echo json_encode([
                "status" => 1,
                "msg" => "Start no updated successfully",
            ]);
        }
    }

    public function menu()
    {
        $data['session'] = $this->session;
        return view('settings/menu', $data);
    }
}