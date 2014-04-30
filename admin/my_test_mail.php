 <?php
if (mail("maa@osa.vaso.ru", "My Subject", "Line 1\nLine 2\nLine 3", "From:maaZZZ@osa.vaso.ru\r\n"))
//if (mail("acronis_mcis2@mcis.vaso.ru", "My Subject", "Line 1\nLine 2\nLine 3"))
{
	echo ('ok');
}
else
{
	echo ('err');
}

echo ('<br> t=' . time());

?> 