<?php

    require_once( 'cop_page_api.php' );
    require_once( 'cop_forms_api.php' );

    //--------------------------------------------------------------------------
    print_page_top("Тесты", "Тесты - БД Оборудования");


    //--------------------------------------------------------------------------
    $frm = new MForm_t_equipment;
    $frm->f_init();
    $frm->f_print();

    echo('<br><a href="'. plugin_page( 'cop_db_api.php' ) . '"> db_api тест </a>');
    echo('<br><a href="'. plugin_page( 'cop_page_test1_grid.php' ) . '"> grid тест1 </a>');
    echo('<br><a href="'. plugin_page( 'cop_page_test2_grid.php' ) . '"> grid тест2 </a>');
    echo('<br><a href="'. plugin_page( 'cop_page_test3_forms.php' ) . '"> формы тест3 </a>');



    //--------------------------------------------------------------------------
    print_page_bottom();
?>