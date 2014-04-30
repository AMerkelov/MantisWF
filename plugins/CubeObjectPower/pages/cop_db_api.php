<?php
    $cop_hostname = '192.168.18.10';
    $cop_db_type = 'mysql';
    $cop_database_name = 'equipment';
    $cop_db_username = 'mantis_db_user';
    $cop_db_password = '11111111';

    $db_connection = mysql_connect($cop_hostname, $cop_db_username, $cop_db_password);
    if (!$db_connection)
    {
        die("Ошибка соединения c сервером БД ($cop_hostname): " . mysql_error());
    }

    //echo "Успешно соединились c сервером БД:$cop_hostname<BR>";


    // выбираем $cop_database_name в качестве текущей базы данных
    $db_selected = mysql_select_db($cop_database_name, $db_connection);
    if (!$db_selected)
    {
        die ("Не удалось выбрать БД ($cop_database_name): " . mysql_error());
    }

    //echo "Успешно выбрали БД:$cop_database_name<BR>";

/*
 создать соединение
mysql_connect($hostname,$username,$password) OR DIE("Не могу создать соединение ");
 выбрать базу данных. Если произойдет ошибка - вывести ее
mysql_select_db($dbName) or die(mysql_error());

 составить запрос, который выберет всех клиентов - яблочников
$query = "SELECT * FROM $userstable WHERE choise = 'Яблоки'";
 Выполнить запрос. Если произойдет ошибка - вывести ее.
$res = mysql_query($query) or die(mysql_error());

 Как много нашлось таких
$number = mysql_num_rows($res);

 Напечатать всех в красивом виде
if ($number == 0) {
  echo "<CENTER><P>Любителей яблок нет</CENTER>";
} else {
  echo "<CENTER><P>Количество любителей яблок: $number<BR><BR>";
   Получать по одной строке из таблицы в массив $row, пока строки не кончатся
  while ($row=mysql_fetch_array($res)) {
    echo "Клиент ".$row['name']." любит Яблоки.<BR>";
    echo "Его Email: ".$row['email'];
    echo "<BR><BR>";
  }
  echo "</CENTER>";
}
*/


    //mysql_close($con);
?>