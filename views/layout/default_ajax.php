


<script>
    class Ajax{
        constructor(params){
            this.type = "POST";
            this.dataType = "JSON";
            this.async = true;

            this.url = params.url;
            this.formId = params.formId;
            this.data = Arcee.Forms[this.formId].this.serialize();
        }

        render(){
            Arcee.BSModals.ajax.open({
                body : "Loading...<br/>Please wait"
                ,footer : "Please dont close the window"
            });
            $.ajax({
                type: this.type,
                url: this.url,
                data: this.data,
                dataType: this.dataType,
                async: true,
                success: function(result){
                    console.log(result);
                }
            })
            .done(function(){})
            .fail(function(){
                Arcee.BSModals.ajax.open({
                    body : "Ajax fail. Plese try again, or contanct administrator"
                });
            })
            .always(function(){})
        }
    }
</script>
<?php

$ajaxModal = new \app\core\BSModal([
    "id" => "ajax"
]);
$ajaxModal->setTitle("Ajax notification");
$ajaxModal->render();
