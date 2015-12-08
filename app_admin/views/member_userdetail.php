<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!--content // start-->
<div class="col-md-6">
<form method="post" 
      action="/member/userdetail_act/" 
      onsubmit="return user_search_act();">
    <input type="hidden" name="mb_no" value="<?=$userdetail[0]['mb_no']?>">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title"><b><?=$userdetail[0]['mb_id']?></b> 상세 정보</h3>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered">
                <tr>
                    <td>
                        <label>회원상태 : 

                            <?php if (in_array($userdetail[0]['mb_state'], array('차단','휴면','탈퇴'))){ ?>
                            <span class="label label-danger">
                                <?=$userdetail[0]['mb_state']?>
                            </span>

                            <?php } else { ?>
                            <span class="label label-primary">
                                <?=$userdetail[0]['mb_state']?>
                            </span>

                            <?php } ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>본인인증 : 

                            <?php if ($userdetail[0]['mb_certi_key']){ ?>
                            <span class="label label-success">인증</span>

                            <?php } else { ?>
                            <span class="label label-warning">미인증</span>

                            <?php } ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>회원번호:</label>
                        <div class="input-daterange">
                            <input type="text" 
                                   class="form-control pull-right" 
                                   value="<?=$userdetail[0]['mb_no']?>" disabled>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>아이디:</label>
                        <div class="input-daterange">
                            <input type="text" 
                                   class="form-control pull-right" 
                                   value="<?=$userdetail[0]['mb_id']?>" disabled>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>닉네임:</label>
                        <div class="input-daterange">
                            <input type="text" 
                                   name="mb_nick" 
                                   class="form-control pull-right" 
                                   value="<?=$userdetail[0]['mb_nick']?>">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>이름:</label>
                        <div class="input-daterange">
                            <input type="text" 
                                   name="mb_name" 
                                   class="form-control pull-right" 
                                   value="<?=$userdetail[0]['mb_name']?>">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>이메일:</label>
                        <div class="input-daterange">
                            <input type="text" 
                                   name="mb_email" 
                                   class="form-control pull-right" 
                                   value="<?=$userdetail[0]['mb_email']?>">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>휴대폰:</label>
                        <div class="input-daterange">
                            <input type="text" 
                                   name="mb_hp" 
                                   class="form-control pull-right" 
                                   value="<?=$userdetail[0]['mb_hp']?>">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>가입IP:</label>
                        <div class="input-daterange">
                            <input type="text" 
                                   class="form-control pull-right" 
                                   value="<?=$userdetail[0]['mb_reg_ip']?>" disabled>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>가입일:</label>
                        <div class="input-daterange">
                            <input type="text" 
                                   class="form-control pull-right" 
                                   value="<?=$userdetail[0]['mb_reg_dt']?>" disabled>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>비밀번호 변경일:</label>
                        <div class="input-daterange">
                            <input type="text" 
                                   class="form-control pull-right" 
                                   value="<?=$userdetail[0]['mb_pw_edit_dt']?>" disabled>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>최근 로그인:</label>
                        <div class="input-daterange">
                            <input type="text" 
                                   class="form-control pull-right" 
                                   value="<?=$userdetail[0]['mb_login_dt']?>" disabled>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>비밀번호:</label>
                        <div class="input-group-btn">
                            <button class="btn btn-block btn-danger" type="button">
                                <b>초기화 메일 발송</b>
                            </button>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="input-group-btn">
            <button class="btn btn-block btn-default btn-lg" 
                    type="button" 
                    onclick="history.back(-1);">
                <b>이전페이지</b> <i class="fa fa-caret-left"></i>
            </button>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="input-group-btn">
            <button class="btn btn-block btn-primary btn-lg">
                <b>저장</b> <i class="fa fa-save"></i>
            </button>
        </div>
    </div>
</form>
</div>
<div class="col-md-6"></div>