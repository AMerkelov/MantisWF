<?php
    require_once( 'cop_page_api.php' );

    print_page_top("Конфигурация", "Конфигурация - БД Оборудования");

    function print_list_attribut_row($in_obj, $in_caption)
    {
        return ('
          <tr border="1" bgcolor="#D8D8D8" valign="top">
            <td><a href="'.plugin_page("cop_obj_api").'&cmd=search&obj='.$in_obj.'&dyn=1&frm_caption='.$in_caption.'">'.$in_caption.'</a></td>
            <td><a href="'.plugin_page("cop_obj_api").'&cmd=insert&obj='.$in_obj.'&dyn=1&frm_caption='.$in_caption.'">Добавить</a></td>
          </tr>
        ');
    }

    $str_t_list_attributs = '
<div align="center">
<br>
<table id="t_list_attributs" class="width90" cellspacing="1">
    <tbody>
            <tr>
                <td class="form-title" colspan="9">
                    <span class="floatleft">
                        Список контейнеров атрибутов
                    </span>
                </td>
            </tr>
            <tr class="row-category">
                <td>Название контейнера</td>
                <td>Функции</td>
            </tr>

            <tr class="spacer">
                <td colspan="2"></td>
            </tr>
            <tr border="1" bgcolor="#c9ccc4" valign="top">
                <td>Объект "Оборудование"</td>
                <td></td>
            </tr>
            '.print_list_attribut_row("t_equipment_manufacturer", "Производитель оборудования").'
            '.print_list_attribut_row("t_equipment_geo_place", "Текущее месторасположение").'
            <tr border="1" bgcolor="#c9ccc4" valign="top">
                <td>Объект "Типы оборудования"</td>
                <td></td>
            </tr>
            '.print_list_attribut_row("t_controller_type", "Тип Контроллера").'
            '.print_list_attribut_row("t_pult_type", "Тип Пульта").'
            '.print_list_attribut_row("t_multipleksor_type", "Тип Мультиплексора").'
            '.print_list_attribut_row("t_tool_type", "Тип Станка").'
            '.print_list_attribut_row("t_cnc_type", "Тип ЧПУ").'
            '.print_list_attribut_row("t_tool_model", "Тип Модели станка").'
    </tbody>
</table>
</div>
';

    echo ($str_t_list_attributs);

    print_page_bottom();
?>