var general = {};

const _watermak = {

    _masking : () => {

    },

    _pagesIndex : {

        submit : () => {

            $("#"+general.form.init).on('submit', function(event){

                event.preventDefault();
                event.stopPropagation();

                var formData = new FormData(this);

                $.ajax({
                    type: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: general.url,
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend: function(){
                        $('.submitBtn').attr("disabled","disabled");
                        $('#'+general.form.init).css("opacity",".5");
                    },
                    success: function(response){

                        $('.statusMsg').html('');

                        if(response.respon == 'success'){
                            $('.statusMsg').html('<p class="alert alert-success text-center">'+response.message+'</p>'); 
                        }
                        else{
                            $('.statusMsg').html('<p class="alert alert-danger text-center">'+response.message+'</p>');
                        }

                        $('#'+general.form.init).css("opacity","");
                        $(".submitBtn").removeAttr("disabled"); 
                        $('.name').val(null);               
                        $('.image').val(null);  

                        _watermak._masking();             
                    }
                });
            });

        }
    },

    init: () => {
        _watermak._masking();
        _watermak._pagesIndex.submit();
    }

}

$(function () {
    
    general = {
        module: 'watermak',
        url: route('home_action'),
        form: {
            init: 'FormAction'
        },
    }

    _watermak.init();

});