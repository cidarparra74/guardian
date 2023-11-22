// ***** 
function validar(form) {

	// campos requeridos que no esten vacios
	
	if (!novacio(form.idtipoprecio,form.idtipoprecio.value.length,0,"Id. de tipo de precio, ")){return false;}
	if (!novacio(form.tipoprecio,form.tipoprecio.value.length,0,"la descripci\u00F3n, ")){return false;}
	if (!novacio(form.factor,form.factor.value.length,0,"el factor, ")){return false;}
	
	//  que no contengan valores invalidos
	if (form.factor.value!=''){	
		if (!numeros(form.factor)){return false;}
		
	}
	if (form.factor.value!=''){
		/*if((form.factor.value ==0) || (form.factor.value ==1) ){
			alert("Error, valores reservados [0] [1]");
			return false;
			}else if(form.factor.value>1){
				alert("Aviso, esta asignando un numero mayor que 1");				
			}else{
					if(form.factor.value>1){					
						alert("Error, no se puede asignar nros. negativos");
						return false;
					}
			}*/
		if(form.factor.value>1){
			alert("Aviso, esta asignando un numero mayor que 1");				
		}else{
			if(form.factor.value>1){					
				alert("Error, no se puede asignar nros. negativos");
				return false;
			}
		}
	}
}
