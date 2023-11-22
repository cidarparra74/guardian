/* *******************************************************
** C\u00F3digo JavaScript para editar los datos de una tabla **
** JavierB Enero 2007                                   **
*********************************************************/ 

var miTabla = 'tabla'; // poner aqu\u00ED el id de la tabla que queremos editar
 
// preparar la tabla para edici\u00F3n
function iniciarTabla() {
  tab = document.getElementById(miTabla);
  filas = tab.getElementsByTagName('tr');
  for (i=1; fil = filas[i]; i++) {
  	celdas = fil.getElementsByTagName('td');
    for (j=1; cel = celdas[j]; j++) {
      if ((i>0 && j==celdas.length-1) || (i==filas.length-1 && j!=0)) continue; // La \u00FAltima columna  y la \u00FAltima fila no se pueden editar
	  if (j == 1)
	      cel.onclick = function() {crearLink(this)}
	  else {
		  if (j == 3)
	  	  	cel.onclick = function() {crearInput(this)}
		  else {
			  if (j > 3) {
				cel.align = "right";
			  	cel.onclick = function() {crearInputNum(this)}
			  }
		  }
	  }
    } // end for j 
  } //end for i
  
  // a\u00F1adir funcion onclick a las im\u00E1genes para borrar y a\u00F1adir y ocultar las im\u00E1genes de borrar
  for (i=0; im = document.images[i]; i++) 
    // alert(im.alt);
    if (im.alt=='a\u00F1adir fila')
      im.onclick = function() {anadir(this,0)}
    else if (im.alt=='a\u00F1adir columna')  
      im.onclick = function() {anadir(this,1)}
    else if (im.alt=='borrar fila') {
      im.onclick = function() {borrar(this,0)}
      im.style.visibility = 'hidden';
    }
    else if (im.alt=='borrar columna') {
      im.onclick = function() {borrar(this,1)}
      im.style.visibility = 'hidden';
    }  
} // end function

// crear input para editar datos
function crearInput(celda) {
  celda.onclick = function() {return false}
  txt = celda.innerHTML;
  celda.innerHTML = '';
  obj = celda.appendChild(document.createElement('input'));
  obj.value = txt;
  obj.focus();
  obj.onblur = function() {
    txt = this.value;
    celda.removeChild(obj);
    celda.innerHTML = txt;
    celda.onclick = function() {crearInput(celda)}	
    sumar();    
  }
}

function crearInputNum(celda) {
  celda.onclick = function() {return false}
  txt = celda.innerHTML;
  celda.innerHTML = '';
  celda.align = "right";
  
  obj = celda.appendChild(document.createElement('input'));
  obj.value = txt;  
  obj.focus();
  obj.onkeypress = function() {	  
	  if (this.value == 1)
	  	alert("Pressed 1");
  }
  obj.onblur = function() {
    txt = this.value;
    celda.removeChild(obj);
    celda.innerHTML = txt;
    celda.onclick = function() {crearInput(celda);}	
    sumar();    
  }
}

function crearLink(celda)
{
	celda.onclick = function() {return true}
  	txt = celda.innerHTML;
  	celda.innerHTML = '';
	
	obj = document.createElement('input');	
	obj.value = txt;	
	celda.appendChild(obj);
	
  	img = document.createElement('img');	
	img.src = "editatabla/anadir.gif";
	celda.appendChild(img);		
	
	lnk = document.createElement('a');
	lnk.href = "#";
	
	celda.appendChild(lnk);
	
  	obj.focus();
	//obj.ondblclick = openpopup();
  	obj.onblur = function() {
    	txt = this.value;
    	celda.removeChild(obj);
    	celda.innerHTML = txt;
    	celda.onclick = function() {crearLink(celda)};
  	}
}

function openpopup()
{
	var opciones="left=400, top=50, width=600, height=800, toolbar=0, location=0, directories=0, status=0, menubar=0, scrollbars=1, resizable=1"
	window.open("popwindow.html", "master", opciones);
}

// sumar los datos de la tabla
function sumar() {
  tab = document.getElementById(miTabla);
  filas = tab.getElementsByTagName('tr');
  sum = new Array(2); //(filas.length);
  for (i=0; i<sum.length; i++)
    sum[i]=0;
  	
  for (i=1, tot=filas.length-1; i<tot; i++) { 
    k = 0;
    celdas = filas[i].getElementsByTagName('td');
    for (j=4, to=celdas.length-1; j<to; j++) {	  
      num = parseFloat(celdas[j].innerHTML);

      if (isNaN(num))
	  	num = 0;	  

	  sum[k] += num;
	  k++;
    } // end for j	    
  } // end for i
   
  subt = filas[filas.length-1].getElementsByTagName('td');
  subt[4].align = "right";
  subt[4].innerHTML = parseFloat(sum[0]).toFixed(2);  
  subt[5].align = "right";
  subt[5].innerHTML = parseFloat(sum[1]).toFixed(2);
} // end function

// a\u00F1adir filas o columnas
function anadir(obj,num) {
  if (num==0) { // a\u00F1adir filas
  	fila = obj.parentNode.parentNode;
  	nuevaFila = fila.cloneNode(true);
  	im = nuevaFila.getElementsByTagName('img');
  	im[0].onclick = function() {anadir(this,0)}
  	im[1].onclick = function() {borrar(this,0)}
  	for (i=1, tot=nuevaFila.getElementsByTagName('td').length-1; i<tot; i++) {
		if (i == 1)
	    	nuevaFila.getElementsByTagName('td')[i].onclick = function() {crearLink(this)} ;
		else { 
			if (i == 3)
    			nuevaFila.getElementsByTagName('td')[i].onclick = function() {crearInput(this)} ;
			else {
				if (i > 3)
					nuevaFila.getElementsByTagName('td')[i].onclick = function() {crearInputNum(this)}
			}
		}
	
    	nuevaFila.getElementsByTagName('td')[i].innerHTML = 0;
  	}
  	fila.parentNode.insertBefore(nuevaFila,fila);
  } // end a\u00F1adir filas  
  else { // a\u00F1adir columnas
    tab = document.getElementById(miTabla);
    cabecera = tab.getElementsByTagName('tr')[0];
    // averiguar nº de columna
    for (num=0; cel = cabecera.getElementsByTagName('td')[num]; num++)
      if (cel==obj.parentNode) break;
    for (i=0; fila = tab.getElementsByTagName('tr')[i]; i++) {
      ele = fila.getElementsByTagName('td')[num];
      nuevaColumna = ele.cloneNode(true);
      if (i==0) {
          im = nuevaColumna.getElementsByTagName('img');
          im[0].onclick = function() {anadir(this,1)}
          im[1].onclick = function() {borrar(this,1)}
       }
       else {
          nuevaColumna.innerHTML = (i==1) ? '' : 0;
          nuevaColumna.onclick = function() {crearInput(this)} ;
      }
      fila.insertBefore(nuevaColumna,ele); //
    } //end for i
  } // end a\u00F1adir columnas
  mostrarImagenes();
}

// borrar filas o columnas 
function borrar(obj,num) {
  if (num==0) { // borrar filas
    tab = obj.parentNode.parentNode.parentNode;
    tab.removeChild(obj.parentNode.parentNode);
  } // end borrar filas
  else { // borrar columnas
    tab = document.getElementById(miTabla);
    cabecera = tab.getElementsByTagName('tr')[0];
    // averiguar nº de columna
    for (num=0; cel = cabecera.getElementsByTagName('td')[num]; num++)
      if (cel==obj.parentNode) break;
    for (i=0; fila = tab.getElementsByTagName('tr')[i]; i++)
      fila.removeChild(fila.getElementsByTagName('td')[num]);
  }
  sumar();
  mostrarImagenes();
}

// mostrar/ocultar imagenes para borrar
function mostrarImagenes() {
  tab = document.getElementById(miTabla);
  filas = tab.getElementsByTagName('tr');
  columnas = filas[0].getElementsByTagName('td');
  numFilas = filas.length;
  numColumnas = columnas.length;
  for (i=0; im=tab.getElementsByTagName('img')[i]; i++)
    if (im.alt == 'borrar fila')
      im.style.visibility = (numFilas>4) ? 'visible' : 'hidden';
    else if (im.alt == 'borrar columna')
      im.style.visibility = (numColumnas>7) ? 'visible' : 'hidden';
}