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

        $form_table = $get_val[MLink_Object::c_get_value_from_post_TABLE_NAME];   // table_name
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
        $cmd = $_GET['cmd'];

        // пустое значение
        if (($cmd == 'read' || $cmd == 'delete') && ($selected == ''))
        {
            // при readonly - не выводим
        }
        else
        {
            $str_res .= '<option value="-1"></option>';
        }

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

            if (($cmd == 'read' || $cmd == 'delete') && ($selected == ''))
            {
                // при readonly - не выводим
            }
            else
            {
                // value= [0]=id, [1]=значение
                $str_res .= '<option '.$selected.' value="'.$row[$this->list_key_name].','.$row[$this->list_value_name].'">'.$row[$this->list_value_name].'</option>';
            }
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
        $cmd = $_GET['cmd'];

        // пустое значение
        if (($cmd == 'read' || $cmd == 'delete') && ($selected == ''))
        {
            // при readonly - не выводим
        }
        else
        {
            $str_res .= '<option value="-1"></option>';
        }

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

            if (($cmd == 'read' || $cmd == 'delete') && ($selected == ''))
            {
                // при readonly - не выводим
            }
            else
            {
                // value="t_equipment_type_controller,1,Тип контроллера" - передаем сразу название таблицы, ее id и ее название
                // [0] - table_name
                // [1] - table_id
                // [2] - table_caption
                $str_res .= '<option '.$selected.' value="'.$row[$this->object_table_name].','.$row["id"].','.$row[$this->list_value_name].'">'.$row[$this->list_value_name].'</option>';
            }
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
// MLink_Ptr
//==============================================================================
class MLink_Ptr //extends MLink
{
    var $link_type_id;        // ID типа связи в t_link_type

    //--------------------------------------------------------------------------
    // Парсим пост и возвращаем массив
    // m[0]=table_name
    // m[1]=table_id
    // m[2] - table_caption
    const c_get_value_from_post_TABLE_NAME = 0;
    const c_get_value_from_post_OBJ_ID = 1;
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
    var $m_unique;         // требуется ли уникальность значений?

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
    const c_link_type_PTR = 4;      // связь с объектами
    var $m_link_type;

    // если link_type != NONE хранит объект описывающий связь MLink
    var $m_link_value;

    // конструктор
    function __construct()
    {
        $this->m_unique = false;
    }

    //--------------------------------------------------------------------------
    //
    function f_show($in_row_odd)
    {
        $ret = '';

        $cmd = $_GET['cmd'];

        $select_data = &$this->m_parent_form->m_select_data;

        $str_additional_row = '';

        $row_class = 'row-2';
        if ($in_row_odd)
        {
            $row_class = 'row-1';
        }

        $str_required = '';
        if ($this->m_required && ($cmd != 'search' && $cmd != 'search_result'))
        {
            $str_required = '<span class="required">*</span>';
        }

        // Если в режиме поиска, то проверяем так же и куки
        // и признак перехода на страницу $_GET['p']
        // для корректного перехода по страницам результатам поиска
        $cookie_val = null;
        if ((/*$cmd == 'search' ||*/ $cmd == 'search_result') && isset($_GET['p']))   // только для result - в просто search ничего не восстанавливаем
        {
            if (isset($_COOKIE[$this->m_name]))
            {
                $cookie_val = $_COOKIE[$this->m_name];
            }
        }

        $str_control = '';
        $str_value = '';
        if ($this->m_link_type == MField::c_link_type_NONE)
        {
            $my_readonly = '';
            if ($cmd == 'read' || $cmd == 'delete')
            {
                $my_readonly = 'class="cop_control_readonly" readonly';
            }

            if (empty($_POST[$this->m_name]))
            {
                if (empty($select_data[$this->m_name]))
                {
                    if (isset($cookie_val))
                    {
                        $str_value = $cookie_val;
                    }
                    else
                    {
                        $str_value = $this->m_default_value;
                    }
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

            $str_control = '<input name="'.$this->m_name.'" size="105" maxlength="128" value="'.$str_value.'" type="text" '.$my_readonly.'>';
        }
        else
        if ($this->m_link_type == MField::c_link_type_LIST)
        {
            $my_readonly = '';
            if ($cmd == 'read' || $cmd == 'delete')
            {
                $my_readonly = 'class="cop_control_readonly"';
            }

            if (empty($_POST[$this->m_name]))
            {
                if (empty($select_data[$this->m_name]))
                {
                    if (isset($cookie_val))
                    {
                        $str_value = $cookie_val;
                    }
                    else
                    {
                        $str_value = $this->m_default_value;
                    }
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
                <select name="'.$this->m_name.'" '.$my_readonly.'>
                    '.$this->m_link_value->f_show_list_from_table($str_value).'
                </select>
            ';
        }
        else
        if ($this->m_link_type == MField::c_link_type_OBJECT)
        {
            $my_readonly = '';
            if ($cmd == 'read' || $cmd == 'delete')
            {
                $my_readonly = 'class="cop_control_readonly"';
            }

            if (empty($_POST[$this->m_name]))
            {
                if (empty($select_data[$this->m_name]))
                {
                    if (isset($cookie_val))
                    {
                        $str_value = $cookie_val;
                    }
                    else
                    {
                        //$str_value = $this->m_default_value; нет значения по умолчанию!!!
                        unset($str_value);
                    }
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
                <select name="'.$this->m_name.'" onchange="on_select_'.$this->m_name.'(this)" '.$my_readonly.'>
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

            if (empty($select_data[$this->m_name]) && ($cmd != 'search' && $cmd != 'search_result'))
            {
                // не выводим для редактирования и просмотра (вывод - только в таблице и при поиске )
                $str_control = '';
            }
            else
            {
                $my_readonly = '';
                if ($cmd != 'search' && $cmd != 'search_result')
                {
                    $my_readonly = 'class="cop_control_readonly" readonly';
                }

                // Если есть AUTONAME - то выводим и ID объекта
                if ($cmd == 'search' || $cmd == 'search_result')
                {
                    $str_value = $_POST[$this->m_parent_form->m_name.'_id'];
                }
                else
                {
                    $str_value = $select_data['id'];    // id передается в селекте для autuname
                }
                $str_control = '<input name="'.$this->m_parent_form->m_name.'_id" size="105" maxlength="128" value="'.$str_value.'" type="text" '.$my_readonly.' >';

                // доп строка для ID
                $ret .= '
                        <tr class="'.$row_class.'">
                            <td class="category" width="30%">
                                OBJ_ID
                            </td>
                            <td width="70%">
                            '.$str_control.'
                            </td>
                        </tr>
                    ';

                //
                // autoname
                if ($cmd == 'search' || $cmd == 'search_result')
                {
                    if (empty($_POST[$this->m_name]) && isset($cookie_val))
                    {
                        $str_value = $cookie_val;
                    }
                    else
                    {
                        $str_value = $_POST[$this->m_name];
                    }
                }
                else
                {
                    $str_value = $select_data[$this->m_name];
                }
                $str_control = '<input name="'.$this->m_name.'" size="105" maxlength="128" value="'.$str_value.'" type="text" '.$my_readonly.' >';
            }
        }
        else
        if ($this->m_link_type == MField::c_link_type_PTR)
        {
            $my_readonly = '';
            if ($cmd == 'read' || $cmd == 'delete')
            {
                $my_readonly = 'class="cop_control_readonly"';
            }

            if (empty($_POST[$this->m_name]))
            {
                if (empty($select_data[$this->m_name]))
                {
                    if (isset($cookie_val))
                    {
                        $str_value = $cookie_val;
                    }
                    else
                    {
                        $str_value = $this->m_default_value;
                    }
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
            $linked_obj_id = $str_value;
            $linked_obj_table_name = '';
            $v2_name = $this->m_name.'_v2';
            if ($select_data[$v2_name] != '')
            {
                $linked_obj_table_name = $select_data[$v2_name];
            }


            // Врежиме чтения выводим просто ссылку
            if ($my_readonly != '')
            {
                $str_control = '
                    <a href="'.plugin_page("cop_obj_api").'&cmd=read&obj_id='.$linked_obj_id
                    .'&obj='.$linked_obj_table_name.'&dyn='.$_GET['dyn'].'&frm_caption='.$_GET['frm_caption'].'">'.
                    $linked_obj_id.'</a>
                    <input type="hidden" name="'.$v2_name.'" value="'.$linked_obj_table_name.'">
                ';
            }
            // в режиме редактирования - поле ввода
            else
            {
                $str_control = '<input name="'.$this->m_name.'" size="105" maxlength="128" value="'.$linked_obj_id.'" type="text" '.$my_readonly.'>'
                              ;//.'<input type="hidden" name="'.$v2_name.'" value="'.$linked_obj_table_name.'">';
            }
        }


        //
        if ($str_control == '')
        {
            return '';
        }

        // осговная строка для поля
        $ret .= '
                <tr class="'.$row_class.'">
                    <td class="category" width="30%">
                        '. $str_required . $this->m_caption.'
                    </td>
                    <td width="70%">
                    '.$str_control.'
                    </td>
                </tr>
                '.$str_additional_row.'
            ';

        return $ret;
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

                $is_required = $frm->f_test_required_fields();

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
    function f_test_unique()
    {
        if ($this->m_unique == false)
            return true;

        // Разрешаем добавление пустых полей (т.е. пустые поля - считаем успешно прошедшими проверку уникальности)
        if (empty($_POST[$this->m_name]))
            return true;

        $field_value = $_POST[$this->m_name];

        // Проверяем уникальность вложенного объекта
        if ($this->m_link_type == MField::c_link_type_OBJECT)
        {
            $post_val = $this->m_link_value->f_get_value_from_post($field_value);

            //$form_name = 'MForm_' . $_POST[$this->m_name];
            $form_name = 'MForm_' . $post_val[MLink_Object::c_get_value_from_post_TABLE_NAME];   // table_name

            $frm = eval('$frm_new = new '.$form_name.'; return $frm_new;'); // создаем класс по имени
            $frm->f_init();

            $is_unique = $frm->f_test_unicue_fields();

            if ($is_unique['result'] == false)
            {
                return $is_unique;
            }
        }
        else
        {
            // проверяем было ли изменение значения поля
            // если нет - проверять не будем (используется при update)
            if ($this->m_parent_form->m_select_data[$this->m_name] == $field_value)
            {
                return true;
            }
        }

        // поверяем уникальность поля в БД
        $sql_value = $this->f_wrap_value_to_sql($field_value);

        $query = 'SELECT id FROM '.$this->m_parent_form->m_name.
            ' WHERE '.$this->m_name.'='.$sql_value.' LIMIT 1;';
        //echo($query.'<br>');
        $q_res = mysql_query($query) or die('query='.$query.'; Err='.mysql_error());

        $number = mysql_num_rows($q_res);

        if ($number == 1)
        {
            // НЕ УНИКАЛЬНО - в БД уже есть запись с таким значением
            return false;
        }


        // ок - поле уникально
        return true;
    }


    //--------------------------------------------------------------------------
    //
    function f_wrap_value_to_sql
    (
        $in_value
    )
    {
        $res = false;

        switch ($this->m_type)
        {
            case MField::c_type_INT:
            {
                //if (is_int($val))
                if (eregi('^[0-9]{1,20}$',$in_value)) // число состоящее от 1 до 20 цифр
                {                                // TODO: Отрицательное значение???? !!!
                    $res = intval($in_value);
                }
                else
                {
                    $res = false;
                }

                break;
            }
            case MField::c_type_STRING:
            {
                // TODO: Экранирование слешей
                $val = str_replace("'", "''", $in_value);
                $res = "'".$val."'";

                break;
            }
        }

        //
        return $res;
    }

    //--------------------------------------------------------------------------
    //
    function f_get_value_for_sql
    (
        & $out_values_array,
        & $in_form  // & - для оптимизации без копирования
    )
    {
        $cmd = $_GET['cmd'];

        $val = $_POST[$this->m_name];
        $res = '';

        // если в режиме поиска, и не переход по страницам $_GET['p']
        // то сохраняем POST в куки
        if (($cmd == 'search' || $cmd == 'search_result'))
        {
            // если признака перехода на страницу нет - т.е. идет поиск
            // то сохраняем параметры поиска в куки
            if (empty($_GET['p']))
            {
                setcookie($this->m_name, $val);
            }
            // мы прсто переходим на странцу результата, поэтому восстанавливаем
            // параметры запроса из куков
            else
            {
                $val = $_COOKIE[$this->m_name];
            }
        }

        // Сохраняем значение для удобства
        $saved_val = $val;

        // Если значение поля - пустое
        if (empty($saved_val))
        {
            // для автополя нет необходимости в передаче значения $_POST[]
            if ($this->m_link_type != MField::c_link_type_AUTONAME)
            {
                // для остальных - если пусто то в insert запрос поле не попадает
                return;
            }
            else
            {
                // так же если автонейм в режиме search - то оно ПУСТОЕ тоже в запрос не попадает
                // а так как AUTONAME представляет так же и id объекта - то проверяем и его пустоту
                if (($cmd == 'search' || $cmd == 'search_result')
                    && empty($_POST[$this->m_parent_form->m_name.'_id']) )
                {
                    return;
                }
            }
        }

        //-----
        //
        switch ($this->m_type)
        {
            case MField::c_type_INT:
                // Если объект - то получаем его id его таблицы в таблице-списке типов данного объекта
                if ($this->m_link_type == MField::c_link_type_OBJECT)
                {
                    $post_val = $this->m_link_value->f_get_value_from_post($saved_val);

                    if ($post_val[0] == '-1')
                    {
                        $val = null;
                    }
                    else
                    {
                        $val = $post_val[MLink_Object::c_get_value_from_post_TABLE_ID];    // table_id
                    }
                }
                else
                if ($this->m_link_type == MField::c_link_type_LIST)
                {
                    $post_val = $this->m_link_value->f_get_value_from_post($saved_val);

                    if ($post_val[0] == '-1')
                    {
                        $val = null;
                    }
                    else
                    {
                        $val = $post_val[MLink_List::c_get_value_from_post_VAR_ID];    // var_id
                    }
                }

                // В режиме поиска пустое значение списка в запрос не добавляем
                if (($val == null) && ($cmd == 'search' || $cmd == 'search_result'))
                {
                    return '';
                }

                //
                $res = $this->f_wrap_value_to_sql($val);
                if ($res == false)
                {
                    return ('Не верно задан параметр: '.$this->m_caption);
                }

//                if (eregi('^[0-9]{1,20}$',$val)) // число состоящее от 1 до 20 цифр
//                {
//                    $res = intval($val);
//                }
//                else
//                {
//                    return ('Не верно задан параметр: '.$this->m_caption);
//                }
            break;
            case MField::c_type_STRING:
                // Если автонейм - то формируем строку по вложенным объектам
                if (($this->m_link_type == MField::c_link_type_AUTONAME)
                    && $cmd != 'search' && $cmd != 'search_result')
                {
                    // рекурсивно генерим $val
                    $val = $in_form->f_generate_auto_name();
                }


                // если autoname
                // из поста так же берем доп поле 'id'
                if (($this->m_link_type == MField::c_link_type_AUTONAME)
                    && ($cmd == 'search' || $cmd == 'search_result'))
                {
                    $frm_field_name = $this->m_parent_form->m_name.'_id';

                    if ($_POST[$frm_field_name] != '')
                    {
                        $res2 = intval($_POST[$frm_field_name]);
                        $out_values_array[$this->m_parent_form->m_name.'.id'] = $res2;
                    }

                    // если поиск и autoname - пустое то, не добавляем его в $out_values_array[]!!!
                    if ($val == '')
                    {
                        return;
                    }
                }

                // поле autoname
                //$val = str_replace("'", "''", $val);
                //$res = "'".$val."'";

                $res = $this->f_wrap_value_to_sql($val);

            break;
        }

        // сохраняем поле
        $out_values_array[$this->m_name] = $res;


        // вложенные объекты
        $error_str = '';
        if ($this->m_link_type == MField::c_link_type_OBJECT)
        {
            $post_val = $this->m_link_value->f_get_value_from_post($saved_val);

            $table_name = $post_val[MLink_Object::c_get_value_from_post_TABLE_NAME]; // table_name
            $form_name = 'MForm_' . $table_name;

            // создаем подформу по table_name
            $frm = eval('$frm_new = new '.$form_name.'; return $frm_new;'); // создаем класс по имени
            $frm->f_init();

            $out_values_array2 = array();

            // вызываем рекурсию
            $error_str = $frm->f_get_values_array_for_sql($out_values_array2);

            // если в sql ошибок нет - выполняем запрос и создаем вложенный объект
            // получаем id созданного подобъекта и передаем его в наш текущий запрос
            if ($error_str == '')
            {
                // если в режиме update
              if (isset($cmd))
              {
                if ($cmd == 'update_result')
                {
                    // обновляемый объект
                    $exist_obj_id = $this->m_parent_form->m_select_data[$this->m_name]; // AS key_name  $this->m_link_value->list_key_name

                    // проверяем изменение названия таблицы (типа объекта)
                    // в POST относительно БД
                    // если разные - то удаляем старый и insertim новый
                    $table_name2 = $this->m_parent_form->m_select_data[$this->m_link_value->object_table_name];
                    if ($table_name != $table_name2)
                    {
                        //---
                        // удаляем старый подобъект
                        $sub_obj_id = $this->m_parent_form->m_select_data[$this->m_name.'_obj'];

                        $form_name_old = 'MForm_' . $table_name2;
                        $frm_old = eval('$frm_new = new '.$form_name_old.'; return $frm_new;'); // создаем класс по имени
                        $frm_old->f_init();

                        $query = $frm_old->f_get_sql_delete_for_id($out_values_array2, $sub_obj_id);

                        $q_res = mysql_query($query) or die('query='.$query.'; Err='.mysql_error());

                        //---
                        // инсертим новый
                        $query = $frm->f_get_sql_insert($out_values_array2);

                        // выполняем запрос
                        $q_res = mysql_query($query) or die('query='.$query.'; Err='.mysql_error());

                        // получаем id созданного объекта
                        $last_id = mysql_insert_id();
                        //$last_id = 7777;

                        // формируем поле связанное со вложенным объектом
                        $out_values_array[$this->m_name . '_obj'] = $last_id;
                    }
                    // тип объекта не изменился - просто апдейтим его
                    // по его id в БД
                    else
                    {
                        //
                        $query = $frm->f_get_sql_update_for_id($out_values_array2, $exist_obj_id);

                        // выполняем запрос
                        $q_res = mysql_query($query) or die('query='.$query.'; Err='.mysql_error());

                        // НЕ МЕНЯЕМ поле связанное со вложенным объектом
                        //$out_values_array[$this->m_name . '_obj'] = $exist_obj_id;
                    }

                }
                // режим простого insert
                else
                if ($cmd == 'insert_result')
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
                else
                if ($cmd == 'delete_result')
                {
                    // удаляемый подобъект
                    $sub_obj_id = $this->m_parent_form->m_select_data[$this->m_name.'_obj'];

                    //
                    $query = $frm->f_get_sql_delete_for_id($out_values_array2, $sub_obj_id);

                    // выполняем запрос
                    $q_res = mysql_query($query) or die('query='.$query.'; Err='.mysql_error());
                }
                else
                if ($cmd == 'search_result')
                {
                    // объединяем массивы
                    $out_values_array = array_merge($out_values_array, $out_values_array2);
                }
              }
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
            // id таблицы,  имя поля
            $out_str_select .= $in_table_name.'.id,'
                                .$in_table_name.'.'.$this->m_name.','; //' AS '.$in_table_name.'_'.$this->m_name.',';

            //$out_str_from .= '';
        }
        if($this->m_link_type == MField::c_link_type_PTR)
        {
            $obj_field = '';

            if ($this->m_link_value->obj_num == 1)
            {
                $obj_field = 't_link_obj1_id';
            }
            else
            {
                $obj_field = 't_link_obj2_id';
            }

            $out_str_select .= 't_link.id,'
                                .'t_link.'.$obj_field.' AS '.$this->m_name.',';
                                                    !!!!!!!!!!!!!!!!!!!!!!!!!
            $out_str_from .= ' INNER JOIN '.'t_link'.
                ' ON '.$in_table_name.'.'.$this->m_name.'='. $this->m_link_value->list_table_name.'.'.$this->m_link_value->list_key_name;

//            INNER JOIN t_equipment_state
//            ON t_equipment.t_equipment_state_id = t_equipment_state.id
        }
    }

    //--------------------------------------------------------------------------
    // добавлена рекурсия для объекта
    function f_get_str_for_sql_search
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

            //---------
            // Рекурсия
            $post_val = $this->m_link_value->f_get_value_from_post($_POST[$this->m_name]);
            if (($post_val[0] != '') && ($post_val[0] != -1))
            {
                $obj_table_name = $post_val[MLink_Object::c_get_value_from_post_TABLE_NAME];

                $out_str_from .= ' INNER JOIN '.$obj_table_name.
                    ' ON '.$in_table_name.'.'.$this->m_name.'_obj='. $obj_table_name.'.id';

                $form_name = 'MForm_' . $obj_table_name;   // table_name

                $frm = eval('$frm_new = new '.$form_name.'; return $frm_new;'); // создаем класс по имени
                $frm->f_init();

                $frm->f_get_str_for_sql_search($out_str_select, $out_str_from);
            }

        }
        else
        if ($this->m_link_type == MField::c_link_type_AUTONAME)
        {
            // id таблицы,  имя поля
            $out_str_select .= $in_table_name.'.id,'
                                .$in_table_name.'.'.$this->m_name.','; //' AS '.$in_table_name.'_'.$this->m_name.',';

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
        else
        if ($this->m_link_type == MField::c_link_type_PTR)
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


    // конструктор
    function __construct()
    {
    }

    // Переопределяется в наследниках
    function f_init()
    {
    }

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

        if ($in_cmd == 'read')
        {
            return $this->f_show_root_read();
        }
        else
        if ($in_cmd == 'search')
        {
            return $this->f_show_root_search();
        }
        else
        if ($in_cmd == 'search_result')
        {
            return $this->f_show_root_search_result();
        }
        else
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
        else
        if ($in_cmd == 'update_next')
        {
            return $this->f_show_root_update_next();
        }
        else
        if ($in_cmd == 'delete')
        {
            return $this->f_show_root_delete();
        }
        else
        if ($in_cmd == 'delete_result')
        {
            return $this->f_show_root_delete_result();
        }
        else
        if ($in_cmd == 'delete_next')
        {
            return $this->f_show_root_delete_next();
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
    function f_test_required_fields()
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
                    $res['msg_err'] = 'Не заполнено поле "'.$field->m_caption.'"!';
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
    function f_test_unique_fields()
    {
        $res = array();

        // проверяем заполненность всех обязательных полей
        foreach ($this->m_fields as $field)
        {
            $test = $field->f_test_unique();

            if (is_array($test))
            {
                return $test;
            }
            else
            {
                if ($test == false)
                {
                    $res['result'] = false;
                    $res['msg_err'] = 'В БД уже есть объект с таким же значением поля "'.$field->m_caption.'"!';
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

        // Получаем последние 10 объектов
        $str_grid_last_edited_objects = $this->f_show_grid_last_edited();


        // вывод
        $res = '
        <div align="center">
            <form name="'.$this->m_name.'" method="post" enctype="multipart/form-data" action="plugin.php?page='.$in_page.'&obj='.$this->m_name.'&cmd=insert_result&dyn='.$_GET['dyn'].'&frm_caption='.$_GET['frm_caption'].'">
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

            '.$str_grid_last_edited_objects.'
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


        do
        {
            // проверка простого перехода на другую страницу
            // в гриде последних объектов
            if (isset($_GET['p']))
            {
                $res = $this->f_show_root_insert_base($in_page);
                break;
            }

            $is_required = $this->f_test_required_fields();
            if ($is_required['result'] == false)
            {
                $result_msg = $is_required['msg_err'];
                $result_color = "red";

                $res = $this->f_show_root_insert_base($in_page, $result_color, $result_msg);
                break;
            }

            $is_unique = $this->f_test_unique_fields();
            if ($is_unique['result'] == false)
            {
                $result_msg = $is_unique['msg_err'];
                $result_color = "red";

                $res = $this->f_show_root_insert_base($in_page, $result_color, $result_msg);
                break;
            }


            // Формируем запрос
            $values_array = array();
            $error_str = $this->f_get_values_array_for_sql($values_array);

            if ($error_str != '')
            {
                // ошибка формата данных контрола
                $result_msg = $error_str;
                $result_color = "red";

                $res = $this->f_show_root_insert_base($in_page, $result_color, $result_msg);
                break;
            }


            //
            $query = $this->f_get_sql_insert($values_array);

            // Выполняем запрос
            //$result_msg .= '<br>req='.$query;
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
                    <form name="'.$this->m_name.'" method="post" enctype="multipart/form-data" action="plugin.php?page='.$in_page.'&obj='.$this->m_name.'&cmd=insert&dyn='.$_GET['dyn'].'&frm_caption='.$_GET['frm_caption'].'">
                        <div id="'.$this->m_name.'_msg" align="center" style="color:'.$result_color.'">'.$result_msg.'</div>
                        <div class="center">
                            <input tabindex="11" class="button" value="Создать еще один объект" type="submit">
                        </td>
                    </form>
                </div>
                ';
            }

        }while(false);

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
        $in_afther_from = ''// для допавления произвольных WHERE ... ORDER BY ....
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

        //$ret = 'SELECT '.$str_select.' FROM '.$str_from.
        //        ' ORDER BY '.$this->m_name.'.id DESC;';

        $ret = 'SELECT '.$str_select.' FROM '.$str_from.' '.$in_afther_from; //.';';

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
    // Формируем запрос для поиска
    function f_get_str_for_sql_search
    (
        & $out_str_select,
        & $out_str_from
    )
    {
        foreach ($this->m_fields as $field)
        {
            $field->f_get_str_for_sql_search($this->m_name, $out_str_select, $out_str_from);
        }
    }

    //--------------------------------------------------------------------------
    // Формируем запрос для поиска
    function f_get_sql_search
    (
        & $in_values_array  // по ссылки чтоб не копировать лишний раз
    )
    {
        $str_vars = '';
        $str_values = '';

        // Перебираем все поля формы
        // и получаем select строку
        $str_select = '';
        $str_from = '';

        //id
        //$str_select .= $this->m_name.'.id,';  уже есть?
        //$str_select .= $this->m_name.'.id AS '.$this->m_name.'_id,'; Определяется автоматом если есть поле AUTONAME
        $str_from .= $this->m_name.' ';

        //
        $this->f_get_str_for_sql_search($str_select, $str_from);


        foreach ($in_values_array as $var => $value)
        {
            $str_vars = $var.',';
            $str_values .= $var.'='.$value.' AND ';
        }

        // обрезаем последние ANDзапятые
        $str_vars = substr($str_vars, 0, strlen($str_vars)-1);
        $str_values = substr($str_values, 0, strlen($str_values)-5);

        $str_select = substr($str_select, 0, strlen($str_select)-1);

        // id - всегда должен быть в запросе для возможности редактирования результат для списков (даже с дублированием при autoname)
        $id_field_name = $this->m_name . '.id,';

        // WHERE не пустой
        if ($str_values != '')
        {
            $res = 'SELECT '.$id_field_name . $str_select.' FROM '.$str_from.    // id - всегда должен быть в запросе для возможности редактирования результат для списков (даже с дублированием при autoname)
                ' WHERE '.$str_values;
        }
        // WHERE пустой
        else
        {
            $res = 'SELECT '.$id_field_name . $str_select.' FROM '.$str_from; // id - всегда должен быть в запросе для возможности редактирования результат для списков (даже с дублированием при autoname)
        }

//        echo($res);

        return $res;
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

        //$res .= $query;
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

        //$res .= $query;
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
        // Получаем последние 10 объектов
        $str_grid_last_edited_objects = $this->f_show_grid_last_edited();

        // вывод
        $res = '
        <div align="center">
            <form name="'.$this->m_name.'" method="post" enctype="multipart/form-data" action="plugin.php?page='.$in_page.'&cmd=update_result&obj_id='.$_GET['obj_id'].'&obj='.$this->m_name.'&dyn='.$_GET['dyn'].'&frm_caption='.$_GET['frm_caption'].'">
                <div id="'.$this->m_name.'_msg" align="center" style="color:'.$in_result_color.'">'.$in_result_msg.'</div>
                <table class="width90" cellspacing="1">
                <tbody>
                    <tr>
                        <td colspan="2">
                            '.$this->f_show().'
                        </td>
                    </tr>
                    <tr>
                        <td class="left">
                            <span class="required"> * Поле, обязательное для заполнения</span>
                        </td>
                        <td class="center">
                            <input tabindex="11" class="button" value="Изменить объект" type="submit">
                        </td>
                    </tr>
                </tbody>
                </table>
            </form>

            '.$str_grid_last_edited_objects.'
        </div>
        ';

        return $res;
    }

    //--------------------------------------------------------------------------
    //
    function f_show_root_update_result()
    {
        $result_msg = 'Операция выполнена успешно!';
        $result_color = "green";
        $res = '';

        $in_page = $_GET['page'];

        $obj_id = $_GET['obj_id'];
        $obj_id = intval($obj_id);

        // обработка
        do
        {
            // проверка простого перехода на другую страницу
            // в гриде последних объектов
            if (isset($_GET['p']))
            {
                $res = $this->f_show_root_update_base($in_page);
                break;
            }

            // проверяем обязательные поля
            $is_required = $this->f_test_required_fields();
            if ($is_required['result'] == false)
            {
                $result_msg = $is_required['msg_err'];
                $result_color = "red";

                $res = $this->f_show_root_update_base($in_page, $result_color, $result_msg);
                break;
            }

            //------
            // Получаем данные объекта из БД
            // для определения изменений в update
            // Запрос
            $query = $this->f_get_select_for_id($obj_id);

            //$res .= $query;
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

            // А вот теперь проверяем уникальность (с учетом полей которые изменились относительно БД)
            $is_unique = $this->f_test_unique_fields();
            if ($is_unique['result'] == false)
            {
                $result_msg = $is_unique['msg_err'];
                $result_color = "red";

                $res = $this->f_show_root_update_base($in_page, $result_color, $result_msg);
                break;
            }

            //------
            // Формируем запрос
            $values_array = array();
            $error_str = $this->f_get_values_array_for_sql($values_array);

            if ($error_str != '')
            {
                // ошибка формата данных контрола
                $result_msg = $error_str;
                $result_color = "red";

                $res = $this->f_show_root_update_base($in_page, $result_color, $result_msg);
                break;
            }

            //
            $query = $this->f_get_sql_update_for_id($values_array, $obj_id);


            // Выполняем запрос
            //$result_msg .= '<br>req='.$query;
            $q_res = mysql_query($query) or die('query='.$query.'; Err='.mysql_error());

            $result_msg .= '<br>ID измененного объекта: <span style="font-size:large">'.$obj_id.'</span>';

            $res = '
            <div align="center">
                <form name="'.$this->m_name.'" method="post" enctype="multipart/form-data" action="'.plugin_page("cop_obj_api.php").'&cmd=update_next&obj='.$this->m_name.'&dyn='.$_GET['dyn'].'&frm_caption='.$_GET['frm_caption'].'">
                    <div id="'.$this->m_name.'_msg" align="center" style="color:'.$result_color.'">'.$result_msg.'</div>
                    <div class="center">
                        <input tabindex="11" class="button" value="Изменить еще один объект" type="submit">
                    </td>
                </form>
            </div>
            ';

        }while(false);



        return $res;
    }

    //--------------------------------------------------------------------------
    //
    function f_show_root_update_next()
    {
        $ret = '
        <br>
        <div align="center">
        <form name="input_edit_object_id" method="get" enctype="multipart/form-data" action="plugin.php">
        <input type="hidden" name="page" value="CubeObjectPower/cop_obj_api.php">
        <input type="hidden" name="cmd" value="update">
        <input type="hidden" name="obj" value="'.$this->m_name.'">
        <input type="hidden" name="dyn" value="'.$_GET['dyn'].'">
        <input type="hidden" name="frm_caption" value="'.$_GET['frm_caption'].'">

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

        return $ret;
    }


    // Формируем полный апдейт для формы
    function f_get_sql_update_for_id
    (
        & $in_values_array,  // по ссылки чтоб не копировать лишний раз
        $in_obj_id
    )
    {

        $str_values = '';


        foreach ($in_values_array as $var => $value)
        {
            $str_values .= $var.'='.$value.',';
        }

        // обрезаем последние запятые
        $str_values = substr($str_values, 0, strlen($str_values)-1);

        $res = 'UPDATE '.$this->m_name. ' SET '.$str_values.
                ' WHERE '.$this->m_name.'.id='.$in_obj_id.';';

        return $res;
    }

    // Формируем полный апдейт для формы
    function f_get_sql_delete_for_id
    (
        & $in_values_array,  // по ссылки чтоб не копировать лишний раз
        $in_obj_id
    )
    {
        $res = 'DELETE FROM '.$this->m_name.
                ' WHERE '.$this->m_name.'.id='.$in_obj_id.';';

//        echo($res);
        return $res;
    }

    //--------------------------------------------------------------------------
    //
    function f_show_root_search()
    {
        $res = '';
        $in_page = $_GET['page'];

        $res .= $this->f_show_root_search_base($in_page);

        return $res;
    }


    //--------------------------------------------------------------------------
    //
    function f_show_root_search_base
    (
        $in_page,
        $in_result_color = 'black',
        $in_result_msg = '',
        $in_query = ''
    )
    {
        $str_grid_finded_objects = '';
        $result_color = $in_result_color;
        $result_msg = $in_result_msg;

        //-------
        if ($in_query == '')
        {
            // Получаем последние 10 объектов
            $str_grid_finded_objects = $this->f_show_grid_last_edited();
        }
        else
        {
            $k_finded_rows = 0;
            $str_grid_finded_objects = $this->f_show_grid_by_query($in_query, $k_finded_rows);

            if ($k_finded_rows == 0)
            {
                $result_color = 'red';
                $result_msg = "Не найдено ни одного объекта!";
            }
            else
            {
                $result_msg = sprintf($result_msg, $k_finded_rows);
            }
        }


        // вывод
        $res = '
        <div align="center">
            <form name="'.$this->m_name.'" method="post" enctype="multipart/form-data" action="plugin.php?page='.$in_page.'&obj='.$this->m_name.'&cmd=search_result&dyn='.$_GET['dyn'].'&frm_caption='.$_GET['frm_caption'].'">
                <div id="'.$this->m_name.'_msg" align="center" style="color:'.$result_color.'">'.$result_msg.'</div>
                <table class="width90" cellspacing="1">
                <tbody>
                    <tr>
                        <td colspan="2">
                            '.$this->f_show().'
                        </td>
                    </tr>
                    <tr>
                        <td class="left" width="30%">
                            <span class="required"> * Поле, обязательное для заполнения</span>
                        </td>
                        <td class="center">
                            <input tabindex="11" class="button" value="Поиск" type="submit">
                        </td>
                    </tr>
                </tbody>
                </table>
            </form>

            '.$str_grid_finded_objects.'

        </div>
        ';

        return $res;
    }

    //--------------------------------------------------------------------------
    //
    function f_show_root_search_result()
    {
        $result_msg = 'Найдено %d подходящих объектов!';
        $result_color = "green";
        $res = '';


        $in_page = $_GET['page'];


        // Формируем запрос
        $values_array = array();
        $error_str = $this->f_get_values_array_for_sql($values_array);

        if ($error_str != '')
        {
            // ошибка формата данных контрола
            $result_msg = $error_str;
            $result_color = "red";

            $res = $this->f_show_root_search_base($in_page, $result_color, $result_msg);
        }
        else
        {
            $query = $this->f_get_sql_search($values_array);

            $res .= $this->f_show_root_search_base($in_page, $result_color, $result_msg, $query);
        }


        return $res;
    }

    //--------------------------------------------------------------------------
    //
    function f_show_root_read()
    {
        $res = '';
        $in_page = $_GET['page'];

        //-------
        // Проверяем существует ли объект
        $obj_id = $_GET['obj_id'];
        $obj_id = intval($obj_id);

        // Запрос
        $query = $this->f_get_select_for_id($obj_id);

        //$res .= $query;
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
            $res .= $this->f_show_root_read_base($in_page);
        }

        return $res;
    }

    //--------------------------------------------------------------------------
    //
    function f_show_root_read_base
    (
        $in_page,
        $in_result_color = 'black',
        $in_result_msg = ''
    )
    {
        // Получаем последние 10 объектов
        $str_grid_last_edited_objects = $this->f_show_grid_last_edited();


        // вывод
        $res = '
        <div align="center">
            <form name="'.$this->m_name.'" method="post" enctype="multipart/form-data" action="plugin.php?page='.$in_page.'&cmd=insert&obj='.$this->m_name.'&dyn='.$_GET['dyn'].'&frm_caption='.$_GET['frm_caption'].'">
                <div id="'.$this->m_name.'_msg" align="center" style="color:'.$in_result_color.'">'.$in_result_msg.'</div>
                <table class="width90" cellspacing="1">
                <tbody>
                    <tr>
                        <td colspan="2">
                            '.$this->f_show().'
                        </td>
                    </tr>
                    <tr>
                        <td class="left">
                            <span class="required"> * Поле, обязательное для заполнения</span>
                        </td>
                        <td class="center">
                            <input class="button" value="Клонировать" type="submit">
                            <input class="button" value="Изменить" type="button" onclick="location.href=\'plugin.php?page='.$in_page.'&obj='.$this->m_name.'&cmd=update&obj_id='.$_GET['obj_id'].'&dyn='.$_GET['dyn'].'&frm_caption='.$_GET['frm_caption'].'\';">
                            <input class="button" value="Удалить" type="button" onclick="location.href=\'plugin.php?page='.$in_page.'&obj='.$this->m_name.'&cmd=delete&obj_id='.$_GET['obj_id'].'&dyn='.$_GET['dyn'].'&frm_caption='.$_GET['frm_caption'].'\';">
                        </td>
                    </tr>
                </tbody>
                </table>
            </form>

            '.$str_grid_last_edited_objects.'
        </div>
        ';

        return $res;
    }

    //--------------------------------------------------------------------------
    //
    function f_show_root_delete()
    {
        $res = '';
        $in_page = $_GET['page'];

        //-------
        // Проверяем существует ли объект
        $obj_id = $_GET['obj_id'];
        $obj_id = intval($obj_id);

        // Запрос
        $query = $this->f_get_select_for_id($obj_id);

        //$res .= $query;
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
            $res .= $this->f_show_root_delete_base($in_page);
        }

        return $res;
    }


    //--------------------------------------------------------------------------
    //
    function f_show_root_delete_base
    (
        $in_page,
        $in_result_color = 'black',
        $in_result_msg = ''
    )
    {
        // Получаем последние 10 объектов
        $str_grid_last_edited_objects = $this->f_show_grid_last_edited();

        // вывод
        $res = '
        <div align="center">
            <form name="'.$this->m_name.'" method="post" enctype="multipart/form-data" action="plugin.php?page='.$in_page.'&obj='.$this->m_name.'&cmd=delete_result&obj_id='.$_GET['obj_id'].'&dyn='.$_GET['dyn'].'&frm_caption='.$_GET['frm_caption'].'">
                <div id="'.$this->m_name.'_msg" align="center" style="color:'.$in_result_color.'">'.$in_result_msg.'</div>
                <table class="width90" cellspacing="1">
                <tbody>
                    <tr>
                        <td colspan="2">
                            '.$this->f_show().'
                        </td>
                    </tr>
                    <tr>
                        <td class="left">
                            <span class="required"> * Поле, обязательное для заполнения</span>
                        </td>
                        <td class="center">
                            <div style="color:red"><b>Вы действительно хотите удалить объект?</b></div>
                            <input class="button" value="Да" type="submit">
                            <input class="button" value="Нет" type="button" onclick="location.href=\'plugin.php?page='.$in_page.'&obj='.$this->m_name.'&cmd=read&obj_id='.$_GET['obj_id'].'&dyn='.$_GET['dyn'].'&frm_caption='.$_GET['frm_caption'].'\';">
                        </td>
                    </tr>
                </tbody>
                </table>
            </form>

            '.$str_grid_last_edited_objects.'
         </div>
        ';

        return $res;
    }


    //--------------------------------------------------------------------------
    //
    function f_show_root_delete_result()
    {
        $result_msg = 'Операция выполнена успешно!';
        $result_color = "green";
        $res = '';

        $in_page = $_GET['page'];

        $obj_id = $_GET['obj_id'];
        $obj_id = intval($obj_id);

        // проверяем заполненность необходимых полей
        //$is_required = $this->f_test_required_fields();

        // обработка
        do
        {
            // проверка простого перехода на другую страницу
            // в гриде последних объектов
            if (isset($_GET['p']))
            {
                $res = $this->f_show_root_delete_base($in_page);
                break;
            }

//            // проверяем обязательные поля
//            if ($is_required['result'] == false)
//            {
//                $result_msg = $is_required['msg_err'];
//                $result_color = "red";
//
//                $res = $this->f_show_root_update_base($in_page, $result_color, $result_msg);
//                break;
//            }


            //------
            // Получаем данные объекта из БД
            // для определения изменений в update
            // Запрос
            $query = $this->f_get_select_for_id($obj_id);

            //$res .= $query;
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

            //------
            // Формируем запрос и вызываем рекурсию!!
            $values_array = array();
            $error_str = $this->f_get_values_array_for_sql($values_array);

            if ($error_str != '')
            {
                // ошибка формата данных контрола
                $result_msg = $error_str;
                $result_color = "red";

                $res = $this->f_show_root_delete_base($in_page, $result_color, $result_msg);
                break;
            }

            // действие
            $query = $this->f_get_sql_delete_for_id($values_array, $obj_id);


            // Выполняем запрос
            //$result_msg .= '<br>req='.$query;
            $q_res = mysql_query($query) or die('query='.$query.'; Err='.mysql_error());

            $result_msg .= '<br>ID удаленного объекта: <span style="font-size:large">'.$obj_id.'</span>';

            $res = '
            <div align="center">
                <form name="'.$this->m_name.'" method="post" enctype="multipart/form-data" action="'.plugin_page("cop_obj_api.php").'&cmd=delete_next&obj='.$this->m_name.'&dyn='.$_GET['dyn'].'&frm_caption='.$_GET['frm_caption'].'">
                    <div id="'.$this->m_name.'_msg" align="center" style="color:'.$result_color.'">'.$result_msg.'</div>
                    <div class="center">
                        <input tabindex="11" class="button" value="Удалить еще один объект" type="submit">
                    </td>
                </form>
            </div>
            ';

        }while(false);



        return $res;
    }

    //--------------------------------------------------------------------------
    //
    function f_show_root_delete_next()
    {
        $ret = '
        <br>
        <div align="center">
        <form name="input_delete_object_id" method="get" enctype="multipart/form-data" action="plugin.php">
        <input type="hidden" name="page" value="CubeObjectPower/cop_obj_api.php">
        <input type="hidden" name="cmd" value="delete">
        <input type="hidden" name="obj" value="'.$this->m_name.'">

        <table class="width90" cellspacing="1">
            <tbody>
            <tr>
                <td class="form-title" colspan="2">
                    Введите ID наклейки
                </td>
            </tr>
            <tr class="row-1">
                <td class="category" width="30%">
                    <span class="required">*</span>ID объекта
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
                    <input tabindex="2" class="button" value="Удалить объект" type="submit">
                </td>
            </tr>
        </tbody></table>
        </form>
        </div>
        ';

        return $ret;
    }

    //--------------------------------------------------------------------------
    // Показываем грид с N последними измененными/созданными объектами
    // N - количество строк на странице
    function f_show_grid_last_edited()
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
        $str_where = ' ORDER BY '.$this->m_name.'.id DESC';
        $query = $this->f_get_select_for_form($str_where);
        //$q_res = mysql_query($query) or die('query='.$query.'; Err='.mysql_error());
        //echo ($query);

        //----
        //подключаем класс Paging
        require_once('cop_grid_paging_api.php');

        //создаем экземпляр класса Paging
        //в качестве параметра передаем ему указатель на соединение с MySQL
        $paging = new MPaging();

        //выполняем обычный запрос данных не заботясь
        //о разбивке на страницы через метод get_page объекта класса Paging
        $q_res = $paging->get_page( $query );




        //$number = mysql_num_rows($q_res);

        // формируем теги tr
        $str_rows = '';
        while ($row = mysql_fetch_array($q_res))
        {
            //$bgcolor = '#FFFFFF';
            $bgcolor = '#D8D8D8';
            if ($row['t_equipment_state_a_name'] == 'Исправен') $bgcolor = '#D8D8D8';//'#c9ccc4'; //'#d2f5b0';
            else if ($row['t_equipment_state_a_name'] == 'Не исправен') $bgcolor = '#fcbdbd';
            else if ($row['t_equipment_state_a_name'] == 'Ремонт') $bgcolor = '#fff494';


            $str_rows .= '<tr border="1" bgcolor="'.$bgcolor.'" valign="top">';

            // id
            $str_rows .= '<td><a href="'.plugin_page( 'cop_obj_api.php' ).'&cmd=read&obj_id='.$row['id']
                        .'&obj='.$this->m_name.'&dyn='.$_GET['dyn'].'&frm_caption='.$_GET['frm_caption'].'">'
                        .$row['id'].'</a></td>';

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
            <br>

            <form name="'.$this->m_name.'_last_changed" method="get" action="">
            <table id="'.$this->m_name.'_obj_list" class="width100" cellspacing="1">
            <tbody>
            <tr>
                <td class="form-title" colspan="'.$k_table_objects_headers.'">
                    <span class="floatleft">
                        Список недавно измененных объектов ('.$paging->get_result_text().')
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
            <tr>
                <td colspan="0" style="text-align:right">'.$paging->get_prev_page_link().'|'.$paging->get_page_links().'|'.$paging->get_next_page_link().'</td>
            </tr>
            </tbody></table>
            </form>

        </div>
        ';

        return $res;
    }

    //--------------------------------------------------------------------------
    // Показываем грид с результатом sql запроса
    function f_show_grid_by_query
    (
        & $in_query,            // запрос для отображения
        & $out_k_finded_rows    // количество ВСЕХ записей полученных в запросе
    )
    {
        //-------
        // Поехали

        // получаем заголовки таблицы
        $k_table_objects_headers = count($this->m_fields) + 1;  // +id
        $str_table_objects_headers = '';
        // id
        $str_table_objects_headers .= '<td>ID</td>';
        foreach ($this->m_fields as $field)
        {
            $str_table_objects_headers .= '<td>'.$field->m_caption.'</td>';
        }


        //----
        //подключаем класс Paging
        require_once('cop_grid_paging_api.php');

        //создаем экземпляр класса Paging
        //в качестве параметра передаем ему указатель на соединение с MySQL
        $paging = new MPaging();


        //---- Выполняем SQL запрос
        //выполняем обычный запрос данных не заботясь
        //о разбивке на страницы через метод get_page объекта класса Paging
        $q_res = $paging->get_page( $in_query );

        //$number = mysql_num_rows($q_res);
        $out_k_finded_rows = $paging->total_rows;

        // формируем теги tr
        $str_rows = '';
        while ($row = mysql_fetch_array($q_res))
        {
            //$bgcolor = '#FFFFFF';
            $bgcolor = '#D8D8D8';
            if ($row['t_equipment_state_a_name'] == 'Исправен') $bgcolor = '#D8D8D8';//'#c9ccc4'; //'#d2f5b0';
            else if ($row['t_equipment_state_a_name'] == 'Не исправен') $bgcolor = '#fcbdbd';
            else if ($row['t_equipment_state_a_name'] == 'Ремонт') $bgcolor = '#fff494';


            $str_rows .= '<tr border="1" bgcolor="'.$bgcolor.'" valign="top">';

            // id
            $str_rows .= '<td><a href="'.plugin_page( 'cop_obj_api.php' ).'&cmd=read&obj_id='.$row['id']
                        .'&obj='.$this->m_name.'&dyn='.$_GET['dyn'].'&frm_caption='.$_GET['frm_caption'].'">'
                        .$row['id'].'</a></td>';

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
            <br>

            <form name="'.$this->m_name.'_last_changed" method="get" action="">
            <table id="'.$this->m_name.'_obj_list" class="width100" cellspacing="1">
            <tbody>
            <tr>
                <td class="form-title" colspan="'.$k_table_objects_headers.'">
                    <span class="floatleft">
                        Список найденных объектов ('.$paging->get_result_text().')
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
            <tr>
                <td colspan="0" style="text-align:right">'.$paging->get_prev_page_link().'|'.$paging->get_page_links().'|'.$paging->get_next_page_link().'</td>
            </tr>
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
    function __construct()
    {
       parent::__construct();
       //...
    }

    function f_init()
    {
        $this->m_name = "t_equipment";
        //$this->m_caption = "Введите данные оборудования";
        $this->m_caption = "Оборудование";
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
        $f->m_unique = true;
        $f->m_type = MField::c_type_STRING;
        $f->m_link_type = MField::c_link_type_NONE;
        $f->m_parent_form = $this;
        $this->m_fields[] = $f;

        $f = new MField;
        $f->m_name = 't_equipment_a_zavod_num';
        $f->m_caption = 'Заводской номер';
        $f->m_unique = true;
        $f->m_type = MField::c_type_STRING;
        $f->m_link_type = MField::c_link_type_NONE;
        $f->m_parent_form = $this;
        $this->m_fields[] = $f;

        $f = new MField;
        $f->m_name = 't_equipment_manufacturer_id';
        $f->m_caption = 'Производитель оборудования';
        $f->m_default_value = 0;
        $f->m_required = true;
        $f->m_type = MField::c_type_INT;
        $f->m_link_type = MField::c_link_type_LIST;
        $list = new MLink_List;
        $list->list_table_name = 't_equipment_manufacturer';
        $list->list_key_name = 'id';
        $list->list_value_name = 't_equipment_manufacturer_a_name';
        $f->m_link_value = $list;
        $f->m_parent_form = $this;
        $this->m_fields[] = $f;

        $f = new MField;
        $f->m_name = 't_equipment_a_year_creation';
        $f->m_caption = 'Год изготовления';
        $f->m_type = MField::c_type_INT;
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
    function __construct()
    {
       parent::__construct();
       //...
    }

    function f_init()
    {
        $this->m_name = "t_equipment_type_controller";
        //$this->m_caption = "Введите данные контроллера";
        $this->m_caption = "Контроллер";

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

        $f = new MField;
        $f->m_name = 't_link_to_tool_id';
        $f->m_caption = 'Подключен к станку';
        $f->m_default_value = 0;
        //$f->m_required = true;
        $f->m_type = MField::c_type_INT;
        $f->m_link_type = MField::c_link_type_PTR;
        $list = new MLink_Ptr;
        $list->link_type_id = 1; // Контроллер-Станок
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
    function __construct()
    {
       parent::__construct();
       //...
    }

    function f_init()
    {
        $this->m_name = "t_equipment_type_pult";
        //$this->m_caption = "Введите данные пульта";
        $this->m_caption = "Пульт";

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
    function __construct()
    {
       parent::__construct();
       //...
    }

    function f_init()
    {
        $this->m_name = "t_equipment_type_multipleksor";
        //$this->m_caption = "Введите данные мультиплексора";
        $this->m_caption = "Мультиплексор";

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


//==============================================================================
//
//==============================================================================
class MForm_t_equipment_type_tool extends MForm
{
    function __construct()
    {
       parent::__construct();
       //...
    }

    function f_init()
    {
        $this->m_name = "t_equipment_type_tool";
        //$this->m_caption = "Введите данные станка";
        $this->m_caption = "Станок";

        $f = new MField;
        $f->m_name = 't_tool_type_id';
        $f->m_caption = 'Тип станка';
        $f->m_default_value = 0;
        $f->m_required = true;
        $f->m_type = MField::c_type_INT;
        $f->m_link_type = MField::c_link_type_LIST;
        $list = new MLink_List;
        $list->list_table_name = 't_tool_type';
        $list->list_key_name = 'id';
        $list->list_value_name = 't_tool_type_a_name';
        $f->m_link_value = $list;
        $f->m_parent_form = $this;
        $this->m_fields[] = $f;

        $f = new MField;
        $f->m_name = 't_tool_model_id';
        $f->m_caption = 'Модель станка';
        $f->m_default_value = 0;
        $f->m_required = true;
        $f->m_type = MField::c_type_INT;
        $f->m_link_type = MField::c_link_type_LIST;
        $list = new MLink_List;
        $list->list_table_name = 't_tool_model';
        $list->list_key_name = 'id';
        $list->list_value_name = 't_tool_model_a_name';
        $f->m_link_value = $list;
        $f->m_parent_form = $this;
        $this->m_fields[] = $f;

        $f = new MField;
        $f->m_name = 't_cnc_type_id';
        $f->m_caption = 'Тип ЧПУ';
        $f->m_default_value = 0;
        $f->m_required = true;
        $f->m_type = MField::c_type_INT;
        $f->m_link_type = MField::c_link_type_LIST;
        $list = new MLink_List;
        $list->list_table_name = 't_cnc_type';
        $list->list_key_name = 'id';
        $list->list_value_name = 't_cnc_type_a_name';
        $f->m_link_value = $list;
        $f->m_parent_form = $this;
        $this->m_fields[] = $f;
    }
}
?>