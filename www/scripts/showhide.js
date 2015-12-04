// JavaScript Document
var seleccion_anterior = '';
function seleccionar_fila_listado(id_fila, cuenta_correlativo){
 if (document.getElementById('fila_b_' + cuenta_correlativo).style.display == 'none'){
  document.getElementById('fila_b_' + cuenta_correlativo).style.display = ''; 
  mostrar_detalles_fila(cuenta_correlativo);
 }
 seleccion_anterior = id_fila;
}

function mostrar_detalles_fila(id_fila){
 document.getElementById('fila_mostrar_' + id_fila).style.display = 'none';
 document.getElementById('fila_ocultar_' + id_fila).style.display = '';
}
				
function ocultar_detalles_fila(id_fila){
 document.getElementById('fila_mostrar_' + id_fila).style.display = '';
 document.getElementById('fila_ocultar_' + id_fila).style.display = 'none';
 document.getElementById('fila_b_' + id_fila).style.display = 'none';
}