<?php

require_once( 'cop_db_api.php' );


    //-------------
    // ajax

    if (isset($_GET['funk']))
    {
        eval($_GET['funk'] . '();');   // TODO: добавить проверку префикса ф-ции f_ajax!!!!
        exit;
    }

    //--------------------------------------------------------------------------
    function f_ajax_show_form()
    {
        $form_table = $_GET['form_table'];

        $form_name = 'MForm_' . $form_table;

        $elem_id = $_GET['elem_id'];

        $html_str = '';

        if ($form_table == -1)
        {
            $html_str = '';
        }
        else
        {
/*
            $html_str = eval('
                $frm = new '.$form_name.';
                $frm->f_init();
                return $frm->f_show();
            ');
*/
                $frm = eval('$frm_new = new '.$form_name.'; return $frm_new;'); // создаем класс по имени
                $frm->f_init();
                $html_str = $frm->f_show();
        }

        $html_str = str_replace("\r\n", "", $html_str); // js не понимает переходы строк

        echo('
        setInnerHtmlById(\''.$elem_id.'\', \''. $html_str . '\');
        ');
    }


    // ajax
    //-------------

/*
class MAttribut
{
    var $value = array();
}

// Описание связи поля с другой таблицей
class MLink
{
    var $type;    //  '' - нет связи, просто заполняем поле в ручную (EDIT)
                  //  'list_table' - связь с таблицей хранящей список значений (COMBOBOX)
                  //  'Object_table' - связь с таблицей хранящей список названий объектов и таблиц в которых они хранятся (COMBOBOX)
}
*/

class MLink_List //extends MLink
{
    var $list_table_name;   // имя таблицы в которой хранится список значений поля
    var $list_key_name;     // имя ключего поля в связанной таблице со значениями (values для комбобокса)
    var $list_value_name;   // имя поля хранящее данные в связанной таблице со значениями (текстовые-данные для комбобокса )

    //
    function f_show_list_from_table($in_selected_value)
    {
        $str_res = "";

        $query = "SELECT $this->list_key_name, $this->list_value_name FROM $this->list_table_name;";

        //echo($query.'<br>');

        $q_res = mysql_query($query) or die(mysql_error());

        //$number = mysql_num_rows($q_res);

        // пустое значение
        $str_res .= '<option value="-1"></option>';

        // из БД
        while ($row = mysql_fetch_array($q_res))
        {
            $selected = '';
            if ($in_selected_value == $row[$this->list_key_name])
            {
                $selected = 'selected="selected"';
            }

            $str_res .= '<option '.$selected.' value="'.$row[$this->list_key_name].'">'.$row[$this->list_value_name].'</option>';
        }


        return($str_res);
    }
}

class MLink_Object //extends MLink
{
    var $list_table_name;   // имя таблицы в которой хранится список значений поля
    var $list_key_name;     // имя ключего поля в связанной таблице со значениями (values для комбобокса)
    var $list_value_name;   // имя поля хранящее данные в связанной таблице со значениями (текстовые-данные для комбобокса )
    var $object_table_name; // имя поля хранящее имя таблицы хранящей описание связанного объекта

    //
    function f_show_list_obj_from_table($in_selected_value)
    {
        $str_res = "";

        $query = "SELECT $this->list_key_name, $this->list_value_name, $this->object_table_name FROM $this->list_table_name;";

        //echo($query.'<br>');

        $q_res = mysql_query($query) or die(mysql_error());

        //$number = mysql_num_rows($q_res);

        // пустое значение
        $str_res .= '<option value="-1"></option>';

        // из БД
        while ($row = mysql_fetch_array($q_res))
        {
            $selected = '';
            if ($in_selected_value == $row[$this->object_table_name])
            {
                $selected = 'selected="selected"';
            }

            $str_res .= '<option '.$selected.' value="'.$row[$this->object_table_name].'">'.$row[$this->list_value_name].'</option>';
        }


        return($str_res);
    }
}

// Описание поля таблицы
class MField
{

    var $m_name;           // название поля в таблице
    var $m_caption;        // заголовок поля для отображения
    var $m_default_value;  // значение по умолчанию при создании формы
    var $m_required;       // обязательное для заполнения?

    // Тип данных для ввода-отображения
    // int, float, string, text, date, time, datetime, image, url, (file?)
    const c_type_STRING = 1;
    var $m_type;

    // тип связи:
    const c_link_type_NONE = ''; //  '' - нет связи, просто заполняем поле в ручную (EDIT)
    const c_link_type_LIST = 'list';    //  'list' - связь с таблицей хранящей список возможных значений (COMBOBOX)
    const c_link_type_OBJECT = 'object'; //  'object_type' - связь с таблицей хранящей список названий объектов и их таблиц (COMBOBOX)
    var $m_link_type;

    // если link_type != NONE хранит объект описывающий связь MLink
    var $m_link_value;

    //--------------------------------------------------------------------------
    //
    function f_show($in_row_odd)
    {
        $str_additional_row = '';

        $row_class = 'row-2';
        if ($in_row_odd)
        {
            $row_class = 'row-1';
        }

        $str_required = '';
        if ($this->m_required)
        {
            $str_required = '<span class="required">*</span>';
        }

        $str_control = '';
        $str_value = '';
        if ($this->m_link_type == MField::c_link_type_NONE)
        {
            if (empty($_POST[$this->m_name]))
            {
                $str_value = $this->m_default_value;
            }
            else
            {
                $str_value = $_POST[$this->m_name];
            }

            $str_control = '<input name="'.$this->m_name.'" size="105" maxlength="128" value="'.$str_value.'" type="text">';
        }
        else
        if ($this->m_link_type == MField::c_link_type_LIST)
        {
            $str_control = '
                <select name="'.$this->m_name.'">
                    '.$this->m_link_value->f_show_list_from_table($_POST[$this->m_name]).'
                </select>
            ';
        }
        else
        if ($this->m_link_type == MField::c_link_type_OBJECT)
        {
            $str_control = '
                <script>
                  function on_select_'.$this->m_name.'(self)
                  {
                    var funk = "f_ajax_show_form";
                    var params = "&form_table="+self.value+"&elem_id='.$this->m_name.'_obj";
                    var action = "'.plugin_page( 'cop_forms_api.php' ).'";
                    runAjaxJS(self, funk, params, action);
                  }
                </script>
                <select name="'.$this->m_name.'" onchange="on_select_'.$this->m_name.'(this)">
                    '.$this->m_link_value->f_show_list_obj_from_table($_POST[$this->m_name]).'
                </select>
            ';

            // вложенная форма
            $obj_str = '';
            if (isset($_POST[$this->m_name]))
            {
                $form_name = 'MForm_' . $_POST[$this->m_name];

                $frm = eval('$frm_new = new '.$form_name.'; return $frm_new;'); // создаем класс по имени
                $frm->f_init();
                $obj_str = $frm->f_show();
            }

            // дополнительная строка для вложенной формы
            $str_additional_row = '
                <tr class="'.$row_class.'">
                    <td colspan="2" id="'.$this->m_name.'_obj">
                        '.$obj_str.'
                    </td>
                </tr>
            ';
        }

        return('
                <tr class="'.$row_class.'">
                    <td class="category" width="30%">
                        '. $str_required . $this->m_caption.'
                    </td>
                    <td width="70%">
                    '.$str_control.'
                    </td>
                </tr>
                '.$str_additional_row.'
            ');
    }

    //--------------------------------------------------------------------------
    //
    function f_test_required()
    {
        if ($this->m_required == false)
            return true;

        if (empty($_POST[$this->m_name]) )
            return false;

        $field_value = $_POST[$this->m_name];


        if (($this->m_link_type == MField::c_link_type_LIST) ||
            ($this->m_link_type == MField::c_link_type_OBJECT))
        {
            $id = intval($field_value);
            if ($id < 0)
            {
                return false;
            }

            // Проверяем заполненность вложенного объекта
            if ($this->m_link_type == MField::c_link_type_OBJECT)
            {
                $form_name = 'MForm_' . $_POST[$this->m_name];

                $frm = eval('$frm_new = new '.$form_name.'; return $frm_new;'); // создаем класс по имени
                $frm->f_init();

                $is_required = $frm->f_test_reqired_fields();

                if ($is_required['result'] == false)
                {
                    return $is_required;
                }
/*
                $res['result'] = false;
                $res['msg_err'] = 'Не заполнено поле "'.$field->m_caption.'"';
                return $res;
*/
            }
        }
        else
        {
            if ($field_value == '')
                return false;
        }

        return true;
    }
}


//------------------------------------------------------------------------------
//
class MForm
{
    var $m_name;        // имя таблицы в БД которую отображает форма (для html <form>)
    var $m_caption;
    var $m_fields = array();

    function f_init(){} // Переопределяется в наследниках

    //--------------------------------------------------------------------------
    // показываем просто таблицу с контролами
    function f_show()
    {
        $str_fields = '';

        $row_odd = false;
        foreach ($this->m_fields as $field)
        {
            $row_odd = !$row_odd;
            $str_fields .= $field->f_show($row_odd);
        }

        return('
            <table class="width100" cellspacing="1">
            <tbody>
                <tr>
                    <td colspan="2" class="form-title">
                        '.$this->m_caption.'
                    </td>
                </tr>
                '.$str_fields.'
            </tbody>
            </table>
        ');
    }

    function f_print()
    {
        echo($this->f_show());
    }

    //--------------------------------------------------------------------------
    // показываем как главную корневую форму с кнопкой action
    // и табоицей содержащей контролы и подформы
    function f_show_root()
    {
        // обработка команды для формы
        //$in_cmd = $_POST['cmd'];
        $in_cmd = $_GET['cmd'];

/*
        if ($in_cmd == 'insert')
        {
            return f_show_root_insert();
        }
        else
*/
        if ($in_cmd == 'insert_result')
        {
            return $this->f_show_root_insert_result();
        }
        else
        {
            return $this->f_show_root_insert();
        }

    }

    //--------------------------------------------------------------------------
    //
    function f_show_root_insert()
    {
        $in_page = $_GET['page'];

        return('
        <div align="center">
            <form name="'.$this->m_name.'" method="post" enctype="multipart/form-data" action="plugin.php?page='.$in_page.'&cmd=insert_result">
                <div id="'.$this->m_name.'_msg" align="center"></div>
                <table class="width90" cellspacing="1">
                <tbody>
                    <tr>
                        <td colspan="2">
                            '.$this->f_show().'
                        </td>
                    </tr>
                    <tr class="row-1">
                        <td class="category">
                            Продолжить создание объекта
                        </td>
                        <td>
                            <label><input tabindex="10" id="report_stay" name="report_stay" type="checkbox"> отметьте, если собираетесь создавать несколько объектов</label>
                        </td>
                    </tr>
                    <tr>
                        <td class="left">
                            <span class="required"> * Поле, обязательное для заполнения</span>
                        </td>
                        <td class="center">
                            <input tabindex="11" class="button" value="Создать объект" type="submit">
                        </td>
                    </tr>
                </tbody>
                </table>
            </form>
        </div>
        ');
    }

    //--------------------------------------------------------------------------
    //
    function f_test_reqired_fields()
    {
        $res = array();

        // проверяем заполненность всех обязательных полей
        foreach ($this->m_fields as $field)
        {
            $test = $field->f_test_required();

            if (is_array($test))
            {
                return $test;
            }
            else
            {
                if ($test == false)
                {
                    $res['result'] = false;
                    $res['msg_err'] = 'Не заполнено поле "'.$field->m_caption.'"';
                    return $res;
                }
            }
        }

        // все поля заполнены
        $res['result'] = true;
        return $res;
    }

    //--------------------------------------------------------------------------
    //
    function f_show_root_insert_result()
    {
        $result_msg = 'Операция выполнена успешно!';
        $result_color = "green";


        $in_page = $_GET['page'];

        $is_required = $this->f_test_reqired_fields();

        if ($is_required['result'] == false)
        {
            $result_msg = $is_required['msg_err'];
            $result_color = "red";
        }


        return('
        <div align="center">
            <form name="'.$this->m_name.'" method="post" enctype="multipart/form-data" action="plugin.php?page='.$in_page.'&cmd=insert_result">
                <div id="'.$this->m_name.'_msg" align="center" style="color:'.$result_color.'">'.$result_msg.'</div>
                <table class="width90" cellspacing="1">
                <tbody>
                    <tr>
                        <td colspan="2">
                            '.$this->f_show().'
                        </td>
                    </tr>
                    <tr class="row-1">
                        <td class="category">
                            Продолжить создание объекта
                        </td>
                        <td>
                            <label><input tabindex="10" id="report_stay" name="report_stay" type="checkbox"> отметьте, если собираетесь создавать несколько объектов</label>
                        </td>
                    </tr>
                    <tr>
                        <td class="left">
                            <span class="required"> * Поле, обязательное для заполнения</span>
                        </td>
                        <td class="center">
                            <input tabindex="11" class="button" value="Создать объект" type="submit">
                        </td>
                    </tr>
                </tbody>
                </table>
            </form>
        </div>
        ');
    }

    //--------------------------------------------------------------------------
    // Проверяем POST переменные для полей
    // И формируем строку с sql
    function f_get_insert_str
    (
        $in_full_mode   // false-возвращаются только поля-значения,
                        // true-возвращается готовый INSERT
    )
    {
        // Перебираем все поля формы
        // и получаем insert строку
        $insert_fields='';
        $insert_values='';
        foreach ($this->m_fields as $field)
        {
            $insert_fields .= $field->m_name . ',';
            $insert_values .= 'NULL' . ',';
        }
    }
}

//------------------------------------------------------------------------------
//
class MForm_t_equipment extends MForm
{
    function f_init()
    {
        $this->m_name = "t_equipment";
        $this->m_caption = "Введите данные оборудования";

        $f = new MField;
        $f->m_name = 't_equipment_a_name';
        $f->m_caption = 'Наименование';
        $f->m_default_value = '--- введите имя объекта ---';
        $f->m_required = true;
        $f->m_type = MField::c_type_STRING;
        $f->m_link_type = MField::c_link_type_NONE;
        $f->m_link_value = '';
        $this->m_fields[] = $f;

        $f = new MField;
        $f->m_name = 't_equipment_a_inv_num';
        $f->m_caption = 'Инвентарный номер';
        $this->m_fields[] = $f;

        $f = new MField;
        $f->m_name = 't_equipment_a_zavod_num';
        $f->m_caption = 'Заводской номер';
        $this->m_fields[] = $f;

        $f = new MField;
        $f->m_name = 't_equipment_state_id';
        $f->m_caption = 'Состояние оборудования';
        $f->m_default_value = 0;
        $f->m_required = true;
        $f->m_type = MField::c_type_STRING;
        $f->m_link_type = MField::c_link_type_LIST;
        $list = new MLink_List;
        $list->list_table_name = 't_equipment_state';
        $list->list_key_name = 'id';
        $list->list_value_name = 't_equipment_state_a_name';
        $f->m_link_value = $list;
        $this->m_fields[] = $f;

        $f = new MField;
        $f->m_name = 't_equipment_geo_place_id';
        $f->m_caption = 'Текущее месторасположение';
        $f->m_default_value = 0;
        $f->m_required = true;
        $f->m_type = MField::c_type_STRING;
        $f->m_link_type = MField::c_link_type_LIST;
        $list = new MLink_List;
        $list->list_table_name = 't_equipment_geo_place';
        $list->list_key_name = 'id';
        $list->list_value_name = 't_equipment_geo_place_a_name';
        $f->m_link_value = $list;
        $this->m_fields[] = $f;

        $f = new MField;
        $f->m_name = 't_equipment_type_id';
        $f->m_caption = 'Тип оборудования';
        $f->m_default_value = 0;
        $f->m_required = true;
        $f->m_type = MField::c_type_STRING;
        $f->m_link_type = MField::c_link_type_OBJECT;
        $list = new MLink_Object;
        $list->list_table_name = 't_equipment_type';
        $list->list_key_name = 'id';
        $list->list_value_name = 't_equipment_type_a_name';
        $list->object_table_name = 't_equipment_type_link_t_name';
        $f->m_link_value = $list;
        $this->m_fields[] = $f;
    }
}


//------------------------------------------------------------------------------
//
class MForm_t_equipment_type_controller extends MForm
{
    function f_init()
    {
        $this->m_name = "t_equipment_type_controller";
        $this->m_caption = "Введите данные контроллера";

        $f = new MField;
        $f->m_name = 't_equipment_type_controller_id';
        $f->m_caption = 'Тип контроллера';
        $f->m_default_value = 0;
        $f->m_required = true;
        $f->m_type = MField::c_type_STRING;
        $f->m_link_type = MField::c_link_type_LIST;
        $list = new MLink_List;
        $list->list_table_name = 't_controller_type';
        $list->list_key_name = 'id';
        $list->list_value_name = 't_controller_type_a_name';
        $f->m_link_value = $list;
        $this->m_fields[] = $f;
    }
}

//------------------------------------------------------------------------------
//
class MForm_t_equipment_type_pult extends MForm
{
    function f_init()
    {
        $this->m_name = "t_equipment_type_pult";
        $this->m_caption = "Введите данные пульта";

        $f = new MField;
        $f->m_name = 't_equipment_type_pult_id';
        $f->m_caption = 'Тип пульта';
        $f->m_default_value = 0;
        $f->m_required = true;
        $f->m_type = MField::c_type_STRING;
        $f->m_link_type = MField::c_link_type_LIST;
        $list = new MLink_List;
        $list->list_table_name = 't_pult_type';
        $list->list_key_name = 'id';
        $list->list_value_name = 't_pult_type_a_name';
        $f->m_link_value = $list;
        $this->m_fields[] = $f;
    }
}

//------------------------------------------------------------------------------
//
class MForm_t_equipment_type_multipleksor extends MForm
{
    function f_init()
    {
        $this->m_name = "t_equipment_type_multipleksor";
        $this->m_caption = "Введите данные мультиплексора";

        $f = new MField;
        $f->m_name = 't_equipment_type_multipleksor_id';
        $f->m_caption = 'Тип мультиплексора';
        $f->m_default_value = 0;
        $f->m_required = true;
        $f->m_type = MField::c_type_STRING;
        $f->m_link_type = MField::c_link_type_LIST;
        $list = new MLink_List;
        $list->list_table_name = 't_multipleksor_type';
        $list->list_key_name = 'id';
        $list->list_value_name = 't_multipleksor_type_a_name';
        $f->m_link_value = $list;
        $this->m_fields[] = $f;
    }
}

?>