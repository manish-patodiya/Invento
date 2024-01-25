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
                    <h4 class="page-title"><i class="fa fa-plus"></i> Create Transaction </h4>
                </div>
            </div>
        </div>
        <section class="content">
            <!-- mein body -->
            <div class="row">

                <!-- <div class="col-md-12">
                    <div class="card card-default">
                        <div class="m-3 d-flex justify-content-center">
                            <div class='col-md-8'> -->
                <!-- <div class="row" id='slimtest2'> -->
                <?php foreach ($type_list as $k => $v) {?>
                <div class="col-md-4">
                    <div class='box'>
                        <a href="<?=base_url("transaction_copy/create_transaction/$v->id")?>">
                            <div class='box-body'>
                                <h5><?=$v->title?></h5>
                            </div>
                        </a>
                    </div>
                </div>
                <?php }?>
                <!-- </div> -->
                <!-- </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </section>
    </div>
</div>
<?php
echo view('include/footer/footer.php');
?>

<script>
var AnimationsCSS3 = function() {
    // CSS3 animations
    var _componentAnimationCSS = function() {
        $('.box').addClass('animated zoomIn').one(
            'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend',
            function() {
                $(this).removeClass('animated zoomIn');
            });
    };

    return {
        init: function() {
            _componentAnimationCSS();
        }
    }
}();

AnimationsCSS3.init();
</script>