<?php

namespace App\Models;

use CodeIgniter\Model;

class StockManagementModel extends Model
{
    public function __construct()
    {
        $this->session = service('session');
        $this->db = db_connect();
        $this->stock_builder = $this->db->table('stock_management');
    }

    public function getStockOfProduct($product_id)
    {
        $cid = $this->session->get('user_details')['company_id'];
        $bid = $this->session->get('user_details')['branch_id'];
        $stock_builder = $this->stock_builder;
        $stock_builder->select('SUM(stock) as stock')->where(['product_id' => $product_id, "company_id" => $cid, "branch_id" => $bid]);
        $res = $stock_builder->get()->getRow();
        return $res->stock;
    }

    public function insertRow($data)
    {
        $this->stock_builder->insert($data);
        return $this->db->insertID();
    }
    public function updatestockRow($data, $pid)
    {
        $stock_builder = $this->stock_builder;
        $stock_builder->set($data);
        $stock_builder->where('stock_management.product_id', $pid);
        return $stock_builder->update();
    }

    public function updateStock($product_id, $stock)
    {
        $cid = $this->session->get('user_details')['company_id'];
        $bid = $this->session->get('user_details')['branch_id'];
        $this->stock_builder->set(['stock' => $stock]);
        $this->stock_builder->where(['product_id' => $product_id, "company_id" => $cid, "branch_id" => $bid]);
        return $this->stock_builder->update();

    }

    public function getStockReport($filter, $branch_id = '')
    {

        if ($branch_id) {
            $cid = $this->session->get('user_details')['company_id'];
            $bid = $this->session->get('user_details')['branch_id'];
            $stock_builder = $this->stock_builder;
            $stock_builder->select('p.title,p.product_details,p.hsn_code,p.product_img,stock_management.stock,stock_management.low_stock,u.title as unit')->join('products p', 'p.id = stock_management.product_id')->join('master_units u', 'u.id=p.unit_id')->where('stock_management.company_id', $cid)->where('stock_management.branch_id', $branch_id);

            if ($filter['search']) {
                $search = $filter['search'];
                $stock_builder->like('p.title', $search);
                $stock_builder->orlike('p.hsn_code', $search);
            }
            if ($filter['low_stock'] && $filter['out_stock']) {
                $stock_builder->where('stock <= stock_management.low_stock');
            } else if ($filter['low_stock']) {
                $stock_builder->where('stock <= stock_management.low_stock AND stock > 0');
            } else if ($filter['out_stock']) {
                $stock_builder->Where('stock = 0');
            }
            if ($filter['limit'] || $filter['offset']) {
                $stock_builder->limit($filter['limit'], $filter['offset']);
            }
            return $stock_builder->get()->getResult();
        } else {

            $cid = $this->session->get('user_details')['company_id'];
            $bid = $this->session->get('user_details')['branch_id'];
            $active_role_id = $this->session->get('user_details')['active_role_id'];
            $stock_builder = $this->stock_builder;
            $stock_builder->select('p.title,p.product_details,p.hsn_code,p.product_img,stock_management.stock,stock_management.low_stock,u.title as unit')->join('products p', 'p.id = stock_management.product_id')->join('master_units u', 'u.id=p.unit_id');
            if ($active_role_id == 2) {
                $stock_builder->where(["stock_management.company_id" => $cid]);
            } else if ($active_role_id != 2) {
                $stock_builder->where(["stock_management.company_id" => $cid, "stock_management.branch_id" => $bid]);
            }

            if ($filter['search']) {
                $search = $filter['search'];
                $stock_builder->like('p.title', $search);
                $stock_builder->orlike('p.hsn_code', $search);
            }

            if ($filter['low_stock'] && $filter['out_stock']) {
                $stock_builder->where('stock <= stock_management.low_stock');
            } else if ($filter['low_stock']) {
                $stock_builder->where('stock <= stock_management.low_stock AND stock > 0');
            } else if ($filter['out_stock']) {
                $stock_builder->Where('stock = 0');
            }
            // ($filter['limit'] && $filter['offset']);
            if ($filter['limit'] || $filter['offset']) {
                $stock_builder->limit($filter['limit'], $filter['offset']);
            }
            return $stock_builder->get()->getResult();
        }
    }

    public function getTotalProductCount($search = '')
    {
        $cid = $this->session->get('user_details')['company_id'];
        $bid = $this->session->get('user_details')['branch_id'];
        $this->stock_builder->select('*')->where(["stock_management.company_id" => $cid, "stock_management.branch_id" => $bid]);
        if ($search) {
            $this->stock_builder
                ->join('products p', 'p.id = stock_management.product_id')
                ->like('p.title', $search)
                ->orlike('p.hsn_code', $search);
        }
        return $this->stock_builder->countAllResults();
    }

    public function getLowStockCount($branch_id = '')
    {
        if ($branch_id) {
            $cid = $this->session->get('user_details')['company_id'];
            $bid = $this->session->get('user_details')['branch_id'];
            $active_role_id = $this->session->get('user_details')['active_role_id'];
            $this->stock_builder->select('*');
            $this->stock_builder->where("stock_management.company_id", $cid)->where("stock_management.branch_id", $branch_id)->where('stock <= low_stock and stock > 0');
            return $this->stock_builder->countAllResults();
        } else {
            $cid = $this->session->get('user_details')['company_id'];
            $bid = $this->session->get('user_details')['branch_id'];
            $active_role_id = $this->session->get('user_details')['active_role_id'];
            $this->stock_builder->select('*');
            if ($active_role_id == 2) {
                $this->stock_builder->where(["stock_management.company_id" => $cid])->where('stock <= low_stock and stock > 0');
            } else if ($active_role_id != 2) {
                $this->stock_builder->where(["stock_management.company_id" => $cid, "stock_management.branch_id" => $bid])->where('stock <= low_stock and stock > 0');
            }

            return $this->stock_builder->countAllResults();
        }
    }

    public function getOutStockCount($branch_id = '')
    {

        if ($branch_id) {
            $cid = $this->session->get('user_details')['company_id'];
            $bid = $this->session->get('user_details')['branch_id'];
            $active_role_id = $this->session->get('user_details')['active_role_id'];
            $this->stock_builder->select('*');
            $this->stock_builder->where("stock_management.company_id", $cid)->where("stock_management.branch_id", $branch_id)->where('stock = 0');
            return $this->stock_builder->countAllResults();
        } else {

            $cid = $this->session->get('user_details')['company_id'];
            $bid = $this->session->get('user_details')['branch_id'];
            $active_role_id = $this->session->get('user_details')['active_role_id'];
            $this->stock_builder->select('*');
            if ($active_role_id == 2) {
                $this->stock_builder->where(["stock_management.company_id" => $cid])->where('stock = 0');
            } else if ($active_role_id != 2) {
                $this->stock_builder->where(["stock_management.company_id" => $cid, "stock_management.branch_id" => $bid])->where('stock = 0');
            }

            return $this->stock_builder->countAllResults();
        }
    }

    public function getComp_by_branch()
    {
        $cid = $this->session->get('user_details')['company_id'];
        $comp_builder = $this->db->table('company_users');
        $comp_builder->select('*,ms.state_name,mc.city_name')->join('company_branches B', 'B.id=company_users.branch_id')->join('master_states ms', 'ms.state_id=B.state_id')->join('master_cities mc', 'mc.city_id=B.city_id')->where('company_id', $cid)->where('branch_id!=', 0)->where('staff_id=', 0);
        return $comp_builder->get()->getResult();

    }
}
