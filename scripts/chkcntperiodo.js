function validar(form) {
	
	// campos requeridos que no esten vacios
	if (!novacio(form.gestion,form.gestion.value.length,1,"un nombre para el periodo"))
		{return false;}
	
	//  que no contengan valores invalidos
	if (!alfanum(form.gestion)){return false;}
	
	if (!novacio(form.fechadesde,form.fechadesde.value.length,1,"una fecha inicial para el periodo"))
		{return false;}
	
	if (!novacio(form.fechahasta,form.fechahasta.value.length,1,"una fecha final para el periodo"))
		{return false;}
		
	// validar rango de fechas
	if (form.fechadesde.value >= form.fechahasta.value)
		{alert("La fecha inicial debe ser mayor a la fecha final."); return false;}
}