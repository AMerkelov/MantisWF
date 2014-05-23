<?php
    require_once( 'cop_page_api.php' );
    require_once( 'cop_forms_api.php' );

    //--------------------------------------------------------------------------
    $page_caption = '';

    $cmd = $_GET['cmd'];

    if ($cmd == 'read')
    {
        $page_caption = 'Просмотр оборудования';
    }
    else
    if (($cmd == 'insert') || ($cmd == 'insert_result'))
    {
        $page_caption = 'Создание оборудования';
    }
    else
    if (($cmd == 'update') || ($cmd == 'update_result'))
    {
        $page_caption = 'Редактирование оборудования';
    }
    else
    if (($cmd == 'search') || ($cmd == 'search_result'))
    {
        $page_caption = 'Поиск оборудования';
    }
    else
    if (($cmd == 'delete') || ($cmd == 'delete_result'))
    {
        $page_caption = 'Удаление оборудования';
    }

    print_page_top($page_caption, $page_caption . " - БД Оборудования");

    //print_r($_POST);
    //--------------------------------------------------------------------------
    $frm = new MForm_t_equipment;
    $frm->f_init();
    echo($frm->f_show_root());

    //--------------------------------------------------------------------------
    print_page_bottom();
?>