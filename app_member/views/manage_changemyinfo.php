<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<h1>녹스 MEMBER 개인정보 변경</h1>
<form action="/manage/changemyinfo_act/" method="post">
    <label>아이디 : <?=$myinfo[0]['mb_id']?></label><br>
    <label>닉네임<input type="text" name="nick" value="<?=$myinfo[0]['mb_nick']?>"></label><br>
    <p>2자 이상 12자 이하 (영문 대소문자, 숫자, 한글만 입력가능)</p>        
    <label>이름<input type="text" name="name" value="<?=$myinfo[0]['mb_name']?>"></label><br>
    <p>2자 이상 12자 이하 (영문 대소문자, 숫자, 한글만 입력가능)</p>        
    <label>휴대폰<input type="text" name="hp" value="<?=$myinfo[0]['mb_hp']?>"></label><br>
    <label>이메일<input type="text" name="email" value="<?=$myinfo[0]['mb_email']?>"></label><br>
    <input type="submit" value="저장">
</form>