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
                    <!-- <h4 class="page-title"><i class="fa fa-plus"></i> Invoice Concept </h4> -->
                </div>
            </div>
        </div>
        <section class="content">
            <div class="card card-default">
                <div class="m-3">
                    <form method="post" autocomplete="off" id="invoice_concept_details" onsubmit="return false">
                        <h4 class="text-info mb-0">Invoice Concept</h4>
                        <hr class="my-15">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="demo-radio-button">
                                    <?php $n = 1;foreach ($trans_concept_master as $k => $v) {?>
                                    <input name="group4" type="radio" id="radio_<?=$n?>" class="radio-col-success"
                                        value="<?=$v->id?>" <?=$v->id == 1 ? "Checked" : ""?>>
                                    <label for="radio_<?=$n?>"><?=$v->title?></label>
                                    <?php $n++;}?>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-info btn-sm px-4 pull-right " id="">
                                    Update
                                </button>
                            </div>
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