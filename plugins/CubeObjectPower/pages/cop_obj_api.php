<?php
    require_once( 'cop_page_api.php' );
    require_once( 'cop_forms_api.php' );

    //--------------------------------------------------------------------------
    $page_caption = '';

    $cmd = $_GET['cmd'];
    $obj = $_GET['obj'];    // имя таблицы объекта


    // Если существует параметр dyn=1 - динамическая форма
    // то создаем ащкту на лету
    $frm = null;
    if (isset($_GET['dyn']) && $_GET['dyn'] != '')
    {
        $frm_caption = $_GET['frm_caption'];

        // Динамически создаем класс
        $eval_str = '
            class MForm_'.$obj.' extends MForm
            {
                function __construct()
                {
                   parent::__construct();
                   //...
                }

                function f_init()
                {
                    //
                    $this->m_name = "'.$obj.'";

                    $this->m_caption = "'.$frm_caption.'";


                    $f = new MField;
                    $f->m_name = "'.$obj.'_a_name";
                    $f->m_caption = "Наименование";
                    $f->m_unique = true;
                    $f->m_type = MField::c_type_STRING;
                    $f->m_link_type = MField::c_link_type_NONE;
                    $f->m_parent_form = $this;
                    $this->m_fields[] = $f;
                }
            }
        ';

        eval($eval_str);

        $form_name = 'MForm_'.$obj;
        $frm = eval('$frm_new = new '.$form_name.'; return $frm_new;'); // создаем класс по имени
        $frm->f_init();
    }
    else
    {
        $form_name = 'MForm_' . $obj;
        $frm = eval('$frm_new = new '.$form_name.'; return $frm_new;'); // создаем класс по имени
        $frm->f_init();
    }

    if ($cmd == 'read')
    {
        $page_caption = 'Просмотр объекта "'.$frm->m_caption.'"';
    }
    else
    if (($cmd == 'insert') || ($cmd == 'insert_result'))
    {
        $page_caption = 'Создание объекта "'.$frm->m_caption.'"';
    }
    else
    if (($cmd == 'update') || ($cmd == 'update_result'))
    {
        $page_caption = 'Редактирование объекта "'.$frm->m_caption.'"';
    }
    else
    if (($cmd == 'search') || ($cmd == 'search_result'))
    {
        $page_caption = 'Поиск объекта "'.$frm->m_caption.'"';
    }
    else
    if (($cmd == 'delete') || ($cmd == 'delete_result'))
    {
        $page_caption = 'Удаление объекта "'.$frm->m_caption.'"';
    }

    print_page_top($page_caption, $page_caption . " - БД Оборудования");

    //print_r($_POST);
    //--------------------------------------------------------------------------

    echo($frm->f_show_root());

    //--------------------------------------------------------------------------
    print_page_bottom();
?>