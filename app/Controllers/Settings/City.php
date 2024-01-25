<?php

namespace App\Controllers\settings;

use App\Controllers\BaseController;

class City extends BaseController
{
    public $usermodel;

    public $companymodel;
    public $citymodel;
    public $statemodel;

    public function __construct()
    {
        check_auth();
        check_access('city');
        $this->citymodel = model('CityModel');
        $this->usermodel = model('UserModel');
        $this->companymodel = model('CompanyModel');
        $this->statemodel = model('StateModel');

    }
    public function index()
    {
        check_method_access('city', 'view');
        $state = $this->statemodel->getAll();
        $data = [
            'session' => $this->session,
            'state' => $state,
        ];

        return view('settings/cities/cities_list', $data);
    }

    public function cities_list()
    {
        check_method_access('city', 'view');
        $cities = $this->citymodel->getAll();
        $arr = [];
        $i = 0;
        foreach ($cities as $k => $v) {
            $action = '';
            if (check_method_access('city', 'edit', true)) {
                $action .= '<a title="Edit" class="text-warning sup_update me-1" href="#" cities_id="' . $v->city_id . '" style="font-size: 1.2rem;"> <i class="fa fa-pencil-square-o"></i></a>';
            }
            if (check_method_access('city', 'delete', true)) {
                $action .= '<a title="Delete" class="text-danger sup_delete"  style="font-size: 1.2rem;" city_id="' . $v->city_id . '" href="#" title="Delete" > <i class="fa fa-trash-o"></i></a>';
            }

            $arr[] = [
                ++$i,
                $v->city_name,
                $v->state_name,
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
        check_method_access('city', 'add');
        $data = array(
            "city_name" => $this->request->getPost('city_name'),
            "state_id" => $this->request->getPost('state_id'),
        );
        $city_id = $this->citymodel->insertData($data);
        if ($city_id) {
            echo json_encode([
                "status" => 1,
                "msg" => "Insert Successfully city ",
            ]);
        }
    }
    public function edit()
    {
        check_method_access('city', 'edit');
        if ($this->request->getPost('submit')) {
            $id = $this->request->getPost('city_id');
            $data = array(
                "city_name" => $this->request->getPost('city_name'),
                "state_id" => $this->request->getPost('state_id'),
            );
            $city = $this->citymodel->updateRow($data, "city_id=$id");
            if ($city) {
                echo json_encode([
                    "status" => 1,
                    "msg" => "updated Successfully city",
                ]);
            }
        } else {
            $data['state'] = $this->statemodel->getAll();
            return view('sub_modals/edit_cities_modal', $data);
        }
    }

    public function delete()
    {
        check_method_access('city', 'delete');
        $id = $this->request->getPost('id');
        $result = $this->citymodel->deleteRow($id);
        if ($result) {
            echo json_encode([
                "status" => 1,
            ]);
        }
    }
    public function get_cities_id()
    {
        $id = $this->request->getPost('id');
        $data = $this->citymodel->get_city_by_id($id);

        if (!empty($data)) {
            json_response(1, 'Successfully Fetched', $data);
        } else {
            json_response(0, 'Users not found', $data);
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
}