<?php
echo view('include/header/header_top');
echo view('include/header/header');
echo view('include/sidebar/sidebar_member');
?>
<style>
.btn-lg {
    font-size: 1.286rem;
    padding: 6px 32px;
}
</style>
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title"><i class="fa fa-list"></i> Manage Product</h4>
                </div>
                <?php if (check_method_access('product', 'add', true)): ?>
                <div class="d-inline-block float-right">
                    <a href="<?php echo base_url("/product/product") ?>" class="btn btn-info btn-sm"><i
                            class="fa fa-list"></i>
                        Add New Product
                    </a>
                </div>
                <?php endif;?>
            </div>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="manage_product" class="table no-wrap product-order" data-page-size="10">
                                    <div class="row">
                                        <div class='mb-2 col-md-2'>
                                            <select name="care" class='select_category form-control' id="get_categ_id">
                                                <option value="">Select Category</option>
                                                <?php foreach ($category as $val) {?>
                                                <option value="<?=$val->id?>"><?=$val->category_name?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                    <thead>
                                        <tr>
                                            <th>Photo</th>
                                            <th>Product Details</th>
                                            <th>Category</th>
                                            <th>HSN</th>
                                            <th>GST</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
echo view('sub_modals/product_base/edit_category_modal.php');
echo view('include/footer/footer.php');
?>

<script src="<?=base_url('public/custom/js/product.js')?>"></script>