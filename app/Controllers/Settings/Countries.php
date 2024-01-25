<?php

namespace App\Controllers\settings;

use App\Controllers\BaseController;

class Countries extends BaseController
{
    public function __construct()
    {
        check_auth();
        check_access('countries');
        $this->countrymodel = model('CountryModel');
    }
    public function index()
    {
        check_method_access('countries', 'view');
        $data = [
            'session' => $this->session,
        ];
        return view('settings/countries/countries_list', $data);
    }

    public function countries_list()
    {
        check_method_access('countries', 'view');
        $countries = $this->countrymodel->getAll();
        $arr = [];
        $i = 0;
        foreach ($countries as $k => $v) {
            $action = '';
            if (check_method_access('countries', 'edit', true)) {
                $action .= '<a title="Edit" class="text-warning sup_update me-1" href="#" country_id="' . $v->id . '" style="font-size: 1.2rem;"> <i class="fa fa-pencil-square-o"></i></a>';
            }
            if (check_method_access('countries', 'delete', true)) {
                $action .= '<a title="Delete" class="text-danger sup_delete me-1" alt="alert"  country_id="' . $v->id . '" href="#" title="Delete" style="font-size: 1.2rem;"> <i class="fa fa-trash-o"></i></a>';
            }
            $arr[] = [
                ++$i,
                $v->name,
                $v->sortname,
                $v->phonecode,
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
        check_method_access('countries', 'add');
        $data = array(
            "name" => trim(ucwords($this->request->getPost('country_name'))),
            "sortname" => strtoupper(trim($this->request->getPost('sort_name'))),
            "slug" => $this->request->getPost('slug'),
            "phonecode" => $this->request->getPost('phone_code'),
        );
        $country_id = $this->countrymodel->insertData($data);
        if ($country_id) {
            json_response(1, 'Successfully insert country');
        } else {
            json_response(0, 'Something went wrong');
        }
    }
    public function edit()
    {
        check_method_access('countries', 'edit');
        $id = $this->request->getPost('country_id');
        $details = array(
            "name" => trim(ucwords($this->request->getPost('country_name'))),
            "sortname" => strtoupper(trim($this->request->getPost('sort_name'))),
            "slug" => $this->request->getPost('slug'),
            "phonecode" => $this->request->getPost('phone_code'),
        );
        $res = $this->countrymodel->updateRow($details, "id=$id");
        if ($res) {
            json_response(1, 'Succsessfully updated');
        }

    }

    public function delete()
    {
        check_method_access('countries', 'delete');
        $id = $this->request->getPost('id');
        $result = $this->countrymodel->deleteRow($id);
        if ($result) {
            json_response(1, 'Succsessfully deleted');
        }
    }
    public function get_country_id()
    {
        $id = $this->request->getPost('id');
        $data = $this->countrymodel->get_country_by_id($id);

        if (!empty($data)) {
            json_response(1, 'Successfully Fetched', $data);
        } else {
            json_response(0, 'Users not found', $data);
        }
    }
}