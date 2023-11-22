var clicked=""
var gtype=".png"
var selstate="_over"
if (typeof(loc)=="undefined" || loc==""){
	var loc=""
	if (document.body&&document.body.innerHTML){
		var tt = document.body.innerHTML.toLowerCase();
		var last = tt.indexOf("menu_.js\"");
		if (last>0){
			var first = tt.lastIndexOf("\"", last);
			if (first>0 && first<last) loc = document.body.innerHTML.substr(first+1,last-first-1);
		}
	}
}

document.write("<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr>");
document.write("<td><img src=\""+loc+"menu__top.png\" alt=\"\" width=\"151\" height=\"30\"></td>");
tr(false);
writeButton(loc+"","../../../administrador/asesores.php","menu__b1",151,30,"Asesores del Sistema","contenido",0);
writeButton(loc+"","../../../administrador/documentos.php","menu__b2",151,30,"Documentos del Sistema","contenido",0);
writeButton(loc+"","../../../administrador/grupos_documentos.php","menu__b3",151,30,"Grupos de Documentos","contenido",0);
writeButton(loc+"","../../../administrador/oficinas.php","menu__b4",151,30,"Oficinas del Sistema","contenido",0);
writeButton(loc+"","../../../administrador/responsables.php","menu__b5",151,30,"Responsables del Sistema","contenido",0);
writeButton(loc+"","../../../administrador/tipos_documentos.php","menu__b6",151,30,"Tipos de Documentos","contenido",0);
writeButton(loc+"","../../../administrador/usuarios.php","menu__b7",151,30,"Usuarios del Sistema","contenido",0);
writeButton(loc+"","../../../administrador/tipos_carpetas.php","menu__b8",151,30,"Tipos de Carpetas","contenido",0);
writeButton(loc+"","../../../administrador/tipos_bien.php","menu__b9",151,30,"Tipos de Bien","contenido",0);
writeButton(loc+"","../../../administrador/notarios.php","menu__b10",151,30,"Notarios","contenido",0);
writeButton(loc+"","../../../administrador/tramitadores.php","menu__b11",151,30,"Tramitadores","contenido",0);
writeButton(loc+"","../../../administrador/tipos_identificacion.php","menu__b12",151,30,"Tipos Identificacion","contenido",0);
writeButton(loc+"","../../../administrador/entidades.php","menu__b13",151,30,"Definir Entidades e Insituciones","contenido",0);tr(true);
document.write("<td><img src=\""+loc+"menu__bottom.png\" alt=\"\" width=\"151\" height=\"30\"></td>");
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
