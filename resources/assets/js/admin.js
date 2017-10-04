$(function () {

    var isMobile = {
      Android: function() {
          return navigator.userAgent.match(/Android/i);
      },
      BlackBerry: function() {
          return navigator.userAgent.match(/BlackBerry/i);
      },
      iOS: function() {
          return navigator.userAgent.match(/iPhone|iPad|iPod/i);
      },
      Opera: function() {
          return navigator.userAgent.match(/Opera Mini/i);
      },
      Windows: function() {
          return navigator.userAgent.match(/IEMobile/i);
      },
      any: function() {
          return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
      }
  };

  /* if( isMobile.any() ) {
      $('.box-create-appointment').hide();
    }else{
      //$('.box-create-appointment').show();
    }*/

    //$(".dropdown-toggle").dropdown();

    $("form[data-confirm]").submit(function() {
        if ( ! confirm($(this).attr("data-confirm"))) {
            return false;
        }
    });
    
     function submitForm(){
        $('.filters').find('form').submit();
    }


    $('select[name=role]').change(submitForm);
    $('select[name=status]').change(submitForm);
    
    
   

  });
