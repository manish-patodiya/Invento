<?php

namespace App\Controllers\transaction;

use App\Controllers\BaseController;

class Est extends BaseController
{
    public function __construct()
    {
        check_auth();
        check_access('est');
        //initialize model variables
        $this->EstModel = model('transaction_models/EstModel');
        $this->AddressModel = model('AddressModel');
        $this->CategoryModel = model('product_models/CategoryModel');
        $this->unitModel = model('UnitModel');
    }
    public function index()
    {
        check_method_access('est', 'view');
        $data = [
            'session' => $this->session,
            'branches' => $this->EstModel->getComp_by_branch(),
        ];
        return view('transaction/est/estimation', $data);
    }

    public function create_estimation()
    {
        check_method_access('est', 'add');
        $trans_id = 1;
        $info = $this->EstModel->getPrefix($trans_id);

        $brid = $this->session->get('user_details')['branch_id'];
        $current_branch_info = model('Branches')->getFeilds_by_id("address,gst_no", "id='$brid'");
        $data = [
            'session' => $this->session,
            'trans_type_info' => $info,
            'branch' => $current_branch_info,
            'company_address_list' => $this->AddressModel->getAll(),
            'category_list' => $this->CategoryModel->getAll(),
        ];
        return view('transaction/est/add_est_trans', $data);
    }
    public function getbranchaddress()
    {
        $term = $this->request->getGet('term');
        $branch_adr_list = $this->AddressModel->get_branch_address($term);
        json_response(1, 'fetched seccessfully', $branch_adr_list);
    }
    public function get_base_unit()
    {
        $unit_id = $this->request->getpost('id');
        $unit = [];
        if ($unit_id) {
            $id = explode(',', $unit_id);
            $unit = $this->unitModel->getAll_base_unit($id);
        }
        json_response(1, 'Fetched successfully', $unit);

    }

    public function add_transaction()
    {
        check_method_access('est', 'add');
        $post_data = $this->request->getPost();
        $data = [
            'date' => $post_data['create_date'],
            'from_address' => $post_data['branch_address'],
            'create_user' => $post_data['user_name'],
            'invoice_prefix_id' => $post_data['prefix'],
            'company_id' => $this->session->get('user_details')['company_id'],
            'branch_id' => $this->session->get('user_details')['branch_id'],
            'trans_type_id' => $post_data['transaction_type'],
            'company_adrs_id' => $post_data['company'],
            'branch_adrs_id' => $post_data['branch'],
            'sec_branch_adrs_id' => isset($post_data['sec_branch']) ? $post_data['sec_branch'] : $post_data['branch'],
            'from_gst_no' => $post_data['from_gst_no'],
            'to_gst_no' => $post_data['gst_no'],
            'to_sec_gst_no' => $post_data['sec_gst_no'],
            'reference_date' => $post_data['reference_date'],
            'reference_no' => $post_data['reference_no'],
            'billing_adrs' => $post_data['billing_address'],
            'shipping_adrs' => $post_data['shipping_address'],
            'taxes_json' => $post_data['taxes_json'],
            'total_discount' => $post_data['total_discount'],
            'total_gst_tax' => $post_data['total_tax'],
            'total_taxable_amt' => $post_data['total_taxable_amt'],
            'grand_total' => $post_data['grand_total'],
            'notes' => $post_data['notes'],
            'status' => 1,
            'round_of' => $post_data['round_of_amt'],
            // 'details' => $post_data['details'],
            'shipping_charges' => $post_data['shipping_charge'],
            'payable_amt' => $post_data['payable_amt'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $trans_id = $this->EstModel->insertTransaction($data);

        // Increament the start no in transaction setting
        $start_no = $post_data['start_no'];
        $this->EstModel->update_start_no($post_data['transaction_type'], ++$start_no);

        $products = $post_data['product_id'];
        $gst = $post_data['gst'];
        $qty = $post_data['quantity'];
        $unit_id = $post_data['unit'];
        $mrp = $post_data['mrp'];
        $rate = $post_data['rate'];
        $dis = $post_data['discount'];
        $dis_amt = $post_data['discount_amt'];
        $dis_type = $post_data['discount_type'];
        $tax = $post_data['tax'];
        $tax_amt = $post_data['taxable_amt'];
        $total = $post_data['total'];
        $data2 = [];
        foreach ($products as $k => $v) {
            if ($products[$k] && $qty[$k]) {
                $data2[] = [
                    'invoice_id' => $trans_id,
                    'product_id' => $products[$k],
                    'gst_rate' => $gst[$k],
                    'quantity' => $qty[$k],
                    'unit_id' => $unit_id[$k],
                    'mrp' => $mrp[$k],
                    'rate' => $rate[$k],
                    'discount' => $dis[$k],
                    'discount_type' => $dis_type[$k],
                    'discount_amt' => $dis_amt[$k],
                    'tax' => $tax[$k],
                    'taxable_amt' => $tax_amt[$k],
                    'total' => $total[$k],
                ];
            }
        }
        $res = $this->EstModel->insertTransDetails($data2);

        json_response(1, 'Transaction created succesfully');
    }
    //Invoce listung All function

    public function invo_list()
    {
        $post_data = $this->request->getGet();
        $invoice = $this->EstModel->getAllInvoice($post_data);
        $arr = [];
        $i = 0;
        foreach ($invoice as $k => $v) {
            $action = '';
            $generate = '';
            $class = '';
            $inv_url = base_url("invoice/view_invoice/$v->trans_type_id/$v->In_id");
            $cpo_url = base_url("transaction/Cpo/create_cpo/$v->In_id");

            if ($v->status_id == 1) {
                $class = "badge-danger";
            } elseif ($v->status_id == 2) {
                $class = "badge-success";
            } elseif ($v->status_id == 3) {
                $class = "badge-danger  bg-orange";
            }

            if (check_method_access('est', 'view', true)) {
                $action .= '<a title="Print" class="text-info sup_delete me-1" id="print"  href="' . $inv_url . '"  > <i class="fa fa-print"></i></a>';
            }
            if ($v->status_id == 3 || $v->status_id == 2) {

            } elseif ($v->status_id != 3) {
                if (check_method_access('est', 'edit', true)) {
                    $generate .= '<a title="CPO" class=" btn btn-sm btn-success sup_update me-1"  href="' . $cpo_url . '" >
              CPO </a>';
                }
            }
            if (check_method_access('est', 'delete', true)) {
                $action .= '<a  class="text-danger cancel m-5" est=' . $v->In_id . '  href="#" title="Cancel"  > <i class="fa fa-times""></i></a>';
            }
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
                '<lable class="badge  ' . $class . '"  >' . $v->status . '</lable>',
                $generate,
                $action,
            ];
        }
        echo json_encode([
            "status" => 1,
            "details" => $arr,
        ]);
    }

    public function get_estimation_details_by_id()
    {
        $id = $this->request->getPost('est_id');
        $data['estimation_details'] = model('transaction_models/EstModel')->get_estimation_details($id);
        $data['estimation_product_details'] = model('transaction_models/EstModel')->get_estimation_product_details($id);
        if ($data) {
            json_response(1, 'Successful', $data);
        } else {
            json_response(0, 'Something went wrong');
        }
    }

    public function Cancel()
    {
        check_method_access('est', 'delete');
        $id = $this->request->getPost('id');
        $data = [
            'status' => 3,
        ];
        $this->EstModel->update_est_status($data, $id);
        echo json_encode([
            'status' => 1,
            'msg' => 'ESt. was cancel successfully',
        ]);

    }
}