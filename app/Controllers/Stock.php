<?php
namespace App\Controllers;

class Stock extends BaseController
{
    public function __construct()
    {
        $this->session = service('session');
        $this->StockManagement = model('StockManagementModel');
    }

    public function index()
    {

        $data = [
            'session' => $this->session,
            'branches' => $this->StockManagement->getComp_by_branch(),
        ];
        return view('stock/stock_report', $data);
    }

    public function stockreport()
    {

        $branch_id = $this->request->getGet('branch_id');
        $limit = $this->request->getGet("length");
        $offset = $this->request->getGet("start");
        $search = $this->request->getGet("search[value]");
        $low_stock = $this->request->getGet('low_stock');
        $out_stock = $this->request->getGet('out_stock');
        $filter = [
            'limit' => $limit,
            'offset' => $offset,
            'search' => $search,
            'low_stock' => $low_stock,
            'out_stock' => $out_stock,
        ];
        $totalRecords = $this->StockManagement->getTotalProductCount($search);
        $stock_data = $this->StockManagement->getStockReport($filter, $branch_id);
        $getOutStockCount = $this->StockManagement->getOutStockCount($branch_id);
        $getLowStockCount = $this->StockManagement->getLowStockCount($branch_id);
        $arr = [];
        $i = $offset;
        $isLowStock = [];
        foreach ($stock_data as $k => $v) {
            $arr[] = [
                ++$i . '.',
                "<img src='$v->product_img' width='60' height='60'/>",
                "<h4 class='m-0 col-md-8'>$v->title</h4>
                <span class='m-0 text-secondary'>HSN: $v->hsn_code</span>
                <p class='m-0'>$v->product_details</p>",
                "$v->unit",
                "$v->stock",
                "$v->low_stock",
            ];
            $isLowStock[$i] = $v->stock < $v->low_stock;
        }

        echo json_encode([
            "iTotalDisplayRecords" => $totalRecords,
            "recordsTotal" => $totalRecords,
            "report" => $arr,
            "OutStockCount" => $getOutStockCount,
            "LowStockCount" => $getLowStockCount,
            "lowStockData" => $isLowStock,
        ]);
    }

}