<?php
namespace App\Models\product_models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    public $builder;
    public $db;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->session->start();

        $this->db = db_connect();
        $this->builder = $this->db->table('products');
    }

    public function insertData($data)
    {
        $builder = $this->builder;
        $data1 = [
            "updated_at" => date('Y-m-d'),
            "created_at" => date('Y-m-d'),
        ];
        $data = array_merge($data, $data1);
        $builder->insert($data);
        return $this->db->insertID();
    }

    public function getAll($catid = false)
    {
        $arlid = $this->session->get('user_details')['active_role_id'];
        $cid = $this->session->get('user_details')['company_id'];
        $bid = $this->session->get('user_details')['branch_id'];
        $builder = $this->builder;
        $builder->select("products.* ,products.id as pid ,categories.id as cid,categories.category_name,hsn_details.hsn_code_4_digits")
            ->join('categories', 'categories.id=products.category_id', 'left')
            ->join('hsn_details', 'hsn_details.hsn_code=products.hsn_code', 'left')
            ->where("products.deleted_at is NULL");
        if ($catid != "") {
            $builder->where('products.category_id', $catid);
        };
        if ($arlid == 2) {
            $builder->where("products.company_id= $cid");
        } elseif ($cid) {
            $builder->where("products.company_id= $cid");
            $builder->where("products.branch_id= $bid");

        }
        return $builder->get()->getResult();
    }

    public function get_product_by_id($id)
    {
        $builder = $this->builder;
        $builder->select("products.*,hd.hsn_code_4_digits")->where('products.id', $id)->join('hsn_details hd', 'hd.hsn_code = products.hsn_code', 'left');
        return $builder->get()->getRowArray();
        // echo $this->db->getLastQuery();die;
    }

    public function updateRow($data, $where)
    {

        $data1 = [
            "updated_at" => date('Y-m-d'),
        ];
        $data = array_merge($data, $data1);
        $builder = $this->builder;
        $builder->set($data);
        $builder->where($where);
        return $builder->update();
    }
    public function deleteRow($id)
    {
        $builder = $this->builder;
        $builder->set(['deleted_at' => date('Y-m-d')]);
        $builder->where('id', $id);
        $builder->update();
    }

    public function insertProperties($data)
    {
        return $this->db->table('product_meta')->insertBatch($data);
    }

    public function deleteProperites($product_id)
    {
        return $this->db->table('product_meta')->delete(['product_id' => $product_id]);
    }

    public function get_meta_data_of_product($product_id)
    {
        return $this->db->table('product_meta')->getWhere(['product_id' => $product_id])->getResult();
    }

    public function search_products($hsn_code, $cat_id, $search)
    {
        $comp_id = $this->session->get('user_details')['company_id'];
        $brnch_id = $this->session->get('user_details')['branch_id'];
        $this->builder->select('id,title,product_img,product_details')
            ->where("deleted_at is NULL")
            ->where(['company_id' => $comp_id])
            ->where("(title like '%$search%' or product_details like '%$search%')");
        if ($cat_id) {
            $this->builder->where('category_id', $cat_id);
        }
        if ($hsn_code) {
            $this->builder->where('hsn_code', $hsn_code);
        }
        $this->builder->groupBy('id', 'asc');
        return $this->builder->get()->getResult();
    }

    public function get_products_by_id($id_arr)
    {

        $comp_id = $this->session->get('user_details')['company_id'];
        $builder = $this->builder;
        $builder->select("products.*,hd.hsn_code_4_digits,ut.title as ut_title")->whereIn('products.id', $id_arr)->join('hsn_details hd', 'hd.hsn_code = products.hsn_code', 'left')->join('master_units ut', 'ut.id = products.unit_id', 'left')->groupBy('products.id', 'asc')->where('products.company_id', $comp_id);
        return $builder->get()->getResult();
        // echo $this->db->getLastQuery();die;
    }

}