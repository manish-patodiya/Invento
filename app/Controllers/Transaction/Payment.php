<?php

namespace App\Controllers\transaction;

use App\Controllers\BaseController;

class payment extends BaseController
{
    public function __construct()
    {
        check_auth();
        check_access('payment');

        $uri = service('uri');
        //initialize model variables
        $this->PaymentModel = model('transaction_models/PaymentModel');
        $this->AddressModel = model('AddressModel');
        $this->productmodel = model('product_models/productModel');
        $this->CategoryModel = model('product_models/CategoryModel');
        $this->unitModel = model('UnitModel');
    }
    public function index()
    {
        check_method_access('payment', 'view');
        $data = [
            'session' => $this->session,
            'branches' => $this->PaymentModel->getComp_by_branch(),
        ];
        return view('transaction/payment/payment_receipt', $data);
    }

    public function create_payment($branchid, $payment_invo_id = 0)
    {
        check_method_access('payment', 'add');
        $trans_id = 11;
        $info = $this->PaymentModel->getPrefix($trans_id, $branchid);

        $brid = $this->session->get('user_details')['branch_id'];
        $current_branch_info = model('Branches')->getFeilds_by_id("address,gst_no", "id='$brid'");

        $data = [
            'session' => $this->session,
            'trans_type_info' => $info,
            'payment_invo_id' => $payment_invo_id,
            'branch' => $current_branch_info,
            'brid' => $branchid,
            'branches' => $this->PaymentModel->getComp_by_branch(),
            'company_address_list' => $this->AddressModel->getAll(),
            'category_list' => $this->CategoryModel->getAll(),
        ];
        return view('transaction/payment/add_payment_trans', $data);
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
        check_method_access('payment', 'add');
        $post_data = $this->request->getPost();
        $data = [
            'date' => $post_data['create_date'],
            'from_address' => $post_data['branch_address'],
            'create_user' => $post_data['user_name'],
            'invoice_prefix_id' => $post_data['prefix'],
            'company_id' => $this->session->get('user_details')['company_id'],
            'branch_id' => $post_data['branch_id'],
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
            'shipping_charges' => $post_data['shipping_charge'],
            'payable_amt' => $post_data['payable_amt'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $trans_id = $this->PaymentModel->insertTransaction($data);

        // Increament the start no in transaction setting
        $start_no = $post_data['start_no'];
        $this->PaymentModel->update_start_no($post_data['transaction_type'], ++$start_no);

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
        $res = $this->PaymentModel->insertTransDetails($data2);

        json_response(1, 'Transaction created succesfully');
    }

    //Invoce listung All function

    public function PR_list()
    {
        $post_data = $this->request->getGet();
        $invoice = $this->PaymentModel->getAllInvoice($post_data);
        $arr = [];
        $i = 0;
        foreach ($invoice as $k => $v) {
            $class = '';
            if ($v->status_id == 1) {
                $class = "badge-danger";
            } elseif ($v->status_id == 5) {
                $class = "badge-success";
            } elseif ($v->status_id == 3) {
                $class = "badge-danger  bg-orange";
            }
            $action = '';
            $generate = '';
            $on_click = '';
            $inv_url = base_url("invoice/view_invoice/$v->trans_type_id/$v->In_id");
            $cpo_url = base_url("Transaction_copy/cpo/create_cpo/$v->In_id");
            $role_pre = $this->session->get('user_details')['active_role_id'];
            if ($v->status_id != 3) {
                if ($role_pre == 7) {
                    $on_click = 'btn-status-pr';
                }}
            if (check_method_access('payment', 'view', true)) {
                $action .= '<a title="Print" class="text-info sup_delete me-1" id="print"  href="' . $inv_url . '"  > <i class="fa fa-print"></i></a>';
            }
            if (check_method_access('payment', 'delete', true)) {
                $action .= '<a  class="text-danger cancel me-1" pr=' . $v->In_id . '  href="#" title="Cancel"  > <i class="fa fa-times""></i></a>';
            }
            $status = '<lable class="badge  ' . $class . ' ' . $on_click . '" pr_attr=' . $v->In_id . ' >' . $v->status . '</lable>';

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

    public function get_grn_details_by_id()
    {
        $id = $this->request->getPost('payment_invo_id');
        $data['grn_details'] = model('transaction_models/PaymentModel')->get_grn_details($id);
        $data['grn_product_details'] = model('transaction_models/PaymentModel')->get_grn_product_details($id);
        if ($data) {
            json_response(1, 'Successful', $data);
        } else {
            json_response(0, 'Something went wrong');
        }
    }

    public function Cancel()
    {
        check_method_access('payment', 'delete');
        $id = $this->request->getPost('id');
        $data = [
            'status' => 3,
        ];
        $this->PaymentModel->update_pr_status($data, $id);
        echo json_encode([
            'status' => 1,
            'msg' => 'ESt. was cancel successfully',
        ]);

    }
    public function Status()
    {
        check_method_access('payment', 'edit');
        $id = $this->request->getPost('id');
        $data = [
            'status' => 5,
        ];
        $this->taxinvoModel->update_pr_status_by_id($data, $id);
        echo json_encode([
            'status' => 1,
            'msg' => 'Tax invoice was approved successfully',
        ]);

    }

    public function choose_inovice()
    {
        $id = $this->request->getPost('id');
        $branch_id = $this->request->getPost('branch_id');
        $slc_grn = model('transaction_models/PaymentModel')->get_all_grn_id($id, $branch_id);
        echo json_encode([
            'status' => 1,
            'payment_type' => $slc_grn,
            'msg' => 'Tax invoice was approved successfully',
        ]);
    }

    public function get_branch_list()
    {
        $company_address_id = $this->request->getPost('cid');
        $list = $this->AddressModel->get_branch_list($company_address_id);
        json_response(1, 'Fetched succesfully', $list);
    }

    public function search_products()
    {
        $cat_id = $this->request->getGet('cat_id');
        $search_val = $this->request->getGet('search');
        $hsn_code = $this->request->getGet('hsn_code');
        $products = $this->productmodel->search_products($hsn_code, $cat_id, $search_val);
        json_response(1, 'Successfully Fetched', $products);

    }
    public function get_products_by_id()
    {
        $pids = $this->request->getPost('product_ids');
        $product_info = [];
        if ($pids) {
            $id_arr = explode(',', $pids);
            $product_info['product'] = $this->productmodel->get_products_by_id($id_arr);
        }
        json_response(1, 'Fetched successfully', $product_info);
    }
}