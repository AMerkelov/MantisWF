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
   <img src="./plugins/CubeObjectPower/files/zanaves.jpg" />
   </div>
 </body>
</html>
        ');

        die();
    }

/*
    //echo("<a href='./plugins/CubeObjectPower/pages/unit_page2.php'>Тест1</a>");
    echo("<a href='".plugin_page('unit_page2', false)."'>Тест1</a>");

    echo("<br>post=");
    print_r($_POST);
    echo("<br>get=");
    print_r($_GET);
    echo("<br>c=");
    print_r($_COOKIE);
*/

/*
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

    bug_ensure_exists( $f_bug_id );

    $f_bugnote_text = 'comment test1. Тест комментария1 \r\n 2я строка!!!';
	//$t_bugnote_id = bugnote_add( $f_bug_id, $f_bugnote_text, $f_time_tracking, $f_private, BUGNOTE );
    $t_bugnote_id = bugnote_add( $f_bug_id, $f_bugnote_text);
    if ( !$t_bugnote_id ) {
        error_parameters( lang_get( 'bugnote' ) );
        trigger_error( ERROR_EMPTY_FIELD, ERROR );
    }


    echo("<br>OK!");
*/

/*
    //
    $page_title = "Главная";

    // формируем начало страницы
echo
('
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/default.css">
	<link rel="stylesheet" type="text/css" href="'.plugin_file("main.css").'">
	<script type="text/javascript"><!--
		if(document.layers) {document.write("<style>td{padding:0px;}<\/style>")}
	// --></script>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Cache-Control" content="no-cache">
	<meta http-equiv="Pragma-directive" content="no-cache">
	<meta http-equiv="Cache-Directive" content="no-cache">

	<meta name="robots" content="noindex,follow">
	<link rel="shortcut icon" href="/mantis/images/favicon.ico" type="image/x-icon">

<script type="text/javascript" src="/mantis/javascript/min/common.js"></script>
<script type="text/javascript">var loading_lang = "Загрузка...";</script><script type="text/javascript" src="/mantis/javascript/min/ajax.js"></script>

    <title>'.$page_title.'</title>
</head>
<body>
');

    // Логотип
echo
('
<div align="left"><a href="my_view_page.php"><img alt="MantisBT - БД Оборудования" src="'.plugin_file("mantis_db_oborud_logo.jpg").'" border="0"></a></div>
');

    // Заголовок
echo
('
<div align="center">
<table class="hide, cop_page_caption"><tbody><tr><td class="">'.$page_title.'</td></tr></tbody></table>
</div>
');

    // Меню
echo
('
<table class="width100" cellspacing="0"><tbody><tr><td class="menu">
    <a href="'.plugin_page("page_main").'">Главная</a> |
    <a href="'.plugin_page("page_main").'">Поиск оборудования</a> |
    <a href="'.plugin_page("page_main").'">Статистика</a> |
    <a href="'.plugin_page("page_main").'">Конфигурация</a> |
    <a href="index.php">Мантис</a> |
    </td>
    <td class="menu right nowrap"><form method="post" action="'.plugin_page("jump_to_object.php").'"><input name="obj_id" size="10" class="small" value="Объект #" onfocus="if (this.value == \'Объект #\') this.value = \'\'" onblur="if (this.value == \'\') this.value = \'Объект #\'" type="text">&nbsp;<input class="button-small" value="Перейти" type="submit">&nbsp;</form>
    </td></tr></tbody></table>
');


    // формируем конец страницы
echo
('
<hr size="1">
<table cellpadding="0" cellspacing="0" border="0" width="100%"><tbody><tr valign="top">
<td>	<address>Copyright © 2014 CubeObjectPower Team</address>
	<address><a href="mailto:maa@osa.vaso.ru">Alexander Merkelov</a></address>
</td><td>
	<div align="right"><a href="http://www.cube-object-power.com" title="Cube Object Power Technologic!!!"><img src="'.plugin_file("cube_object_power_logo.JPG").'" alt="Powered by Alexander Merkelov" border="0" height="50" width="145"></a></div>
</td></tr></tbody></table>
</body>
</html>
');
*/

    require_once( 'cop_page_api.php' );

    print_page_top("Главная", "Главная - БД Оборудования");

	$t_username = current_user_get_field( 'username' );
	$t_access_level = get_enum_element( 'access_levels', current_user_get_access_level() );
	$t_now = date( config_get( 'complete_date_format' ) );
	$t_realname = current_user_get_field( 'realname' );
    //$p_user_id = auth_get_current_user_id(); ипользуется в current_user_get_field ;)

    echo "<div align='center'>Пользователь: <span class=\"italic\">", string_html_specialchars( $t_username ), "</span> <span class=\"small\">";
    echo is_blank( $t_realname ) ? "($t_access_level)" : "(" . string_html_specialchars( $t_realname ) . " - $t_access_level)";
    echo "</span></div>";

    include("templates/cop_page_main.inc");

    print_page_bottom();
?>