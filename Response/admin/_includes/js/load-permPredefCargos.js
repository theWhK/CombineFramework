/**
 * by theWhK - 2018
 * --------
 * jQuery, forte indentação, OOF.
 */

jQuery('[data-load-perm-predef-cargos=button]').click(function (e) { 
    var cargoID = jQuery('[data-load-perm-predef-cargos=select]').val();

    jQuery.ajax({
        type: "method",
        url: URL_BASE + "/admin/permissoes/ajax_resgatarPermissoesCargo/" + cargoID,
        success: function (response) {
            if (response == false) {
                swal({
                    title: "Ops!",
                    text: "Não há pré-definições de permissões para o cargo escolhido.",
                    icon: "warning",
                    button: {
                        text: "OK",
                        className: "btn-warning waves-effect waves-light"
                    }
                });
            } else {
                jQuery('[data-load-perm-predef-cargos=wrapper]')
                    .find('input[type=checkbox]')
                    .prop('checked', false);

                response.forEach(element => {        
                    jQuery('[data-load-perm-predef-cargos=wrapper]')
                        .find('input[type=checkbox][value=' + element + ']')
                        .prop('checked', true);
                });

                swal({
                    title: "Feito!",
                    text: "Pré-definições de permissão carregadas..",
                    icon: "success",
                    button: {
                        text: "OK",
                        className: "btn-success waves-effect waves-light"
                    }
                });
            }
        },
        error : function() {
            swal({
                title: "Ops!",
                    text: "Não foi possível carregar as pré-definições do cargo. Tente novamente mais tarde.",
                        icon: "error",
                            button: {
                    text: "OK",
                        className: "btn-danger waves-effect waves-light"
                }
            });
        }
    });
});