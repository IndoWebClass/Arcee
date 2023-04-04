//console.log("loaded");
function formLoginSubmit(){
    $("#formLogin_errorMessage").html("");

    $.ajax({
        type: "POST",
        url: "/Arcee/ajax/auth/login.php",
        data: $("#formLogin").serialize(),
        dataType: "JSON",
        async: true,
        success: function(result){
            if(result["isError"]) $("#formLogin_errorMessage").html(result["errMessage"]);
            else if(result["isLogin"]) location.reload();
        }
    })
    .done(function(){})
    .fail(function(){})
    .always(function(){})
}
