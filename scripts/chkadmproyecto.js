function validar(form) {
	// VALIDACIONES
	
	// descripcion
	if (!novacio(form.txtdescripcion,form.txtdescripcion.value.length, 0," Descripci\u00F3n del proyecto, "))
		return false;
	
	// fecha inicio
	if (!novacio(form.txtf_inicio,form.txtf_inicio.value.length, 6," Fecha de Inicio, "))
		return false;

	// fecha final
	if (!novacio(form.txtf_final,form.txtf_final.value.length, 6," Fecha Final, "))
		return false;
	
	fec1 = form.txtf_inicio.value.split("-");
	fec2 = form.txtf_final.value.split("-");
		
	fechaini = new Date();
	fechaini.setFullYear(fec1[2], eval(fec1[1])-1, fec1[0]);
	fechafin = new Date();
	fechafin.setFullYear(fec2[2], eval(fec2[1])-1, fec2[0]);
	
	if (fechaini>fechafin) { alert("La fecha de inicio debe ser menor \u00F3 igual a la fecha final."); return false;}
}
