<?php
echo view('include/header/header_top');
echo view('include/header/header');
echo view('include/sidebar/sidebar_member');
?>
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <!-- <h4 class="page-title"><i class="fa fa-plus"></i> Transaction Setting</h4> -->
                </div>
            </div>
        </div>
        <section class="content">
            <div class="card card-default">
                <div class="m-3">
                    <form method="post" autocomplete="off" id="branch_trans_details" onsubmit="return false">
                        <h4 class="text-info mb-0">Transaction Setting</h4>
                        <hr class="my-15">
                        <?php foreach ($trans_type as $k => $v) {?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="username" class="control-label"><?=$v->title?></label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <!-- <div class=""> -->
                                    <input type="hidden" name='trans_type_id[]'
                                        value='<?=isset($v->id) ? $v->id : ""?>'>
                                    <input type="text" name="prefix[]" class="form-control" id="prefix"
                                        placeholder="Type your prefix" value="<?=isset($v->prefix) ? $v->prefix : ""?>">
                                    <!-- </div> -->
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="username" class="control-label">Starting No.</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <!-- <div class=""> -->
                                    <input type="text" name="start_no[]" class="form-control" id="start_no"
                                        placeholder="Type Starting No."
                                        value="<?=isset($v->start_no) ? $v->start_no : $start_no->start_no?>">
                                    <!-- </div> -->
                                </div>
                            </div>
                        </div>
                        <?php }?>
                        <div class="form-group">
                            <button type="submit" class="btn btn-info btn-sm px-4 pull-right " id="">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

<?php
echo view('include/footer/footer.php');
?>

<script src="<?=base_url('public/custom/js/generalsetting/branch_general_setting.js')?>"></script>