<?php

namespace App\Controllers\product;

use App\Controllers\BaseController;

class Categories extends BaseController
{
    public $categorymodel;
    public function __construct()
    {
        check_auth();
        check_access('categories');
        $this->categorymodel = model('product_models/CategoryModel');
    }

    public function index()
    {
        check_method_access('categories', 'view');
        $data = [
            'session' => $this->session,
        ];
        return view('product/category/category_list', $data);
    }
    public function categoriesList()
    {
        check_method_access('categories', 'view');
        $category = $this->categorymodel->getAll();
        $arr = [];
        $i = 0;
        foreach ($category as $k => $v) {
            $action = '';
            if (check_method_access('categories', 'edit', true)) {
                $action .= '<a title="Edit" class="text-warning sup_update me-1" href="#" cate_id="' . $v->id . '" style="font-size: 1.2rem;"> <i class="fa fa-pencil-square-o"></i></a>';
            }
            if (check_method_access('categories', 'delete', true)) {
                $action .= '<a title="Delete" class="text-danger sup_delete me-1"  uid="' . $v->id . '" href="#" title="Delete" style="font-size: 1.2rem;" > <i class="fa fa-trash-o"></i></a>';
            }
            $arr[] = [
                ++$i,
                $v->category_name,
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
        check_method_access('categories', 'add');
        $data = array(
            "category_name" => format_name($this->request->getPost('category_name')),
        );
        $cate_id = $this->categorymodel->insertData($data);
        if ($cate_id) {
            echo json_encode([
                "status" => 1,
                "msg" => "Insert Successfully category ",
            ]);
        }
    }

    public function edit()
    {
        check_method_access('categories', 'edit');
        if ($this->request->getPost('submit')) {
            $id = $this->request->getPost('cate_id');
            $data = array(
                "category_name" => format_name($this->request->getPost('category_name')),

            );
            $category = $this->categorymodel->updateRow($data, "id=$id");
            if ($category) {
                echo json_encode([
                    "status" => 1,
                    "msg" => "updated Successfully category ",
                ]);
            }
        } else {
            $data['unit'] = $this->categorymodel->getAll_unit();
            return view('sub_modals/edit_category_modal', $data);
        }
    }
    public function deleted()
    {
        check_method_access('categories', 'delete');
        $id = $this->request->getPost('id');
        $this->categorymodel->deleteRow($id);
        echo json_encode([
            'status' => 1,
            'msg' => 'category was deleted successfully',
        ]);

    }
    public function get_categories_id()
    {
        $id = $this->request->getPost('id');
        $res = $this->categorymodel->get_category($id);

        if (!empty($res)) {
            json_response(1, 'Successfully Fetched', $res);
        } else {
            json_response(0, 'Category not found', $res);
        }
    }

    public function uploadCSV()
    {
        check_method_access('categories', 'add');
        $file = $this->request->getFile('csv');
        $type = $file->guessExtension();
        if ($file->isValid() && $type == "csv") {
            $rows = array_map('str_getcsv', file($file));
            $header = array_shift($rows);
            $csv = array();
            foreach ($rows as $row) {
                if ($row) {
                    $csv[] = array_combine($header, $row);
                }
            }
            $all = [];
            foreach ($csv as $value) {
                array_shift($value);
                array_push($all, $value);
            }
            $cate_id;
            foreach ($all as $key => $cate_name) {
                $data = array(
                    "category_name" => format_name(strtolower($cate_name['category'])),
                );
                $cate_id = $this->categorymodel->insertData($data);
            }
            if ($cate_id) {
                echo json_encode([
                    "status" => 1,
                    "msg" => "CSV import successfully",
                ]);
            }
        } else {
            echo json_encode([
                "status" => 0,
                "msg" => "Not a CSV file",
            ]);
        }
    }
}