<?php
namespace App\Controllers\product;

use App\Controllers\BaseController;

class Product extends BaseController
{
    public $categorymodel;
    public $unitModel;
    public $productmodel;
    public $hsnmodel;
    public function __construct()
    {
        check_auth();
        check_access('product');
        $this->categorymodel = model('product_models/CategoryModel');
        $this->productmodel = model('product_models/ProductModel');
        $this->unitModel = model('UnitModel');
        $this->labelModel = model('LabelModel');
        $this->hsnmodel = model('HsnModel');
    }

    public function hsn_datatable_json()
    {
        $limit = $this->request->getGet("length");
        $offset = $this->request->getGet("start");
        $filter = $this->request->getGet("search[value]");
        $totalRecords = $this->hsnmodel->getCount($filter);

        $details = $this->hsnmodel->getAll($limit, $offset, $filter);

        $arr = [];
        foreach ($details as $k => $v) {
            $arr[] = [
                $v->hsn_code,
                $v->details,
                $v->gst_rate,
                '<button title="Select" class="delete btn btn-success select_hsn" gst_rate="' . $v->gst_rate . '"  hsn_id="' . $v->id . '" details="' . $v->details . '" hsn_code="' . $v->hsn_code . '"href="#" data-bs-toggle="modal" data-bs-target="#modal-center" > Select</button>',

            ];
        }
        echo json_encode([
            "status" => 1,
            "iTotalDisplayRecords" => $totalRecords,
            "recordsTotal" => 0,
            "details" => $arr,
        ]);
    }

    public function index()
    {
        check_method_access('product', 'add');
        $data = [
            'session' => $this->session,
            "category" => $this->categorymodel->getAll(),
            "unit" => $this->unitModel->getAll_unit_by_pro(),
            'labels' => $this->labelModel->getAll(),
        ];
        return view('product/add_product/add_product', $data);
    }
    public function add()
    {
        check_method_access('product', 'add');
        $category_id = $this->request->getPost('category_id');
        $data = array(
            'company_id' => $this->session->get('user_details')['company_id'],
            'branch_id' => $this->session->get('user_details')['branch_id'],
            "title" => format_name($this->request->getPost('product')),
            "barcode" => $this->request->getPost('barcode'),
            "product_img" => $this->_upload_logo(),
            "catalog_name" => !empty($_FILES['pdf']) ? $_FILES['pdf']['name'] : '',
            "catalog_file" => $this->_upload_pdf(),
            "category_id" => $category_id,
            "unit_id" => $this->request->getPost('unit_id'),
            "hsn_code" => $this->request->getPost('hsn_code'),
            "gst_rate" => $this->request->getPost('gst_rate'),
            "low_stock" => $this->request->getPost('stock'),
            "product_details" => format_name($this->request->getPost('pro_details')),
        );
        $product_id = $this->productmodel->insertData($data);

        //stock
        $data1 = array(
            'product_id' => $product_id,
            'low_stock' => $this->request->getPost('stock'),
            'company_id' => $this->session->get('user_details')['company_id'],
            'branch_id' => $this->session->get('user_details')['branch_id'],
        );
        $stock = model('StockManagementModel')->insertRow($data1);

        // insert properties of product
        $labels = $this->request->getPost('label');
        $values = $this->request->getPost('value');
        $this->insertProperties($labels, $values, $category_id, $product_id);

        if ($product_id) {
            echo json_encode([
                "status" => 1,
                "msg" => "Inserted Successfully products ",
            ]);
        }
    }

    private function insertProperties($labels, $values, $category_id, $product_id, $action = 'insert')
    {
        if ($action == 'update') {
            $this->productmodel->deleteProperites($product_id);
        }
        if (!$labels) {
            return false;
        }
        $data1 = [];
        foreach ($labels as $k => $label) {
            $properties = [];
            if (!is_numeric($label)) {
                $label_id = $this->labelModel->insertData(['label' => $label]);
                $properties['label_id'] = $label_id;
                $value_id = $this->labelModel->insertLabelValue([
                    'label_id' => $label_id,
                    'value' => $values[$k],
                    'product_cat_id' => $category_id,
                ]);
                $properties['value_id'] = $value_id;
            } else {
                if (!is_numeric($values[$k])) {
                    $value_id = $this->labelModel->insertLabelValue([
                        'label_id' => $label,
                        'value' => $values[$k],
                        'product_cat_id' => $category_id,
                    ]);
                    $properties['label_id'] = $label;
                    $properties['value_id'] = $value_id;
                } else {
                    $properties = [
                        'label_id' => $label,
                        'value_id' => $values[$k],
                    ];
                }
            }
            $properties = array_merge(['product_id' => $product_id], $properties);
            $data1[] = $properties;
        }
        if (!empty($data1)) {
            $this->productmodel->insertProperties($data1);
        }
    }

    private function _upload_logo()
    {
        $logo = $this->request->getFile('logo');
        $file_path = '';
        if ($logo->isValid()) {
            $upload_path = 'public/uploads/product_images/';
            $logo_name = $logo->getRandomName();
            $res = $logo->move($upload_path, $logo_name);
            if ($res) {
                $file_path = base_url($upload_path . $logo_name);
            }
        }
        return $file_path;

    }
    private function _upload_pdf()
    {
        $logo = $this->request->getFile('pdf');

        $file_path = '';
        if ($logo->isValid()) {
            $upload_path = 'public/uploads/catalog_pdf/';
            $logo_name = $logo->getRandomName();
            $res = $logo->move($upload_path, $logo_name);
            if ($res) {
                $file_path = base_url($upload_path . $logo_name);
            }
        }
        return $file_path;

    }

    public function manage_product()
    {
        check_method_access('product', 'view');

        $category = $this->categorymodel->getAll();
        $data = [
            'session' => $this->session,
            'category' => $category,
        ];
        return view('product/add_product/manage_product_list', $data);
    }

    public function manage_product_list()
    {
        check_method_access('product', 'view');
        $catid = $this->request->getGet('cid');
        $man_pro = $this->productmodel->getAll($catid);
        $arr = [];
        $i = 0;
        foreach ($man_pro as $k => $v) {
            $product_img = @getimagesize($v->product_img) ? $v->product_img : base_url('public/images/product/product-1.png');
            $action = '';
            if (check_method_access('product', 'edit', true)) {
                $action .= '<a title="Edit" class="text-warning sup_update me-1" href="' . base_url('product/product/edit/' . $v->id) . '" style="font-size: 1.2rem;" > <i class="fa fa-pencil-square-o"></i></a>';
            }
            if (check_method_access('product', 'delete', true)) {
                $action .= '<a title="Delete" class="text-danger sup_delete me-1"  uid="' . $v->id . '" href="#" title="Delete"  style="font-size: 1.2rem;"> <i class="fa fa-trash-o"></i></a>';
            }
            $arr[] = [
                '<img src="' . $product_img . '" style="width: 50px">',
                '<h5>' . $v->title . '</h5> <p class="mb-0">' . $v->product_details . '</p>',
                $v->category_name,
                $v->hsn_code_4_digits,
                $v->gst_rate . '%',
                $action,
            ];
        }
        echo json_encode([
            "status" => 1,
            "details" => $arr,
        ]);
    }

    public function edit($id)
    {
        check_method_access('product', 'edit');
        if ($this->request->getPost('submit')) {
            $pro_id = $this->request->getPost('pro_id');
            $category_id = $this->request->getPost('category_id');
            $data = array(
                "title" => format_name($this->request->getPost('product')),
                "barcode" => $this->request->getPost('barcode'),
                "category_id" => $category_id,
                "unit_id" => $this->request->getPost('unit_id'),
                "hsn_code" => $this->request->getPost('hsn_code'),
                "gst_rate" => $this->request->getPost('gst_rate'),
                "low_stock" => $this->request->getPost('stock'),
                "product_details" => format_name($this->request->getPost('pro_details')),
            );
            if (!empty($_FILES['logo']['name'])) {
                $data = array_merge($data, ['product_img' => $this->_upload_logo()]);
            }
            if (!empty($_FILES['pdf']['name'])) {
                $data['catalog_name'] = $_FILES['pdf']['name'];
                $data = array_merge($data, ['catalog_file' => $this->_upload_pdf()]);
            }
            $product = $this->productmodel->updateRow($data, "id=$pro_id");
            //stock .......
            $data1 = array(
                'low_stock' => $this->request->getPost('stock'),
            );
            // ($data1);
            $stock = model('StockManagementModel')->updatestockRow($data1, $id);

            // insert properties of product
            $labels = $this->request->getPost('label');
            $values = $this->request->getPost('value');
            $this->insertProperties($labels, $values, $category_id, $pro_id, 'update');

            if ($product) {
                echo json_encode([
                    "status" => 1,
                    "msg" => "Updated Successfully  Products",
                ]);
            }
        } else {
            $edit_data = $this->productmodel->get_product_by_id($id);
            $data['details'] = "";
            if ($edit_data['hsn_code'] != "") {
                $hsn_details = $this->hsnmodel->get_hsn_detail($edit_data['hsn_code']);
                $data['details'] = $hsn_details['details'];
                $data['hsn_code'] = $hsn_details['hsn_code'];
            }
            $data['product'] = $edit_data;
            $meta_data = $this->productmodel->get_meta_data_of_product($id);
            foreach ($meta_data as $k => $v) {
                $list = $this->labelModel->getValues($edit_data['category_id'], $v->label_id);
                $meta_data[$k]->value_list = $list;
            }
            $data['meta_data'] = $meta_data;
            $data['category'] = $this->categorymodel->getAll();
            $data['unit'] = $this->unitModel->getAll_unit_by_pro();
            $data['session'] = $this->session;
            $data['labels'] = $this->labelModel->getAll();
            return view('product/add_product/edit_product', $data);
        }

    }
    public function deleted()
    {
        check_method_access('product', 'delete');
        $id = $this->request->getPost('id');
        $this->productmodel->deleteRow($id);
        echo json_encode([
            'status' => 1,
            'msg' => 'Product was deleted successfully',
        ]);
    }

    public function search_products()
    {
        $cat_id = $this->request->getGet('cat_id');
        $search_val = $this->request->getGet('search');
        $hsn_code = $this->request->getGet('hsn_code');
        $products = $this->productmodel->search_products($hsn_code, $cat_id, $search_val);
        json_response(1, 'Successfully Fetched', $products);

    }

    public function get_products_by_id()
    {

        $pids = $this->request->getPost('product_ids');
        $product_info = [];
        if ($pids) {
            $id_arr = explode(',', $pids);
            $product_info['product'] = $this->productmodel->get_products_by_id($id_arr);
            // $product_info['unit'] = $this->unitModel->getAll_base_unit();
        }
        json_response(1, 'Fetched successfully', $product_info);
    }

    public function get_values($id = '0')
    {
        if ($this->request->getPost('value') != "") {
            $data = [
                "label_id" => $this->request->getPost('label_id'),
                "product_cat_id" => $this->request->getPost('cat_id'),
                "value" => $this->request->getPost('value'),
            ];
            $result = $this->labelModel->insertLabelValue($data);
            if ($result) {
                echo json_encode([
                    "status" => 1,
                ]);
            }
        } else {
            $id = $this->request->getPost('cat_id');
            $label_id = $this->request->getPost('label_id');
            $values = $this->labelModel->getValues($id, $label_id);
            echo json_encode([
                "status" => '1',
                'values' => $values,
            ]);
        }
    }
}