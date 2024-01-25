<?php

namespace App\Controllers\settings;

use App\Controllers\BaseController;

class Invoiceconcept extends BaseController
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
        check_access('Invoiceconcept');
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
                'trans_concept_master' => $this->general_setting->getTransConceptMaster($company_id),
            ];
            return view('general_setting/company_setting/invoices_concept', $data);
        }

        //active role id  is 3 for branch manager (login with branch )
        // this load view for general setting -> branch setting (folder)-> general setting (file)

        if ($active_role_id == 3) {

            $branch_id = $this->session->user_details['branch_id'];

            $data = [
                'session' => $this->session,
                'trans_concept_master' => $this->general_setting->getTransConceptMaster($company_id),
                'info' => $this->branches->get_branches_by_id($branch_id),
            ];

            return view('general_setting/branch_setting/invoices_concept', $data);
        }

    }

    // add to invoice  concept  for company
    //  login with comoany admin .......................

    public function company_invoice_concept()
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

    // add to invoice concept for branch manager
    // login with branch manager

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
}