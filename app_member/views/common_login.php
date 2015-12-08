<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<h1>녹스 MEMBER 로그인</h1>
<form action="/common/login_act/" method="post">
    <label>아이디<input type="text" name="id"></label>
    <label>비밀번호<input type="password" name="pw"></label>
    <input type="hidden"name="redirect" value="<?=$redirect?>">
    <input type="submit" value="로그인">
</form>