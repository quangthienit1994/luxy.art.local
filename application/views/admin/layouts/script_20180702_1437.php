<!-- Loader -->
<div class="loader hidden"><div class="loader-item"><div class="circle"></div><div class="circle"></div><div class="circle"></div><div class="circle"></div><div class="circle"></div></div></div>

<!-- BEGIN: PAGE SCRIPTS -->
<!-- jQuery -->
<!--<script type="text/javascript" src="publics/admin/vendor/jquery/jquery-1.11.1.min.js"></script>-->
<script type="text/javascript" src="publics/admin/vendor/jquery/jquery_ui/jquery-ui.min.js"></script>

<!-- Angular JS -->
<script type="text/javascript" src='publics/admin/angularjs/angular.min.js'></script>

<!-- Bootstrap -->
<script type="text/javascript" src="publics/admin/js/bootstrap/bootstrap.min.js"></script>

<!-- Sparklines CDN -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.min.js"></script>

<!-- Chart Plugins -->
<script type="text/javascript" src="publics/admin/vendor/plugins/highcharts/highcharts.js"></script>
<script type="text/javascript" src="publics/admin/vendor/plugins/circles/circles.js"></script>
<script type="text/javascript" src="publics/admin/vendor/plugins/raphael/raphael.js"></script>

<!-- Holder js  -->
<script type="text/javascript" src="publics/admin/js/bootstrap/holder.min.js"></script>

<!-- Theme Javascript -->
<script type="text/javascript" src="publics/admin/js/utility/utility.js"></script>
<script type="text/javascript" src="publics/admin/js/main.js"></script>
<script type="text/javascript" src="publics/admin/js/demo.js"></script>

<!-- Select -->
<script type="text/javascript" src="publics/admin/vendor/plugins/select/select2.min.js"></script>

<!-- Admin Panels  -->
<script type="text/javascript" src="publics/admin/admin-tools/admin-plugins/admin-panels/json2.js"></script>
<script type="text/javascript" src="publics/admin/admin-tools/admin-plugins/admin-panels/jquery.ui.touch-punch.min.js"></script>
<script type="text/javascript" src="publics/admin/admin-tools/admin-plugins/admin-panels/adminpanels.js"></script>


<!-- Sweetalert -->
<script type="text/javascript" src="publics/admin/sweetalert/dist/sweetalert.min.js"></script>

<!-- Toastr -->
<script type="text/javascript" src="publics/admin/toastr/toastr.min.js"></script>

<!-- Magnific Popup -->
<script type="text/javascript" src="publics/admin/vendor/plugins/magnific/jquery.magnific-popup.js"></script>

<!-- Function custom -->

<!-- Page Javascript -->
<script type="text/javascript" src="publics/admin/js/pages/widgets.js"></script>

<!-- <script type="text/javascript" src="publics/admin/js/pages/login/EasePack.min.js"></script>
<script type="text/javascript" src="publics/admin/js/pages/login/rAF.js"></script>
<script type="text/javascript" src="publics/admin/js/pages/login/TweenLite.min.js"></script>
<script type="text/javascript" src="publics/admin/js/pages/login/login.js"></script> -->

<script type="text/javascript" src="publics/admin/js/functions.js"></script>

<!-- <script src="https://cdn.ckeditor.com/4.7.3/standard/ckeditor.js"></script> -->

<script type="text/javascript" src="<?php echo base_url() ?>publics/ckeditor/ckeditor.js"></script>

<!-- Charts JS -->
<!-- <script type="text/javascript" src="publics/admin/vendor/plugins/highcharts/highcharts.js"></script> -->
<script type="text/javascript" src="publics/admin/vendor/plugins/circles/circles.js"></script>
<script type="text/javascript" src="publics/admin/vendor/plugins/raphael/raphael.js"></script>


<script type="text/javascript">
    $(document).ready(function() {
        "use strict";
        // Init Theme Core      
        Core.init({
            sbm: "sb-l-c",
        });
        // Init Demo JS
        Demo.init();

        //demoHighCharts.init();
    });

    // Angular js setting
    var app = angular.module('app', []);
</script>

<script type="text/javascript">
    function uncheck_all(){
        $('.item_uncheck_all').prop('checked', false);
    }

    function check_all(){
        $('.item_check_all').prop('checked', false);
    }
</script>

<script type="text/javascript">
    // Define chart color patterns
    var highColors = [bgWarning, bgPrimary, bgInfo, bgAlert,
        bgDanger, bgSuccess, bgSystem, bgDark
    ];

    // Color Library we used to grab a random color
    var sparkColors = {
        "primary": [bgPrimary, bgPrimaryLr, bgPrimaryDr],
        "info": [bgInfo, bgInfoLr, bgInfoDr],
        "warning": [bgWarning, bgWarningLr, bgWarningDr],
        "success": [bgSuccess, bgSuccessLr, bgSuccessDr],
        "alert": [bgAlert, bgAlertLr, bgAlertDr]
    };

    $(function(){
        setTimeout(function(){
            $('.load-chart').load('admin/load_statistic',{type:'day',begin:'',end:''});
        },1000);
        $('.btn_search_chart').on('click', function function_name(argument) {
            $('.load-chart').load('admin/load_statistic',{type:$('#form_type').val(),begin:$('#date_start_com_2').val(),end:$('#date_end_com_2').val()});
        })
        
    });
</script>




<!-- END: PAGE SCRIPTS -->
<script src="publics/luxyart/js/jquery.colorbox.js"></script>
<script>
    $(document).ready(function(){
        //Examples of how to assign the Colorbox event to elements
        $(".iframe").colorbox({iframe:true, width:"600px", height:"320px"});
        $(".callbacks").colorbox({
            onOpen:function(){ alert('onOpen: colorbox is about to open'); },
            onLoad:function(){ alert('onLoad: colorbox has started to load the targeted content'); },
            onComplete:function(){ alert('onComplete: colorbox has displayed the loaded content'); },
            onCleanup:function(){ alert('onCleanup: colorbox has begun the close process'); },
            onClosed:function(){ alert('onClosed: colorbox has completely closed'); }
        });

        $('.non-retina').colorbox({rel:'group5', transition:'none'})
        $('.retina').colorbox({rel:'group5', transition:'none', retinaImage:true, retinaUrl:true});
        
        //Example of preserving a JavaScript event for inline calls.
        $("#click").click(function(){ 
            $('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
            return false;
        });
    });
</script>       <!--     JavaScript Area End    --> 


<script type="text/javascript">
    $(function(){
        $('#state_id').load("<?=base_url('admin/load_state')?>/"+$('#country_id').val()+"?state_id="+$('#country_id').data('state'));
        $('#country_id').on('change', function(){
            $('#state_id').load("<?=base_url('admin/load_state')?>/"+$(this).val());
        })

        $('#parent_cate').load("<?=base_url('admin/load_menu')?>/"+$('#root_cate').val()+"?parent_cate="+$('#root_cate').data('state'));
        $('#root_cate').on('change', function(){
            $('#parent_cate').load("<?=base_url('admin/load_menu')?>/"+$(this).val());
        })
    });
</script>
 <script type="text/javascript" >
                
    $(document).ready(function(){
        
        $('#date_start_com').datepicker({
            changeYear:true,
            minDate: 0,
            onClose: function(selectedDate){
                $('#date_end_com').datepicker("option", "minDate", selectedDate);
            }
        });
        $("#date_start_com").datepicker( "option", "dateFormat", "dd/mm/yy");
        
        $('#date_end_com').datepicker({
            changeYear:true,
            minDate: 0,
            onClose: function( selectedDate ){
                $('#date_start_com').datepicker("option", "maxDate", selectedDate);
            }
        });
        $("#date_end_com").datepicker( "option", "dateFormat", "dd/mm/yy");

        // Set time
        $("#date_start_com").val($("#date_start_com").attr('value'));
        $("#date_end_com").val($("#date_end_com").attr('value'));

        $('#date_start_com_2').datepicker({
            changeYear:true,
            onClose: function(selectedDate){
                $('#date_end_com_2').datepicker("option", "minDate", selectedDate);
            }
        });
        $("#date_start_com_2").datepicker( "option", "dateFormat", "dd/mm/yy");
        
        $('#date_end_com_2').datepicker({
            changeYear:true,
            minDate: 0,
            onClose: function( selectedDate ){
                $('#date_start_com_2').datepicker("option", "maxDate", selectedDate);
            }
        });
        $("#date_end_com_2").datepicker( "option", "dateFormat", "dd/mm/yy");

        // Set time
        $("#date_start_com_2").val($("#date_start_com_2").attr('value'));
        $("#date_end_com_2").val($("#date_end_com_2").attr('value'));
    });

</script>   
