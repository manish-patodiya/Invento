<?php
namespace App\Controllers;

use App\Libraries\Linvoice;

class Invoice extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->session->start();
        helper('common');
        $uri = service('uri');
        if (!$this->session->get('is_login')) {
            header("Location:" . base_url());
            exit;
        }
        $this->TransactionModel = model('TransactionModel');
    }
    public function view($id)
    {
        $data = [
            // 'transaction' => $this->TransactionModel->getTransaction($id),
            'trans_details' => $this->TransactionModel->getInvoice($id),
            'session' => $this->session,
        ];
        return view('transaction/invoice', $data);
    }
    public function get_invoice_by_id()
    {
        $id = 19;
        if ($id) {
            echo json_encode([
                "status" => 1,
                // 'transaction' => $this->TransactionModel->getTransaction($id),
                'trans_details' => $this->TransactionModel->getInvoiceDetails($id),
            ]);
        }
    }

    public function view_invoice($type, $inv_id)
    {
        $linvoice = new Linvoice();
        $html = $linvoice->view_invoice($type, $inv_id);
        $data = [
            'type' => $type,
            'inv_id' => $inv_id,
            'html' => $html,
            "session" => $this->session,
        ];
        return view('transaction/invoice1', $data);
    }

    public function print_invoice($type, $inv_id)
    {
        $linvoice = new Linvoice();
        $html = $linvoice->view_invoice($type, $inv_id, true);
        $data = [
            'html' => $html,
            "session" => $this->session,
        ];
        return view('transaction/testprint', $data);
    }
}