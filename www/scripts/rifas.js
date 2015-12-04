function validar(forma){
 if (forma.nombre.value == ""){
  alert("El nombre no puede ser nulo");
  return false;
 } else if (forma.hora_inicial.value >= forma.hora_final.value && forma.hora_final.value != 0){
  alert("La hora inicial no puede ser mayor o igual que la hora final");
  return false;
 } else if (forma.dia_lunes.value == 0 && forma.dia_martes.value == 0 && forma.dia_miercoles.value == 0 && forma.dia_jueves.value == 0 && forma.dia_viernes.value == 0 && forma.dia_sabado.value == 0 && forma.dia_domingo.value == 0 ) {
  alert("Debe seleccionar al menos un dia");
  return false;		 
 }
 return true;
}