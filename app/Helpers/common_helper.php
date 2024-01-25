<?php

function prd($data)
{
    echo "<pre>";
    print_r($data);
    die;
}

function pr($data)
{
    echo "<pre>";
    print_r($data);
}

function to_fixed($number)
{
    return number_format($number, 2, '.', '');
}

function isActive($ctrl, $mthd = false)
{
    $router = service('router');
    $controller = $router->controllerName();
    $controllerName = explode('\\', $controller);
    $controllerName = strtolower(end($controllerName));
    $method = $router->methodName();
    $methodName = explode('\\', $method);
    $methodName = strtolower(end($methodName));
    if (!$mthd) {
        echo $controllerName == $ctrl ? "active" : "";
    } else {
        echo $controllerName == $ctrl && $methodName == $mthd ? "active" : "";
    }
}

function json_response($status, $msg, $data = [])
{
    echo json_encode([
        'status' => $status,
        'msg' => $msg,
        'data' => $data,
    ]);
}

function format_name($s)
{
    return trim(ucwords($s));
}

function check_file_existance($url)
{
    return @getimagesize($url) ? true : false;
}