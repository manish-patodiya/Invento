<?php
namespace App\Models;

use CodeIgniter\Model;

class CompanyModel extends Model
{
    public $builder;
    public $builder1;
    public $builder2;
    public $db;
    public function __construct()
    {
        $this->db = db_connect();
        $this->builder = $this->db->table('companies');
        $this->builder1 = $this->db->table('users');
        $this->builder2 = $this->db->table('company_users');
        $this->builder3 = $this->db->table('transaction_type');
    }

    // function to insert data in the table
    public function add_company($data)
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
    public function get_company_by_id($id)
    {
        $this->builder->select('companies.*,companies.id as company_id,users.id as user_id,users.first_name,users.last_name,users.username,company_users.role_id')
            ->join('company_users', 'companies.id=company_users.company_id', 'left')
            ->join('users', 'users.id=company_users.user_id', 'left')
            ->where('companies.id', $id);
        return $this->builder->get()->getRowArray();
    }
    public function updateRow($data, $where)
    {
        // /($where);
        $data1 = [
            "updated_at" => date('Y-m-d'),
        ];
        $data = array_merge($data, $data1);
        $builder = $this->builder;
        $builder->set($data);
        $builder->where($where);
        //echo $this->db->getLastQuery();die;
        return $builder->update();
    }

    public function getAll()
    {
        $builder = $this->builder;
        $builder->select("*,(select count(*) from company_users where company_id = companies.id) as count");
        $builder->where("deleted_at is NULL");
        return $builder->get()->getResult();
    }

    public function getCompaniesWithUsers()
    {
        return $this->db->table('companies c')
            ->select('c.*,u.first_name,u.last_name,ms.state_name,mc.city_name')
            ->join('company_users cu', 'cu.company_id=c.id')
            ->join('users u', 'u.id=cu.user_id')
            ->join('master_states ms', 'ms.state_id=c.state_id')
            ->join('master_cities mc', 'mc.city_id =c.city_id')
            ->where(["cu.branch_id" => 0, 'cu.staff_id' => 0])
            ->where('c.deleted_at is NULL')
            ->get()->getResult();
    }

    public function deleteRow($id)
    {
        $date = date('Y-m-d');
        $res = $this->db->query("update users,company_users,companies  set companies.deleted_at=$date, users.deleted_at = '$date' WHERE users.id= company_users.user_id and companies.id = company_users.company_id and companies.id=$id");
        return $res;
    }

    public function getField($fields, $where)
    {
        $this->builder->select($fields);
        $this->builder->where("deleted_at is NULL");
        if ($where) {
            $this->builder->where($where);
        }
        return $this->db->get()->getRowArray();
    }
    public function insertTransConceptId($data, $company_id)
    {
        // prd($company_id);
        $builder = $this->builder;
        $builder->set($data);
        $builder->where('id', $company_id);
        $builder->update();
        return $this->db->affectedRows();
    }

    // get feilds by id for companies tabel
    public function getFeilds_by_id($select, $where = "")
    {
        $builder = $this->builder;
        $builder->select($select);
        if ($where) {
            $builder->where($where);
        }
        $builder->where('deleted_at is NULL');
        return $builder->get()->getRow();
    }
    public function getBranches($company_id)
    {
        $builder = $this->builder2;
        $builder->select('company_id,branch_id')
            ->where('company_id', $company_id)
            ->where('branch_id!=', 0);
        return $builder->get()->getResult();

    }
    public function get_state_and_city($post_data)
    {

        $this->master_zipcode = $this->db->table('master_zipcode');
        $pincode = $post_data['pincode'];
        $this->master_zipcode->select('*');
        if ($pincode) {
            $this->master_zipcode->where("pincode=$pincode");
        }
        return $this->master_zipcode->get()->getRow();
    }

    // Inside the list, click on the button View branch picture to display the list of branches which appear in the company's behavior
    //We get to show the list of branch per company id basis
    //add, Edit, Delete, functionality form company id basis

    public function getBranch_by_company_id($cid)
    {
        $cid = base64_decode($cid);
        $this->CompanyBranch = $this->db->table('company_branches');
        return $this->db->table('company_branches b')
            ->select('b.*,cu.*,u.first_name,u.last_name,ms.state_name,mc.city_name')
            ->join('company_users cu', 'b.id=cu.branch_id')
            ->join('users u', 'cu.user_id=u.id')
            ->join('master_states ms', 'ms.state_id=b.state_id')
            ->join('master_cities mc', 'mc.city_id =b.city_id')
            ->where('cu.company_id', $cid)
            ->where('b.deleted_at is NULL')->where('cu.staff_id=0')
            ->get()->getResult();

    }
    public function get_branches_by_id($id)
    {

        $this->CompanyBranch = $this->db->table('company_branches');
        $this->CompanyBranch->select('company_branches.*,company_branches.id as bid,cus.branch_id, cus.user_id,cus.company_id,u.*')
            ->join('company_users as cus', 'cus.branch_id=company_branches.id')
            ->join('users u', 'u.id=cus.user_id')
            ->where('company_branches.id', $id);
        //echo $this->db->getLastQuery();die;
        return $this->CompanyBranch->get()->getRowArray();
    }
    public function updateRow_branch($data, $where)
    {

        $this->CompanyBranch = $this->db->table('company_branches');
        $data1 = [
            "updated_at" => date('Y-m-d'),
        ];
        $data = array_merge($data, $data1);
        $this->CompanyBranch->set($data);
        $this->CompanyBranch->where($where);
        $this->CompanyBranch->update();
        return $this->db->affectedRows();
    }

    // function to insert data in the table
    public function addview_branches($data)
    {
        $this->CompanyBranch = $this->db->table('company_branches');
        $data1 = [
            "updated_at" => date('Y-m-d'),
            "created_at" => date('Y-m-d'),
        ];
        $data = array_merge($data, $data1);
        $this->CompanyBranch->insert($data);
        return $this->db->insertID();
    }
    public function getTransType()
    {
        $builder3 = $this->builder1;
        $builder3->select('*');
        return $this->builder3->get()->getResult();
    }
    public function insertData($data, $branch_id = false)
    {

        $this->builder4 = $this->db->table('transaction_settings');
        $builder4 = $this->builder4;
        if (isset($branch_id) && $branch_id != "0") {
            $builder4->select('*')
                ->where('branch_id', $branch_id);
            $builder4->delete();
        }
        $result = $builder4->insertBatch($data);
        if ($result) {
            return true;
        }
    }
    public function deleteRow_branch($id)
    {
        $date = date('Y-m-d');
        $res = $this->db->query("update users,company_users,company_branches  set company_branches.deleted_at=$date, users.deleted_at = '$date' WHERE users.id= company_users.user_id and company_branches.id = company_users.branch_id and company_branches.id=$id");
        return $res;
    }

}