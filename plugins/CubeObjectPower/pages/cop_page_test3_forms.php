<?php
    require_once( 'cop_page_api.php' );
    require_once( 'cop_forms_api.php' );

    //--------------------------------------------------------------------------
    print_page_top("Тесты", "Тесты - БД Оборудования");

    //--------------------------------------------------------------------------
    $frm = new MForm_t_equipment;
    $frm->f_init();
    echo($frm->f_show_root());

    //--------------------------------------------------------------------------
    print_page_bottom();
?>