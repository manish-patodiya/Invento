<?php

namespace App\Controllers\settings;

use App\Controllers\BaseController;

class States extends BaseController
{
    public $usermodel;
    public $statemodel;
    public $countrymodel;
    public function __construct()
    {
        check_auth();
        check_access('states');
        $this->usermodel = model('UserModel');
        $this->statemodel = model('StateModel');
        $this->countrymodel = model('CountryModel');
    }
    public function index()
    {
        check_method_access('states', 'view');
        $country = $this->countrymodel->getAll();
        $data = [
            'session' => $this->session,
            'country' => $country,
        ];
        return view('settings/states/states_list', $data);
    }

    public function states_list()
    {
        check_method_access('states', 'view');
        $states = $this->statemodel->getAll();
        $arr = [];
        $i = 0;
        foreach ($states as $k => $v) {
            $action = '';
            if (check_method_access('states', 'edit', true)) {
                $action .= '<a title="Edit" class="text-warning sup_update me-1" href="#" state_id="' . $v->state_id . '" style="font-size: 1.2rem;"> <i class="fa fa-pencil-square-o"></i></a>';
            }
            if (check_method_access('states', 'delete', true)) {
                $action .= '<a title="Delete" class="text-danger sup_delete me-1"  state_id="' . $v->state_id . '" href="#" title="Delete" style="font-size: 1.2rem;"> <i class="fa fa-trash-o"></i></a>';
            }
            $arr[] = [
                ++$i,
                $v->state_name,
                $v->state_code,
                $v->name,
                $action,
            ];
        }
        echo json_encode([
            "status" => 1,
            "details" => $arr,
        ]);
    }

    public function add()
    {
        check_method_access('states', 'add');
        $data = array(
            "state_name" => $this->request->getPost('state_name'),
            "state_code" => $this->request->getPost('state_code'),
            "country_id" => $this->request->getPost('country_id'),
        );
        $state_id = $this->statemodel->insertData($data);
        if ($state_id) {
            echo json_encode([
                "status" => 1,
            ]);
        }
    }
    public function edit()
    {
        check_method_access('states', 'edit');
        if ($this->request->getPost('submit')) {
            $id = $this->request->getPost('state_id');
            $data = array(
                "state_name" => $this->request->getPost('state_name'),
                "state_code" => $this->request->getPost('state_code'),
                "country_id" => $this->request->getPost('country_id'),
            );
            $state = $this->statemodel->updateRow($data, "state_id=$id");
            if ($state) {
                echo json_encode([
                    "status" => 1,
                    "msg" => "updated Successfully state",
                ]);
            }
        } else {
            $data['country'] = $this->countrymodel->getAll();
            return view('sub_modals/edit_state_modal', $data);
        }
    }
    public function delete()
    {
        check_method_access('states', 'delete');
        $id = $this->request->getPost('id');
        $result = $this->statemodel->deleteRow($id);
        if ($result) {
            echo json_encode([
                "status" => 1,
            ]);
        }
    }
    public function get_state_id()
    {
        $id = $this->request->getPost('id');
        $data = $this->statemodel->get_state_by_id($id);

        if (!empty($data)) {
            json_response(1, 'Successfully Fetched', $data);
        } else {
            json_response(0, 'Users not found', $data);
        }
    }
}