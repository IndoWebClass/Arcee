console.log("default.js loaded");

function logout(){
    $.ajax({
        type: "POST",
        url: "/arcee/ajax/auth/logout.php",
        data: $("#formLogin").serialize(),
        dataType: "JSON",
        async: true,
        success: function(result){
            location.reload();
        }
    })
    .done(function(){})
    .fail(function(){})
    .always(function(){})
}
