$(function () {


     $(".dropdown-toggle").dropdown();
     var infoBox = $("#infoBox");
     var modalForm = $('#modalInvoice');

    $('.date').datetimepicker({
      format:'YYYY-MM-DD',
      locale: 'es',
      
   });

  $('#clinic').on('change', function (e) {


    $(this).parents('form').submit();

  });

  $('#medic').on('change', function (e) {


    $(this).parents('form').submit();

  });

  //  $('input[name="pay_with"]').keypress(function (e) {
  //   //if the letter is not digit then display error and don't type anything
  //   if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
  //      //display error message
  //     // $("#errmsg").html("Digits Only").show().fadeOut("slow");
  //             return false;
  //  }
  // });

     modalForm.on('shown.bs.modal', function (event) {
     
      var button = $(event.relatedTarget)
      var invoice_id = button.attr('data-id')
      
      window.bus.$emit('showInvoiceModal', invoice_id);

   
    });

});
