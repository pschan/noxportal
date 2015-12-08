<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once '../global/Library.php';

# App 종속적 라이브러리 구현
class App_lib extends Noxent\Common\Library{
    # 비밀번호 해쉬 생성
    public function create_password_hash($value){
        
        $options = [
            'cost' => 11,
            'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
        ];
        return password_hash($value, PASSWORD_BCRYPT, $options);
    }    
}