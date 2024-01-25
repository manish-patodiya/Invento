<?php
echo view('include/header/header_top');
echo view('include/header/header');
echo view('include/sidebar/sidebar_member');
?>
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
                    <h4 class="page-title"><i class="fa fa-plus"></i> Add New Product</h4>
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
                            <form method="post" autocomplete="off" id="product_detail">
                                <h4 class="text-info mb-0"><i class="ti-user"></i> Product Info</h4>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Product Image</label>
                                            <div class='d-flex bg-secondary flex-column align-items-center justify-content-center mb-2'
                                                style='height: 250px; width:100%;'>
                                                <img src="<?=base_url('/public/uploads/image_found/add_product_images.jpg')?>"
                                                    id="logo" class="logo" style='max-height:100%; max-width:100%;'>
                                            </div>
                                            <input type="file" class='form-control' name="logo" id="product_img"
                                                accept=".png, .jpg, .jpeg">
                                            <small><i>(File type: png, jpg, jpeg)</i></small>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h4 for="username" class="control-label">General Information</h4>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="username" class="control-label">Barcode/QR-code</label>
                                                    <div class="controls">
                                                        <input type="text" name="barcode" class="form-control"
                                                            id="barcode" placeholder="Enter Your Barcode/QR-code">
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
                                                        placeholder="Enter your product details" rows='3'
                                                        data-validation-required-message="This field is required"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class='mt-0'>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label">Upload Catalog File</label>
                                                <div class="controls">
                                                    <input type="file" class='form-control' name='pdf' accept=".pdf"
                                                        id="pdf" />
                                                </div>
                                                <small><i>(File type should be pdf)</i></small>
                                            </div>
                                            <div class="form-group">
                                                <label for="username" class="control-label">Category<span
                                                        class='mandatory'>*</span></label>
                                                <div class="controls">
                                                    <select name="category_id" class="form-control bg-white"
                                                        id="category"
                                                        data-validation-required-message="This field is required">
                                                        <option value="">Select Category</option>
                                                        <?php foreach ($category as $v) {?>
                                                        <option value="<?=$v->id?>"><?=$v->category_name?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="username" class="control-label">Unit</label>
                                                <div class="controls">
                                                    <select name="unit_id" class="form-control bg-white" id="unit">
                                                        <option value="">Select Unit</option>
                                                        <?php foreach ($unit as $v) {?>
                                                        <option value="<?=$v->id?>"><?=$v->title?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="">HSN Detail</label>
                                                <div class="controls">
                                                    <div class="input-group">
                                                        <input type="text" id="hsn_detail" class="form-control"
                                                            placeholder="Select your HSN code" data-bs-toggle="modal"
                                                            data-bs-target=".bs-example-modal-lg" readonly
                                                            style='flex:2' />
                                                        <input type="text" class='form-control' readonly
                                                            placeholder="HSN code" id='inpt-hsn-code' style='flex:1'>
                                                        <input type="hidden" name="hsn_code" id="hsn_code" value="" />
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
                                                        data-validation-required-message="This field is required">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="">Minimum Reorder Level</label>
                                                <div class="controls">
                                                    <input type="number" class='form-control' name='stock' id='stock'
                                                        value='0'>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-md-8'>
                                        <h4>Properties</h4>
                                        <hr>
                                        <div id='div-prpty'>
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
                                                    <a class='btn btn-danger btn-sm' onclick='deleteRow(1)'><i
                                                            class='fa fa-close'></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class=''>
                                            <a class='btn btn-success btn-sm' onclick='addRow()'>Add Property</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">


                                    <button type="submit" class="btn btn-info btn-sm  pull-right ms-3" id="btn-save">
                                        Submit
                                    </button>
                                    <div class="pull-right ms-2">

                                    </div>
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

<script>
// upload pdf file
$("#pdf").change(function() {
    var file = this.files[0];
})
</script>

<script src="<?=base_url('public/custom/js/product.js')?>"></script>