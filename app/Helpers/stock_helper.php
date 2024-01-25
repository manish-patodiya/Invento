<?php

function updateStock($product_id, $qty, $action)
{
    $model = model('StockManagementModel');
    $prev_stock = $model->getStockOfProduct($product_id);
    if ($action == 'add') {
        $stock_qty = $prev_stock + $qty;
    } else if ($action == 'less') {
        $stock_qty = $prev_stock - $qty;
    }
    $stock = model('StockManagementModel')->updateStock($product_id, $stock_qty);
    return $stock;
}