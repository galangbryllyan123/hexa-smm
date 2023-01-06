<?php

/**
 * Code By : Mahiruddin a.k.a Mhrdpy.NET
 * Date Edit : 16 - 12 - 2018
 * Dont Edit Anything If You Don't Know About Script
 * SMM Panel Script - Mhrdpy.NET
 * Demo => https://scriptsmm.web.id/ ( User & Pass : admin )
 * Contact Person :
                => Whatsapp  : 0895 3378 26740
                => Facebook  : Mahir Depay (https://facebook.com/hirpayzzz)
                => Instagram : mahirdpy_   (https://instagram.com/mahirdpy_) 
                => Email     : mahirdpy@gmail.com             
  __  __ _             _               _   _ ______ _______ 
 |  \/  | |           | |             | \ | |  ____|__   __|
 | \  / | |__  _ __ __| |_ __  _   _  |  \| | |__     | |   
 | |\/| | '_ \| '__/ _` | '_ \| | | | | . ` |  __|    | |   
 | |  | | | | | | | (_| | |_) | |_| |_| |\  | |____   | |   
 |_|  |_|_| |_|_|  \__,_| .__/ \__, (_)_| \_|______|  |_|   
                        | |     __/ |                       
                        |_|    |___/                         
**/


$check_website = $db->query("SELECT * FROM website WHERE id ='1'");
$data_website = $check_website->fetch_assoc();
?>
              </div>
        </div>
        <!-- end wrapper -->


        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <?php echo date("Y"); ?> © <?php echo $data_website['title']; ?></a>
                    </div>
                </div>
            </div>
        </footer>
        <!-- End Footer -->

        <script src="<?php echo $site_config['base_url']; ?>assets/js/jquery.min.js"></script>
        <script src="<?php echo $site_config['base_url']; ?>assets/js/popper.min.js"></script>
        <script src="<?php echo $site_config['base_url']; ?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo $site_config['base_url']; ?>assets/js/waves.js"></script>
        <script src="<?php echo $site_config['base_url']; ?>assets/js/jquery.slimscroll.js"></script>
        <script src="<?php echo $site_config['base_url']; ?>assets/pages/jquery.dashboard.js"></script>
        <script src="<?php echo $site_config['base_url']; ?>assets/js/jquery.scrollTo.min.js"></script>

        <script src="<?php echo $site_config['base_url']; ?>plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo $site_config['base_url']; ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>
        <script src="<?php echo $site_config['base_url']; ?>plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="<?php echo $site_config['base_url']; ?>plugins/datatables/responsive.bootstrap4.min.js"></script>
        <script src="<?php echo $site_config['base_url']; ?>plugins/datatables/dataTables.select.min.js"></script>
        
        <script src="<?php echo $site_config['base_url']; ?>plugins/morris/morris.min.js"></script>
        <script src="<?php echo $site_config['base_url']; ?>plugins/raphael/raphael-min.js"></script>
        <script src="<?php echo $site_config['base_url']; ?>assets/pages/morris.init.js"></script>
        
        <script src="<?php echo $site_config['base_url']; ?>assets/js/jquery.core.js"></script>
        <script src="<?php echo $site_config['base_url']; ?>assets/js/jquery.app.js"></script>

        <script type="text/javascript"> 
            var htmlobjek;
            $(document).ready(function(){ 
                $("#level").change(function(){ 
                    var level = $("#level").val(); 
                    $.ajax( { 
                        url    : '<?php echo $site_config['base_url']; ?>inc/note_adduser.php', 
                        data    : 'level='+level, 
                        type    : 'POST', 
                        dataType: 'html', 
                        success    : function(msg){ 
                            $("#note").html(msg); 
                        } 
                    }); 
                }); 
            }); 
            $(document).ready(function(){ 
                $("#level").change(function(){ 
                    var level = $("#level").val(); 
                    $.ajax( { 
                        url    : '<?php echo $site_config['base_url']; ?>inc/note_upuser.php', 
                        data    : 'level='+level, 
                        type    : 'POST', 
                        dataType: 'html', 
                        success    : function(msg){ 
                            $("#note_up").html(msg); 
                        } 
                    }); 
                }); 
            }); 
            $(document).ready(function(){ 
                $("#credit").change(function(){ 
                    var credit = $("#credit").val(); 
                    $.ajax( { 
                        url    : '<?php echo $site_config['base_url']; ?>inc/note_cre.php', 
                        data    : 'credit='+credit, 
                        type    : 'POST', 
                        dataType: 'html', 
                        success    : function(msg){ 
                            $("#note_cre").html(msg); 
                        } 
                    }); 
                }); 
            }); 
        </script>
        <script type="text/javascript">
            $(document).ready(function() {

                // Default Datatable
                $('#datatable').DataTable();

                //Buttons examples
                var table = $('#datatable-buttons').DataTable({
                    lengthChange: false,
                    buttons: ['copy', 'excel', 'pdf']
                });

                // Key Tables

                $('#key-table').DataTable({
                    keys: true
                });

                // Responsive Datatable
                $('#responsive-datatable').DataTable();

                // Multi Selection Datatable
                $('#selection-datatable').DataTable({
                    select: {
                        style: 'multi'
                    }
                });

                table.buttons().container()
                        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
            } );

        </script>

    </body>
</html>
