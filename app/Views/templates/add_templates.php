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
                    <h4 class="page-title"><i class="fa fa-plus"></i> Create Templates </h4>
                </div>
                <div class="d-inline-block float-right">
                    <a href="<?=base_url('/templates');?>" class="btn btn-info btn-sm"><i class="fa fa-list"></i>
                        Template List</a>
                </div>
            </div>
        </div>
        <section class="content">

            <!-- mein body -->
            <div class="row">

                <div class="col-md-12">
                    <div class="card card-default">
                        <div class="m-3">
                            <form method="post" autocomplete="off" id="templates_detail" onsubmit="return false">
                                <h4 class="text-info mb-0">Template Info</h4>
                                <hr class="my-15">
                                <div class="form-group">
                                    <label for="username" class="control-label">Title<span
                                            class='mandatory'>*</span></label>
                                    <div class="controls">
                                        <input type="text" name="title" class="form-control" id="title"
                                            placeholder="Enter your title name"
                                            data-validation-required-message="This field is required">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="firstname" class="control-label">Subject<span
                                            class='mandatory'>*</span></label>
                                    <div class="controls">
                                        <input type="text" name="subject" class="form-control" id="subject"
                                            placeholder="Enter your subject"
                                            data-validation-required-message="This field is required">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="lastname" class="control-label">Content<span
                                            class='mandatory'>*</span></label>
                                    <div class="controls">
                                        <textarea name="content" id="content" rows="10" cols="80"
                                            placeholder="type your content "
                                            data-validation-required-message="This field is required"></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-info btn-sm px-4 pull-right">
                                        Save
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
echo view('include/footer/footer.php');
?>
<script src="<?=base_url('public/assets/vendor_components/ckeditor/ckeditor.js')?>"></script>
<script>
$(function() {
    CKEDITOR.replace('content');
});
</script>
<script src="<?=base_url('public/custom/js/templates.js')?>"></script>
</script>