function validarForm(){
 if(document.getElementById('txtUser').value.length < 1){
  alert("El campo \"Usuario\" no puede estar vacio");
  document.getElementById('txtUser').focus();
  return false;
 }
 if(document.getElementById('txtName').value.length < 1){
  alert("El campo \"Nombre\" no puede estar vacio");
  document.getElementById('txtName').focus();
  return false;
 }
 if(document.getElementById('txtPass').value.length < 1){
  alert("El campo \"Contrase"+String.fromCharCode(241)+"a\" no puede estar vacio");
  document.getElementById('txtPass').focus();
  return false;
 }
 return true;
}

function validarFormEdit(){
 if(document.getElementById('txtUser').value.length < 1){
  alert("El campo \"Usuario\" no puede estar vacio");
  document.getElementById('txtUser').focus();
  return false;
 }
 if(document.getElementById('txtName').value.length < 1){
  alert("El campo \"Nombre\" no puede estar vacio");
  document.getElementById('txtName').focus();
  return false;
 }
 return true;
}

function agregarActividad(idP, idQ, idTxtP){
    var objeto = document.getElementById(idP);
    var objeto1 = document.getElementById(idQ);
    if(objeto1.selectedIndex != -1){
        objeto.options[objeto.length] = objeto1.options[objeto1.selectedIndex];
        objeto1.options[objeto1.selectedIndex] = null;
        armarPermitidos(idP, idTxtP);
    }
}
function quitarActividad(idP, idQ, idTxtP){
    var objeto = document.getElementById(idP);
    var objeto1 = document.getElementById(idQ);
    if(objeto.selectedIndex != -1){
        objeto1.options[objeto1.length] = objeto.options[objeto.selectedIndex];
        objeto.options[objeto.selectedIndex] = null;
        armarPermitidos(idP, idTxtP);
    }
    
}

function armarPermitidos(idP, idTxtP){
    var objeto = document.getElementById(idP);
   
    var claves="";
    for (var i = 0; i < objeto.length; i++) {
        var opt = objeto[i];
        claves += opt.value + ",";
    }
    document.getElementById(idTxtP).value = claves.slice(0,-1);
}