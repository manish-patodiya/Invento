<div class="modal modal-left fade" id="mdl-find-product" style='width:700px'>
    <div class="modal-dialog">
        <div class="modal-content" style='width:700px'>
            <div class="modal-header p-2">
                <h5 class="modal-title">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class='modal-body pb-0'>
                <div class='row form-group'>
                    <div class="col-md-6">
                        <select id="slct-hsn" style='width:100%' onchange='searchProducts()'>
                        </select>
                    </div>
                    <div class='col-md-6'>
                        <select id="slct-category" style='width:100%' onchange='searchProducts()'>
                            <option value="">Select Category</option>
                            <?php foreach ($category_list as $c) {?>
                            <option value="<?=$c->id?>">
                                <?=$c->category_name?>
                            </option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class='form-group'>
                    <input type="text" id='srch-product' class='form-control' onkeyup='searchProducts()'
                        placeholder='Search Here' />
                </div>
            </div>
            <div class="modal-body p-2" style='overflow-y:scroll; overflow-x:hidden;height:100%;'>
                <table class='table product-overview'>
                    <tbody id='srched-products'></tbody>
                </table>
            </div>
            <div class="modal-footer p-2">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary float-end" data-bs-dismiss="modal"
                    onclick="addProductRow();">Add</button>
            </div>
        </div>
    </div>
</div>