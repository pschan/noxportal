<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<h1>녹스 MEMBER 비밀번호 찾기</h1>
<form action="/common/login_act/" method="post">
    <label>아이디<input type="text" name="id"></label>
    <label>비밀번호<input type="password" name="pw"></label>
    <input type="submit" value="로그인">
</form>