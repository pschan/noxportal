<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!--content // start-->
<div class="col-xs-12">
    <div class="box">
        <form method="get" name="search_form">
        <div class="box-header">
            <h3 class="box-title">total : <?=number_format($total_num)?></h3>
            <div class="box-tools">
                <div class="input-group" style="width: 200px;">
                    <button id="search_button" 
                            class="btn btn-sm pull-right <?=$search_button?>" 
                            type="button" 
                            onclick="search_detail();">
                        <i id="search_icon" class="fa <?=$search_icon?>"></i>
                    </button>
                    <select class="form-control" 
                            name="list_limit"
                            style="width:80px;height:30px;float:right;margin-right:10px;"
                            onchange="document.search_form.submit();">
                        <option value="10"  <?php if($list_limit==10) {echo 'selected';} ?>>10</option>
                        <option value="20"  <?php if($list_limit==20) {echo 'selected';} ?>>20</option>
                        <option value="30"  <?php if($list_limit==30) {echo 'selected';} ?>>30</option>
                        <option value="50"  <?php if($list_limit==50) {echo 'selected';} ?>>50</option>
                        <option value="100" <?php if($list_limit==100){echo 'selected';} ?>>100</option>
                    </select>
                </div>
            </div>
        </div><!-- /.box-header -->
        <div class="box-header" id="search_detail" style="display:<?=$search_display?>;">
            <div class="row" style="margin-top:20px;">
                <div class="col-md-6">
                    <!-- Date range -->
                    <div class="form-group">
                        <label>날짜 범위:</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" 
                                   class="form-control pull-right" 
                                   id="search-range" 
                                   name="range" 
                                   value="<?=$search_query['range']?>">
                        </div><!-- /.input group -->
                    </div><!-- /.form group -->
                </div>
                <div class="col-md-3">
                    <div class="form-group" style="width: 200px;">
                        <label>검색어:</label>
                        <div class="input-group">
                            <input type="text" 
                                   name="key" 
                                   class="form-control pull-right" 
                                   value="<?=$search_query['key']?>" 
                                   placeholder="<?=$search_field_array[$search_query['field']]?>">
                            <div class="input-group-btn">
                                <button class="btn btn-danger">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3"></div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>검색 필드:</label>
                        <div class="input-group">
                            <?php foreach ($search_field_array as $key=>$value){ ?>
                            <label>
                                <input type="radio" 
                                       name="field" 
                                       value="<?=$key?>" 
                                       title="<?=$value?>" 
                                       class="minimal" <?php if ($search_query['field']==$key){echo 'checked';} ?>>
                                <?=$value?>
                            </label>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>회원 상태:</label>
                        <div class="input-group">
                            <?php foreach ($search_state_array as $key=>$item){ ?>
                            <label>
                                <input type="checkbox" 
                                       name="state[]" 
                                       value="<?=$key?>" 
                                       class="minimal" <?php if ($item){echo 'checked';} ?>>
                                <?=$key?>
                            </label>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover table-bordered">
                <tr>
                    <th class="text-center">No</th>
                    <th>회원번호</th>
                    <th>아이디</th>
                    <th>이름(닉네임)</th>
                    <th>가입일</th>
                    <th>가입IP</th>
                    <th class="text-center">인증</th>
                    <th class="text-center">회원상태</th>
                    <th class="text-center">기능</th>
                </tr>
                <?php foreach($userlist_query->result_array() as $row){ ?>
                <tr>
                    <td class="text-center"><?=number_format($numbering--)?></td>
                    <td><?=$row['mb_no']?></td>
                    <td><?=$row['mb_id']?></td>
                    <td><?=$row['mb_name']?>(<?=$row['mb_nick']?>)</td>
                    <td><?=date('m-d-Y H:i:s', strtotime($row['mb_reg_dt']))?></td>
                    <td><?=$row['mb_reg_ip']?></td>

                    <?php if ($row['mb_certi_key']){ ?>
                    <td class="text-center">
                        <span class="label label-success">인증</span>
                    </td>

                    <?php } else { ?>
                    <td class="text-center">
                        <span class="label label-warning">미인증</span>
                    </td>

                    <?php } ?>
                    <?php if (in_array($row['mb_state'], array('차단','휴면','탈퇴'))){ ?>
                    <td class="text-center">
                        <span class="label label-danger"><?=$row['mb_state']?></span>
                    </td>

                    <?php } else { ?>
                    <td class="text-center">
                        <span class="label label-primary"><?=$row['mb_state']?></span>
                    </td>

                    <?php } ?>
                    <td class="text-center">
                        <button class="btn btn-block btn-info btn-xs" 
                                onclick="location.href='/member/userdetail/?mb_no=<?=$row['mb_no']?>';">상세</button>
                    </td>
                </tr>
                <?php } ?>
                <?php if (!$userlist_query->num_rows()){ ?>
                <tr>
                    <td colspan="9" 
                        class="no_table_list">목록이 없습니다.</td>
                </tr>
                <?php } ?>
            </table>
        </div><!-- /.box-body -->
        <!--페이징-->
        <?=$pagination?>
    </div><!-- /.box -->
</div>
<!--content // end-->