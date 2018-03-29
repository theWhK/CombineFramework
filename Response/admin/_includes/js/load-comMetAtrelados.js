/**
 * by theWhK - 2018
 * --------
 * jQuery, OOF, forte indentação.
 */

jQuery('[data-selecao-tipo]').change(function() {
    // Remove as opções do select de registros
    jQuery('[data-selecao-idRegistroAtrelado]').find('option, optgroup').remove();
    // Oculta o campo
    jQuery('.campo-selecao-idRegistroAtrelado').fadeOut();
    
    // Se não for uma das opções plausíveis, oculta o select
    if (jQuery('[data-selecao-tipo]').val() == "command" || jQuery('[data-selecao-tipo]').val() == "method") {
        // Resgata os registros
        jQuery.getJSON(
            URL_BASE + "/admin/cadastros_sistema/ajax_resgatarRegistrosAtrelados/" + jQuery('[data-selecao-tipo]').val()
        )
        .done(function (data) {
            console.log(data);
            let htmlContent;

            switch (jQuery('[data-selecao-tipo]').val()) {
                case "command":
                    for (key in data) {
                        htmlContent += '<option value="'+data[key]['id']+'">'+data[key]['rotulo']+'</option>';
                        if (typeof data[key]['listaComandosFilho'] !== 'undefined') {
                            for (subKey in data[key]['listaComandosFilho']) {
                                htmlContent += '<option value="' + data[key]['listaComandosFilho'][subKey]['id'] + '">' + '&rdca; ' + data[key]['listaComandosFilho'][subKey]['rotulo'] + '</option>';
                            }
                        }
                    }
                    jQuery('[data-selecao-idregistroatrelado]').html(htmlContent);
                break;
            
                case "method":
                    for (key in data) {
                        htmlContent += '<optgroup label="' + data[key]['rotulo'] + '">';
                        for (subKey in data[key]['listaMetodos']) {
                            htmlContent += '<option value="' + data[key]['listaMetodos'][subKey]['id'] + '">' + data[key]['listaMetodos'][subKey]['rotulo'] + '</option>';
                        }
                        htmlContent += '</optgroup>';

                        if (typeof data[key]['listaComandosFilho'] !== 'undefined') {
                            for (subKey in data[key]['listaComandosFilho']) {
                                htmlContent += '<optgroup label="' + '&rdca; ' + data[key]['listaComandosFilho'][subKey]['rotulo'] + '">';
                                for (subsubKey in data[key]['listaComandosFilho'][subKey]['listaMetodos']) {
                                    htmlContent += '<option value="' + data[key]['listaComandosFilho'][subKey]['listaMetodos'][subsubKey]['id'] + '">' + '&rdca; ' + data[key]['listaComandosFilho'][subKey]['listaMetodos'][subsubKey]['rotulo'] + '</option>';
                                }
                                htmlContent += '</optgroup>';
                            }
                        }
                    }
                    jQuery('[data-selecao-idregistroatrelado]').html(htmlContent);
                break;
            }
            
            jQuery('.campo-selecao-idRegistroAtrelado').fadeIn();
        });
    }
});