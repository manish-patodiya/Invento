<?php
namespace App\Models\transaction_models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    public $db;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->session->start();
        $this->db = db_connect();
    }

    public function get_transaction_type_list()
    {
        return $this->db->table('transaction_type')->select('*')->get()->getResult();
    }

    public function get_transaction_type_list_with_prefix()
    {
        $comp_id = $this->session->get('user_details')['company_id'];
        $branch_id = $this->session->get('user_details')['branch_id'];
        return $this->db->table('transaction_type tt')
            ->select('tt.*,ts.prefix')
            ->join('transaction_settings ts', 'ts.trans_type_id=tt.id', 'left')
            ->where(['ts.company_id' => $comp_id, 'ts.branch_id' => $branch_id])->get()->getResult();
    }

    public function getPrefix($trans_id, $brid)
    {

        $comp_id = $this->session->get('user_details')['company_id'];
        $branch_id = $this->session->get('user_details')['branch_id'];
        return $this->db->table('transaction_type tt')
            ->select('tt.*,ts.prefix,ts.start_no')
            ->join('transaction_settings ts', 'ts.trans_type_id=tt.id')
            ->where(['ts.company_id' => $comp_id, 'ts.branch_id' => $brid, 'ts.trans_type_id' => $trans_id])
            ->get()->getRow();
    }

    public function insertTransaction($data)
    {
        $builder = $this->db->table('invoices');
        $builder->insert($data);
        return $this->db->insertID();
    }

    public function getTransaction($id)
    {
        $builder = $this->db->table('invoices');
        $builder->select('*')
            ->where('id', $id);
        return $builder->get()->getRow();
    }
    public function updateTransaction($id, $data)
    {
        $builder = $this->db->table('invoices');
        return $builder->set($data)->where('id', $id)->update();
    }

    public function insertTransDetails($data)
    {
        $builder = $this->db->table('invoice_details');
        return $builder->insertBatch($data);
    }

    public function getInvoice($id)
    {
        $builder = $this->db->table('invoices');
        $builder->select('invoices.*,invoices.id as invoice_id, master_states.*, master_cities.*, company_branches.id,company_branches.name, companies.logo, companies.website_url, company_branches.phone, company_branches.gst_no, company_branches.state_id, company_branches.city_id, company_branches.address, company_branches.email,address_book.id as address_id, address_book.address as sec_branch_address, address_book.name as sec_branch_name, address_book.gst_no as sec_branch_gst_no, address_book.email as sec_branch_email, address_book.mobile as sec_branch_mobile,   adb.id as adb_id, adb.address as branch_address, adb.name as branch_name, adb.gst_no as branch_gst_no, adb.email as branch_email, adb.mobile as branch_mobile')
            ->join('company_branches', 'company_branches.id=invoices.branch_id')
            ->join('companies', 'companies.id=invoices.company_id')
            ->join('master_states', 'master_states.state_id=company_branches.state_id')
            ->join('master_cities', 'master_cities.city_id=company_branches.city_id')
            ->join('address_book', 'address_book.id=invoices.sec_branch_adrs_id', 'left')
            ->join('address_book as adb', 'adb.id=invoices.branch_adrs_id', 'left')
            ->where('invoices.id', $id);
        return $builder->get()->getRow();
    }

    public function getInvoiceDetails($id)
    {
        $builder = $this->db->table('invoice_details');
        $builder->select('invoice_details.*,invoices.*,products.id,products.product_img, products.title as product_name, products.gst_rate,products.product_details,master_units.title as unit, hd.hsn_code_4_digits as hsn_code')
            ->join('products', 'products.id=invoice_details.product_id')
            ->join('invoices', 'invoices.id=invoice_details.invoice_id')
            ->join('master_units', 'master_units.id=invoice_details.unit_id')
            ->join('hsn_details hd', 'hd.hsn_code=products.hsn_code')
            ->where('invoice_details.invoice_id', $id);
        return $builder->get()->getResult();
    }

    public function getTransDetails($id)
    {
        $builder = $this->db->table('invoice_details');
        $builder->select('invoices.*, invoice_details.*, master_states.*, master_cities.*, products.id,products.product_img, products.title, products.hsn_code,products.product_details, products.gst_rate, company_branches.id,company_branches.name, company_branches.phone, company_branches.gst_no, company_branches.state_id, company_branches.city_id, company_branches.address, company_branches.email,address_book.id as address_id, address_book.address as sec_branch_address, address_book.name as sec_branch_name, address_book.gst_no as sec_branch_gst_no, address_book.email as sec_branch_email, address_book.mobile as sec_branch_mobile,   adb.id as adb_id, adb.address as branch_address, adb.name as branch_name, adb.gst_no as branch_gst_no, adb.email as branch_email, adb.mobile as branch_mobile')
            ->join('products', 'products.id=invoice_details.product_id')
            ->join('invoices', 'invoices.id=invoice_details.invoice_id')
            ->join('company_branches', 'company_branches.id=invoices.branch_id')
            ->join('master_states', 'master_states.state_id=company_branches.state_id')
            ->join('master_cities', 'master_cities.city_id=company_branches.city_id')
            ->join('address_book', 'address_book.id=invoices.sec_branch_adrs_id', 'left')
            ->join('address_book as adb', 'adb.id=invoices.branch_adrs_id', 'left')
            ->where('invoice_details.invoice_id', $id);
        return $builder->get()->getResult();
    }
    public function getFeild($branch_id, $company_id)
    {
        $builder = $this->db->table('invoices');
        $builder->select('*');
        $builder->where('branch_id', $branch_id)
            ->where('company_id', $company_id);
        $builder->where('deleted_at is NULL');
        return $builder->get()->getRow();
    }

    //.........
    public function getAllInvoice($post_data)
    {

        //login with brnach..........
        $Active_role_id = $this->session->get('user_details')['active_role_id'];
        $branch_id = '';
        if ($Active_role_id == 3) {
            $branch_id = $this->session->get('user_details')['branch_id'] ? $this->session->get('user_details')['branch_id'] : 0;
        }

        $cid = $this->session->get('user_details')['company_id'];
        //login with company admin...........
        $bid = $post_data['bid'];

        $tid = $post_data['tid'];
        $cust_name = isset($post_data['cust_name']) && $post_data['cust_name'] ? $post_data['cust_name'] : "";
        $frm_date = isset($post_data['frm_date']) && $post_data['frm_date'] ? $post_data['frm_date'] : "";
        $to_date = isset($post_data['to_date']) && $post_data['to_date'] ? $post_data['to_date'] : "";
        if ($bid) {
            $builder = $this->db->table('invoices');
            $builder->select('invoices.*, invoices.id as In_id,adr.*,adr.name as adr_name,mst.*,mst.status,mst.id as status_id')->join('address_book as adr', 'adr.id = invoices.branch_adrs_id', 'left')->join('master_status_trans as mst', 'mst.id=invoices.status', 'left')->where("invoices.company_id= $cid")->where('invoices.branch_id', $bid);
            if ($tid != "") {
                $builder->where('invoices.trans_type_id', $tid);
            };
            if ($cust_name != "") {
                $branch_adrs_id = implode(",", $cust_name);
                $builder->where("invoices.branch_adrs_id IN ($branch_adrs_id)");
            };
            $builder->where("invoices.reference_date BETWEEN '$frm_date' AND '$to_date'");
            return $builder->get()->getResult();

        } else if ($branch_id) {
            $builder = $this->db->table('invoices');
            $builder->select('invoices.*, invoices.id as In_id,adr.*,adr.name as adr_name,mst.*,mst.status,mst.id as status_id')->join('address_book as adr', 'adr.id = invoices.branch_adrs_id', 'left')->join('master_status_trans as mst', 'mst.id=invoices.status', 'left')->where("invoices.company_id= $cid")->where('invoices.branch_id', $branch_id);

            if ($tid != "") {
                $builder->where('invoices.trans_type_id', $tid);
            };
            if ($cust_name != "") {
                $branch_adrs_id = implode(",", $cust_name);
                $builder->where("invoices.branch_adrs_id IN ($branch_adrs_id)");
            };
            $builder->where("invoices.reference_date BETWEEN '$frm_date' AND '$to_date'");
            return $builder->get()->getResult();

        } else {
            $builder = $this->db->table('invoices');
            $builder->select('invoices.*, invoices.id as In_id,adr.*,adr.name as adr_name,mst.*,mst.status,mst.id as status_id')->join('address_book as adr', 'adr.id = invoices.branch_adrs_id', 'left')->join('master_status_trans as mst', 'mst.id=invoices.status', 'left')->where("invoices.company_id= $cid");

            if ($tid != "") {
                $builder->where('invoices.trans_type_id', $tid);
            };
            if ($cust_name != "") {
                $branch_adrs_id = implode(",", $cust_name);
                $builder->where("invoices.branch_adrs_id IN ($branch_adrs_id)");
            };
            $builder->where("invoices.reference_date BETWEEN '$frm_date' AND '$to_date'");
            return $builder->get()->getResult();
        }
    }

    public function getAll_Status()
    {
        $builder = $this->builder = $this->db->table('master_status_trans');
        $builder->select("*");
        return $builder->get()->getResult();
    }

    public function update_start_no($type_id, $s_no)
    {
        $comp_id = $this->session->get('user_details')['company_id'];
        $branch_id = $this->session->get('user_details')['branch_id'];
        $this->db->table('transaction_settings')->set(['start_no' => $s_no])->where(['company_id' => $comp_id, 'branch_id' => $branch_id, 'trans_type_id' => $type_id])->update();
    }

    public function get_grn_product_details($id)
    {
        $builder = $this->db->table('invoice_details');
        $builder->select('invoice_details.*,invoices.*,products.id,products.product_img, products.title as product_name, products.gst_rate,products.product_details,master_units.title as unit, hd.hsn_code_4_digits as hsn_code')
            ->join('products', 'products.id=invoice_details.product_id')
            ->join('invoices', 'invoices.id=invoice_details.invoice_id')
            ->join('master_units', 'master_units.id=invoice_details.unit_id')
            ->join('hsn_details hd', 'hd.hsn_code=products.hsn_code')
            ->where('invoice_details.invoice_id', $id);
        return $builder->get()->getResult();
    }

    public function get_grn_details($id)
    {

        $comp_id = $this->session->get('user_details')['company_id'];
        $builder = $this->db->table('invoices');
        $builder->select('invoices.*,invoices.id as invoice_id, master_states.*, master_cities.*, company_branches.id,company_branches.name, companies.logo, companies.website_url, company_branches.phone, company_branches.gst_no, company_branches.state_id, company_branches.city_id, company_branches.address, company_branches.email,address_book.id as address_id, address_book.address as sec_branch_address, address_book.name as sec_branch_name, address_book.gst_no as sec_branch_gst_no, address_book.email as sec_branch_email, address_book.mobile as sec_branch_mobile,   adb.id as adb_id, adb.address as branch_address, adb.name as branch_name, adb.gst_no as branch_gst_no, adb.email as branch_email, adb.mobile as branch_mobile')->where('invoices.company_id', $comp_id)
            ->join('company_branches', 'company_branches.id=invoices.branch_id')
            ->join('companies', 'companies.id=invoices.company_id')
            ->join('master_states', 'master_states.state_id=company_branches.state_id')
            ->join('master_cities', 'master_cities.city_id=company_branches.city_id')
            ->join('address_book', 'address_book.id=invoices.sec_branch_adrs_id', 'left')
            ->join('address_book as adb', 'adb.id=invoices.branch_adrs_id', 'left')
            ->where('invoices.id', $id);
        return $builder->get()->getRow();
    }

    public function get_all_est_id()
    {
        $branch_id = $this->session->get('user_details')['branch_id'];
        $builder = $this->db->table('invoices');
        $builder->select('invoices.*')->where(['status' => 1, 'branch_id' => $branch_id]);
        return $builder->get()->getResult();

    }

    public function update_pr_status($data, $id)
    {
        $data1 = [
            "updated_at" => date('Y-m-d'),
        ];
        $data = array_merge($data, $data1);
        return $this->db->table('invoices')->set($data)->where('id', $id)->update();
        // echo $this->db->getLastQuery();die;

    }
    public function update_pr_status_by_id($data, $id)
    {
        $data1 = [
            "updated_at" => date('Y-m-d'),
        ];
        $data = array_merge($data, $data1);
        return $this->db->table('invoices')->set($data)->where('id', $id)->update();

    }

    public function get_all_grn_id($id, $brid)
    {
        $branch_id = $this->session->get('user_details')['branch_id'];
        if ($id == 1) {
            $builder = $this->db->table('invoices')->where('invoices.branch_id', $brid);
            $builder->select('invoices.*')->where(['status' => 5, 'trans_type_id' => 5, 'choose_invoice' => $id]);
            return $builder->get()->getResult();
        } elseif ($id == 2) {
            $builder = $this->db->table('invoices')->where('invoices.branch_id', $brid);
            $builder->select('invoices.*')->where(['status' => 5, 'trans_type_id' => 8, 'choose_invoice' => $id]);
            return $builder->get()->getResult();
        }

    }
    public function get_all_taxinvo_id()
    {
        $branch_id = $this->session->get('user_details')['branch_id'];
        $builder = $this->db->table('invoices');
        $builder->select('invoices.*')->where(['status' => 5, 'trans_type_id' => 8, 'branch_id' => $branch_id]);
        return $builder->get()->getResult();

    }

    public function getComp_by_branch()
    {
        $cid = $this->session->get('user_details')['company_id'];
        $comp_builder = $this->db->table('company_users');
        $comp_builder->select('*,ms.state_name,mc.city_name')->join('company_branches B', 'B.id=company_users.branch_id')->join('master_states ms', 'ms.state_id=B.state_id')->join('master_cities mc', 'mc.city_id=B.city_id')->where('company_id', $cid)->where('branch_id!=', 0)->where('staff_id=', 0);
        return $comp_builder->get()->getResult();

    }
}