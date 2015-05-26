$(document).ready(function(){

});

function show_search_results_popup(selector){
    $(selector).hide();
    var title = $(selector).attr('title');
    var text = $(selector).html();
    $.gritter.add({
        // (string | mandatory) the heading of the notification
        title: title,
        // (string | mandatory) the text inside the notification
        text: text,
        // (bool | optional) if you want it to fade out on its own or just sit there
        sticky: false
        // (int | optional) the time you want it to be alive for before fading out
        //time: ''
    });
}


function get_search_count(){
    var data = $('#form_search').serializeArray();
    var url = base_url+'main/search_count';
    var callback = function(response){
        $('body').removeClass('loading');
        if(response.show_form_pagination == true){
            $('.form_pagination_wrapper').remove();
            $('body').append(response.html);
            $('#btn_close_form_pagination').click(function(){
                $('.form_pagination_wrapper').remove();
            });

            $('#btn_close_form_pagination').click(function(){
                $('.form_pagination_wrapper').remove();
            });

            $('#btn_confirm_form_pagination').click(function(){
                /*var num_page = $('#ti_page').val();
                $('#ih_page').val(num_page);*/
                $('.form_pagination_wrapper').remove();
                $('#form_search').submit();
            });
        }else{
            $('#ih_page').val(1);
            $('#form_search').submit();
        }
    };

    $.post(url,data,callback,'json')
    $('body').addClass('loading');
}