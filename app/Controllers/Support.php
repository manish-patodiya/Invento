<?php

namespace App\Controllers;

class Support extends BaseController
{
    public $supportModel;
    public $branches;
    public function __construct()
    {
        check_auth();
        check_access('support');
        $this->supportModel = model('Support_ticket_model');
        $this->branches = model('Branches');
    }
    public function index()
    {
        check_method_access('support', 'view');
        $status_count = $this->supportModel->get_support_count();
        $status_count_by_status_id[] = 0;
        $count = 0;
        foreach ($status_count as $v) {
            $count = $count + $v->count;
            $status_count_by_status_id[$v->status_id] = $v->count;
        }
        $data = [
            'session' => $this->session,
            'count' => $count,
            'status_count' => $status_count_by_status_id,

        ];

        return view('support/support_list', $data);
    }

    public function supp_tickets_count()
    {
        $status_count = $this->supportModel->get_support_count();
        if ($status_count) {
            echo json_encode([
                "status" => 1,
                'msg' => 'Updated Successfully Status',
                'data' => $status_count,
            ]);
        }
    }

    public function update_status()
    {
        check_method_access('support', 'edit');
        $change_status_id = $this->request->getpost('sel_status_id');
        $ticket_id = $this->request->getpost('ticket_id');
        if ($change_status_id) {
            $data = array("status_id" => $change_status_id);

            $support = $this->supportModel->update_status_id($data, $ticket_id);
            if ($support) {
                echo json_encode([
                    "status" => 1,
                    'msg' => 'Updated Successfully Status',
                ]);
            }
        }

    }

    public function support_list()
    {
        check_method_access('support', 'view');
        $tid = $this->request->getGet('tid');
        $support = $this->supportModel->getSupport($tid);
        $arr = [];
        $i = 0;

        foreach ($support as $k => $v) {
            $label = '';
            $class = '';
            // chnages label css for status names
            if ($v->status_id == 1) {
                $class = "badge-danger";
            } elseif ($v->status_id == 2) {
                $class = "badge-success";
            } elseif ($v->status_id == 3) {
                $class = "badge-warning";
            } elseif ($v->status_id == 4) {
                $class = "badge-danger bg-orange";
            }

            //chnage by status id for click label and change label
            $select = '';
            // click invent on label
            $on_clicK = '';

            $status_name = $this->supportModel->getStatus_id();
            $role_pre = $this->session->get('user_details')['active_role_id'];
            if ($role_pre == 1) {
                $on_clicK = 'btn-chng-sts';
                $select = '<select class=" col-md-2
                form-control select-statu-id" style="display:none;" sup-ticket-id="' . $v->ticket_id . '">';
                foreach ($status_name as $val) {
                    $slct = $v->status_id == $val->id ? "selected" : "";
                    $select .= "<option value=\"$val->id\"  $slct>$val->status_name</option>";
                }
                $select .= '</select>';
            }

            $label .= '<label class="badge  ' . $class . ' ' . $on_clicK . ' "  >' . $v->status_name . '</label>
                    ' . $select . ' ';

            //data formt
            $originalDate = $v->created_at;
            $status = "<div  class='text-center'>
                $label</div>";

            $action = '';
            if (check_method_access('support', 'view', true)) {
                $action .= '<a title="support ticket description " style="font-size: 1.2rem;" class="text-primary description me-1" href="#" support_id="' . $v->ticket_id . '"><i class="fa fa-eye"></i> </a>';
            }
            if (check_method_access('support', 'edit', true)) {
                $action .= '<a title="Edit" style="font-size: 1.2rem;" class="text-warning sup_update me-1" href="#" support_id="' . $v->ticket_id . '" > <i class="fa fa-pencil-square-o"></i></a>';
            }
            if (check_method_access('support', 'delete', true)) {
                $action .= '<a title="Delete" style="font-size: 1.2rem;" class="text-danger sup_delete me-1"  support_id="' . $v->ticket_id . '" href="#" title="Delete" > <i class="fa fa-trash-o"></i></a>';
            }

            $arr[] = [
                '#' . $v->ticket_id,
                '<h5>' . $v->name . '</h5> <p>' . $v->email . '</p>',
                '<div style=" display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical;
                    overflow: hidden;"><h6>' . $v->subject . '</h6></div> <div style=" display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
                    overflow: hidden;">' . $v->description . '</div>',
                '<h6 class="m-0">' . date('d M', strtotime($originalDate)) . '</h6>' . "<h4 class='m-0 text-primary'>" . date('Y', strtotime($originalDate)) . "</h4>",
                $status,
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
        check_method_access('support', 'add');
        $data = array(
            'company_id' => $this->session->get('user_details')['company_id'],
            'branch_id' => $this->session->get('user_details')['branch_id'],
            "subject" => $this->request->getPost('subject'),
            "status_id" => 1,
            "description" => $this->request->getPost('descriptions'),

        );
        $support = $this->supportModel->insertData($data);
        if ($support) {
            echo json_encode([
                "status" => 1,
                'msg' => 'inserted Successfully support ticket',
            ]);
        }
    }
    public function edit()
    {
        check_method_access('support', 'edit');
        $support_id = $this->request->getPost('supprot_id');
        $data = array(
            'company_id' => $this->session->get('user_details')['company_id'],
            'branch_id' => $this->session->get('user_details')['branch_id'],
            "subject" => $this->request->getPost('subject'),
            "description" => $this->request->getPost('descriptions'),

        );
        $support = $this->supportModel->updateRow($data, "ticket_id=$support_id");
        if ($support) {
            echo json_encode([
                "status" => 1,
                'msg' => 'Updated Successfully support ticket',
            ]);
        }
    }
    public function deleted()
    {
        check_method_access('support', 'delete');
        $ticket_id = $this->request->getPost('ticket_id');
        $res = $this->supportModel->deleteRow($ticket_id);
        if ($res) {
            echo json_encode([
                "status" => 1,
                'msg' => 'Deleted Successfully support ticket',
            ]);
        }
    }

    public function get_support_id()
    {
        $ticket_id = $this->request->getPost('ticket_id');
        $data = $this->supportModel->get_support_by_id($ticket_id);

        if (!empty($data)) {
            json_response(1, 'Successfully Fetched', $data);
        } else {
            json_response(0, 'Users not found', $data);
        }
    }

    public function get_all_support()
    {
        $support = $this->supportModel;
        $id = $this->request->getPost('ticket_id');
        $name = $support->getFields('created_at,status_id,subject,description', "ticket_id='$id'");
        $originalDate = $name['created_at'];
        $name['newdate'] = date("d M Y", strtotime($originalDate));
        $name['branch_name'] = $this->session->get('user_details')['branch_name'];
        $name['branch_email'] = $this->session->get('user_details')['branch_email'];

        if (!empty($name)) {
            echo json_encode([
                "status" => 1,
                "name" => $name,
            ]);
        } else {
            echo json_encode([
                "status" => 0,
                "name" => $name,
            ]);
        }
    }

}