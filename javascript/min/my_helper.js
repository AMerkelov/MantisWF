// Флаги для определения браузеров
var uagent    = navigator.userAgent.toLowerCase();
var is_safari = ( (uagent.indexOf('safari') != -1) || (navigator.vendor == "Apple Computer, Inc.") );
var is_ie     = ( (uagent.indexOf('msie') != -1) && (!is_opera) && (!is_safari) && (!is_webtv) );
var is_ie4    = ( (is_ie) && (uagent.indexOf("msie 4.") != -1) );
var is_moz    = (navigator.product == 'Gecko');
var is_ns     = ( (uagent.indexOf('compatible') == -1) && (uagent.indexOf('mozilla') != -1) && (!is_opera) && (!is_webtv) && (!is_safari) );
var is_ns4    = ( (is_ns) && (parseInt(navigator.appVersion) == 4) );
var is_opera  = (uagent.indexOf('opera') != -1);  
var is_kon    = (uagent.indexOf('konqueror') != -1);
var is_webtv  = (uagent.indexOf('webtv') != -1);

var is_win    =  ( (uagent.indexOf("win") != -1) || (uagent.indexOf("16bit") !=- 1) );
var is_mac    = ( (uagent.indexOf("mac") != -1) || (navigator.vendor == "Apple Computer, Inc.") );
var ua_vers   = parseInt(navigator.appVersion);

// Сама функция
function getSelection( textarea )
{
    var selection = null;
    if ((ua_vers >= 4) && is_ie && is_win) {
        if (textarea.isTextEdit) {
            textarea.focus();
            var sel = document.selection;
            var rng = sel.createRange();
            rng.collapse;
            if((sel.type == "Text" || sel.type == "None") && rng != null)
                selection = rng.text;
        }
    } else if (typeof(textarea.selectionEnd) != "undefined" ) { 
        selection = (textarea.value).substring(textarea.selectionStart, textarea.selectionEnd);
    }
    return selection;
}


// str_replace("что заменяем", "чем заменяем", "исходная строка")
function str_replace(search, replace, subject) {
    return subject.split(search).join(replace);
} 

//
function file_path_to_url(path)
{
	var res = "";
	res = str_replace("\\", "/", path); 
	res = str_replace(" ", "%20", res); 
	res = "file:" + res;
	return res;
}