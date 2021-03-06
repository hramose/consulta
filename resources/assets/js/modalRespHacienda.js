$(function () {

    $(".dropdown-toggle").dropdown();
    $('#modalRespHacienda').on('shown.bs.modal', function (event) {

        var button = $(event.relatedTarget) // Button that triggered the modal
        var facturaId = button.attr('data-invoice') // Extract info from data-* attributes
        
        $('.loader').show();
        $("#resp-clave").text('')
        $("#resp-emisor").text('')
        $("#resp-receptor").text('')
        $("#resp-mensaje").text('')
        $("#resp-detalle").text('')
        $.ajax({
            type: 'GET',
            url: '/facturas/' + facturaId + '/recepcion',
            data: { _token: $('meta[name="csrf-token"]').content },
            success: function (resp) {
                $('.loader').hide();
                var respHacienda = JSON.parse(resp.resp_hacienda);
                $("#resp-clave").text(respHacienda.Clave)
                $("#resp-emisor").text(respHacienda.NombreEmisor)
                $("#resp-receptor").text(respHacienda.NombreReceptor)
                $("#resp-mensaje").text(respHacienda.Mensaje)
                $("#resp-detalle").text(respHacienda.DetalleMensaje)

            },
            error: function () {

                $('.loader').hide();
                $("#resp-clave").text('Ha ocurrido un error en la conexion con Hacienda')
            }

        });

    });
     

});