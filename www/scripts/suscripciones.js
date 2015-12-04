// JavaScript Document
function eliminar(id){
 document.location = '?MM_ACTION=eliminarMensaje&id=' + id
}

/*function validarTexto(texto){   
 if (
   texto.indexOf('ñ') != -1 || texto.indexOf('á') != -1 || texto.indexOf('é') != -1 || texto.indexOf('í') != -1 || texto.indexOf('ó') != -1 || 
   texto.indexOf('ú') != -1 || texto.indexOf('ä') != -1 || texto.indexOf('ë') != -1 || texto.indexOf('ï') != -1 || texto.indexOf('ö') != -1 || 
   texto.indexOf('ü') != -1 || texto.indexOf('Ñ') != -1 || texto.indexOf('Á') != -1 || texto.indexOf('É') != -1 || texto.indexOf('Í') != -1 || 
   texto.indexOf('Ó') != -1 || texto.indexOf('Ú') != -1 || texto.indexOf('Ä') != -1 || texto.indexOf('Ë') != -1 || texto.indexOf('Ï') != -1 || 
   texto.indexOf('¡') != -1 || texto.indexOf('¿') != -1 || texto.indexOf('Ö') != -1 || texto.indexOf('Ü') != -1 || texto.indexOf('...') != -1 || 
   texto.indexOf('  ')  !=  -1 || texto.indexOf('!!')  !=  -1){
   return false;
  } else {
   return true;
  }
}*/

function validarTexto(texto){
 var t1 = document.getElementById("divCar");
 var caracteres = new Array(texto.length);
 var ascii = [241, 225, 233, 237, 243, 250, 228, 235, 239, 246, 252, 209, 193, 201, 205, 211, 218, 196, 203, 207, 214, 220, 161, 191, 170, 34, 176, 10];   
 for( i = 0;i < texto.length; i++){
  if(texto.charCodeAt(i) == 32 && texto.charCodeAt(i+1) == 32){
   return false;
  } else if(texto.charCodeAt(i) == 46 && texto.charCodeAt(i+1) == 46 && texto.charCodeAt(i+2) == 46){
   return false;
  } else if(texto.charCodeAt(i) == 33 && texto.charCodeAt(i+1) == 33){
   return false;
  } else {
   for(x = 0; x < ascii.length; x++){
    if(texto.charCodeAt(i) == ascii[x]){
     return false;
    }
   }
  }
 }
 return true;
}

function agregarVariable(id, string){     
 var tb = document.getElementById(id)
 var cursor = -1;
 var texto_inicio = '';
 var text_final = '';

 if (document.selection && (document.selection != 'undefined')){
  var _range = document.selection.createRange();
  var contador = 0;
  while (_range.move('character', -1))
   contador++;
  cursor = contador;
 } else if (tb.selectionStart >= 0)
  cursor = tb.selectionStart;

 texto_inicio = tb.value.substr(0,cursor);
 texto_final = tb.value.substr(cursor,tb.value.length);
 tb.value = texto_inicio + string + texto_final;
}

function agregarCondicion(idVarSelect, idOperSelect, idText, idHidde,idVar){
 var varSelect = document.getElementById(idVarSelect);
 var operSelect = document.getElementById(idOperSelect);
 var campoText = document.getElementById(idText);
 var campoHidden = document.getElementById(idHidde);
 var campo = document.getElementById(idVar);
   
 if(isNaN(varSelect.value) && operSelect.value != '='){
  alert("El operador '" + operSelect.value + "' solo se puede utilizar con numeros.");
 } else if (isNaN(varSelect.value)){
  if (campoHidden.value.length > 0){
   campoText.value +=  " \u00d3 " + campo.value + " " +  operSelect.value  + " '" + varSelect.value  + "'";
   campoHidden.value += "@@OR@@TRIM(p." + idVar + ")" +   operSelect.value + "TRIM('" + varSelect.value  + "')";
  } else {
   campoText.value = campo.value + " " +  operSelect.value  + " '" + varSelect.value  + "'";
   campoHidden.value = "TRIM(p." + idVar + ")" + operSelect.value + "TRIM('" + varSelect.value  + "')";
  }
 } else {
  if (campoHidden.value.length > 0){
   campoText.value += " \u00d3 " + campo.value +  " " + operSelect.value  + " " +  varSelect.value;
   campoHidden.value +=  "@@OR@@TRIM(p." + idVar + ")" + operSelect.value + "TRIM("+ varSelect.value + ")";
  } else {
   campoText.value = campo.value +  " " + operSelect.value  + " " +  varSelect.value;
   campoHidden.value = "TRIM(p." + idVar + ")" + operSelect.value + "TRIM("+ varSelect.value + ")";
  }
 }   
}

function limpiarCondicion(idText, idHidde){
 var campoText = document.getElementById(idText);
 var campoHidden = document.getElementById(idHidde);
 campoText.value = "";
 campoHidden.value = "";
}

function validarFile(){
 var archivo =  document.getElementById('fileExcel').value;
 extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
 if( extension === ".xls" || extension === ".xlsx"){
 } else {
  alert("Solo se permiten archivos de excel en formato .xls(Excel 2003) y .xlsx(Excel 2007)");
  document.getElementById('fileExcel').value = '';
 }
}
