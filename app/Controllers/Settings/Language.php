<?php

namespace App\Controllers\settings;

use App\Controllers\BaseController;

class Language extends BaseController
{
    public $usermodel;

    public $companymodel;
    public $citymodel;
    public $statemodel;
    public $languagemodel;

    public function __construct()
    {
        check_auth();
        check_access('language');
        $this->citymodel = model('CityModel');
        $this->usermodel = model('UserModel');
        $this->companymodel = model('Companymodel');
        $this->statemodel = model('StateModel');
        $this->languagemodel = model('LanguageModel');

    }
    public function index()
    {
        check_method_access('language', 'view');
        $data = [
            'session' => $this->session,
        ];

        return view('settings/languages/languages_list', $data);
    }

    public function languages_list()
    {
        check_method_access('language', 'view');
        $cities = $this->languagemodel->getAll();
        $arr = [];
        $i = 0;
        foreach ($cities as $k => $v) {
            $action = '';
            if (check_method_access('language', 'edit', true)) {
                $action .= '<a title="Edit" style="font-size: 1.2rem;" class="text-warning sup_update me-1" href="#" lang_id="' . $v->id . '" > <i class="fa fa-pencil-square-o"></i></a>';
            }
            if (check_method_access('language', 'delete', true)) {
                $action .= '<a title="Delete" style="font-size: 1.2rem;" class="text-danger sup_delete me-1"  lang_id="' . $v->id . '" href="#" title="Delete" > <i class="fa fa-trash-o"></i></a>';
            }
            $arr[] = [
                ++$i,
                $v->name,
                $v->short_name,
                $v->status == "1" ? "Active" : "Unactive",
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
        check_method_access('language', 'add');
        $data = array(
            "name" => $this->request->getPost('language_name'),
            "short_name" => $this->request->getPost('short_name'),
            "status" => $this->request->getPost('status'),
        );
        $language_id = $this->languagemodel->insertData($data);
        if ($language_id) {
            echo json_encode([
                "status" => 1,
            ]);
        }
    }
    public function edit()
    {
        check_method_access('language', 'edit');
        $id = $this->request->getPost('lang_id');
        $details = array(
            "name" => $this->request->getPost('language_name'),
            "short_name" => $this->request->getPost('short_name'),
            "status" => $this->request->getPost('status'),
        );
        $language_id = $this->languagemodel->updateRow($details, "id=$id");
        if ($language_id) {
            echo json_encode([
                "status" => 1,
            ]);
        }

    }
    public function delete()
    {
        check_method_access('language', 'delete');
        $id = $this->request->getPost('id');
        $result = $this->languagemodel->deleteRow($id);
        if ($result) {
            echo json_encode([
                "status" => 1,
            ]);
        }
    }
    public function get_language_id()
    {
        $id = $this->request->getPost('id');
        $data = $this->languagemodel->get_language_by_id($id);
        if (!empty($data)) {
            json_response(1, 'Successfully Fetched', $data);
        } else {
            json_response(0, 'Users not found', $data);
        }
    }
}