<?php
namespace App\Models;

use CodeIgniter\Model;

class GeneralSetting extends Model
{
    public $builder; // for transaction_settings table
    public $builder1; // for transaction_type table
    public $builder2;
    public $module;
    public $menu;
    public $db;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->session->start();

        $this->db = db_connect();
        $this->builder = $this->db->table('transaction_settings');
        $this->builder1 = $this->db->table('transaction_type');
        $this->builder2 = $this->db->table('trans_concept_master');
        $this->module = $this->db->table('module');
    }
    public function getAll($branch_id)
    {
        $builder1 = $this->builder1;
        $builder1->select('*,transaction_settings.id as tid,transaction_type.id')
            ->join('transaction_settings', "transaction_type.id=transaction_settings.trans_type_id", "left")
            ->where('branch_id', $branch_id);
        // if (isset($company_id) && $company_id != 0) {
        // $this->builder1->where('company_id', $branch_id);
        // }
        return $this->builder1->get()->getResult();
    }
    public function insertData($data, $branch_id = false)
    {
        $builder = $this->builder;
        if (isset($branch_id) && $branch_id != "0") {
            $builder->select('*')
                ->where('branch_id', $branch_id);
            $builder->delete();
        }
        $result = $builder->insertBatch($data);
        if ($result) {
            return true;
        }
    }

    public function getTransType()
    {
        $builder1 = $this->builder1;
        $builder1->select('*');
        return $this->builder1->get()->getResult();
    }
    public function getTransConceptMaster($company_id)
    {
        $builder2 = $this->builder2;
        $builder2->select('*');
        // ->join('companies c', 'trans_concept_master.id=c.trans_concept_id', 'left');
        // ->where('c.id', $company_id);
        return $this->builder2->get()->getResult();
    }
    public function updateStartNo($data, $branch_id)
    {
        $builder = $this->builder;
        foreach ($branch_id as $k => $v) {
            $builder->set($data);
            $builder->where('branch_id', $v);
            $builder->update();}
    }

    public function get_all_module()
    {
        $module = $this->module;
        return $module->select('*')->where('method != ""')->get()->getResult();

        // echo $this->db->getLastQuery()->getQuery();
    }
    // add to zipcode here and get the city  and state from zipcode
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
    public function get_state_by_id($id)
    {
        $this->master_builder = $this->db->table('master_states');
        $this->master_builder->select('*')
            ->join('master_countries', 'master_countries.id=master_states.country_id')
            ->where('state_id', $id);
        return $this->master_builder->get()->getRowArray();
    }
    public function get_cities_id($id)
    {
        $this->master_cities = $this->db->table('master_cities');
        $this->master_cities->select('*')
            ->where('state_id', $id);
        return $this->master_cities->get()->getResult();
    }
}