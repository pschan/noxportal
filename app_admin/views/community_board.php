<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!--content // start-->
<div class="col-md-6">
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
                <div class="col-md-12">
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
            </div>
        </div>
        </form>
        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered">
                <tr>
                    <th class="text-center">No</th>
                    <th>작성자</th>
                    <th>제목</th>
                    <th>작성일</th>
                </tr>
                <?php foreach($board_list_query->result_array() as $row){ ?>
                <tr class="board_list_tr cursor_pointer" 
                    id="list_tr_<?=$row['bc_no']?>"
                    onclick="ADMIN_BOARD.ajax_get_content(<?=$row['bc_no']?>, this)">
                    <td class="text-center"><?=number_format($numbering--)?></td>
                    <td><?=$row['bc_mb_no']?></td>
                    <td><?=$row['bc_subject']?></td>
                    <td><?=$row['bc_reg_dt']?></td>
                </tr>
                <?php } ?>
                <?php if (!$board_list_query->num_rows()){ ?>
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
<div class="col-md-6">
    <div class="box" style="min-height:639px;">
        <div class="box-header">
            <div class="btn-group pull-right" style="margin:3px;">
                <button type="button" 
                        class="btn btn-info" 
                        type="button" 
                        id="content_new_btn" 
                        onclick="ADMIN_BOARD.content_action('new_btn');">새글</button>
                <button type="button" 
                        class="btn btn-warning" 
                        type="button" 
                        id="content_edit_btn"
                        onclick="ADMIN_BOARD.content_action('edit_btn');">수정</button>
                <button type="button" 
                        class="btn btn-danger" 
                        type="button" 
                        id="content_del_btn"
                        onclick="ADMIN_BOARD.content_action('del_btn');">삭제</button>
                <button type="button" 
                        class="btn btn-success" 
                        type="button" 
                        id="content_save_btn"
                        onclick="ADMIN_BOARD.content_action('save_btn');">저장</button>
            </div>
        </div>
        <form name="content_form">
        <input type="hidden" name="bc_no" id="bc_no">
        <div class="box-header">
            <h3 class="box-title" id="board_content_title" style="width:100%;">
                <input type="text" 
                       class="form-control" 
                       name="bc_subject"
                       placeholder="제목을 입력해 주십시요.">
            </h3>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered">
                <tr>
                    <th>작성자</th>
                    <td id="board_content_writer"><?=$view_name?></td>
                </tr>
                <tr>
                    <th>작성일</th>
                    <td id="board_content_date"></td>
                </tr>
                <tr>
                    <td colspan="2" id="board_content_view">
                        <textarea id="editor1" 
                                  name="bc_content" 
                                  style="display: none;"></textarea>
                    </td>
                </tr>
            </table>
        </div>
        </form>
    </div>
</div>
<!--content // end-->