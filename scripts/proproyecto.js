function validar(formulario){
	//Defino una variable boleana, si es 0 es false y si es 1 es true
	var retorno = 1;
	var xsw = 0;
	var lin = '';
	var hbase = '00';
	var mbase = '00';
	var hi, mi, hf, mf, pr = '';
	var elementos = formulario.nlineas.value;
	var j = 0;
	for(i=1; i<=elementos; i++){
		 xsw = 0;
		//hora_i
		hi = formulario.elements[j++].value;
		//minu_i
		mi = formulario.elements[j++].value;
		//hora_f
		hf = formulario.elements[j++].value;
		//minu_f
		mf = formulario.elements[j++].value;
		//proy
		pr = formulario.elements[j++].value;
		lin = hi + mi + hf + mf + pr;
		if(hi=="") xsw = 1;
		if(mi=="") xsw = 1;
		if(hf=="") xsw = 1;
		if(mf=="") xsw = 1;
		
		if(xsw == 0){
			//vemos si selecciono el proyecto
			if(pr == ''){
				alert("Fila " + i + " incompleta! Falta seleccionar el proyecto.");
				return (false);
			}
			//comparamos si hay orden cronologico
			if(CompararHoras(hbase, mbase, hi, mi) == -1){
				alert("La fila " + i + " no tiene orden cronol\u00F3gico!");
				return (false);
			}else{
				hbase = hf;
				mbase = mf;
			}
			//comparamos qi en la fila las horas tienen relacion
			if(CompararHoras(hi, mi, hf, mf) != 1){
				alert("El rango de horas indicado en la fila " + i + " no tiene coherencia!");
				return (false);
			} 
		}else{
			if(lin != ''){
				alert("Fila " + i + " incompleta! Falta seleccionar alguna de las horas.");
				return (false);
			}
			
			
		}
	}
	 
	return (true);
	
} 


function CompararHoras(sHi, sMi, sHf, sMf) { 
     
    // Obtener horas y minutos (hora 1) 
    var hh1 = parseInt(sHi,10); 
    var mm1 = parseInt(sMi,10); 

    // Obtener horas y minutos (hora 2) 
    var hh2 = parseInt(sHf,10); 
    var mm2 = parseInt(sMf,10); 

    // Comparar 
    if (hh1<hh2 || (hh1==hh2 && mm1<mm2)) 
        return 1; //"sHora1 MENOR sHora2"
    else if (hh1>hh2 || (hh1==hh2 && mm1>mm2)) 
        return -1; //"sHora1 MAYOR sHora2"
    else  
        return 0; //"sHora1 IGUAL sHora2";
} 
