/**
 * by theWhK - 2018
 * --------
 * jQuery, OOF.
 * --------
 * Coletânea de scripts para a Ação Admin do projeto BSComm.
 */

    /**
     * Select2
     */
    jQuery(document).ready(function () {
        $(".select2").select2();

        $(".select2-limiting").select2({
            maximumSelectionLength: 2
        });
    });

    /**
     * Alertas de âncoras para propósitos variados.
     * 
     * @abstract utilize a attr 'data-anchor-alert'.
     */

        // ="confirmation"
        jQuery('[data-anchor-alert=confirmation]').click((event) => {
            event.preventDefault();

            swal({
                title: 'Você tem certeza?',
                text: 'Não será possível reverter esta ação.',
                icon: 'warning',
                buttons: {
                    yep: {
                        text: "Sim"
                    },
                    nope: {
                        text: "Não"
                    }
                }
            })
                .then((value) => {
                    switch (value) {
                        case 'yep':
                            window.location = event.currentTarget.href;
                            break;
                        case 'nope':
                            return false;
                            break;
                    }
                });
        });