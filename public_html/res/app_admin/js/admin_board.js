var ADMIN_BOARD = {
    
    content_state : 'write_page',
    
    ajax_get_url : '/community/ajax_content/',
    ajax_get_pram : 'bc_no',
    
    title_id : $('#board_content_title'),
    writer_id : $('#board_content_writer'),
    date_id : $('#board_content_date'),
    view_id : $('#board_content_view'),
    
    board_list_element : $('.board_list_tr'),
    board_color_class : 'board_color_selected',
    
    user_name : '',
    
    init : function(user_name, CKEDITOR){
        if (typeof CKEDITOR !== "undefined"){
            alert('ckeditor.js가 로드되지 않았습니다.');
            return;
        }
        this.user_name = user_name;
    },
    ajax_get_content : function(bc_no, select_tr){

        var parent_this = this;

        $.ajax({
            type : 'GET',
            url  : parent_this.ajax_get_url+'?'+parent_this.ajax_get_pram+'='+bc_no,
            success: function(args){
                
                if (args){
                    try {
                        var result = JSON.parse(args);
                    } catch(e){
                        alert('JSON parse 에러 : '+e+'\n'+args);
                        //console.log(args);
                        return;
                    }
                } else {
                    alert('데이타 수신 실패.');
                }
                
                $('#bc_no').val(bc_no);
                
                $(parent_this.title_id).html(result[0]['bc_subject']);
                $(parent_this.writer_id).html(result[0]['bc_mb_no']);
                $(parent_this.date_id).html(result[0]['bc_reg_dt']);
                $(parent_this.view_id).html(result[0]['bc_content']);
                
                parent_this.content_state = 'read_page';
                parent_this.set_board_list_color(select_tr);
            }
        });
    },
    ajax_content : function(type){
        
        var parent_this = this;
        
        for (instance in CKEDITOR.instances){
            CKEDITOR.instances[instance].updateElement();
        }
        
        $.ajax({
            type : type,
            url : parent_this.ajax_get_url,
            data : $("form[name=content_form]").serialize(),
            success : function(args){
                if (args){
                    try {
                        var result = JSON.parse(args);
                    } catch(e){
                        alert('JSON parse 에러 : '+e+'\n'+args);
                        //console.log(args);
                        return;
                    }
                } else {
                    alert('데이타 수신 실패.');
                }
                
                alert(result['msg']);
                if (result['result']==='ok' && 
                        (type==='POST' || type==='DELETE')){
                    location.replace('/community/board/notice/');
                    return;
                }
                if (result['result']==='ok' && type==='PUT'){
                    var curr_bc_no = $('#bc_no').val();
                    $('#list_tr_'+curr_bc_no).children().eq(2)
                            .text($('input[name=bc_subject]').val());
                    return;
                }
            }
        });
    },
    set_board_list_color : function(select_tr){
        
        var parent_this = this;
        
        $(parent_this.board_list_element).each(function(){
            $(this).removeClass(parent_this.board_color_class);
        });
        $(select_tr).addClass(this.board_color_class);
    },
    remove_all_board_list_color : function(){
        
        var parent_this = this;
        
        $(parent_this.board_list_element).each(function(){
            $(this).removeClass(parent_this.board_color_class);
        });
    },
    content_action : function(type){
        
        switch(this.content_state){
            
            case 'read_page':
                if (type==='new_btn') { this.write(); return; }
                if (type==='edit_btn'){ this.edit(); return; }
                if (type==='del_btn') { this.del('DELETE'); return; }
                if (type==='save_btn'){ alert('저장 할 수 없는 상태 입니다.'); return; }
            case 'edit_page':
                if (type==='new_btn') { this.write(); return; }
                if (type==='edit_btn'){ alert('이미 수정상태 입니다.'); return; }
                if (type==='del_btn') { this.del('DELETE'); return; }
                if (type==='save_btn'){ this.save('PUT'); return; }
            case 'write_page':
                if (type==='new_btn') { this.write(); return; }
                if (type==='edit_btn'){ alert('작성이 완료되면 저장 버튼을 눌러주십시요.'); return; }
                if (type==='del_btn') { alert('작성중인 새 글은 삭제 할 수 없습니다.'); return; }
                if (type==='save_btn'){ this.save('POST'); return; }
        }
    },
    write : function(){
        
        this.content_state = 'write_page';
        
        this.remove_all_board_list_color();
        $('#bc_no').val();
        
        // 글쓰기 입력 창 셋팅
        $(this.title_id).html(
                '<input type="text" '+
                'class="form-control" '+
                'name="bc_subject" '+
                'placeholder="제목을 입력해 주십시요.">');
        $(this.writer_id).html(this.user_name);
        $(this.date_id).text('');
        $(this.view_id).html(
                '<textarea id="editor1" '+
                'name="bc_content" style="display: none;"></textarea>');
        // 에디터 새로 불러오기
        CKEDITOR.replace('editor1');
    },
    edit : function(){
        
        this.content_state = 'edit_page';
        
        // 글쓰기 입력 창 셋팅
        $(this.title_id).html(
                '<input type="text" '+
                'class="form-control" '+
                'name="bc_subject" '+
                'value="'+$(this.title_id).text()+'"'+
                'placeholder="제목을 입력해 주십시요.">');
        $(this.writer_id).html(this.user_name);
        $(this.view_id).html(
                '<textarea id="editor1" '+
                'name="bc_content" '+
                'style="display: none;">'+$(this.view_id).html()+'</textarea>');
        // 에디터 새로 불러오기
        CKEDITOR.replace('editor1');
    },
    save : function(type){
        this.ajax_content(type);
    },
    del : function(type){
        this.ajax_content(type);
    }
};