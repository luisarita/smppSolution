// JavaScript Document
function marcar(id){
 var url = "?MM_ACTION=marcar&id=" + id; 
 document.location = url;
}

function eliminar(numero){
 var url = "?MM_ACTION=delBlackList&numero=" + numero; 
 document.location = url;
}