$(document).ready(function () {
    
$('#burger').on('click', function(){
    
    $('#side-nav').toggleClass('d-none');
});


    
});


function cart(id, logged){

    if(logged == 1){
            window.location.href = './db/addProduct.php?id=' + id;
    }
    else{
        $('#login-alert').modal();
    }
}

