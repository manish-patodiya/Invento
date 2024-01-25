<?php
namespace App\Models;

use CodeIgniter\Model;

class LabelModel extends Model
{
    public $builder;
    public $builder1;
    public $db;
    public function __construct()
    {
        $this->db = db_connect();
        $this->builder = $this->db->table('product_label');
        $this->builder1 = $this->db->table('product_value');
    }
    public function insertData($data)
    {
        $builder = $this->builder;
        $builder->insert($data);
        return $this->db->insertID();
    }
    public function getAll()
    {
        $this->builder->select('*');
        return $this->builder->get()->getResult();
    }
    public function get_detail_by_id($id)
    {
        $this->builder->select('*')
            ->where('id', $id);
        return $this->builder->get()->getRowArray();
    }
    public function deleteRow($id)
    {
        $builder = $this->builder;
        // $builder->select('*');
        // $builder->set(["deleted_at" => date('d-m-y')]);
        $builder->where('id', $id);
        $builder->delete();
        return $this->db->affectedRows();
    }
    public function updateData($data, $id)
    {
        $builder = $this->builder;
        $builder->set($data);
        $builder->where('id', $id);
        return $builder->update();
    }
    public function get_cities_state_id($id)
    {
        $this->builder->select('*')
            ->where('state_id', $id);
        return $this->builder->get()->getResult();
    }
    public function get_citys_state_by_id($state_id)
    {
        $this->builder->select('*')
            ->where('state_id', $state_id);
        return $this->builder->get()->getResult();
    }

    public function getCategories($label_id)
    {
        $this->builder1->select('product_value.product_cat_id,product_value.label_id,categories.category_name,categories.id')
            ->join('categories', 'categories.id=product_value.product_cat_id')
            ->where('label_id', $label_id)
            ->groupBy('product_cat_id');
        return $this->builder1->get()->getResult();
    }
    public function getValues($cat_id, $label_id)
    {
        $this->builder1->select('*')
            ->where('label_id', $label_id);
        if ($cat_id) {
            $this->builder1->where('product_cat_id', $cat_id);
        }
        return $this->builder1->get()->getResult();
    }
    public function insertLabelValue($data)
    {
        $builder1 = $this->builder1;
        $builder1->insert($data);
        return $this->db->insertID();
    }
}