function erroralert(xhr) {
    if (typeof  xhr.responseJSON.errors === 'object') {
        var error = '';
        $('.form-control').removeClass('is-invalid');
        $('.form-control').parent().find('.is-invalid').remove();
        $.each(xhr.responseJSON.errors, function (key, item) {
            error += item+'\n';
            if ($(document).find('[name="'+key+'"]').length>0){
                var element=$(document).find('[name="'+key+'"]');
                if(element.hasClass('form-control')){
                    element.addClass('is-invalid').after('<span class="invalid-feedback is-invalid">'+item+'</span>');
                }else{
                    // $.toast({
                    //     heading: 'Failed',
                    //     text: item,
                    //     icon: 'error',
                    //     position: 'top-right',
                    // });
                    alert(item);
                }
            } else{
                // $.toast({
                //     heading: 'Failed',
                //     text: item,
                //     icon: 'error',
                //     position: 'top-right',
                // });
                alert(item)
            }
        });
        //swal("Failed", error, "error");
    } else {
        alert("Some error occurred");
        // $.toast({
        //     heading: 'Failed',
        //     text: xhr.responseJSON.message,
        //     icon: 'error',
        //     position: 'top-right',
        // });
    }
}