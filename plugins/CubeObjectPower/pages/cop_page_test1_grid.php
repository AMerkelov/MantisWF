<?php

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>DHTMLX Tutorial. Contacts</title>
    <!-- dhtmlx.js contains all necessary dhtmlx library javascript code -->
     <script src= <?php echo('"'.plugin_file("codebase/dhtmlx.js").'"') ?> type="text/javascript"></script>
    <!-- <script src="/mantis/plugins/CubeObjectPower/files/codebase/dhtmlx.js" type="text/javascript"></script> -->
    <!-- connector.js used to integrate with the server-side -->
    <!-- <script src= <?php echo('"'.plugin_file("codebase/connector/connector.js").'"') ?> type="text/javascript"></script> -->
    <!-- <script src="/mantis/plugins/CubeObjectPower/files/codebase/connector/connector.js" type="text/javascript"></script> -->
    <!-- dhtmlx.css contains styles definitions for all included components -->
    <link rel="STYLESHEET" type="text/css" href= <?php echo('"'.plugin_file("codebase/dhtmlx.css").'"') ?> >

    <style>
        /*these styles allow dhtmlxLayout to work in fullscreen mode in different browsers correctly*/
        html, body {
           width: 100%;
           height: 100%;
           margin: 0px;
           overflow: hidden;
           background-color:white;
        }
    </style>

    <script type="text/javascript">
        //Here we'll put the code of the application

        var layout,menu,toolbar,contactsGrid,contactForm;
        //dhtmlx.image_path = "/mantis/plugins/CubeObjectPower/files/codebase/imgs/";
        dhtmlx.image_path = <?php echo('"'.plugin_file("codebase/imgs/").'"') ?> ;
        dhtmlxEvent(window,"load",function()
        {
            //application code goes here

            //layout
            layout = new dhtmlXLayoutObject(document.body,"2U");
            //layout = new dhtmlXLayoutObject(document.getElementById("mylayout_container"),"2U");
            layout.cells("a").setText("Список оборудования Contacts");
            layout.cells("b").setText("Подробности Contact Details");
            layout.cells("a").setWidth(500);

            //
            menu = layout.attachMenu();
            menu.setIconsPath("icons/");
            //menu.loadXML("../xml/cop_menu.xml");
            menu.loadXML( <?php echo('"'.plugin_file("xml/cop_menu.xml").'"') ?> );

        })
    </script>
</head>
<body>
<div id="mylayout_container" style="width:100%;height:100%;"></div>


</body>
</html>