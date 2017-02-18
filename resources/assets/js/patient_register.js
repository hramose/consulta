$(function () {

    $("form.register-patient").keypress(function(e) {
        if (e.which == 13) {
            return false;
        }
    });
    
     $("select[name='province']").select2({
      placeholder: "Selecciona Provincia",
      allowClear: true
    });
    $("select[name='gender']").select2({
      placeholder: "Selecciona Genero",
      allowClear: true
    });
    $("[data-mask]").inputmask();

});