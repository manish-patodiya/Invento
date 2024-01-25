<?php

namespace App\Controllers\transaction_copy;

use App\Controllers\BaseController;

class Cpo extends BaseController
{
    public function __construct()
    {
        check_auth();
        check_access('cpo');
        //initialize model variables
        $this->TransactionModel = model('TransactionModel');
        $this->AddressModel = model('AddressModel');
        $this->CategoryModel = model('product_models/CategoryModel');
        $this->unitModel = model('UnitModel');
        $this->trans_id = 2;
    }
    public function index()
    {
        check_method_access('cpo', 'view');
        $data = [
            'session' => $this->session,
        ];
        // ($data);
        return view('transaction/transaction_sub_menu/cust_purch_order', $data);
    }

    public function get_transaction_details_by_id()
    {

    }

    public function create_CPO($Est_id = 0)
    {
        check_method_access('cpo', 'add');

        $info = $this->TransactionModel->getPrefix($this->trans_id);

        $brid = $this->session->get('user_details')['branch_id'];
        $current_branch_info = model('Branches')->getFeilds_by_id("address,gst_no", "id='$brid'");
        $data = [
            'est_id' => $Est_id,
            'session' => $this->session,
            'trans_type_info' => $info,
            'branch' => $current_branch_info,
            'company_address_list' => $this->AddressModel->getAll(),
            'category_list' => $this->CategoryModel->getAll(),
        ];
        return view('transaction/cpo/add_cpo_trans', $data);
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
        check_method_access('cpo', 'add');
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
            'round_of' => $post_data['round_of_amt'],
            'details' => $post_data['details'],
            'shipping_charges' => $post_data['shipping_charge'],
            'payable_amt' => $post_data['payable_amt'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $trans_id = $this->TransactionModel->insertTransaction($data);

        // Increament the start no in transaction setting
        $start_no = $post_data['start_no'];
        $this->TransactionModel->update_start_no($post_data['transaction_type'], ++$start_no);

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
        $res = $this->TransactionModel->insertTransDetails($data2);

        json_response(1, 'Transaction created succesfully');
    }
    //Invoce listung All function

    public function cpo_list()
    {
        $post_data = $this->request->getGet();
        $invoice = $this->TransactionModel->getAllInvoice($post_data);
        // ($invoice);
        $arr = [];
        $i = 0;
        foreach ($invoice as $k => $v) {
            $action = '';
            $inv_url = base_url("invoice/view_invoice/$v->trans_type_id/$v->In_id");
            if (check_method_access('cpo', 'view', true)) {
                $action .= '<a title="Print" class="delete btn btn-sm  btn-secondary sup_delete me-1" id="print"  href="' . $inv_url . '"  > <i class="fa fa-print"></i></a>';
            }
            if (check_method_access('cpo', 'edit', true)) {
                $action .= '<a title="Edit" class=" btn btn-sm btn-warning sup_update me-1"  href="#" > <i class="fa fa-pencil-square-o"></i></a>';
            }
            if (check_method_access('cpo', 'delete', true)) {
                $action .= '<a title="Delete" class="delete btn btn-sm  btn-danger sup_delete me-1"  href="#" title="Delete"  > <i class="fa fa-times""></i></a>';
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
                $v->status,
                $action,
            ];
        }
        echo json_encode([
            "status" => 1,
            "details" => $arr,
        ]);
    }
}
