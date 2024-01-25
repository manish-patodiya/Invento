<?php
namespace App\Controllers;

class Address extends BaseController
{
    public function __construct()
    {
        check_auth();
        check_access('address');

        //initialize model variables
        $this->addressmodel = model('AddressModel');
    }

    public function index()
    {
        check_method_access('address', 'view');
        $data = [
            'session' => $this->session,
        ];
        return view('address/address_list', $data);
    }

    public function view_branches($address_id)
    {
        check_method_access('address', 'view');
        $data = [
            'session' => $this->session,
            'address_id' => $address_id,
        ];
        return view('address/branch_list', $data);
    }

    public function addressList()
    {
        check_method_access('address', 'view');
        $address = $this->addressmodel->getAll();
        $arr = [];
        $i = 0;

        foreach ($address as $k => $v) {
            $action = '';
            if (check_method_access('address', 'view', true)) {
                $action .= '<a title="Branches" class="text-info me-1" href="' . base_url("address/view_branches/$v->id") . '" address="' . $v->id . '" style="font-size: 1.2rem;"><i class="fa fa-code-fork"></i></a>';
            }
            if (check_method_access('address', 'edit', true)) {
                $action .= '<a title="Edit" class="text-warning sup_update me-1" href="#" address="' . $v->id . '" style="font-size: 1.2rem;"> <i class="fa fa-pencil-square-o"></i></a>';
            }
            if (check_method_access('address', 'delete', true)) {
                $action .= '<a title="Delete" class="text-danger sup_delete me-1"  address="' . $v->id . '" href="#" title="Delete" style="font-size: 1.2rem;" > <i class="fa fa-trash-o"></i></a>';
            }

            $arr[] = [
                ++$i,
                $v->name,
                $v->email,
                $v->mobile,
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
        check_method_access('address', 'add');
        $data = array(
            'company_id' => $this->session->get('user_details')['company_id'],
            'branch_id' => $this->session->get('user_details')['branch_id'],
            'name' => format_name($this->request->getPost('name')),
            'email' => $this->request->getPost('email'),
            'mobile' => $this->request->getPost('phone'),
            'website_url' => $this->request->getPost('website_url'),
        );
        $address = $this->addressmodel->insertData($data);
        if ($address) {
            echo json_encode([
                "status" => 1,
                "msg" => "New address inserted successfully",
            ]);
        }
    }

    public function update()
    {
        check_method_access('address', 'edit');
        if ($this->request->getPost('submit')) {
            $id = $this->request->getPost('address_id');
            $data = array(
                'company_id' => $this->session->get('user_details')['company_id'],
                'branch_id' => $this->session->get('user_details')['branch_id'],
                'name' => format_name($this->request->getPost('name')),
                'email' => $this->request->getPost('email'),
                'mobile' => $this->request->getPost('phone'),
                'website_url' => $this->request->getPost('website_url'),
            );
            $address = $this->addressmodel->updateRow($data, "id=$id");
            if ($address) {
                echo json_encode([
                    "status" => 1,
                    "msg" => "updated Successfully address book ",
                ]);
            }
        }

    }

    public function deleted()
    {
        check_method_access('address', 'delete');
        $id = $this->request->getPost('id');
        $this->addressmodel->deleteRow($id);
        echo json_encode([
            'status' => 1,
            'msg' => 'Address was deleted successfully',
        ]);

    }
    public function get_address_id()
    {
        $id = $this->request->getPost('id');
        $res = $this->addressmodel->get_address($id);

        if (!empty($res)) {
            json_response(1, 'Successfully Fetched', $res);
        } else {
            json_response(0, 'Users not found', $res);
        }
    }

    //branch add for address book
    public function view_branches_List()
    {
        check_method_access('address', 'view');
        $parent_address_id = $this->request->getGet('address_id');
        $address = $this->addressmodel->view_branch_getAll($parent_address_id);
        $arr = [];
        $i = 0;

        foreach ($address as $k => $v) {
            $actions =
            // '<a title="Branches" class="view btn btn-sm btn-info" href="' . base_url("address/view_branches/$v->id") . '" address="' . $v->id . '">Branches </a>
            '<a title="Edit" class="text-warning sup_update_view_branch" href="#" address="' . $v->id . '" style="font-size: 1.2rem;" > <i class="fa fa-pencil-square-o"></i></a>
            <a title="Delete" class="text-danger sup_delete"  address="' . $v->id . '" href="#" title="Delete" style="font-size: 1.2rem;" > <i class="fa fa-trash-o"></i></a>';
            $arr[] = [
                ++$i,
                $v->name,
                $v->email,
                $v->gst_no,
                $v->mobile,
                $actions,
            ];
        }
        echo json_encode([
            "status" => 1,
            "details" => $arr,
        ]);
    }
    public function view_branches_add()
    {
        check_method_access('address', 'add');
        $data = array(
            'parent_id' => $this->request->getPost('address_id'),
            'company_id' => $this->session->get('user_details')['company_id'],
            'branch_id' => $this->session->get('user_details')['branch_id'],
            'name' => format_name($this->request->getPost('name')),
            'email' => $this->request->getPost('email'),
            'mobile' => $this->request->getPost('phone'),
            'gst_no' => $this->request->getPost('gst_no'),
            'address' => format_name($this->request->getPost('address')),
        );
        $address = $this->addressmodel->insertData($data);
        if ($address) {
            echo json_encode([
                "status" => 1,
                "msg" => "New branch inserted successfully",
            ]);
        }
    }
    public function get_view_branch_id()
    {
        $id = $this->request->getPost('id');
        $data = $this->addressmodel->get_view_branch_id($id);

        if (!empty($data)) {
            json_response(1, 'Successfully Fetched', $data);
        } else {
            json_response(0, 'Users not found', $data);
        }

    }

    public function view_branch_update()
    {
        check_method_access('address', 'edit');
        $id = $this->request->getPost('e_address_id');
        $data = array(
            'parent_id' => $this->request->getPost('e_parent_id'),
            'company_id' => $this->session->get('user_details')['company_id'],
            'branch_id' => $this->session->get('user_details')['branch_id'],
            'name' => format_name($this->request->getPost('name')),
            'email' => $this->request->getPost('email'),
            'mobile' => $this->request->getPost('phone'),
            'gst_no' => $this->request->getPost('gst_no'),
            'address' => format_name($this->request->getPost('address')),
        );
        $address = $this->addressmodel->updateRow($data, "id=$id");
        if ($address) {
            echo json_encode([
                "status" => 1,
                "msg" => "updated Successfully branch ",
            ]);
        }
    }
    public function view_branch_deleted()
    {
        check_method_access('address', 'delete');
        $id = $this->request->getPost('id');
        $this->addressmodel->deleteRow($id);
        echo json_encode([
            'status' => 1,
            'msg' => 'Address was deleted successfully',
        ]);
    }

    public function get_branch_list()
    {
        $company_address_id = $this->request->getPost('cid');
        $list = $this->addressmodel->get_branch_list($company_address_id);
        json_response(1, 'Fetched succesfully', $list);
    }
}