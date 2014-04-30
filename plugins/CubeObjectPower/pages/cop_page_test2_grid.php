<?php
    require_once( 'cop_page_api.php' );

    $script_code =
    '
    <script>
        var mygrid;

        function doInitGrid()
        {
            //alert("zzzz");

            mygrid = new dhtmlXGridObject("mygrid_container");
            //mygrid.setImagePath("'.plugin_file("codebase/imgs/").'");
            mygrid.setImagePath("/mantis/plugins/CubeObjectPower/files/codebase/imgs/");
            mygrid.setHeader("Model,Qty,Price222");
            //mygrid.setInitWidths("*,150,150");
            mygrid.setInitWidths("200,150,150");
            mygrid.setColAlign("left,right,right");
            //mygrid.setSkin("light");
            //mygrid.setSkin("blue");
            mygrid.init();
            mygrid.setColumnColor("#FFFF77");
            //mygrid.loadXML("'.plugin_file("xml/grid_data.xml").'");
            mygrid.setColTypes("ed,ed,price");
            mygrid.setColSorting("str,int,na");
            mygrid.loadXML("/mantis/plugins/CubeObjectPower/files/xml/grid_data.xml");
        }
    </script>
    ';

    $str_to_head =
    '
     <link rel="STYLESHEET" type="text/css" href="'.plugin_file("codebase/dhtmlxgrid.css").'">
     <script src="'.plugin_file("codebase/dhtmlxcommon.js").'"></script>
     <script src="'.plugin_file("codebase/dhtmlxgrid.js").'"></script>
     <script src="'.plugin_file("codebase/dhtmlxgridcell.js").'"></script>
     '.$script_code.'
    ';

    $str_to_inner_body = 'onload="doInitGrid();"';

    //--------------------------------------------------------------------------
    print_page_top("Тест грида2", "Тест грида2 - БД Оборудования", $str_to_head, $str_to_inner_body);


    //echo ('<div id="mygrid_container" style="width:600px;height:150px;"></div>');
/*
    echo (
    '
    <div style="width:600px;height:250px;">
        <div id="mygrid_container" style="width:100%;height:100%;"></div>
    </div>
    <div style="bgcolor:#FF0000">zzzz</div>
    ');
*/

    echo (
    '
    <div style="width:600px;height:250px;">
        <div id="mygrid_container" style="width:100%;height:100%;"></div>
    </div>
    <div style="background-color:#0000FF">zzzz</div>
    ');

    //include("templates/cop_page_main.inc");

    //--------------------------------------------------------------------------
    print_page_bottom();
?>