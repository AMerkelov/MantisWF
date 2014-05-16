<?php
    require_once( 'cop_page_api.php' );
    require_once( 'cop_forms_api.php' );

    //--------------------------------------------------------------------------
    print_page_top("Добавление оборудования в БД с наклейкой", "Добавление оборудования в БД с наклейкой - БД Оборудования");

    //print_r($_POST);
    //--------------------------------------------------------------------------

    $str_htm = '
    <br>
    <div align="center">
    <form name="input_edit_object_id" method="get" enctype="multipart/form-data" action="plugin.php">
    <input type="hidden" name="page" value="CubeObjectPower/cop_page_equipment_update.php">
    <input type="hidden" name="cmd" value="update">
    <table class="width90" cellspacing="1">
        <tbody>
        <tr>
            <td class="form-title" colspan="2">
                Введите ID наклейки
            </td>
        </tr>
        <tr class="row-1">
            <td class="category" width="30%">
                <span class="required">*</span>ID наклейки
            </td>
            <td width="70%">
                <input tabindex="1" name="obj_id" size="105" maxlength="128" value="" type="text">
            </td>
        </tr>
        <tr>
            <td class="left">
                <span class="required"> * Поле, обязательное для заполнения</span>
            </td>
            <td class="center">
                <input tabindex="2" class="button" value="Редактировать объект" type="submit">
            </td>
        </tr>
    </tbody></table>
    </form>
    </div>
    ';

    echo($str_htm);

    //--------------------------------------------------------------------------
    print_page_bottom();
?>