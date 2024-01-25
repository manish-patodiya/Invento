<?php
echo view('include/header/header_top');
echo view('include/header/header');
echo view('include/sidebar/sidebar_member');
?>
<style>
.table>tbody>tr>td,
.table>tbody>tr>th {
    padding: 0.4rem;
    vertical-align: middle;
}

.bg-orange {
    background-color: #f01935 !important;
}

.box-body {
    padding: 0.5rem;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    border-radius: 10px;
}
</style>
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title"><i class="fa fa-list"></i> Support Ticket </h4>
                </div>
                <?php if (check_method_access('support', 'add', true)): ?>
                <div class="d-inline-block float-right">
                    <a href="#" class="btn btn-info btn-sm add"><i class="fa fa-plus"></i>
                        Add Support Ticket</a>
                </div>
                <?php endif;?>
            </div>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-md-2">
                    <div class="box box-inverse box-info" id="total_tickets">
                        <div class="box-body">
                            <div class="text-center">
                                <a class="text-white" href="javascript:void(0)">
                                    <div class="fs-24 count"><?=$count?></div>
                                    <span>Total Tickets</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="box box-inverse box-success" id="compl_tickets" tid='2'>
                        <div class="box-body">
                            <div class="text-center">
                                <a class="text-white" href="javascript:void(0)">
                                    <div class="fs-24 count">
                                        <?php if (isset($status_count['2'])) {echo $status_count['2']?>
                                        <?php } else {echo 0;}?></div>
                                    <span>Complete tickets</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="box box-inverse box-danger" id="panding_tickets" tid='1'>
                        <div class="box-body">
                            <div class="text-center">
                                <a class="text-white" href="javascript:void(0)">
                                    <div class="fs-24 count">
                                        <?php if (isset($status_count['1'])) {echo $status_count['1']?>
                                        <?php } else {echo 0;}?></div>
                                    <span>Pending Tickets</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="box box-inverse box-warning" id="hold_tickets" tid='3'>
                        <div class="box-body">
                            <div class="text-center">
                                <a class="text-white" href="javascript:void(0)">
                                    <div class="fs-24 count">
                                        <?php if (isset($status_count['3'])) {echo $status_count['3']?>
                                        <?php } else {echo 0;}?></div>
                                    <span>ON Hold Tickets</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="box box-inverse" style="background-color: #f01935;" id="reject_tickets" tid='4'>
                        <div class="box-body">
                            <div class="text-center">
                                <a class="text-white" href="javascript:void(0)">
                                    <div class="fs-24 count">
                                        <?php if (isset($status_count['4'])) {echo $status_count['4']?>
                                        <?php } else {echo 0;}?>
                                    </div>
                                    <span>Rejected Tickets</span>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="box">
                        <input type="hidden" id='status-shown-in-tbl' value=''>
                        <div class="box-body p-15">
                            <div class="col-12 table-responsive">
                                <table id="support_table" class="table mt-0 table-hover no-wrap">
                                    <thead>
                                        <tr>
                                            <th class="col">Ticket</th>
                                            <th class="col">User. Email</th>
                                            <th class="col">Subject</th>
                                            <th class="col">Date</th>
                                            <th class="col">Status</th>
                                            <th class="col">Action</th>
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
echo view('sub_modals/support_ticket/edit_support_model.php');
echo view('sub_modals/support_ticket/add_support_ticket_model.php');
echo view('sub_modals/support_ticket/support_tickets_view_model.php');
echo view('include/footer/footer.php');
?>

<script src="<?=base_url('public/custom/js/support.js')?>"></script>