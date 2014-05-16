<?php
    require_once( 'cop_page_api.php' );
    require_once( 'cop_forms_api.php' );

    //--------------------------------------------------------------------------
    print_page_top("Добавление оборудования", "Добавление оборудования - БД Оборудования");

    //print_r($_POST);
    //--------------------------------------------------------------------------

    $str_htm = '
    <br>
    <div align="center">
        <form method="post" action="'.plugin_page("cop_page_equipment_add_exist_id.php").'">
            <input class="button" value="Добавление оборудования в БД с наклейкой" type="submit">
        </form>
    </div>
    <div align="center">
        <form method="post" action="'.plugin_page("cop_page_equipment_add_new_id.php").'&cmd=insert">
            <input class="button" value="Добавление оборудования в БД без наклейки" type="submit">
        </form>
    </div>
    ';

    echo($str_htm);

    //--------------------------------------------------------------------------
    print_page_bottom();
?>