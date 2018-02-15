$(function () {


     $(".dropdown-toggle").dropdown();
     var infoBox = $("#infoBox");
     var modalForm = $('#modalInvoice');

    function money(n, currency) {

        return n.toLocaleString();//toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    }
    $('.date').datetimepicker({
      format:'YYYY-MM-DD',
      locale: 'es',
      
   });
   
  $('#datepicker1').datetimepicker({
    format:'YYYY-MM-DD',
    locale: 'es',
    
  });
  
  $('#datepicker2').datetimepicker({
    format:'YYYY-MM-DD',
    locale: 'es',
    
  });
   
   $('input[name="pay_with"]').keypress(function (e) {
    //if the letter is not digit then display error and don't type anything
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
       //display error message
      // $("#errmsg").html("Digits Only").show().fadeOut("slow");
              return false;
   }
  });
   $('input[name="pay_with"]').keyup(function( event ) {
    var payWith = parseFloat(($(this).val()) ? $(this).val() : 0);
    var total =  parseFloat($('input[name="total"]').val());
    var change = 0;
    change = ((payWith - total) < 0) ? 0 : payWith - total;
    $('input[name="change"]').val(change);

    console.log(change)
  })

    $('.btn-facturar').on('click',function (e) {
        e.preventDefault();
    
         var invoice_id = $(this).attr('data-invoice');
         var patient_id = $(this).attr('data-patient');

        $('.loader').show();

        $.ajax({
            type: 'PUT',
            url: '/clinic/invoices/'+invoice_id,
            data: { client_name: $('input[name="client_name"]').val(), client_email: $('input[name="client_email"]').val(), medio_pago: $('select[name="medio_pago"]').val(), pay_with: $('input[name="pay_with"]').val(), change: $('input[name="change"]').val()},
            success: function (resp) {
              $('.loader').hide();

              infoBox.addClass('alert-success').html('Factura procesada!!!').show();
              setTimeout(function()
              { 
                infoBox.removeClass('alert-success').hide();
              },3000);
              
              
             
              window.location.href = "/clinic/patients/"+patient_id+'/invoices'
                
            },
            error: function () {
              console.log('error get details');
              $('.loader').hide();

            }
        });
     

    });

    $('.btn-print').on('click',function (e) {
      e.preventDefault();
    
      var invoice_id = $(this).attr('data-invoice');
     
     var show =  $(this).attr('data-show');
       if(show != '1'){

          $.ajax({
            type: 'PUT',
            url: '/clinic/invoices/'+invoice_id,
            data: { client_name: $('input[name="client_name"]').val(), client_email: $('input[name="client_email"]').val(), medio_pago: $('select[name="medio_pago"]').val(), pay_with: $('input[name="pay_with"]').val(), change: $('input[name="change"]').val()},
            success: function (resp) {
            

              infoBox.addClass('alert-success').html('Factura procesada!!!').show();
              setTimeout(function()
              { 
                infoBox.removeClass('alert-success').hide();
              },3000);
              
          
              if($("#rd_ticket").is(":checked"))
                window.location.href = "/clinic/invoices/"+invoice_id+'/ticket';
              else
                window.location.href = "/clinic/invoices/"+invoice_id+'/print';
              
              
                
            },
            error: function () {
              console.log('error get details');

            }
        });
    }else{
      if($("#rd_ticket").is(":checked"))
        window.location.href = "/clinic/invoices/"+invoice_id+'/ticket';
      else
        window.location.href = "/clinic/invoices/"+invoice_id+'/print';
    }
      /* if($("#rd_ticket").is(":checked"))
        window.location.href = "/clinic/invoices/"+invoice_id+'/ticket';
      else
        window.location.href = "/clinic/invoices/"+invoice_id+'/print';*/

  });

    modalForm.on('hidden.bs.modal', function (e) {
      var modal = $(this);
      var table_details = modal.find('#table-details')
      table_details.find('tbody').html('');
      modal.find('#modal-label-medic').text('')
    });
     modalForm.on('shown.bs.modal', function (event) {
     
      var button = $(event.relatedTarget)
      var modal = $(this);
      var invoice_id = button.attr('data-id')
      var patient_id = button.attr('data-patient')  
      var table_details = modal.find('#table-details')
      var detailsHtml = '';
      modal.find('.btn-facturar').attr('data-invoice', invoice_id);
      modal.find('.btn-facturar').attr('data-patient', patient_id);
      modal.find('.btn-print').attr('data-invoice', invoice_id);
      modal.find('.btn-print').attr('data-medic', patient_id);
  
     

       $('.loader').show();
     
       $.ajax({
            type: 'GET',
            url: '/clinic/invoices/'+invoice_id+'/details',
            data: {},
            success: function (resp) {
              $('.loader').hide();
               modal.find('#modal-label-medic').text('')
               table_details.find('tbody').html('');
              
              var consecutivo = resp.consecutivo;
               modal.find('#modal-label-medic').text(resp.medic.name);
               modal.find('#modal-label-patient').text(resp.appointment.patient.fullname);
               modal.find('#modal-label-patient').text(resp.appointment.patient.fullname);
               $('input[name="client_name"]').val(resp.appointment.patient.fullname);
               $('input[name="pay_with"]').val((resp.pay_with) ? resp.pay_with : '');
               $('input[name="change"]').val(resp.change);
            

               $.each(resp.lines, function( index, item ) {
                   
                   detailsHtml +='<tr><td>'+ item.quantity +'</td><td>'+ item.service +'</td><td>'+ money(item.amount) +'</td><td>'+ money(item.total_line) +'</td></tr>'

                });

               table_details.find('tbody').html(detailsHtml);
               
              $("#modalInvoiceLabel").html('Factura #' + consecutivo + '  <span class="label label-warning pull-right">'+resp.created_at+'</span>');
               $("#modal-label-total").html('Total: ₡<span>'+ money(resp.total)+'</span>');
               $('input[name="total"]').val(resp.total);

               if(resp.status){
                modal.find('.btn-print').focus();
                modal.find('.btn-facturar').hide();
                $('.pay_with_label').html( money(resp.pay_with));
                $('.change_label').html( money(resp.change));
                $('.pay_with-field').remove();
                $('.change-field').remove();
                modal.find('.btn-print').attr('data-show','1');
               }else{
                $('input[name="pay_with"]').focus();
               }
            },
            error: function () {
              console.log('error get details');
              $('.loader').hide();
            }
        });
     
  
     
      
   
    });

});
