<div class="wrapper">
    <div id="loader"></div>
    <header class="main-header">
        <div class="d-flex align-items-center logo-box justify-content-start">
            <!-- Logo -->
            <a href="<?=base_url('dashboard')?>" class="logo">
                <!-- logo-->
                <div class="logo-mini w-50">
                    <?php $img = base_url('public/images/logo-letter.png')?>
                    <span class="light-logo"><img src="<?=$img?>" alt="logo"></span>
                    <span class="dark-logo"><img src="<?=$img?>" alt="logo"></span>
                </div>
                <div class="logo-lg">
                    <span class="light-logo"><img src="<?=base_url('public/images/logo-light-text.png')?>"
                            alt="logo"></span>
                    <span class="dark-logo"><img src="<?=base_url('public/images/logo-light-text.png')?>"
                            alt="logo"></span>
                </div>
            </a>
        </div>
        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <div class="app-menu">
                <ul class="header-megamenu nav">
                    <li class="btn-group nav-item">
                        <a href="#" class="waves-effect waves-light nav-link push-btn btn-primary-light"
                            data-toggle="push-menu" role="button">
                            <i data-feather="align-left"></i>
                        </a>
                    </li>
                    <li class="btn-group d-lg-inline-flex d-none">
                        <div class="app-menu">
                            <div class="search-bx mx-5">
                                <form>
                                    <div class="input-group">
                                        <input type="search" class="form-control" placeholder="Search"
                                            aria-label="Search" aria-describedby="button-addon3">
                                        <div class="input-group-append">
                                            <button class="btn" type="submit" id="button-addon3"><i
                                                    data-feather="search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="navbar-custom-menu r-side">
                <ul class="nav navbar-nav">
                    <style>
                    .my-4 {
                        margin-top: 2rem !important;
                        margin-bottom: 1.5rem !important;
                    }
                    </style>
                    <li class='d-flex align-items-center'>
                        <?php if (count($session->user_details['user_roles']) > 1) {?>
                        <label class="w-100">Login as:
                        </label>
                        <select name="" class="form-select form-control" id="slct-switch-role">
                            <?php
foreach ($session->user_details['user_roles'] as $role) {
    $slct = $session->user_details['active_role_id'] == $role->role_id ? "selected" : "";
    echo "<option value='$role->role_id' $slct cid='$role->company_id'> " . ucfirst($role->role) . " of $role->company_name</option>";
}?>
                        </select>
                        <?php } elseif (count($session->user_details['user_roles']) == 1) {?>
                        <div class='d-flex align-items-center justify-content-end me-3' style='height:100%;'>
                            <?php if ($session->user_details['active_role_id'] != 1): ?>
                            <img src="<?=$session->user_details['comp_logo']?>" alt="" height='50%'>
                            <?php endif;?>
                        </div>
                        <div class='text-end d-flex flex-column'>
                            <label style="font-size: 18px;">
                                <?php echo $session->user_details['comapny_name'] ?>
                            </label>
                            <label style="font-size: 13px;">
                                <label style="font-size: 15px;">
                                    <?php echo $session->user_details['branch_name'] ?>&nbsp;</label>
                                <?php echo $session->user_details['role_name'] ?></label>
                        </div>
                        <?php }?>

                    </li>
                    <!-- Notifications -->
                    <li class="dropdown notifications-menu">
                        <a href="#" class="waves-effect waves-light dropdown-toggle btn-info-light"
                            data-bs-toggle="dropdown" title="Notifications">
                            <i data-feather="bell"></i>
                        </a>
                        <ul class="dropdown-menu animated bounceIn">
                            <li class="header">
                                <div class="p-20">
                                    <div class="flexbox">
                                        <div>
                                            <h4 class="mb-0 mt-0">Notifications</h4>
                                        </div>
                                        <div>
                                            <a href="#" class="text-danger">Clear All</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <ul class="menu sm-scrol">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-users text-info"></i> Curabitur id eros quis nunc suscipit
                                            blandit.
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-warning text-warning"></i> Duis malesuada justo eu sapien
                                            elementum, in semper diam posuere.
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-users text-danger"></i> Donec at nisi sit amet tortor
                                            commodo
                                            porttitor pretium a erat.
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-shopping-cart text-success"></i> In gravida mauris et nisi
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-user text-danger"></i> Praesent eu lacus in libero dictum
                                            fermentum.
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-user text-primary"></i> Nunc fringilla lorem
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-user text-success"></i> Nullam euismod dolor ut quam
                                            interdum,
                                            at scelerisque ipsum imperdiet.
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="#">View all</a>
                            </li>
                        </ul>
                    </li>
                    <!-- User Account-->
                    <li class="dropdown user user-menu">
                        <a href="#"
                            class="waves-effect waves-light dropdown-toggle w-auto l-h-12 bg-transparent py-0 no-shadow"
                            data-bs-toggle="dropdown" title="User">
                            <img src="<?php
echo $session->get('user_details')['user_img']; ?>" class="avatar rounded-10 bg-primary-light h-40 w-40" alt="" />
                        </a>
                        <ul class="dropdown-menu animated flipInX">
                            <li class="user-body">
                                <a class="dropdown-item" href="<?=base_url('/profile/profile')?>"><i
                                        class="ti-user text-muted me-2"></i>
                                    Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?=base_url('auth/logout')?>"><i
                                        class="ti-lock text-muted me-2"></i>
                                    Logout</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>