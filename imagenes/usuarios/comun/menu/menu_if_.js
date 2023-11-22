var clicked=""
var gtype=".png"
var selstate="_over"
if (typeof(loc)=="undefined" || loc==""){
	var loc=""
	if (document.body&&document.body.innerHTML){
		var tt = document.body.innerHTML.toLowerCase();
		var last = tt.indexOf("menu_if_.js\"");
		if (last>0){
			var first = tt.lastIndexOf("\"", last);
			if (first>0 && first<last) loc = document.body.innerHTML.substr(first+1,last-first-1);
		}
	}
}

document.write("<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr>");
document.write("<td><img src=\""+loc+"menu__top.png\" alt=\"\" width=\"119\" height=\"30\"></td>");
tr(false);
writeButton(loc+"","../../../../usuarios/comun.php?carpeta_entrar=mensajes","menu__b1",125,30,"Mensajes","contenido",0);
writeButton(loc+"","../../../../usuarios/comun.php?carpeta_entrar=solicitud_carpeta&filtro=ninguno","menu__b2",125,30,"Solc. Carpetas","contenido",0);
writeButton(loc+"","../../../../usuarios/comun.php?carpeta_entrar=reportes","menu__b3",125,30,"Reportes","contenido",0);
writeButton(loc+"","../../../../usuarios/comun.php?carpeta_entrar=ver_informe_legal&filtro=ninguno","menu__b4",125,30,"Ver Informe Legal","contenido",0);
writeButton(loc+"","../../../../usuarios/comun.php?carpeta_entrar=informe_legal&filtro=ninguno","menu__b5",125,30,"Informe Legal","contenido",0);
writeButton(loc+"","../../../../usuarios/comun.php?carpeta_entrar=excepciones","menu__b6",125,30,"Excepciones","contenido",0);
tr(true);
document.write("<td><img src=\""+loc+"menu__bottom.png\" alt=\"\" width=\"119\" height=\"30\"></td>");
document.write("</tr></table>")
loc="";

function tr(b){if (b) document.write("<tr>");else document.write("</tr>");}

function turn_over(name) {
	if (document.images != null && clicked != name) {
		document[name].src = document[name+"_over"].src;
	}
}

function turn_off(name) {
	if (document.images != null && clicked != name) {
		document[name].src = document[name+"_off"].src;
	}
}

function reg(gname,name)
{
if (document.images)
	{
	document[name+"_off"] = new Image();
	document[name+"_off"].src = loc+gname+gtype;
	document[name+"_over"] = new Image();
	document[name+"_over"].src = loc+gname+"_over"+gtype;
	}
}

function evs(name){ return " onmouseover=\"turn_over('"+ name + "')\" onmouseout=\"turn_off('"+ name + "')\""}

function writeButton(urld, url, name, w, h, alt, target, hsp)
{
	gname = name;
	while(typeof(document[name])!="undefined") name += "x";
	reg(gname, name);
	tr(true);
	document.write("<td>");
	if (alt != "") alt = " alt=\"" + alt + "\"";
	if (target != "") target = " target=\"" + target + "\"";
	if (w > 0) w = " width=\""+w+"\""; else w = "";
	if (h > 0) h = " height=\""+h+"\""; else h = "";	
	if (url != "") url = " href=\"" + urld + url + "\"";
	
	document.write("<a " + url + evs(name) + target + ">");	
	
	if (hsp == -1) hsp =" align=\"right\"";
	else if (hsp > 0) hsp = " hspace=\""+hsp+"\"";
	else hsp = "";
	
	document.write("<img src=\""+loc+gname+gtype+"\" name=\"" + name + "\"" + w + h + alt + hsp + " border=\"0\" /></a></td>");
	tr(false);
}
