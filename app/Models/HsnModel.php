<?php
namespace App\Models;

use CodeIgniter\Model;

class HsnModel extends Model
{
    public $builder;
    public $db;
    public function __construct()
    {
        $this->db = db_connect();
        $this->builder = $this->db->table('hsn_details');

    }
    public function insertData($data)
    {
        $builder = $this->builder;
        $builder->insert($data);
        return $this->db->insertID();
    }

    public function insert_batch($data)
    {
        $builder = $this->builder;
        $builder->insertBatch($data);
        return $this->db->insertID();
    }

    public function getAll($limit, $offset, $filter = false)
    {
        $this->builder->select('*');
        if ($filter) {
            $this->builder->like('hsn_code', $filter, 'after')
                ->orlike('details', $filter, 'after');
        }
        $this->builder->limit($limit, $offset);
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
    public function updateRow($data, $where)
    {
        // ($where);
        $builder = $this->builder;
        $builder->set($data);
        $builder->where($where);
        return $builder->update();
    }

    public function getHSNCodes($term)
    {
        $term = $term ?: '';
        $builder = $this->builder;
        $codes = $builder->select('*')
            ->like('hsn_code', $term, 'after')
            ->orlike('hsn_code_4_digits', $term, 'after')
            ->limit(8, 0)
            ->get()
            ->getResult();
        return $codes;
    }
    public function get_hsn_id($id)
    {
        return $this->builder->select('*')
            ->where('id', $id)
            ->get()->getRow();
    }

    public function getCount($filter = '')
    {
        $this->builder->select('count(*) as count');
        if ($filter) {
            $this->builder->like('hsn_code', $filter, 'after')
                ->orlike('details', $filter, 'after');
        }
        return $this->builder->get()->getRow()->count;
    }
    public function get_hsn_detail($hsn_code)
    {
        $this->builder->select('hsn_code,details')
            ->where('hsn_code', $hsn_code);
        return $this->builder->get()->getRowArray();
    }
}
