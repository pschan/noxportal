<script>
    // 상세검색 창 여닫기 버튼
    function search_detail(){
        $('#search_detail').toggle();
        if ($('#search_detail').css('display')==='block'){
            $.cookie('search_detail', 'block');
            $('#search_button').removeClass('btn-primary');
            $('#search_icon').removeClass('fa-plus');
            $('#search_button').addClass('btn-danger');
            $('#search_icon').addClass('fa-minus');
        } else {
            $.cookie('search_detail', 'none');
            $('#search_button').addClass('btn-primary');
            $('#search_icon').addClass('fa-plus');
            $('#search_button').removeClass('btn-danger');
            $('#search_icon').removeClass('fa-minus');
        }
    }
    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });
    //Date range picker
    $('#search-range').daterangepicker({
        ranges: {
            '오늘': [moment(), moment()],
            '어제': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            '최근 7일': [moment().subtract(6, 'days'), moment()],
            '최근 30일': [moment().subtract(29, 'days'), moment()],
            '이번 달': [moment().startOf('month'), moment().endOf('month')],
            '지난 달': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });
    // 검색 필드 변경 시 검색어 placeholder 변경
    $('input[name=field]').on('ifChecked', function(){
        $('input[name=key]').attr('placeholder', $(this).attr('title'));
    });
</script>