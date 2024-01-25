<?php

namespace App\Controllers\product;

use App\Controllers\BaseController;

class Unit extends BaseController
{
    public $unitModel;
    public function __construct()
    {
        check_auth();
        check_access('unit');

        $this->unitModel = model('UnitModel');
    }
    public function index()
    {
        check_method_access('unit', 'view');
        $data = [
            'session' => $this->session,
            'unit' => $this->unitModel->getAll_unit(),
        ];
        return view('product/unit/unit_list', $data);
    }
    public function unitList()
    {
        check_method_access('unit', 'view');
        $unit = $this->unitModel->getAll();
        $arr = [];
        $i = 0;
        foreach ($unit as $k => $v) {
            $action = '';
            if (check_method_access('unit', 'edit', true)) {
                $action .= '<a title="Edit" class="text-warning sup_update me-1" href="#" unit_id="' . $v->id . '" style="font-size: 1.2rem;"> <i class="fa fa-pencil-square-o"></i></a>';
            }
            if (check_method_access('unit', 'delete', true)) {
                $action .= '<a title="Delete" class="text-danger sup_delete me-1"  uid="' . $v->id . '" href="#" title="Delete"  style="font-size: 1.2rem;"> <i class="fa fa-trash-o"></i></a>';
            }
            $arr[] = [
                ++$i,
                $v->title,
                $v->unit,
                $v->conversion_rate,
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
        check_method_access('unit', 'add');
        $data = array(
            "title" => format_name($this->request->getPost('title')),
            "base_unit" => $this->request->getPost('base_unit'),
            "conversion_rate" => $this->request->getPost('con_rate'),
        );
        $unit_id = $this->unitModel->insertData($data);
        if ($unit_id) {
            echo json_encode([
                "status" => 1,
                "msg" => "Inserted Successfully units ",
            ]);
        }
    }

    public function edit()
    {
        check_method_access('unit', 'edit');
        if ($this->request->getPost('submit')) {
            $id = $this->request->getPost('unit');
            $data = array(
                "title" => format_name($this->request->getPost('title')),
                "base_unit" => $this->request->getPost('base_unit'),
                "conversion_rate" => $this->request->getPost('con_rate'),
            );
            $unit_id = $this->unitModel->updateRow($data, "id=$id");
            if ($unit_id) {
                echo json_encode([
                    "status" => 1,
                    "msg" => "Updated Successfully units ",
                ]);
            }
        }
    }
    public function deleted()
    {
        check_method_access('unit', 'delete');
        $id = $this->request->getPost('id');
        $this->unitModel->deleteRow($id);
        echo json_encode([
            'status' => 1,
            'msg' => 'unit was deleted successfully',
        ]);

    }
    public function get_unit_id()
    {
        $id = $this->request->getPost('id');
        $res = $this->unitModel->get_unit($id);

        if (!empty($res)) {
            json_response(1, 'Successfully Fetched', $res);
        } else {
            json_response(0, 'Users not found', $res);
        }
    }

}