<?php

namespace App\Controllers\settings;

use App\Controllers\BaseController;

class Transactionsetting extends BaseController
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
        check_access('transactionsetting');
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
        // this session is get to company id and branch id ,and view for company admin and branch manager but not permission allow for super admin
        // strat number is get by id in conpany id

        //this session  get to active role id and company id
        $active_role_id = $this->session->user_details['active_role_id'];
        $company_id = $this->session->user_details['company_id'];

        // active role id (2=comapny admin , 3= branch manager)
        if ($active_role_id == 2) {

            $data = [
                'session' => $this->session,
                'start_no' => $this->companymodel->getFeilds_by_id('start_no', 'id=' . $company_id),
            ];
            return view('general_setting/company_setting/transaction_setting', $data);
        }

        //active role id  is 3 for branch manager (login with branch )
        // this load view for general setting -> branch setting (folder)-> general setting (file)

        if ($active_role_id == 3) {

            $branch_id = $this->session->user_details['branch_id'];

            $data = [
                'session' => $this->session,
                'trans_concept_master' => $this->general_setting->getTransConceptMaster($company_id),
                'trans_type' => $this->general_setting->getAll($branch_id),
                'start_no' => $this->companymodel->getFeilds_by_id('start_no', 'id=' . $company_id),
                'data' => $this->branches->get_branches_by_id($branch_id),
            ];
            return view('general_setting/branch_setting/transaction_setting', $data);
        }

    }

    // update for start number in company
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

    // genernal setting add for company details
    public function branch_tranch_add()
    {
        //get to company and branch id for  this session
        $company_id = $this->session->get('user_details')['company_id'];
        $branch_id = $this->session->get('user_details')['branch_id'];

        $prefix = $this->request->getPost('prefix');
        $trans_type_id = $this->request->getPost('trans_type_id');

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

}