<?php

namespace App\Controllers\transaction;

use App\Controllers\BaseController;

class Rejection extends BaseController
{
    public function __construct()
    {
        check_auth();
        check_access('rejection');
        //initialize model variables
        $this->Rejection = model('transaction_models/RejectionModal');
        $this->AddressModel = model('AddressModel');
        $this->CategoryModel = model('product_models/CategoryModel');
        $this->unitModel = model('UnitModel');
    }
    public function index()
    {
        check_method_access('rejection', 'view');
        $data = [
            'session' => $this->session,
        ];
        return view('transaction/rejection/rejection', $data);
    }
    public function getbranchaddress()
    {
        $term = $this->request->getGet('term');
        $branch_adr_list = $this->AddressModel->get_branch_address($term);
        json_response(1, 'fetched seccessfully', $branch_adr_list);
    }
    public function rejection_list()
    {
        $post_data = $this->request->getGet();
        $invoice = $this->Rejection->getAllInvoice($post_data);
        $arr = [];
        $i = 0;
        foreach ($invoice as $k => $v) {
            $action = '';
            $class = '';
            if ($v->status_id == 3) {
                $class = "badge-danger  bg-orange";
            }
            $inv_url = base_url("invoice/view_invoice/$v->trans_type_id/$v->In_id");
            // if (check_method_access('rejection', 'view', true)) {
            //     $action .= '<a title="Print" class="text-info sup_delete me-1" id="print"  href="' . $inv_url . '" style="font-size: 1rem;" > <i class="fa fa-print"></i></a>';
            // }

            if (check_method_access('rejection', 'delete', true)) {
                $action .= '<a  class=" text-danger cancel me-1" pv=' . $v->In_id . '  href="#" title="Cancel" style="font-size: 1rem;" > <i class="fa fa-times""></i></a>';
            }
            $status = '<lable class="badge  ' . $class . '"  >' . $v->status . '</lable>';

            //data formt
            $originalDate = $v->reference_date;
            $Date = $v->date;
            $amt = number_format($v->payable_amt, 2);
            $taxable_amt = number_format($v->total_taxable_amt, 2);
            $total_gst_tax = number_format($v->total_gst_tax, 2);
            $arr[] = [
                "#" . $v->reference_no . '<h6 class="m-0">' . date('d M Y', strtotime($originalDate)) . '</h6>',
                '<h5 class="text-primary">' . $v->invoice_prefix_id . '</h5>',
                '<p class="m-0">' . $v->create_user . "</p><h6 class='m-0'>" . date('d M Y', strtotime($Date)) . "</h6> ",
                '<h6 class="m-0">' . $v->adr_name . "</h6><h5 class='m-0'><small>" . $v->billing_adrs . "</small></h5> ",
                "₹ " . $total_gst_tax,
                "₹ " . $taxable_amt,
                "₹ " . $amt,
                $status,
                $action,
            ];
        }
        echo json_encode([
            "status" => 1,
            "details" => $arr,
        ]);
    }

    public function Cancel()
    {
        check_method_access('rejection', 'delete');
        $id = $this->request->getPost('id');
        $data = [
            'status' => 3,
        ];
        $this->Rejection->update_est_status($data, $id);
        echo json_encode([
            'status' => 1,
            'msg' => 'ESt. was cancel successfully',
        ]);

    }
}