


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
            .fail(function(){})
            .always(function(){})
        }
    }


</script>
