<tr class="row-1">
    <td class="category">
        Дополнительные кнопки
    </td>
    <td >
        <?php html_javascript_link( 'my_helper.js' ); ?>

        <script language="javascript">
            function txt_surround(in_begin, in_end)
            {
                var sel_text = getSelection(cur_textarea_for_edit);
                if (sel_text.length == 0)
                {
                    alert("Будьте внимательнее! Вы не выделили текст!!!");
                    return;
                }

                cur_textarea_for_edit.value = str_replace(sel_text, in_begin + sel_text + in_end, cur_textarea_for_edit.value);
            }
            function txt_file_to_url()
            {
                var sel_text = getSelection(cur_textarea_for_edit);
                if (sel_text.length == 0)
                {
                    alert("Будьте внимательнее! Вы не выделили текст!!!");
                    return;
                }

                var res_text = file_path_to_url(sel_text);
                cur_textarea_for_edit.value = str_replace(sel_text, res_text, cur_textarea_for_edit.value);
            }
        </script>


        <b><input type="button" name="format_bold_ssbutton" value="Ж" onclick="txt_surround('<b>', '</b>');" title="Жирный шрифт - Выделите текст и нажмите кнопку" /></b>
        <i><input type="button" name="format_italic_ssbutton" value="К" onclick="txt_surround('<i>', '</i>');" title="Курсивный шрифт - Выделите текст и нажмите кнопку" /></i>
        <u><input type="button" name="format_underline_ssbutton" value="П" onclick="txt_surround('<u>', '</u>');" title="Подчеркнутый шрифт - Выделите текст и нажмите кнопку" /></u>
        <input type="button" name="format_file_url__ssbutton" value="Преобразовать путь к файлу в ссылку" onclick="txt_file_to_url();" title="Выделите текст содержащий путь к файлу и нажмите кнопку" />
    </td>
</tr>