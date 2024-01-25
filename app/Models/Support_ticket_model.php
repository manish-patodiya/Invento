<?php
namespace App\Models;

use CodeIgniter\Model;

class Support_ticket_model extends Model
{
    public $builder;
    public $db;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->session->start();

        $this->db = db_connect();
        $this->builder = $this->db->table('support_tickets');

    }
    public function getSupport($tid = false)
    {
        $cid = $this->session->get('user_details')['company_id'];
        $bid = $this->session->get('user_details')['branch_id'];
        $builder = $this->builder;
        $builder->select("*, company_branches.id as bid, support_tickets_status_master.id as stid")->join('company_branches', 'company_branches.id=support_tickets.branch_id')->join('support_tickets_status_master', 'support_tickets_status_master.id=support_tickets.status_id')
            ->where("support_tickets.deleted_at is NULL");
        if ($tid != "") {
            $builder->where('status_id', $tid);
        };
        if ($cid) {
            $builder->where("support_tickets.company_id= $cid");
            $builder->where("support_tickets.branch_id= $bid");
        }
        return $builder->get()->getResult();
    }
    public function getStatus_id()
    {
        $builder = $this->builder = $this->db->table('support_tickets_status_master');
        $builder->select("*");
        return $builder->get()->getResult();
    }

    public function insertData($data)
    {
        $builder = $this->builder;
        $data1 = [
            "updeted_at" => date('Y-m-d'),
            "created_at" => date('Y-m-d'),
        ];
        $data = array_merge($data, $data1);
        $builder->insert($data);
        return $this->db->insertID();
    }

    public function updateRow($data, $where)
    {

        $data1 = [
            "updeted_at" => date('Y-m-d'),
        ];
        $data = array_merge($data, $data1);
        $builder = $this->builder;
        $builder->set($data);
        $builder->where($where);
        //echo $this->db->getLastQuery();die;
        return $builder->update();
    }
    public function update_status_id($data, $ticket_id)
    {
        $data1 = [
            "updeted_at" => date('Y-m-d'),
        ];
        $data = array_merge($data, $data1);
        $builder = $this->builder;
        $builder->where('ticket_id', $ticket_id)->set($data);
        //echo $this->db->getLastQuery();die;
        return $builder->update();
    }
    public function deleteRow($ticket_id)
    {
        $builder = $this->builder;
        $builder->set(['deleted_at' => date('Y-m-d')]);
        $builder->where('ticket_id', $ticket_id);
        return $builder->update();
    }
    public function get_support_by_id($ticket_id)
    {
        $this->builder->select('*')
            ->where('ticket_id', $ticket_id);
        return $this->builder->get()->getRowArray();
    }
    public function get_support_count()
    {
        $cid = $this->session->get('user_details')['company_id'];
        $bid = $this->session->get('user_details')['branch_id'];
        $this->builder->select('*,status_id,count(status_id) as count');
        $this->builder->groupBy('status_id');
        if ($cid) {
            $this->builder->where("support_tickets.company_id= $cid");
            $this->builder->where("support_tickets.branch_id= $bid");
        }
        $this->builder->where("deleted_at is NULL");
        $query = $this->builder->get()->getResult();
        return $query;
    }

    public function get_all_support_tickets()
    {

        $this->builder->select('*');
        $this->builder->where("deleted_at is NULL");
        return $this->builder->get()->getResult();
    }

    public function getFields($fields, $where)
    {
        $this->builder->select($fields);
        $this->builder->where("deleted_at is NULL");
        if ($where) {
            $this->builder->where($where);
        }
        return $this->builder->get()->getRowArray();
    }

}