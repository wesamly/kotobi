$(document).ready(function(){

    if($('#form_book_edit').length>0){
        $('#book_category').change(function(){
            toggleNewCategory();
        });
        toggleNewCategory();
    }
});

function toggleNewCategory(){
    if($('#book_category').val()==''){
        $('#book_new_category').parent().show();
    } else {
        $('#book_new_category').parent().hide();
    }
}