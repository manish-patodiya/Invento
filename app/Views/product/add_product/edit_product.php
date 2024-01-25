<?php
echo view('include/header/header_top');
echo view('include/header/header');
echo view('include/sidebar/sidebar_member');
// ($product);
 ?>
<style>
.form-control {
    background-color: white !important;
}
</style>
<script>
let LABELS = '<?=json_encode($labels)?>';
var select2_labels_options = [];
JSON.parse(LABELS).forEach(item => {
    select2_labels_options.push({
        id: item.id,
        text: item.label,
    })
});
</script>

<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title"><i class="fa fa-pencil"></i> Edit Product </h4>
                </div>
                <div class="d-inline-block float-right">
                    <a href="<?php echo base_url("/product/product/manage_product") ?>" class="btn btn-info btn-sm"><i
                            class="fa fa-list"></i>
                        Manages Product List
                    </a>
                </div>
            </div>
        </div>
        <section class="content">

            <!-- mein body -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-default">
                        <div class="m-3">
                            <form method="post" autocomplete="off" id="product_updeted_detail" onsubmit="return false">
                                <input type="hidden" name='pro_id' id='pro_id' value="<?=$product['id']?>">
                                <h4 class="text-info mb-0"><i class="ti-user"></i> Product Info</h4>
                                <hr class="my-15">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Product Image<span
                                                    class='mandatory'>*</span></label>
                                            <div class='d-flex bg-secondary flex-column align-items-center justify-content-center mb-2'
                                                style='height: 250px; width:100%;'>
                                                <img src="<?=$product['product_img'] == "" ? base_url('/public/uploads/image_found/add_product_images.jpg') : $product['product_img']?>"
                                                    id="logo" class="logo" style='max-height:100%; max-width:100%;'
                                                    data-validation-required-message="This field is required">
                                            </div>
                                            <input type="file" class='form-control' name="logo" id="product_img"
                                                accept=".png, .jpg, .jpeg">
                                            <small><i>(File type: png, jpg, jpeg)</i></small>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h4>General Information</h4>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="username" class="control-label">Barcode/QR-code</label>
                                                    <div class="controls">
                                                        <input type="text" name="barcode" class="form-control"
                                                            id="barcode" placeholder="Enter Your Barcode/QR-code"
                                                            value="<?=$product['barcode']?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="username" class="control-label">Product Name<span
                                                            class='mandatory'>*</span></label>
                                                    <div class="controls">
                                                        <input type="text" name="product" class="form-control"
                                                            id="product" placeholder="Enter your product name"
                                                            value="<?=$product['title']?>"
                                                            data-validation-required-message="This field is required">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label for="username" class="control-label">Product Details<span
                                                    class='mandatory'>*</span></label>
                                            <div class="form-group">
                                                <div class="controls">
                                                    <textarea name="pro_details" class="form-control" id="pro_details"
                                                        placeholder="Enter your product details"
                                                        data-validation-required-message="This field is required"
                                                        rows='3'><?=$product['product_details']?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="mt-0">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label">Upload Catalog File</label>
                                                <div class="input-group">
                                                    <input type="file" class='form-control' name='pdf' accept=".pdf"
                                                        id="pdf">
                                                    <?php if ($product['catalog_file']): ?>
                                                    <a href="<?=base_url($product['catalog_file'])?>"
                                                        class="btn btn-sm btn-success"
                                                        download="<?=$product['catalog_name']?>"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="<?=$product['catalog_name']?>"><i
                                                            class="fa fa-download"></i></a>
                                                    <?php endif;?>
                                                </div>
                                                <small><i>(File type should be pdf)</i></small>
                                            </div>
                                            <div class="form-group">
                                                <label for="username" class="control-label">Category<span
                                                        class='mandatory'>*</span></label>
                                                <div class="controls">
                                                    <select name="category_id" class="form-control" id="category"
                                                        onchange='$("#div-prpty").html("");addRow();'
                                                        data-validation-required-message="This field is required">
                                                        <option value="">Select Category</option>
                                                        <?php foreach ($category as $v) {?>
                                                        <option value="<?=$v->id?>"
                                                            <?=$v->id == $product['category_id'] ? 'selected' : false?>>
                                                            <?=$v->category_name?>
                                                        </option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="username" class="control-label">Unit</label>
                                                <div class="controls">
                                                    <select name="unit_id" class="form-control" id="unit"
                                                        value="<?=$product['unit_id']?>">
                                                        <option value="">Select Unit</option>
                                                        <?php foreach ($unit as $v) {?>
                                                        <option value="<?=$v->id?>"
                                                            <?=$v->id == $product['unit_id'] ? 'selected' : false?>>
                                                            <?=$v->title?>
                                                        </option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="">HSN Code</label>
                                                <div class="controls">
                                                    <div class="input-group">
                                                        <input type="text" id="hsn_detail" class="form-control"
                                                            placeholder="Select your HSN code" data-bs-toggle="modal"
                                                            data-bs-target=".bs-example-modal-lg" value="<?=$details?>"
                                                            style='flex:2' />

                                                        <input type="text" class='form-control' readonly
                                                            id='inpt-hsn-code' placeholder='HSN code'
                                                            value='<?=$product['hsn_code']?>' style='flex:1'>

                                                        <input type="hidden" name="hsn_code" id="hsn_code"
                                                            value="<?=$hsn_code?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="">GST Rate(%)<span class='mandatory'>*</span></label>
                                                <div class="controls">
                                                    <input type="text" class='form-control' name='gst_rate'
                                                        id='gst-rate'
                                                        data-validation-required-message="This field is required"
                                                        value='<?=$product['gst_rate']?>'>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="">Minimum Reorder Level</label>
                                                <div class="controls">
                                                    <input type="number" class='form-control' name='stock' id='stock'
                                                        value='<?=$product['low_stock'] ?: 0?>'>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-md-8'>
                                        <h4>Properties</h4>
                                        <hr>
                                        <div id='div-prpty'>
                                            <?php if (!empty($meta_data)): ?>
                                            <?php $i = 1;foreach ($meta_data as $k => $v) {?>
                                            <div class='form-group row prpty-row' id='prpty-row-<?=$i?>'>
                                                <div class='col-md-6'>
                                                    <select name="label[]" id='prpty-label-<?=$i?>'
                                                        onchange='getValues(<?=$i?>)' class='prpty-label form-control'
                                                        style='width:100%'>
                                                        <?php foreach ($labels as $label) {?>
                                                        <?php $slct = $v->label_id == $label->id ? 'selected' : false?>
                                                        <option value="<?=$label->id?>" <?=$slct?>><?=$label->label?>
                                                        </option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class='col-md-5'>
                                                    <select name="value[]" id="prpty-val-<?=$i?>"
                                                        class='prpty-val form-control' style='width:100%'>
                                                        <?php foreach ($v->value_list as $k => $value) {?>
                                                        <?php $slct = $v->value_id == $value->id ? 'selected' : false?>
                                                        <option value="<?=$value->id?>" <?=$slct?>><?=$value->value?>
                                                        </option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class='col-md-1'>
                                                    <a class='btn btn-danger' onclick='deleteRow(<?=$i?>)'><i
                                                            class='fa fa-close'></i></a>
                                                </div>
                                            </div>
                                            <?php $i++;}?>
                                            <?php else: ?>
                                            <div class='form-group row prpty-row' id='prpty-row-1'>
                                                <div class='col-md-6'>
                                                    <select name="label[]" id='prpty-label-1' onchange='getValues(1);'
                                                        class='prpty-label form-control' style='width:100%'>
                                                        <option value=""></option>
                                                        <?php foreach ($labels as $label) {?>
                                                        <option value="<?=$label->id?>"><?=$label->label?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class='col-md-5'>
                                                    <select name="value[]" id="prpty-val-1"
                                                        class='prpty-val form-control' style='width:100%'></select>
                                                </div>
                                                <div class='col-md-1'>
                                                    <a class='btn btn-danger' onclick='deleteRow(1)'><i
                                                            class='fa fa-close'></i></a>
                                                </div>
                                            </div>
                                            <?php endif;?>
                                        </div>
                                        <div class=''>
                                            <a class='btn btn-success btn-sm' onclick='addRow()'>Add Property</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="submit" value="1" />
                                    <button type="submit" class="btn btn-info btn-sm pull-right " id="">
                                        Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
echo view('sub_modals/product_base/hsn_code_datatable.php');
echo view('sub_modals/product_base/edit_category_modal.php');
echo view('include/footer/footer.php');
?>

<script src="<?=base_url('public/custom/js/product.js')?>"></script>