<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!doctype html><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$GLOBAL_VARS['meta']['title']?></title>
<meta name="title"       content="<?=$GLOBAL_VARS['meta']['title']?>" />
<meta name="author"      content="<?=$GLOBAL_VARS['meta']['author']?>"/>
<meta name="keywords"    content="<?=$GLOBAL_VARS['meta']['keywords']?>"/>
<meta name="description" content="<?=$GLOBAL_VARS['meta']['description']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link type="text/css" href="<?=$GLOBAL_VARS['path_common_css']?>/default.css" rel="stylesheet" charset="utf-8" />
</head>
<body>
    
<h1>녹스 포탈</h1>
<img src="<?=$GLOBAL_VARS['path_common_img']?>/noxlogo.gif">
<?php if ($is_login){ ?>
<a href="<?=$GLOBAL_VARS['url']['logout']?>">로그아웃</a>
<a href="<?=$GLOBAL_VARS['url']['changemyinfo']?>">정보변경</a>
<a href="<?=$GLOBAL_VARS['url']['changepw']?>">비밀번호변경</a>
<?php } else { ?>
<a href="<?=$GLOBAL_VARS['url']['login']?>">로그인</a>
<a href="<?=$GLOBAL_VARS['url']['join']?>">회원가입</a>
<?php } ?>    