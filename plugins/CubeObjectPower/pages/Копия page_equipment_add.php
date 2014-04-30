<?php
    require_once( 'page_api.php' );

    //-------------
    // ajax

    if (isset($_GET['funk']))
    {
        eval($_GET['funk'] . '();');   // TODO: добавить проверку префикса ф-ции f_ajax!!!!
        exit;
    }

    //--------------------------------------------------------------------------
    function f_ajax_show_hello()
    {
        // Получаем параметры
        $v1 = $_GET['v1'];
        $v2 = $_GET['v2'];

        $str_js = "server скрипт 2! v1=" . $v1 . ",v2=" . $v2;

        echo("alert('$str_js'); self.style.backgroundColor='#FF00FF'");

    }

    //--------------------------------------------------------------------------
    function f_ajax_show_frmAddKontroller()
    {
        //$html_str = "<td>1111</td>
        //<td>2222</td>";

        //$html_str = "<td>1111</td>  <td>2222</td>";


        $html_str =
        '
        <td colspan="2">
            <form name="report_bug_form2" method="post" enctype="multipart/form-data" action="bug_report.php">
            <input name="bug_report_token" value="2014041645dd4ca67cc501cd0ad471febc74bab3212f7739" type="hidden">
                <table class="width100" cellspacing="1">
                <tbody>
                    <tr>
                        <td colspan="2" class="form-title">
                            <input name="m_id" value="0" type="hidden">
                            <input name="project_id" value="18" type="hidden">
                            Введите данные контроллера
                        </td>
                    </tr>
                    <tr class="row-2">
                        <td class="category" width="30%">
                            Тип контроллера
                        </td>
                        <td width="70%">
                            <select tabindex="2" name="priority">
                                <option value="10">нет</option><option value="20">низкий</option><option value="30" selected="selected">обычный</option><option value="40">высокий</option><option value="50">срочный</option><option value="60">неотложный</option>            </select>
                        </td>
                    </tr>
                    <tr class="row-2">
                        <td class="category">
                            Кол-во процессоров
                        </td>
                        <td>
                            <script>
                              function on_select123()
                              {
                                var funk = "f_ajax_show_frmAddKontroller";
                                var params = "&v1="+this.value+"&v2="+encodeURIComponent("русская строка3333");
                                runAjaxJS(this, funk, params);
                              }
                            </script>
                            <select tabindex="2" name="priority"
                                    onchange="on_select123();"
                            >
                                <option value="10">нет</option><option value="20">низкий</option><option value="30" selected="selected">обычный</option><option value="40">высокий</option><option value="50">срочный</option><option value="60">неотложный</option>            </select>
                        </td>
                    </tr>
                    <tr id="row_frmAddKontroller2">
                    </tr>
                </tbody>
                </table>
            </form>
        </td>
        ';


        $html_str = str_replace("\r\n", "", $html_str); // js не понимает переходы строк

        echo('
        setInnerHtmlById("row_frmAddKontroller", \''. $html_str . '\');
        ');
    }


    //--------------------------------------------------------------------------
    function f_ajax_show_frmAddKontroller3()
    {
        echo('
        alert("111");
        elem = document.getElementById("row_frmAddKontroller");
        alert(elem);
        alert(elem.innerHTML);
        elem.innerHTML = "<td>1111</td><td>2222</td>"; //перенос на след.строку не работает

        alert("32222");
        ');
    }

    //--------------------------------------------------------------------------
    function f_ajax_show_frmAddKontroller2()
    {
        echo('
        alert("1");
        elem = document.getElementById("row_frmAddKontroller");
        alert(elem);
        elem.innerHTML = \'
        <td colspan="2">
            <form name="report_bug_form2" method="post" enctype="multipart/form-data" action="bug_report.php">
            <input name="bug_report_token" value="2014041645dd4ca67cc501cd0ad471febc74bab3212f7739" type="hidden">
                <table class="width100" cellspacing="1">
                <tbody>
                    <tr>
                        <td colspan="2" class="form-title">
                            <input name="m_id" value="0" type="hidden">
                            <input name="project_id" value="18" type="hidden">
                            Введите данные контроллера
                        </td>
                    </tr>
                    <tr class="row-2">
                        <td class="category" width="30%">
                            Тип контроллера
                        </td>
                        <td width="70%">
                            <select tabindex="2" name="priority">
                                <option value="10">нет</option><option value="20">низкий</option><option value="30" selected="selected">обычный</option><option value="40">высокий</option><option value="50">срочный</option><option value="60">неотложный</option>            </select>
                        </td>
                    </tr>
                    <tr class="row-2">
                        <td class="category">
                            Кол-во процессоров
                        </td>
                        <td>
                            <select tabindex="2" name="priority"
                                    onchange="

                                    "
                            >
                                <option value="10">нет</option><option value="20">низкий</option><option value="30" selected="selected">обычный</option><option value="40">высокий</option><option value="50">срочный</option><option value="60">неотложный</option>            </select>
                        </td>
                    </tr>
                    <tr id="row_frmAddKontroller2">
                    </tr>
                </tbody>
                </table>
            </form>
        </td>
        \';
        alert("3");
        ');
    }
    //
    //-------------


    print_page_top("Добавление оборудования", "Добавление оборудования - БД Оборудования");

/*
    $t_username = current_user_get_field( 'username' );
    $t_access_level = get_enum_element( 'access_levels', current_user_get_access_level() );
    $t_now = date( config_get( 'complete_date_format' ) );
    $t_realname = current_user_get_field( 'realname' );
    //$p_user_id = auth_get_current_user_id(); ипользуется в current_user_get_field ;)

    echo "<div align='center'>Пользователь: <span class=\"italic\">", string_html_specialchars( $t_username ), "</span> <span class=\"small\">";
    echo is_blank( $t_realname ) ? "($t_access_level)" : "(" . string_html_specialchars( $t_realname ) . " - $t_access_level)";
    echo "</span></div>";


    include("templates/page_main.inc");
*/

    // Контролы атрибутов объекта

?>

<div align="center">
<form name="report_bug_form" method="post" enctype="multipart/form-data" action="bug_report.php">
<input name="bug_report_token" value="2014041645dd4ca67cc501cd0ad471febc74bab3212f7739" type="hidden"><table class="width90" cellspacing="1">
    <tbody>
    <tr>
        <td colspan="2" class="form-title">
            <input name="m_id" value="0" type="hidden">
            <input name="project_id" value="18" type="hidden">
            Введите данные оборудования
        </td>
    </tr>
    <tr class="row-1">
        <td class="category">
            <span class="required">*</span>Наименование
        </td>
        <td>
            <input tabindex="5" name="summary" size="105" maxlength="128" value="" type="text">
        </td>
    </tr>
    <tr class="row-1">
        <td class="category">
            <span class="required">*</span>Инвентарный номер
        </td>
        <td>
            <input tabindex="5" name="summary" size="105" maxlength="128" value="" type="text">
        </td>
    </tr>
    <tr class="row-1">
        <td class="category">
            <span class="required">*</span>Заводской номер
        </td>
        <td>
            <input tabindex="5" name="summary" size="105" maxlength="128" value="" type="text">
        </td>
    </tr>
    <tr class="row-1">
        <td class="category" width="30%">
            <span class="required">*</span>Состояние оборудования
        </td>
        <td width="70%">
                        <select tabindex="1" name="category_id">
                <option value="0" selected="selected">(выбрать)</option><option value="1">[все проекты] General</option><option value="12">[все проекты] Аппаратная ошибка</option><option value="16">[все проекты] Внедрение</option><option value="10">[все проекты] Категория не определена</option><option value="14">[все проекты] Мониторинг</option><option value="19">[все проекты] Обслуживание</option><option value="20">[все проекты] Обучение</option><option value="11">[все проекты] Ошибка ПО</option><option value="15">[все проекты] Разработка</option><option value="18">[все проекты] Установка/замена оборудования</option><option value="17">[все проекты] Установка/обновление ПО</option><option value="13">[все проекты] Периодическое обслуживание</option>            </select>
        </td>
    </tr>
    <tr class="row-2">
        <td class="category">
            Место нахождение оборудования
        </td>
        <td>
            <select tabindex="2" name="priority">
                <option value="10">нет</option><option value="20">низкий</option><option value="30" selected="selected">обычный</option><option value="40">высокий</option><option value="50">срочный</option><option value="60">неотложный</option>            </select>
        </td>
    </tr>
    <tr class="row-2">
        <td class="category">
            Владелец оборудования
        </td>
        <td id="t11">
            <select id="sel1" tabindex="2" name="priority"
                <?php
                      $my_php_var = 'ураа!!';
                    echo
                    ("
                    onchange=\"
                    runAjaxJS(this, 'f_ajax_show_hello', '&v1='+this.value+'&v2='+encodeURIComponent('русская строка путь=$my_php_var'));
                    \"
                    ");
                ?>
            >

                <option value="10">нет</option><option value="20">низкий</option><option value="30" selected="selected">обычный</option><option value="40">высокий</option><option value="50">срочный</option><option value="60">неотложный</option>            </select>
        </td>
    </tr>
    <tr class="row-2">
        <td class="category">
            Тип оборудования
        </td>
        <td>
            <select tabindex="2" name="priority" onchange='test1();'>
                <option value="10">нет</option><option value="20">низкий</option><option value="30" selected="selected">обычный</option><option value="40">высокий</option><option value="50">срочный</option><option value="60">неотложный</option>            </select>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <form name="report_bug_form" method="post" enctype="multipart/form-data" action="bug_report.php">
            <input name="bug_report_token" value="2014041645dd4ca67cc501cd0ad471febc74bab3212f7739" type="hidden">
                <table class="width100" cellspacing="1">
                <tbody>
                    <tr>
                        <td colspan="2" class="form-title">
                            <input name="m_id" value="0" type="hidden">
                            <input name="project_id" value="18" type="hidden">
                            Введите данные контроллера
                        </td>
                    </tr>
                    <tr class="row-2">
                        <td class="category" width="30%">
                            Тип контроллера
                        </td>
                        <td width="70%">
                            <select tabindex="2" name="priority">
                                <option value="10">нет</option><option value="20">низкий</option><option value="30" selected="selected">обычный</option><option value="40">высокий</option><option value="50">срочный</option><option value="60">неотложный</option>            </select>
                        </td>
                    </tr>
                    <tr class="row-2">
                        <td class="category">
                            Кол-во процессоров
                        </td>
                        <td>
                            <select tabindex="2" name="priority"
                                <?php
                                    echo
                                    ("
                                    onchange=\"
                                    runAjaxJS(this, 'f_ajax_show_frmAddKontroller', '&v1='+this.value+'&v2='+encodeURIComponent('русская строка3333'));
                                    \"
                                    ");
                                ?>
                            >
                                <option value="10">нет</option><option value="20">низкий</option><option value="30" selected="selected">обычный</option><option value="40">высокий</option><option value="50">срочный</option><option value="60">неотложный</option>            </select>
                        </td>
                    </tr>
                    <tr id="row_frmAddKontroller">
                    </tr>
                </tbody>
                </table>
            </form>
        </td>
    </tr>


</tbody></table>
</form>
</div>

<?php


    print_page_bottom();
?>