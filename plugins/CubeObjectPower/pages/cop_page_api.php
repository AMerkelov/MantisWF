<?php

    //--------------------------------------------------------------------------
    // Выводим начало страницы
    function print_page_begin($in_page_title, $str_to_head='', $str_to_inner_body='')
    {
echo
('
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/default.css">
    <link rel="stylesheet" type="text/css" href="'.plugin_file("cop_main.css").'">
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
<script type="text/javascript" src="'.plugin_file("js/cop_ajax.js").'"></script>

    <title>'.$in_page_title.'</title>

    '.$str_to_head.'
</head>
<body '.$str_to_inner_body.'>
');
    }

    //--------------------------------------------------------------------------
    // Выводим Логотип
    function print_page_logo()
    {
echo
('
<div align="left"><a href="my_view_page.php"><img alt="MantisBT - БД Оборудования" src="'.plugin_file("mantis_db_oborud_logo.jpg").'" border="0"></a></div>
');
    }

    //--------------------------------------------------------------------------
    // Выводим Заголовок
    function print_page_caption($in_page_caption)
    {
echo
('
<div align="center" style="background-color: rgb(158, 218, 158);">
<table class="hide, cop_page_caption"><tbody><tr><td class="">'.$in_page_caption.'</td></tr></tbody></table>
</div>
');
    }

    //--------------------------------------------------------------------------
    // Выводим Меню
    function print_page_menu()
    {
echo
('
<table class="width100" cellspacing="0"><tbody><tr><td class="menu">
    <a href="'.plugin_page("cop_page_main").'">Главная</a> |
    <a href="'.plugin_page("cop_page_obj_t_equipment").'&cmd=search">Поиск оборудования</a> |
    <a href="'.plugin_page("cop_page_equipment_add").'">Добавление оборудования</a> |
    <a href="'.plugin_page("cop_page_main").'">Статистика</a> |
    <a href="'.plugin_page("cop_page_main").'">Конфигурация</a> |
    <a href="'.plugin_page("cop_page_tests").'">Тесты</a> |
    <a href="index.php">Мантис</a> |
    </td>
    <td class="menu right nowrap"><form method="post" action="'.plugin_page("cop_jump_to_object.php").'"><input name="obj_id" size="10" class="small" value="Объект #" onfocus="if (this.value == \'Объект #\') this.value = \'\'" onblur="if (this.value == \'\') this.value = \'Объект #\'" type="text">&nbsp;<input class="button-small" value="Перейти" type="submit">&nbsp;</form>
    </td></tr></tbody></table>
');
    }

    //--------------------------------------------------------------------------
    // Выводим конец страницы
    function print_page_end()
    {
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
    }

    //--------------------------------------------------------------------------
    // Выводим Верх страницы
    function print_page_top($in_page_caption, $in_page_title, $str_to_head='', $str_to_inner_body='')
    {
        print_page_begin($in_page_title, $str_to_head, $str_to_inner_body);
        print_page_logo();

        print_page_menu();
        print_page_caption($in_page_caption);
    }

    //--------------------------------------------------------------------------
    // Выводим Низ страницы
    function print_page_bottom()
    {
        print_page_end();
    }

?>