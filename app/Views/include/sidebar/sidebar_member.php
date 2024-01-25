<?php
$modules = model('Modules')->getModules();
$uris = service('uri');
$uri_arr = $uris->getSegments();
$final_uri = implode('/', $uri_arr);
?>
<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar position-relative">
        <div class="multinav">
            <div class="multinav-scroll" style="height: 100%;">
                <!-- sidebar menu-->
                <ul class="sidebar-menu" data-widget="tree">
                    <?php
function sidebar($modules, $uri, $uri_arr, $is_sub = 0)
{
    foreach ($modules as $key => $m) {
        $sub_modules = model('Modules')->getSubModulesByID($m->id);
        $icon = $m->feather_icon ?: 'circle';
        $title = $m->title;
        $w = $is_sub ? 'width:8px;margin:0 0.3rem 0 1rem ;' : '';
        if (!$sub_modules) {
            if (check_module_access($m->controller)):
                $start_uri = $m->controller ? '/' . "$m->start_uri" : '';
                $controller = $m->controller ? '/' . "$m->controller" : '';
                $method = $m->method ? '/' . "$m->method" : '';
                $url = base_url($start_uri . $controller . $method);
                $active = '';
                if ($url == base_url($uri)) {
                    $active = 'active';
                    $icon_clr = '';
                } else if ($m->other_uri && strpos(base_url($uri), base_url($start_uri . $controller)) === 0) {
                foreach (explode('|', $m->other_uri) as $op) {
                    if (in_array($op, $uri_arr)) {
                        $active = 'active';
                        $icon_clr = '';
                    }
                }
            } else {
                $active = '';
                $icon_clr = 'text-white';
            }
            echo "<li class='$active sub-menu-tab'>
                    <a href='$url'>
                        <i data-feather='$icon' style='$w' class='$icon_clr'></i>
                        <span>$title</span>
                    </a>
                 </li>";
            endif;
        } else {
            $has_sub_modules = 0;
            foreach ($sub_modules as $sm) {
                if (check_module_access($sm->controller)) {
                    $has_sub_modules = 1;
                    break;
                }
            }
            if ($has_sub_modules) {
                echo "<li class='treeview'>
                        <a href='#'>
                        <i data-feather='$icon' style='$w'></i>
                        <span>$title</span>
                        <span class='pull-right-container'>
                            <i class='fa fa-angle-right pull-right'></i>
                        </span>
                    </a>
                    <ul class='treeview-menu'>";
                sidebar($sub_modules, $uri, $uri_arr, 1);
                echo "</ul></li>";
            }
        }
    }
}
sidebar($modules, $final_uri, $uri_arr);
?>
                </ul>
            </div>
        </div>
    </section>
</aside>