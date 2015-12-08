<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<h1>녹스 MEMBER 회원가입</h1>
<form action="/join/join_act/" method="post">
    <label>아이디<input type="text" name="id"></label><br>
    <p>5자 이상 24자 이하. 영어 대소문자, 숫자, 언더바, 대쉬(언더바, 대쉬는 맨 앞에 올 수 없습니다.)</p>
    <label>비밀번호<input type="password" name="pw"></label><br>
    <p>8자 이상</p>
    <label>비밀번호확인<input type="password" name="pw_re"></label><br>
    <p>8자 이상</p>
    <label>이름<input type="text" name="name"></label><br>
    <p>2자 이상 12자 이하 (영문 대소문자, 숫자, 한글만 입력가능)</p>        
    <label>휴대폰<input type="text" name="hp"></label><br>
    <label>이메일<input type="text" name="email"></label><br>
    <input type="submit" value="가입">
</form>