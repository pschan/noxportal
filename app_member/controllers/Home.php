<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public $DATA = array();
    
    public function __construct() {
        parent::__construct();
        
        # 뷰 파일에 전달 할 [공통 데이터]
        $this->app_lib->set_common_data($this->DATA);
    }
    
	public function index(){
        
        # 뷰 파일 로딩
		$this->load->complete('home_index', $this->DATA);
	}
}