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
                    <form method="post" autocomplete="off" id="edit_start_no" onsubmit="return false">
                        <h4 class="text-info mb-0">Transaction Setting</h4>
                        <hr class="my-15">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="username" class="control-label">Start No.</label>
                                </div>
                            </div>

                            <div class="col-md-10">
                                <div class="form-group">
                                    <input type="text" name="start_no" class="form-control" id="start_no"
                                        placeholder="Type your start no." value="<?=$start_no->start_no?>">
                                </div>
                            </div>

                        </div>
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

<script src="<?=base_url('public/custom/js/generalsetting/comp_general_setting.js')?>"></script>