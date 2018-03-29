        <!-- jQuery  -->
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/jquery.min.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/bootstrap.min.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/detect.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/fastclick.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/jquery.slimscroll.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/jquery.blockUI.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/waves.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/wow.min.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/jquery.nicescroll.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/jquery.scrollTo.min.js"></script>

        <!-- KNOB JS -->
        <!--[if IE]>
        <script type="text/javascript" src="<?=URL_BASE?>/response/admin/_includes/plugins/jquery-knob/excanvas.js"></script>
        <![endif]-->
        <script src="<?=URL_BASE?>/response/admin/_includes/plugins/jquery-knob/jquery.knob.js"></script>

        <!--Morris Chart-->
		<script src="<?=URL_BASE?>/response/admin/_includes/plugins/morris/morris.min.js"></script>
		<script src="<?=URL_BASE?>/response/admin/_includes/plugins/raphael/raphael-min.js"></script>

        <!-- Dashboard init -->
        <script src="<?=URL_BASE?>/response/admin/_includes/pages/jquery.dashboard.js"></script>

        <!-- App js -->
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/jquery.core.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/jquery.app.js"></script>

        <!-- Plugins Js -->
        <script src="<?=URL_BASE?>/Response/admin/_includes/plugins/switchery/switchery.min.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
        <script type="text/javascript" src="<?=URL_BASE?>/Response/admin/_includes/plugins/multiselect/js/jquery.multi-select.js"></script>
        <script type="text/javascript" src="<?=URL_BASE?>/Response/admin/_includes/plugins/jquery-quicksearch/jquery.quicksearch.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/plugins/select2/dist/js/select2.min.js" type="text/javascript"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js" type="text/javascript"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" type="text/javascript"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/plugins/moment/moment.js"></script>
     	<script src="<?=URL_BASE?>/Response/admin/_includes/plugins/timepicker/bootstrap-timepicker.min.js"></script>
     	<script src="<?=URL_BASE?>/Response/admin/_includes/plugins/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
     	<script src="<?=URL_BASE?>/Response/admin/_includes/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
     	<script src="<?=URL_BASE?>/Response/admin/_includes/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script>

        <!-- responsive-table-->
        <script src="<?=URL_BASE?>/Response/admin/_includes/plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js" type="text/javascript"></script>

        <!-- Sweet Alert js -->
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/sweetalert.min.js"></script>

        <!-- Inputmask -->
        <script src="<?=URL_BASE?>/Response/admin/_includes/plugins/inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/plugins/inputmask/dist/min/inputmask/bindings/inputmask.binding.min.js"></script>

        <!-- Admin-general custom script -->
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/admin-custom.js"></script>
        
        <!-- Notificações -->
        <script>
            <?php
            // Impressão dos códigos de notificações
            if (isset($_SESSION['data_notificacoes'])) {
                if (isset($_SESSION['data_notificacoes']['toast'])) {
                    foreach ($_SESSION['data_notificacoes']['toast'] as $item) {
                        echo $item;
                    }
                }
                if ($_SESSION['data_notificacoes']['alert']) {
                    foreach ($_SESSION['data_notificacoes']['alert'] as $item) {
                        echo $item;
                    }
                }
                unset($_SESSION['data_notificacoes']);
            }
            ?>
        </script>