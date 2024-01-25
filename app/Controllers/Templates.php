<?php

namespace App\Controllers;

class Templates extends BaseController
{
    public $templates;
    public function __construct()
    {
        check_auth();
        check_access('templates');

        $this->templates = model('Templates');
    }
    public function index()
    {
        check_method_access('templates', 'view');
        $data = [
            'session' => $this->session,
        ];
        return view('templates/templates_list', $data);
    }
    public function add_templates()
    {
        check_method_access('templates', 'add');
        $info = model('Branches')->get_branch_info();
        $data = [
            'info' => $info,
            'session' => $this->session,
        ];
        return view('templates/add_templates', $data);
    }
    public function templatesList()
    {
        check_method_access('templates', 'view');
        $templates = $this->templates->getAll();
        $arr = [];
        $i = 0;
        foreach ($templates as $k => $v) {
            $action = '';
            if (check_method_access('templates', 'edit', true)) {
                $action .= '<a title="Edit" class="text-primary sup_update me-1"  href="' . base_url('templates/edit/' . $v->id) . '"temp_id="' . $v->id . '" style="font-size: 1.2rem;"> <i class="fa fa-eye"></i></a>';
            }
            // if (check_method_access('templates', 'delete', true)) {
            //     $action .= '<a title="Delete" class="delete btn btn-sm  btn-danger sup_delete me-1"  uid="' . $v->id . '" href="#" title="Delete"  > <i class="fa fa-trash-o"></i></a>';
            // }
            $arr[] = [
                ++$i,
                $v->title,
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
        check_method_access('templates', 'add');
        $data = array(
            "title" => format_name($this->request->getPost('title')),
            "subject" => $this->request->getPost('subject'),
            "content" => $this->request->getPost('content'),
        );
        $templates_id = $this->templates->insertData($data);
        if ($templates_id) {
            echo json_encode([
                "status" => 1,
                "msg" => "Insert Successfully Template ",
            ]);
        }
    }
    public function deleted()
    {
        prd('test');
        check_method_access('templates', 'delete');
        $id = $this->request->getPost('id');
        $this->templates->deleteRow($id);
        echo json_encode([
            'status' => 1,
            'msg' => 'templates was deleted successfully',
        ]);

    }

    public function edit($id)
    {
        check_method_access('templates', 'edit');
        if ($this->request->getPost('submit')) {
            $temp_id = $this->request->getPost('temp_id');
            $data = array(
                // "title" => format_name($this->request->getPost('title')),
                // "subject" => $this->request->getPost('subject'),
                "content" => $this->request->getPost('content'),
            );
            $update = $this->templates->updateRow($data, "id=$temp_id");
            if ($update) {
                echo json_encode([
                    "status" => 1,
                    "msg" => "Updated Successfully Template ",
                ]);
            }
        } else {
            $tmp_data = $this->templates->get_temp_by_id($id);
            $data['temp'] = $tmp_data;
            $data['session'] = $this->session;
            if ($tmp_data['sub_type_id'] == 1) {
                return view('templates/edit_header', $data);
            } else {
                // if ($tmp_data['sub_type_id'] == 2)
                return view('templates/edit_templates', $data);
            }
        }
    }

    // private function _upload_logo()
    // {
    //     prd('test');
    //     $logo = $this->request->getFile('logo');
    //     $file_path = '';
    //     if ($logo->isValid()) {
    //         $upload_path = 'public/uploads/companise_logo/';
    //         $logo_name = $logo->getRandomName();
    //         $res = $logo->move($upload_path, $logo_name);
    //         if ($res) {
    //             $file_path = base_url($upload_path . $logo_name);
    //         }
    //     }
    //     return $file_path;

    // }
    public function upload_logo()
    {
        $logo = $this->request->getFile('file');
        $file_path = '';
        if ($logo->isValid()) {
            $upload_path = 'public/uploads/companise_logo/';
            $logo_name = $logo->getRandomName();
            $res = $logo->move($upload_path, $logo_name);
            if ($res) {
                $file_path = base_url($upload_path . $logo_name);
            }
        }
        if ($file_path) {
            echo json_encode([
                'status' => 1,
                'info' => $file_path,
                // 'msg' => 'templates was deleted successfully',
            ]);
        }

    }
}