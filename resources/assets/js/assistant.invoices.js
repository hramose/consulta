$(function () {


     $(".dropdown-toggle").dropdown();
     var infoBox = $("#infoBox");
     var modalForm = $('#modalInvoice');

    function money(n, currency) {

        return n.toLocaleString();//toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    }

    $('.btn-facturar').on('click',function (e) {
        e.preventDefault();
      
         var invoice_id = $(this).attr('data-invoice');
         var medic_id = $(this).attr('data-medic');
        $.ajax({
            type: 'PUT',
            url: '/assistant/invoices/'+invoice_id,
            data: {client_name: $('input[name="client_name"]').val()},
            success: function (resp) {
               
              infoBox.addClass('alert-success').html('Factura procesada!!!').show();
              setTimeout(function()
              { 
                infoBox.removeClass('alert-success').hide();
              },3000);
              
              if(medic_id)
                 window.location.href = "/assistant/medics/"+ medic_id +"/invoices";
             else
                window.location.href = "/assistant/invoices";
              
                
            },
            error: function () {
              console.log('error get details');

            }
        });
     

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
      var medic_id = button.attr('data-medic')  
      var table_details = modal.find('#table-details')
      var detailsHtml = '';
      modal.find('.btn-facturar').attr('data-invoice', invoice_id);
      modal.find('.btn-facturar').attr('data-medic', medic_id);


     
       $.ajax({
            type: 'GET',
            url: '/assistant/invoices/'+invoice_id+'/details',
            data: {},
            success: function (resp) {
               modal.find('#modal-label-medic').text('')
               table_details.find('tbody').html('');
              

               modal.find('#modal-label-medic').text(resp.medic.name);
               modal.find('#modal-label-patient').text(resp.appointment.patient.fullname);
               modal.find('#modal-label-patient').text(resp.appointment.patient.fullname);
               $('input[name="client_name"]').val(resp.appointment.patient.fullname);

               $.each(resp.lines, function( index, item ) {
                   
                   detailsHtml +='<tr><td>'+ item.quantity +'</td><td>'+ item.service +'</td><td>'+ money(item.amount) +'</td><td>'+ money(item.amount * item.quantity)  +'</td><td>'+ money(item.total_line) +'</td></tr>'

                });

               table_details.find('tbody').html(detailsHtml);
               
               $("#modalInvoiceLabel").html('Factura #'+ invoice_id + '  <span class="label label-warning pull-right">'+resp.created_at+'</span>');
               $("#modal-label-total").html('Total: ₡<span>'+ money(resp.total)+'</span>');
                
            },
            error: function () {
              console.log('error get details');

            }
        });
     
  
     
      
   
    });

});
