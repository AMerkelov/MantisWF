function getXmlHttp(){
  var xmlhttp;
  try {
    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (e) {
    try {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (E) {
      xmlhttp = false;
    }
  }
  if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
    xmlhttp = new XMLHttpRequest();
  }
  return xmlhttp;
}

//---
// runAjaxJS('f_ajax_show_hello', '&v1='+this.value+'&v2='+encodeURIComponent('СЂСѓСЃСЃРєР°СЏ СЃС‚СЂРѕРєР°'));

function runAjaxJS(self, funk_name, params, action_page)
{
alert('1');

	var xmlhttp = getXmlHttp();
	//xmlhttp.open('GET', '?page=CubeObjectPower/page_equipment_add.php&funk='+funk_name+params, true);
	//xmlhttp.open('GET', page_path+'&funk='+funk_name+params, true);
	
	if (action_page == undefined)
	{
		xmlhttp.open('GET', window.location+'&funk='+funk_name+params, true);
	}
	else
	{
		alert('1a_open='+action_page+'&funk='+funk_name+params);
		xmlhttp.open('GET', action_page+'&funk='+funk_name+params, true);
	}
	
	xmlhttp.onreadystatechange = function() {
	  if (xmlhttp.readyState == 4) {
		 if(xmlhttp.status == 200) {
alert('2=' + xmlhttp.responseText);
		   eval(xmlhttp.responseText);
alert('3');
			 }
	  }
	};
	xmlhttp.send(null);
}

function test1()
{
alert('hello');
}

function setInnerHtmlById(obj_id, html_str)
{
alert('11');
	var elem = document.getElementById(obj_id);
alert('12z='+elem);
	elem.innerHTML = html_str;
alert('13');
}

function getElemById(obj_id)
{
    return document.getElementById(obj_id);
}
