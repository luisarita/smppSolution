// JavaScript Document
function validateTextArea(textarea){  
 var texto = textarea.value.toString();

 if (
  texto.indexOf('ñ') != -1 || texto.indexOf('á') != -1 || texto.indexOf('é') != -1 || texto.indexOf('í') != -1 || texto.indexOf('ó') != -1 || 
  texto.indexOf('ú') != -1 || texto.indexOf('ä') != -1 || texto.indexOf('ë') != -1 || texto.indexOf('ï') != -1 || texto.indexOf('ö') != -1 || 
  texto.indexOf('ü') != -1 || texto.indexOf('Ñ') != -1 || texto.indexOf('Á') != -1 || texto.indexOf('É') != -1 || texto.indexOf('Í') != -1 || 
  texto.indexOf('Ó') != -1 || texto.indexOf('Ú') != -1 || texto.indexOf('Ä') != -1 || texto.indexOf('Ë') != -1 || texto.indexOf('Ï') != -1 || 
  texto.indexOf('¡') != -1 || texto.indexOf('¿') != -1 || texto.indexOf('Ö') != -1 || texto.indexOf('Ü') != -1 || texto.indexOf('...') != -1 || 
  texto.indexOf('  ')  !=  -1 || texto.indexOf('!!')  !=  -1){
  alert('El texto contiene caracteres inválidos');
  return false;
 } else {
  return true;
 }
}