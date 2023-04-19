//console.log("loaded");
function loginSubmit(){
    $("#login_errorMessage").html("");

    $.ajax({
        type: "POST",
        url: "/Arcee/ajax/auth/login.php",
        data: Arcee.Forms.login.this.serialize(),
        dataType: "JSON",
        async: true,
        success: function(result){
            if(result["isError"]) $("#login_errorMessage").html(result["errMessage"]);
            else if(result["isLogin"]) location.reload();
        }
    })
    .done(function(){})
    .fail(function(){})
    .always(function(){})
}
