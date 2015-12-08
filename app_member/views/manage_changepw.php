<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<h1>녹스 MEMBER 비밀번호 변경</h1>
<form action="/manage/changepw_act/" method="post">
    <label>아이디 : <?=$myinfo[0]['mb_id']?></label><br>
    <label>기존 비밀번호 입력<input type="password" name="old_pw"></label><br>
    <p>8자 이상</p>
    <label>새로운 비밀번호 입력<input type="password" name="new_pw"></label><br>
    <p>8자 이상</p>
    <label>기존 비밀번호 다시 입력<input type="password" name="new_pw_re"></label><br>
    <p>8자 이상</p>
    <input type="submit" value="저장">
</form>