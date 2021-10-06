jQuery(document).ready(function(){
    jQuery('#ajaxSubmit').click(function(e){
       e.preventDefault();
       $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
          }
      });
       jQuery.ajax({
          url: '/ajax-test',
          method: 'post',
          data: {
             name: jQuery('#name').val(),
             type: jQuery('#type').val(),
             price: jQuery('#price').val()
          },
          success: function(result){
             console.log(result);
          }});
       });
    });