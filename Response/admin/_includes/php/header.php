<!-- Navigation Bar-->
        <header id="topnav">
            <div class="topbar-main">
                <div class="container">

                    <!-- LOGO -->
                    <div class="topbar-left">
                        <a href="<?=URL_BASE?>/<?=$this->core->action_urlName?>" class="logo" style="height: 45px;"><img src="<?=URL_BASE?>/Response/admin/_includes/images/brand/blacksuit-white.png" alt="Blacksuit" width="180"></a>
                    </div>
                    <!-- End Logo container-->


                    <div class="menu-extras">

                        <ul class="nav navbar-nav navbar-right pull-right">
                            <?php /*
                            <li>
                                <!-- Notification -->
                                <div class="notification-box">
                                    <ul class="list-inline m-b-0">
                                        <li>
                                            <a href="javascript:void(0);" class="right-bar-toggle">
                                                <i class="zmdi zmdi-notifications-none"></i>
                                            </a>
                                            <div class="noti-dot">
                                                <span class="dot"></span>
                                                <span class="pulse"></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <!-- End Notification bar -->
                            </li>
                            */ ?>

                            <li class="dropdown user-box">
                                <a href="" class="dropdown-toggle waves-effect waves-light profile " data-toggle="dropdown" aria-expanded="true">
                                    <img src="<?=URL_BASE?>/response/admin/_includes/images/users/avatar-1.jpg" alt="user-img" class="img-circle user-img">
                                    <div class="user-status online"><i class="zmdi zmdi-dot-circle"></i></div>
                                </a>

                                <ul class="dropdown-menu">
                                    <li class="dropdown-header">Olá, <?=$usuarioLogado['nome']?></li>
                                    <li class="divider" role="separator"></li>
                                    <li><a href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/perfil"><i class="ti-user m-r-5"></i> Editar meu perfil</a></li>
                                    <?php /*<li><a href="javascript:void(0)"><i class="ti-settings m-r-5"></i> Configurações</a></li>
                                    <li><a href="javascript:void(0)"><i class="ti-lock m-r-5"></i> Travar</a></li> */?>
                                    <li><a href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/login/logout"><i class="ti-power-off m-r-5"></i> Sair</a></li>
                                </ul>
                            </li>
                        </ul>
                        <div class="menu-item">
                            <!-- Mobile menu toggle-->
                            <a class="navbar-toggle">
                                <div class="lines">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </a>
                            <!-- End mobile menu toggle-->
                        </div>
                    </div>

                </div>
            </div>

            <div class="navbar-custom">
                <div class="container">
                    <div id="navigation">
                        <!-- Navigation Menu-->
                        <ul class="navigation-menu">
                            <?php
                            if (is_array($menuHeader)) {
                                foreach ($menuHeader as $item) {
                                ?>
                                <li <?php if (is_array($item['listaSubitens'])) {?>class="has-submenu"<?php } ?>>
                                <a <?php if ($item['urlAmigavel']) { ?>href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$item['urlAmigavel']?>"<?php } ?>>
                                    <?php if (!empty($item['classeIcone'])) {?><i class="<?=$item['classeIcone']?>"></i><?php } ?> <span> <?=$item['rotulo']?> </span> 
                                <?php if ($item['urlAmigavel']) { ?></a><?php } ?>
                                    <?php
                                    if (is_array($item['listaSubitens'])) {
                                    ?>
                                        <ul class="submenu">
                                        <?php
                                        foreach($item['listaSubitens'] as $subitem) {
                                        ?>
                                        <li title="<?=$subitem['descricao']?>"><a href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$subitem['urlAmigavel']?>"><?=$subitem['rotulo']?></a></li>
                                        <?php
                                        }
                                        ?>
                                        </ul>
                                    <?php
                                    }
                                    ?>
                                </li>
                                <?php
                                }
                            }
                            ?>

                            <?php
                            /*<li class="has-submenu">
                                <a href="#"><i class="zmdi zmdi-invert-colors"></i> <span> User Interface </span> </a>
                                <ul class="submenu megamenu">
                                    <li>
                                        <ul>
                                            <li><a href="ui-buttons.html">Buttons</a></li>
                                            <li><a href="ui-cards.html">Cards</a></li>
                                            <li><a href="ui-draggable-cards.html">Draggable Cards</a></li>
                                            <li><a href="ui-typography.html">Typography </a></li>
                                            <li><a href="ui-checkbox-radio.html">Checkboxs-Radios</a></li>
                                            <li><a href="ui-material-icons.html">Material Design Icons</a></li>
                                            <li><a href="ui-font-awesome-icons.html">Font Awesome</a></li>
                                            <li><a href="ui-themify-icons.html">Themify Icons</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <ul>
                                            <li><a href="ui-dripicons.html">Dripicons</a></li>
                                            <li><a href="ui-modals.html">Modals</a></li>
                                            <li><a href="ui-notification.html">Notification</a></li>
                                            <li><a href="ui-range-slider.html">Range Slider</a></li>
                                            <li><a href="ui-components.html">Components</a>
                                            <li><a href="ui-sweetalert.html">Sweet Alert</a>
                                            <li><a href="ui-treeview.html">Tree view</a>
                                            <li><a href="ui-widgets.html">Widgets</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>

                            <li class="has-submenu">
                                <a href="#"><i class="zmdi zmdi-collection-text"></i><span> Forms </span> </a>
                                <ul class="submenu">
                                    <li><a href="form-elements.html">General Elements</a></li>
                                    <li><a href="form-advanced.html">Advanced Form</a></li>
                                    <li><a href="form-validation.html">Form Validation</a></li>
                                    <li><a href="form-wizard.html">Form Wizard</a></li>
                                    <li><a href="form-fileupload.html">Form Uploads</a></li>
                                    <li><a href="form-wysiwig.html">Wysiwig Editors</a></li>
                                    <li><a href="form-xeditable.html">X-editable</a></li>
                                </ul>
                            </li>

                            <li class="has-submenu">
                                <a href="#"><i class="zmdi zmdi-view-list"></i> <span> Tables </span> </a>
                                <ul class="submenu">
                                    <li><a href="tables-basic.html">Basic Tables</a></li>
                                    <li><a href="tables-datatable.html">Data Table</a></li>
                                    <li><a href="tables-responsive.html">Responsive Table</a></li>
                                    <li><a href="tables-editable.html">Editable Table</a></li>
                                    <li><a href="tables-tablesaw.html">Tablesaw Table</a></li>
                                </ul>
                            </li>

                            <li class="has-submenu">
                                <a href="#"><i class="zmdi zmdi-chart"></i><span> Charts </span> </a>
                                <ul class="submenu">
                                    <li><a href="chart-flot.html">Flot Chart</a></li>
                                    <li><a href="chart-morris.html">Morris Chart</a></li>
                                    <li><a href="chart-chartist.html">Chartist Charts</a></li>
                                    <li><a href="chart-chartjs.html">Chartjs Chart</a></li>
                                    <li><a href="chart-other.html">Other Chart</a></li>
                                </ul>
                            </li>

                            <li class="has-submenu">
                                <a href="#"><i class="zmdi zmdi-collection-item"></i><span> Pages </span> </a>
                                <ul class="submenu">
                                    <li><a href="page-starter.html">Starter Page</a></li>
                                    <li><a href="page-login.html">Login</a></li>
                                    <li><a href="page-register.html">Register</a></li>
                                    <li><a href="page-recoverpw.html">Recover Password</a></li>
                                    <li><a href="page-lock-screen.html">Lock Screen</a></li>
                                    <li><a href="page-confirm-mail.html">Confirm Mail</a></li>
                                    <li><a href="page-404.html">Error 404</a></li>
                                    <li><a href="page-500.html">Error 500</a></li>
                                </ul>
                            </li>

                            <li class="has-submenu">
                                <a href="#"><i class="zmdi zmdi-layers"></i><span>Extra Pages </span> </a>
                                <ul class="submenu megamenu">
                                    <li>
                                        <ul>
                                            <li><a href="extras-projects.html">Projects</a></li>
                                            <li><a href="extras-tour.html">Tour</a></li>
                                            <li><a href="extras-taskboard.html">Taskboard</a></li>
                                            <li><a href="extras-inbox.html">Mail</a></li>
                                            <li><a href="extras-taskdetail.html">Task Detail</a></li>
                                            <li><a href="extras-maps.html">Maps</a></li>
                                            <li><a href="extras-calendar.html" >Calendar</a></li>
                                            <li><a href="extras-contact.html">Contact list</a></li>
                                            <li><a href="extras-pricing.html">Pricing</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <ul>
                                            <li><a href="extras-timeline.html">Timeline</a></li>
                                            <li><a href="extras-invoice.html">Invoice</a></li>
                                            <li><a href="extras-profile.html">Profile</a></li>
                                            <li><a href="extras-faq.html">FAQ</a></li>
                                            <li><a href="extras-gallery.html">Gallery</a></li>
                                            <li><a href="extras-email-template.html">Email template</a></li>
                                            <li><a href="extras-maintenance.html">Maintenance</a></li>
                                            <li><a href="extras-comingsoon.html">Coming soon</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            */?>
                        </ul>
                        <!-- End navigation menu  -->
                    </div>
                </div>
            </div>
        </header>
        <!-- End Navigation Bar-->