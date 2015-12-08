<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!doctype html><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?=$GLOBAL_VARS['meta']['title']?></title>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="title"       content="<?=$GLOBAL_VARS['meta']['title']?>" />
<meta name="author"      content="<?=$GLOBAL_VARS['meta']['author']?>"/>
<meta name="keywords"    content="<?=$GLOBAL_VARS['meta']['keywords']?>"/>
<meta name="description" content="<?=$GLOBAL_VARS['meta']['description']?>" />
<link type="text/css" href="<?=$GLOBAL_VARS['path_common_css']?>/default.css" rel="stylesheet" charset="utf-8" />
<link type="text/css" href="<?=$GLOBAL_VARS['path_app_css']?>/admin.style.css" rel="stylesheet" charset="utf-8" />
<!--ADMIN LTE RESOURCE-->
<link rel="stylesheet" href="<?=$GLOBAL_VARS['path_app_etc']?>/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="<?=$GLOBAL_VARS['path_app_etc']?>/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="<?=$GLOBAL_VARS['path_app_etc']?>/ionicons/css/ionicons.min.css">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="<?=$GLOBAL_VARS['path_app_etc']?>/plugins/iCheck/all.css">
<!-- daterange picker -->
<link rel="stylesheet" href="<?=$GLOBAL_VARS['path_app_etc']?>/plugins/daterangepicker/daterangepicker-bs3.css">
<link rel="stylesheet" href="<?=$GLOBAL_VARS['path_app_etc']?>/dist/css/AdminLTE.min.css">
<link rel="stylesheet" href="<?=$GLOBAL_VARS['path_app_etc']?>/dist/css/skins/skin-blue.min.css">
</head>
<body class="hold-transition skin-blue sidebar-collapse sidebar-mini">
    <div class="wrapper">
        <!-- Main Header -->
        <header class="main-header">
            <!-- Logo -->
            <a href="/" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b><?=$admin['company']?></b></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b><?=$admin['company']?></b> ADMIN</span>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
            </nav>
        </header>
        <aside class="main-sidebar">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">

                <!-- Sidebar user panel (optional) -->
                <div class="user-panel">
                    <div class="pull-left info">
                        <p><?=$view_name?></p>
                    </div>
                </div>

                <!-- search form (Optional) -->
                <form action="#" method="get" class="sidebar-form">
                    <div class="input-group">
                        <input type="text" 
                               name="q" 
                               class="form-control" 
                               placeholder="메뉴검색">
                        <span class="input-group-btn">
                            <button type="submit" 
                                    name="search" 
                                    id="search-btn" 
                                    class="btn btn-flat">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </form>
                <!-- /.search form -->

                <!-- Sidebar Menu -->
                <ul class="sidebar-menu">
                    
                <?php foreach($admin['menus'] as $menu){ ?>
                    <?php if ($menu['is_tree']){ ?>
                    <li class="treeview <?=$menu['active']?>">
                        <a href="#">
                            <i class="<?=$menu['icon']?>"></i> 
                            <span><?=$menu['title']?></span> 
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            
                            <?php foreach($menu['tree_menus'] as $sub_menu){ ?>
                            <li>
                                <a href="<?=$sub_menu['url']?>"><?=$sub_menu['title']?></a>
                            </li>
                            
                            <?php } ?>
                        </ul>
                    </li>
                    
                    <?php } else { ?>
                    <li class="<?=$menu['active']?>">
                        <a href="<?=$menu['url']?>">
                            <i class="<?=$menu['icon']?>"></i> 
                            <span><?=$menu['title']?></span>
                        </a>
                    </li>
                    
                    <?php } ?>
                <?php } ?>
                </ul><!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>
        <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <?=$admin_sub_header?>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <!--콘텐츠 상단 경고, 알림 메세지 박스 영역-->
                <?=$content_top_message?>