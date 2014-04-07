<?php
    //setcookie("Zet", 1, time()+3600*24*31*12);  /* срок действия 1 год */

    if (!isset($_COOKIE['Zet']) || ($_COOKIE['Zet'] != 1))
    {
        echo ('
<!DOCTYPE HTML>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>БД Оборудования" находится в разработке...</title>
 </head>
 <body bgcolor="#000000">
   <div align="center" style="color:blue">
   <H1>Проект "БД Оборудования" находится в разработке...</H1>
   <img src="./plugins/CubeObjectPower/images/zanaves.jpg" />
   </div>
 </body>
</html>
        ');

        die();
    }

    //echo("Main РІ СЂР°Р·СЂР°Р±РѕС‚РєРµ...");
    echo("<a href='./plugins/CubeObjectPower/pages/unit_page2.php'>Тест1</a>");
    echo("<br>post=");
    print_r($_POST);
    echo("<br>get=");
    print_r($_GET);
    echo("<br>c=");
    print_r($_COOKIE);


    //------------------------------------------
	require_once( 'core.php' );

	require_once( 'bug_api.php' );
	require_once( 'bugnote_api.php' );

//	form_security_validate( 'bugnote_add' );

//	$f_bug_id		= gpc_get_int( 'bug_id' );
//	$f_private		= gpc_get_bool( 'private' );


//    echo("<br>f_bug_id=");
//    print_r($f_bug_id);

    $f_bug_id = 77;
    $f_bugnote_text = 'comment test1. Тест комментария1';
	//$t_bugnote_id = bugnote_add( $f_bug_id, $f_bugnote_text, $f_time_tracking, $f_private, BUGNOTE );
    $t_bugnote_id = bugnote_add( $f_bug_id, $f_bugnote_text);
    if ( !$t_bugnote_id ) {
        error_parameters( lang_get( 'bugnote' ) );
        trigger_error( ERROR_EMPTY_FIELD, ERROR );
    }


    echo("<br>OK!");
?>