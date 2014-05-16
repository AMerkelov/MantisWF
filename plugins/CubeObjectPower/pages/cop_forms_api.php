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
        //$form_table = $_GET['form_table'];
        //$form_name = 'MForm_' . $form_table;

        // Получаем имя таблицы из 0го параметра из списка разделенного запятыми
        // т.к. в form_table может так же передаваться ее table_id
        $get_val = explode(',', $_GET['form_table']);

        $form_name = 'MForm_' . $get_val[MLink_Object::c_get_value_from_post_TABLE_NAME];   // table_name


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

//==============================================================================
// MLink_List
//==============================================================================
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

        $q_res = mysql_query($query) or die('query='.$query.'; Err='.mysql_error());

        //$number = mysql_num_rows($q_res);

        // пустое значение
        $str_res .= '<option value="-1"></option>';

        // из БД
        while ($row = mysql_fetch_array($q_res))
        {
           $post_val = $this->f_get_value_from_post($in_selected_value);
           $selected_var_id = $post_val[MLink_List::c_get_value_from_post_VAR_ID];

            $selected = '';
            if ($selected_var_id == $row[$this->list_key_name])
            {
                $selected = 'selected="selected"';
            }

            // value= [0]=id, [1]=значение
            $str_res .= '<option '.$selected.' value="'.$row[$this->list_key_name].','.$row[$this->list_value_name].'">'.$row[$this->list_value_name].'</option>';
        }


        return($str_res);
    }

    //--------------------------------------------------------------------------
    // Парсим пост и возвращаем массив
    // m[0]=var_id
    // m[1]=var_value
    const c_get_value_from_post_VAR_ID = 0;
    const c_get_value_from_post_VAR_VALUE = 1;
    function f_get_value_from_post($in_post_value)
    {
        $result = array();

        $result = explode(',', $in_post_value);

        return $result;
    }
}

//==============================================================================
// MLink_Object
//==============================================================================
class MLink_Object //extends MLink
{
    var $list_table_name;   // имя таблицы в которой хранится список значений поля
    var $list_key_name;     // имя ключего поля в связанной таблице со значениями (values для комбобокса)
    var $list_value_name;   // имя поля хранящее данные в связанной таблице со значениями (текстовые-данные для комбобокса )
    var $object_table_name; // имя поля хранящее имя таблицы хранящей описание связанного объекта

    //--------------------------------------------------------------------------
    //
    function f_show_list_obj_from_table($in_selected_value)
    {
        $str_res = "";

        $query = "SELECT $this->list_key_name, $this->list_value_name, $this->object_table_name FROM $this->list_table_name;";

        //echo($query.'<br>');

        $q_res = mysql_query($query) or die('query='.$query.'; Err='.mysql_error());

        //$number = mysql_num_rows($q_res);

        // пустое значение
        $str_res .= '<option value="-1"></option>';

        // из БД
        while ($row = mysql_fetch_array($q_res))
        {
           $post_val = $this->f_get_value_from_post($in_selected_value);
           $selected_var_table_name = $post_val[MLink_Object::c_get_value_from_post_TABLE_NAME];

            $selected = '';
            if ($selected_var_table_name == $row[$this->object_table_name])
            {
                $selected = 'selected="selected"';
            }

            // value="t_equipment_type_controller,1,Тип контроллера" - передаем сразу название таблицы, ее id и ее название
            // [0] - table_name
            // [1] - table_id
            // [2] - table_caption
            $str_res .= '<option '.$selected.' value="'.$row[$this->object_table_name].','.$row["id"].','.$row[$this->list_value_name].'">'.$row[$this->list_value_name].'</option>';
        }


        return($str_res);
    }

    //--------------------------------------------------------------------------
    // Парсим пост и возвращаем массив
    // m[0]=table_name
    // m[1]=table_id
    // m[2] - table_caption
    const c_get_value_from_post_TABLE_NAME = 0;
    const c_get_value_from_post_TABLE_ID = 1;
    const c_get_value_from_post_TABLE_CAPTION = 2;
    function f_get_value_from_post($in_post_value)
    {
        $result = array();

        $result = explode(',', $in_post_value);

        return $result;
    }
}

//==============================================================================
// Описание поля таблицы
//==============================================================================
class MField
{
    var $m_parent_form;    // форма которой принадлежит поле

    var $m_name;           // название поля в таблице
    var $m_caption;        // заголовок поля для отображения
    var $m_default_value;  // значение по умолчанию при создании формы
    var $m_required;       // обязательное для заполнения?

    // Тип данных для ввода-отображения
    // int, float, string, text, date, time, datetime, image, url, (file?)
    const c_type_STRING = 1;
    const c_type_INT = 2;
    var $m_type;

    // тип связи:
    const c_link_type_NONE = 0;     //  нет связи, просто заполняем поле в ручную (EDIT)
    const c_link_type_LIST = 1;     //  связь с таблицей хранящей список возможных значений (COMBOBOX)
    const c_link_type_OBJECT = 2;   //  связь с таблицей хранящей список названий объектов и их таблиц (COMBOBOX)
    const c_link_type_AUTONAME = 3; //  автоматическое формирование поля из атрибутов под-объектов (EDIT-READONLY)
    var $m_link_type;

    // если link_type != NONE хранит объект описывающий связь MLink
    var $m_link_value;

    //--------------------------------------------------------------------------
    //
    function f_show($in_row_odd)
    {
        $select_data = $this->m_parent_form->m_select_data;

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
                if (empty($select_data[$this->m_name]))
                {
                    $str_value = $this->m_default_value;
                }
                else
                {
                     $str_value = $select_data[$this->m_name];
                }
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
            if (empty($_POST[$this->m_name]))
            {
                if (empty($select_data[$this->m_name]))
                {
                    $str_value = $this->m_default_value;
                }
                else
                {
                     $str_value = $select_data[$this->m_name];
                }
            }
            else
            {
                $str_value = $_POST[$this->m_name];
            }

            //
            $str_control = '
                <select name="'.$this->m_name.'">
                    '.$this->m_link_value->f_show_list_from_table($str_value).'
                </select>
            ';
        }
        else
        if ($this->m_link_type == MField::c_link_type_OBJECT)
        {
            if (empty($_POST[$this->m_name]))
            {
                if (empty($select_data[$this->m_name]))
                {
                    //$str_value = $this->m_default_value; нет значения по умолчанию!!!
                    unset($str_value);
                }
                else
                {
                    // формируем value для select по table_name,table_id,table_caption
                     $str_value =
                        $select_data[$this->m_link_value->object_table_name].','.
                        $select_data[$this->m_name].','.
                        $select_data[$this->m_link_value->list_value_name];
                }
            }
            else
            {
                $str_value = $_POST[$this->m_name];
            }


            //
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
                    '.$this->m_link_value->f_show_list_obj_from_table($str_value).'
                </select>
            ';

            // вложенная форма
            $obj_str = '';
            if (isset($str_value) && ($str_value != -1))
            {
                $post_val = $this->m_link_value->f_get_value_from_post($str_value);

                //$form_name = 'MForm_' . $_POST[$this->m_name];
                $form_name = 'MForm_' . $post_val[MLink_Object::c_get_value_from_post_TABLE_NAME];   // table_name

                $frm = eval('$frm_new = new '.$form_name.'; return $frm_new;'); // создаем класс по имени
                $frm->f_init();

                $obj_id_field = $select_data[$this->m_name.'_obj'];
                if (empty($obj_id_field))
                {
                    $obj_str = $frm->f_show();
                }
                else
                {
                    $obj_str = $frm->f_show_update($obj_id_field);
                }
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
        else
        if ($this->m_link_type == MField::c_link_type_AUTONAME)
        {
            if (empty($select_data[$this->m_name]))
            {
                // не выводим для редактирования и просмотра (только в таблице)
                $str_control = '';
            }
            else
            {
                $str_value = $select_data[$this->m_name];
            }

            $str_control = '<input name="'.$this->m_name.'" size="105" maxlength="128" value="'.$str_value.'" type="text" disabled >';
        }

        //
        if ($str_control == '')
        {
            return '';
        }

        //
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
                $post_val = $this->m_link_value->f_get_value_from_post($_POST[$this->m_name]);

                //$form_name = 'MForm_' . $_POST[$this->m_name];
                $form_name = 'MForm_' . $post_val[MLink_Object::c_get_value_from_post_TABLE_NAME];   // table_name

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

    //--------------------------------------------------------------------------
    //
    function f_get_value_for_sql
    (
        & $out_values_array,
        & $in_form  // & - для оптимизации без копирования
    )
    {
        if (empty($_POST[$this->m_name]))
        {
            // для автополя нет необходимости в передаче значения $_POST[]
            if ($this->m_link_type != MField::c_link_type_AUTONAME)
            {
                // для остальных - если пусто то в insert запрос поле не попадает
                return;
            }
        }

        $val = $_POST[$this->m_name];
        $res = '';

        switch ($this->m_type)
        {
            case MField::c_type_INT:
                // Если объект - то получаем его id его таблицы в таблице-списке типов данного объекта
                if ($this->m_link_type == MField::c_link_type_OBJECT)
                {
                    $post_val = $this->m_link_value->f_get_value_from_post($_POST[$this->m_name]);

                    $val = $post_val[MLink_Object::c_get_value_from_post_TABLE_ID];    // table_id
                }
                else
                if ($this->m_link_type == MField::c_link_type_LIST)
                {
                    $post_val = $this->m_link_value->f_get_value_from_post($_POST[$this->m_name]);

                    $val = $post_val[MLink_List::c_get_value_from_post_VAR_ID];    // var_id
                }

                //if (is_int($val))
                if (eregi('^[0-9]{1,20}$',$val)) // число состоящее от 1 до 20 цифр
                {
                    $res = intval($val);
                }
                else
                {
                    return ('Не верно задан параметр: '.$this->m_caption);
                }
            break;
            case MField::c_type_STRING:
                // Если автонейм - то формируем строку по вложенным объектам
                if ($this->m_link_type == MField::c_link_type_AUTONAME)
                {
                    // рекурсивно генерим $val
                    $val = $in_form->f_generate_auto_name();

                    //
                    $val = str_replace("'", "''", $val);
                    $res = "'".$val."'";
                }
                else
                {
                    $val = str_replace("'", "''", $val);
                    $res = "'".$val."'";
                }
            break;
        }

        // сохраняем поле
        $out_values_array[$this->m_name] = $res;

        // вложенные объекты
        $error_str = '';
        if ($this->m_link_type == MField::c_link_type_OBJECT)
        {
            $post_val = $this->m_link_value->f_get_value_from_post($_POST[$this->m_name]);

            //$form_name = 'MForm_' . $_POST[$this->m_name];
            $form_name = 'MForm_' . $post_val[MLink_Object::c_get_value_from_post_TABLE_NAME];   // table_name

            $frm = eval('$frm_new = new '.$form_name.'; return $frm_new;'); // создаем класс по имени
            $frm->f_init();

            $out_values_array2 = array();

            $error_str = $frm->f_get_values_array_for_sql($out_values_array2);

            // если в sql ошибок нет - выполняем запрос и создаем вложенный объект
            // получаем id созданного подобъекта и передаем его в наш текущий запрос
            if ($error_str == '')
            {
                $query = $frm->f_get_sql_insert($out_values_array2);

                // выполняем запрос
                $q_res = mysql_query($query) or die('query='.$query.'; Err='.mysql_error());

                // получаем id созданного объекта
                $last_id = mysql_insert_id();
                //$last_id = 7777;

                // формируем поле связанное со вложенным объектом
                $out_values_array[$this->m_name . '_obj'] = $last_id;
            }
        }

        return $error_str;
    }

    //--------------------------------------------------------------------------
    //
    function f_get_str_for_sql_select
    (
        $in_table_name,      // имя таблицы к которой относится поле
        & $out_str_select,
        & $out_str_from
    )
    {
        if ($this->m_link_type == MField::c_link_type_NONE)
        {
            $out_str_select .= $in_table_name.'.'.$this->m_name.','; //' AS '.$in_table_name.'_'.$this->m_name.',';
            //$out_str_from .= '';
        }
        else
        if($this->m_link_type == MField::c_link_type_LIST)
        {
            $out_str_select .= $this->m_link_value->list_table_name.'.'.$this->m_link_value->list_value_name.','
                                .$this->m_link_value->list_table_name.'.'.$this->m_link_value->list_key_name.' AS '.$this->m_name.',';

            $out_str_from .= ' INNER JOIN '.$this->m_link_value->list_table_name.
                ' ON '.$in_table_name.'.'.$this->m_name.'='. $this->m_link_value->list_table_name.'.'.$this->m_link_value->list_key_name;

//            INNER JOIN t_equipment_state
//            ON t_equipment.t_equipment_state_id = t_equipment_state.id
        }
        else
        if($this->m_link_type == MField::c_link_type_OBJECT)
        {
            $out_str_select .= $this->m_link_value->list_table_name.'.'.$this->m_link_value->list_value_name.','
                                .$this->m_link_value->list_table_name.'.'.$this->m_link_value->list_key_name.' AS '.$this->m_name.','
                                .$this->m_link_value->list_table_name.'.'.$this->m_link_value->object_table_name.','
                                .$in_table_name.'.'.$this->m_name.'_obj,';

            $out_str_from .= ' INNER JOIN '.$this->m_link_value->list_table_name.
                ' ON '.$in_table_name.'.'.$this->m_name.'='. $this->m_link_value->list_table_name.'.'.$this->m_link_value->list_key_name;
        }
        else
        if ($this->m_link_type == MField::c_link_type_AUTONAME)
        {
            $out_str_select .= $in_table_name.'.'.$this->m_name.','; //' AS '.$in_table_name.'_'.$this->m_name.',';
            //$out_str_from .= '';
        }
    }

    //--------------------------------------------------------------------------
    //
    function f_get_name_for_select_column()
    {
        if ($this->m_link_type == MField::c_link_type_NONE)
        {
            return $this->m_name;
        }
        else
        if($this->m_link_type == MField::c_link_type_LIST)
        {
            return $this->m_link_value->list_value_name;
        }
        else
        if($this->m_link_type == MField::c_link_type_OBJECT)
        {
            return $this->m_link_value->list_value_name;
        }
        else
        if ($this->m_link_type == MField::c_link_type_AUTONAME)
        {
            return $this->m_name;
        }
    }
}


//==============================================================================
// MForm
//==============================================================================
class MForm
{
    var $m_name;        // имя таблицы в БД которую отображает форма (для html <form>)
    var $m_caption;
    var $m_fields = array();
    var $m_select_data; // данные полученные селектом для полей формы

    function f_init(){} // Переопределяется в наследниках

    //--------------------------------------------------------------------------
    // показываем просто таблицу с контролами
    // получаея информацию из переменных POST или $m_select_data или пусто
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
        if ($in_cmd == 'insert')
        {
            return $this->f_show_root_insert();
        }
        else
        if ($in_cmd == 'insert_result')
        {
            return $this->f_show_root_insert_result();
        }
        else
        if ($in_cmd == 'update')
        {
            return $this->f_show_root_update();
        }
        else
        if ($in_cmd == 'update_result')
        {
            return $this->f_show_root_update_result();
        }

    }

    //--------------------------------------------------------------------------
    //
    function f_show_root_insert()
    {
        $in_page = $_GET['page'];

        $res = $this->f_show_root_insert_base($in_page);
        return $res;
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
    function f_show_root_insert_base
    (
        $in_page,
        $in_result_color = 'black',
        $in_result_msg = ''
    )
    {
        $continue_operation = '';
        if ($_POST[$this->m_name.'_continue'] == 'on')
        {
            $continue_operation = 'checked = "checked"';
        }

        //-------
        // Получаем последние 10 объектов

        // получаем заголовки таблицы
        $k_table_objects_headers = count($this->m_fields) + 1;  // +id
        $str_table_objects_headers = '';
        // id
        $str_table_objects_headers .= '<td>ID</td>';
        foreach ($this->m_fields as $field)
        {
            $str_table_objects_headers .= '<td>'.$field->m_caption.'</td>';
        }


        // Получаем строки таблицы
        //$query = "SELECT * FROM $this->m_name;";
        $query = $this->f_get_select_for_form();
        $q_res = mysql_query($query) or die('query='.$query.'; Err='.mysql_error());

        //$number = mysql_num_rows($q_res);

        // формируем теги tr
        $str_rows = '';
        while ($row = mysql_fetch_array($q_res))
        {
            $bgcolor = '#FFFFFF';
            if ($row['t_equipment_state_a_name'] == 'Исправен') $bgcolor = '#c9ccc4'; //'#d2f5b0';
            else if ($row['t_equipment_state_a_name'] == 'Не исправен') $bgcolor = '#fcbdbd';
            else if ($row['t_equipment_state_a_name'] == 'Ремонт') $bgcolor = '#fff494';


            $str_rows .= '<tr border="1" bgcolor="'.$bgcolor.'" valign="top">';

            // id
            $str_rows .= '<td>'.$row['id'].'</td>';

            // цикл по колонкам
            foreach ($this->m_fields as $field)
            {
                //$str_rows .= '<td>'.$row[$field->m_name].'</td>';
                $str_rows .= '<td>'.$row[$field->f_get_name_for_select_column()].'</td>';
            }

            $str_rows .= '</tr>';
        }



        // вывод
        $res = '
        <div align="center">
            <form name="'.$this->m_name.'" method="post" enctype="multipart/form-data" action="plugin.php?page='.$in_page.'&cmd=insert_result">
                <div id="'.$this->m_name.'_msg" align="center" style="color:'.$in_result_color.'">'.$in_result_msg.'</div>
                <table class="width90" cellspacing="1">
                <tbody>
                    <tr>
                        <td colspan="2">
                            '.$this->f_show().'
                        </td>
                    </tr>
                    <tr class="row-1">
                        <td class="category">
                            Продолжить создание объектов
                        </td>
                        <td>
                            <label><input tabindex="10" id="'.$this->m_name.'_continue" name="'.$this->m_name.'_continue" type="checkbox" '.$continue_operation.'> отметьте, если собираетесь создавать несколько объектов</label>
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

            <br>

            <form name="'.$this->m_name.'_last_inserted" method="get" action="">
            <table id="'.$this->m_name.'_obj_list" class="width100" cellspacing="1">
            <tbody>
            <tr>
                <td class="form-title" colspan="'.$k_table_objects_headers.'">
                    <span class="floatleft">
                        Список объектов (1 - 3 / 3)
                    </span>
                </td>
            </tr>
            <tr class="row-category">
                '.$str_table_objects_headers.'
            </tr>

            <tr class="spacer">
                <td colspan="'.$k_table_objects_headers.'"></td>
            </tr>
            '.$str_rows.'
            </tbody></table>
            </form>

        </div>
        ';

        return $res;
    }

    //--------------------------------------------------------------------------
    //
    function f_show_root_insert_result()
    {
        $result_msg = 'Операция выполнена успешно!';
        $result_color = "green";
        $res = '';


        $in_page = $_GET['page'];

        $is_required = $this->f_test_reqired_fields();

        if ($is_required['result'] == false)
        {
            $result_msg = $is_required['msg_err'];
            $result_color = "red";

            $res = $this->f_show_root_insert_base($in_page, $result_color, $result_msg);
        }
        else
        {
            // Формируем запрос
            $values_array = array();
            $error_str = $this->f_get_values_array_for_sql($values_array);

            if ($error_str != '')
            {
                // ошибка формата данных контрола
                $result_msg = $error_str;
                $result_color = "red";

                $res = $this->f_show_root_insert_base($in_page, $result_color, $result_msg);
            }
            else
            {
                $query = $this->f_get_sql_insert($values_array);

                // Выполняем запрос
                $result_msg .= '<br>req='.$query;
                $q_res = mysql_query($query) or die('query='.$query.'; Err='.mysql_error());

                // Поучаем ID созданного объекта
                $last_id = mysql_insert_id();

                $result_msg .= '<br>ID созданного объекта: <span style="font-size:large">'.$last_id.'</span>';

                if ($_POST[$this->m_name.'_continue'] == 'on')
                {
                    $res = $this->f_show_root_insert_base($in_page, $result_color, $result_msg);
                }
                else
                {
                    $res = '
                    <div align="center">
                        <form name="'.$this->m_name.'" method="post" enctype="multipart/form-data" action="plugin.php?page='.$in_page.'&cmd=insert">
                            <div id="'.$this->m_name.'_msg" align="center" style="color:'.$result_color.'">'.$result_msg.'</div>
                            <div class="center">
                                <input tabindex="11" class="button" value="Создать еще один объект" type="submit">
                            </td>
                        </form>
                    </div>
                    ';
                }
            }
        }

        return $res;
    }

    //--------------------------------------------------------------------------
    // Проверяем POST переменные для полей
    // И формируем массив с sql оберткой значений переменных
    // который потом можно использовать для формирования полного запроса INSERT,SELECT,UPDATE...
    function f_get_values_array_for_sql
    (
        & $out_values_array
    )
    {
        $res = array();

        // Перебираем все поля формы
        // и получаем insert строку
        foreach ($this->m_fields as $field)
        {
            $error_str = $field->f_get_value_for_sql($out_values_array, $this);

            if ($error_str != '')
            {
                return $error_str;
            }
        }

        return '';
    }


    // Формируем полный инсерт для формы
    function f_get_sql_insert
    (
        & $in_values_array  // по ссылки чтоб не копировать лишний раз
    )
    {
        $str_names = '';
        $str_values = '';


        foreach ($in_values_array as $var => $value)
        {
            $str_names .= $var . ',';
            $str_values .= $value . ',';
        }

        // обрезаем последние запятые
        $str_names = substr($str_names, 0, strlen($str_names)-1);
        $str_values = substr($str_values, 0, strlen($str_values)-1);

        $res = 'INSERT INTO '.$this->m_name. ' ('.$str_names.') VALUES ('.$str_values.');';

        return $res;
    }

    //--------------------------------------------------------------------------
    // Формируем select для root-формы
    function f_get_select_for_form
    (
    )
    {
        // Перебираем все поля формы
        // и получаем select строку
        $str_select = '';
        $str_from = '';

        //id
        $str_select .= $this->m_name.'.id,';
        $str_from .= $this->m_name.' ';

        foreach ($this->m_fields as $field)
        {
            $field->f_get_str_for_sql_select($this->m_name, $str_select, $str_from);
        }

        // обрезаем последние запятые
        $str_select = substr($str_select, 0, strlen($str_select)-1);

        $ret = 'SELECT '.$str_select.' FROM '.$str_from.
                ' ORDER BY '.$this->m_name.'.id DESC;';

/*
        $ret = '
            SELECT `t_equipment`.`id`,
                `t_equipment`.`t_equipment_a_name`,
                `t_equipment`.`t_equipment_a_inv_num`,
                `t_equipment`.`t_equipment_a_zavod_num`,
                `t_equipment_state`.`t_equipment_state_a_name`,
                `t_equipment_geo_place`.`t_equipment_geo_place_a_name`,
                `t_equipment`.`link_org_owner`,
                `t_equipment_type`.`t_equipment_type_a_name`,
                `t_equipment`.`t_equipment_type_id_obj`
            FROM `t_equipment`
            INNER JOIN t_equipment_state
            ON t_equipment.t_equipment_state_id = t_equipment_state.id
            INNER JOIN t_equipment_geo_place
            ON t_equipment.t_equipment_geo_place_id = t_equipment_geo_place.id
            INNER JOIN t_equipment_type
            ON t_equipment.t_equipment_type_id = t_equipment_type.id;
        ';
*/
        //echo($ret);
        return $ret;
    }

    //--------------------------------------------------------------------------
    // Формируем select для объекта по id
    function f_get_select_for_id
    (
        $in_obj_id
    )
    {
        // Перебираем все поля формы
        // и получаем select строку
        $str_select = '';
        $str_from = '';

        //id
        $str_select .= $this->m_name.'.id,';
        $str_from .= $this->m_name.' ';

        foreach ($this->m_fields as $field)
        {
            $field->f_get_str_for_sql_select($this->m_name, $str_select, $str_from);
        }

        // обрезаем последние запятые
        $str_select = substr($str_select, 0, strlen($str_select)-1);

        $ret = 'SELECT '.$str_select.' FROM '.$str_from.
                ' WHERE '.$this->m_name.'.id='.$in_obj_id.';';

/*
        $ret = '
            SELECT `t_equipment`.`id`,
                `t_equipment`.`t_equipment_a_name`,
                `t_equipment`.`t_equipment_a_inv_num`,
                `t_equipment`.`t_equipment_a_zavod_num`,
                `t_equipment_state`.`t_equipment_state_a_name`,
                `t_equipment_geo_place`.`t_equipment_geo_place_a_name`,
                `t_equipment`.`link_org_owner`,
                `t_equipment_type`.`t_equipment_type_a_name`,
                `t_equipment`.`t_equipment_type_id_obj`
            FROM `t_equipment`
            INNER JOIN t_equipment_state
            ON t_equipment.t_equipment_state_id = t_equipment_state.id
            INNER JOIN t_equipment_geo_place
            ON t_equipment.t_equipment_geo_place_id = t_equipment_geo_place.id
            INNER JOIN t_equipment_type
            ON t_equipment.t_equipment_type_id = t_equipment_type.id;
        ';
*/
        //echo($ret);
        return $ret;
    }

    //--------------------------------------------------------------------------
    //
    function f_generate_auto_name
    (
        $in_root = true        //   Флаг корневого запуска, все остальные будут с false
    )
    {
        //return 'автосгенерированное наименование';
        $res = '';

        // перебираем все поля формы
        // получаем текстовые названия полей вложенных объектов
        foreach ($this->m_fields as $field)
        {
            // объекты - добавляем в наименование всегда
            if ($field->m_link_type == MField::c_link_type_OBJECT)
            {
                $post_val = $field->m_link_value->f_get_value_from_post($_POST[$field->m_name]);

                $form_name = 'MForm_' . $post_val[MLink_Object::c_get_value_from_post_TABLE_NAME];   // table_name
                $form_value = $post_val[MLink_Object::c_get_value_from_post_TABLE_CAPTION]; // table_caption

                $res .= $form_value.'|';

                $frm = eval('$frm_new = new '.$form_name.'; return $frm_new;'); // создаем класс по имени
                $frm->f_init();
                $res .= $frm->f_generate_auto_name(false);
            }
            else
            {
                // остальные поля - только если не в root режиме
                if ($in_root == false)
                {
                    if ($field->m_link_type == MField::c_link_type_NONE)
                    {
                        $res .= $_POST[$field->m_name]. '|';
                    }
                    else
                    if ($field->m_link_type == MField::c_link_type_LIST)
                    {
                        $post_val = $field->m_link_value->f_get_value_from_post($_POST[$field->m_name]);

                        $var_value = $post_val[MLink_List::c_get_value_from_post_VAR_VALUE]; // table_caption

                        $res .= $var_value.'|';
                    }
                }
            }
        }


        return $res;
    }

    //--------------------------------------------------------------------------
    //
    function f_show_update($in_id)
    {
        $res = '';
        $in_page = $_GET['page'];

        //-------
        // Проверяем существует ли объект
        $obj_id = intval($in_id);

        // Запрос
        $query = $this->f_get_select_for_id($obj_id);
        echo($query);
        $res .= $query;
        $q_res = mysql_query($query) or die('query='.$query.'; Err='.mysql_error());

        // сохраняем запрос в форму для последующей визуализации
        $this->m_select_data = mysql_fetch_array($q_res);

        //if ( mysql_num_rows($q_res) == 0)
        if ($this->m_select_data == false)
        {
            $result_color = 'red';
            $result_msg = "Объекта с $this->m_name ID = $obj_id не существует!";
            $res .= '
            <div id="'.$this->m_name.'_msg" align="center" style="color:'.$result_color.'">'.$result_msg.'</div>
            ';
        }
        else
        {
            $res .= $this->f_show();
        }

        return $res;
    }


    //--------------------------------------------------------------------------
    //
    function f_show_root_update()
    {
        $res = '';
        $in_page = $_GET['page'];

        //-------
        // Проверяем существует ли объект
        $obj_id = $_GET['obj_id'];
        $obj_id = intval($obj_id);

        // Запрос
        $query = $this->f_get_select_for_id($obj_id);
        echo($query);
        $res .= $query;
        $q_res = mysql_query($query) or die('query='.$query.'; Err='.mysql_error());

        // сохраняем запрос в форму для последующей визуализации
        $this->m_select_data = mysql_fetch_array($q_res);

        //if ( mysql_num_rows($q_res) == 0)
        if ($this->m_select_data == false)
        {
            $result_color = 'red';
            $result_msg = "Объекта с ID = $obj_id не существует!";
            $res .= '
            <div id="'.$this->m_name.'_msg" align="center" style="color:'.$result_color.'">'.$result_msg.'</div>
            ';
        }
        else
        {
            $res .= $this->f_show_root_update_base($in_page);
        }

        return $res;
    }

    //--------------------------------------------------------------------------
    //
    function f_show_root_update_base
    (
        $in_page,
        $in_result_color = 'black',
        $in_result_msg = ''
    )
    {


        //-------
        // Получаем последние 10 объектов

        // получаем заголовки таблицы
        $k_table_objects_headers = count($this->m_fields) + 1;  // +id
        $str_table_objects_headers = '';
        // id
        $str_table_objects_headers .= '<td>ID</td>';
        foreach ($this->m_fields as $field)
        {
            $str_table_objects_headers .= '<td>'.$field->m_caption.'</td>';
        }


        // Получаем строки таблицы
        //$query = "SELECT * FROM $this->m_name;";
        $query = $this->f_get_select_for_form();
        $q_res = mysql_query($query) or die('query='.$query.'; Err='.mysql_error());

        //$number = mysql_num_rows($q_res);

        // формируем теги tr
        $str_rows = '';
        while ($row = mysql_fetch_array($q_res))
        {
            $bgcolor = '#FFFFFF';
            if ($row['t_equipment_state_a_name'] == 'Исправен') $bgcolor = '#c9ccc4'; //'#d2f5b0';
            else if ($row['t_equipment_state_a_name'] == 'Не исправен') $bgcolor = '#fcbdbd';
            else if ($row['t_equipment_state_a_name'] == 'Ремонт') $bgcolor = '#fff494';


            $str_rows .= '<tr border="1" bgcolor="'.$bgcolor.'" valign="top">';

            // id
            $str_rows .= '<td>'.$row['id'].'</td>';

            // цикл по колонкам
            foreach ($this->m_fields as $field)
            {
                //$str_rows .= '<td>'.$row[$field->m_name].'</td>';
                $str_rows .= '<td>'.$row[$field->f_get_name_for_select_column()].'</td>';
            }

            $str_rows .= '</tr>';
        }



        // вывод
        $res = '
        <div align="center">
            <form name="'.$this->m_name.'" method="post" enctype="multipart/form-data" action="plugin.php?page='.$in_page.'&cmd=insert_result">
                <div id="'.$this->m_name.'_msg" align="center" style="color:'.$in_result_color.'">'.$in_result_msg.'</div>
                <table class="width90" cellspacing="1">
                <tbody>
                    <tr>
                        <td colspan="2">
                            '.$this->f_show().'
                        </td>
                    </tr>
                    <tr class="row-1">
                        <td class="category">
                            Продолжить создание объектов
                        </td>
                        <td>
                            <label><input tabindex="10" id="'.$this->m_name.'_continue" name="'.$this->m_name.'_continue" type="checkbox" '.$continue_operation.'> отметьте, если собираетесь создавать несколько объектов</label>
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

            <br>

            <form name="'.$this->m_name.'_last_inserted" method="get" action="">
            <table id="'.$this->m_name.'_obj_list" class="width100" cellspacing="1">
            <tbody>
            <tr>
                <td class="form-title" colspan="'.$k_table_objects_headers.'">
                    <span class="floatleft">
                        Список объектов (1 - 3 / 3)
                    </span>
                </td>
            </tr>
            <tr class="row-category">
                '.$str_table_objects_headers.'
            </tr>

            <tr class="spacer">
                <td colspan="'.$k_table_objects_headers.'"></td>
            </tr>
            '.$str_rows.'
            </tbody></table>
            </form>

        </div>
        ';

        return $res;
    }

}

//==============================================================================
//
//==============================================================================
class MForm_t_equipment extends MForm
{
    function f_init()
    {
        $this->m_name = "t_equipment";
        $this->m_caption = "Введите данные оборудования";
        $this->m_select_data = $in_parent_select_data;

        $f = new MField;
        $f->m_name = 't_equipment_a_name';
        $f->m_caption = 'Наименование';
        //$f->m_default_value = '--- введите имя объекта ---';
        $f->m_required = false;
        $f->m_type = MField::c_type_STRING;
        $f->m_link_type = MField::c_link_type_AUTONAME;
        $f->m_link_value = '';
        $f->m_parent_form = $this;
        $this->m_fields[] = $f;

        $f = new MField;
        $f->m_name = 't_equipment_a_inv_num';
        $f->m_caption = 'Инвентарный номер';
        $f->m_type = MField::c_type_STRING;
        $f->m_link_type = MField::c_link_type_NONE;
        $f->m_parent_form = $this;
        $this->m_fields[] = $f;

        $f = new MField;
        $f->m_name = 't_equipment_a_zavod_num';
        $f->m_caption = 'Заводской номер';
        $f->m_type = MField::c_type_STRING;
        $f->m_link_type = MField::c_link_type_NONE;
        $f->m_parent_form = $this;
        $this->m_fields[] = $f;

        $f = new MField;
        $f->m_name = 't_equipment_state_id';
        $f->m_caption = 'Состояние оборудования';
        $f->m_default_value = 0;
        $f->m_required = true;
        $f->m_type = MField::c_type_INT;
        $f->m_link_type = MField::c_link_type_LIST;
        $list = new MLink_List;
        $list->list_table_name = 't_equipment_state';
        $list->list_key_name = 'id';
        $list->list_value_name = 't_equipment_state_a_name';
        $f->m_link_value = $list;
        $f->m_parent_form = $this;
        $this->m_fields[] = $f;

        $f = new MField;
        $f->m_name = 't_equipment_geo_place_id';
        $f->m_caption = 'Текущее месторасположение';
        $f->m_default_value = 0;
        $f->m_required = true;
        $f->m_type = MField::c_type_INT;
        $f->m_link_type = MField::c_link_type_LIST;
        $list = new MLink_List;
        $list->list_table_name = 't_equipment_geo_place';
        $list->list_key_name = 'id';
        $list->list_value_name = 't_equipment_geo_place_a_name';
        $f->m_link_value = $list;
        $f->m_parent_form = $this;
        $this->m_fields[] = $f;

        $f = new MField;
        $f->m_name = 't_equipment_type_id';
        $f->m_caption = 'Тип оборудования';
        $f->m_default_value = 0;
        $f->m_required = true;
        $f->m_type = MField::c_type_INT;
        $f->m_link_type = MField::c_link_type_OBJECT;
        $list = new MLink_Object;
        $list->list_table_name = 't_equipment_type';
        $list->list_key_name = 'id';
        $list->list_value_name = 't_equipment_type_a_name';
        $list->object_table_name = 't_equipment_type_link_t_name';
        $f->m_link_value = $list;
        $f->m_parent_form = $this;
        $this->m_fields[] = $f;
    }
}


//==============================================================================
//
//==============================================================================
class MForm_t_equipment_type_controller extends MForm
{
    function f_init()
    {
        $this->m_name = "t_equipment_type_controller";
        $this->m_caption = "Введите данные контроллера";

        $f = new MField;
        $f->m_name = 't_controller_type_id';
        $f->m_caption = 'Тип контроллера';
        $f->m_default_value = 0;
        $f->m_required = true;
        $f->m_type = MField::c_type_INT;
        $f->m_link_type = MField::c_link_type_LIST;
        $list = new MLink_List;
        $list->list_table_name = 't_controller_type';
        $list->list_key_name = 'id';
        $list->list_value_name = 't_controller_type_a_name';
        $f->m_link_value = $list;
        $f->m_parent_form = $this;
        $this->m_fields[] = $f;
    }
}

//==============================================================================
//
//==============================================================================
class MForm_t_equipment_type_pult extends MForm
{
    function f_init()
    {
        $this->m_name = "t_equipment_type_pult";
        $this->m_caption = "Введите данные пульта";

        $f = new MField;
        $f->m_name = 't_pult_type_id';
        $f->m_caption = 'Тип пульта';
        $f->m_default_value = 0;
        $f->m_required = true;
        $f->m_type = MField::c_type_INT;
        $f->m_link_type = MField::c_link_type_LIST;
        $list = new MLink_List;
        $list->list_table_name = 't_pult_type';
        $list->list_key_name = 'id';
        $list->list_value_name = 't_pult_type_a_name';
        $f->m_link_value = $list;
        $f->m_parent_form = $this;
        $this->m_fields[] = $f;
    }
}

//==============================================================================
//
//==============================================================================
class MForm_t_equipment_type_multipleksor extends MForm
{
    function f_init()
    {
        $this->m_name = "t_equipment_type_multipleksor";
        $this->m_caption = "Введите данные мультиплексора";

        $f = new MField;
        $f->m_name = 't_multipleksor_type_id';
        $f->m_caption = 'Тип мультиплексора';
        $f->m_default_value = 0;
        $f->m_required = true;
        $f->m_type = MField::c_type_INT;
        $f->m_link_type = MField::c_link_type_LIST;
        $list = new MLink_List;
        $list->list_table_name = 't_multipleksor_type';
        $list->list_key_name = 'id';
        $list->list_value_name = 't_multipleksor_type_a_name';
        $f->m_link_value = $list;
        $f->m_parent_form = $this;
        $this->m_fields[] = $f;
    }
}

?>