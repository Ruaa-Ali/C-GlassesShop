
$(document).ready(function () {

    $('#burger').on('click', function(){
        $('#sidebar').toggleClass('collapsed');
    });

    if(sessionVars == 1){        
        $('#alert').modal();
    }

    
});

function delAdmin(id) {
    // $('#admin-alert').modal();
    var ask = window.confirm('This action can\'t be undone, are you sure?');
    if (ask) {
        window.location.href = './db/deleteAdmin.php?id='+id;
    }
}


function delCustomer(id) {
    // $('#admin-alert').modal();
    var ask = window.confirm('This action can\'t be undone, are you sure?');
    if (ask) {
        window.location.href = './db/deleteProduct.php?id='+id;
    }
}

