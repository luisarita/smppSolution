// JavaScript Document
function validar(forma) {
    if (forma.nombre.value == '') {
        alert("El nombre no puede ser nulo");
        return false;
    } else if (forma.msjfallido.value == '') {
        alert("El mensaje fallido no puede ser nulo");
        return false;
    } else if (forma.msjgane.value == '') {
        alert("El mensaje gane no puede ser vacio");
        return false;
    } else if (forma.msjacierto.value == '') {
        alert("El mensaje acierto no pude ser vacio");
        return false;
    } else if (forma.montoinicio.value < 0) {
        alert("El monto inicial no pude ser menor a 0");
        return false;
    } else if (forma.tasamsj.value < 0) {
        alert("La tasa por mensaje no puede ser menor a 0");
        return false;
    } else if (forma.tasaseg.value < 0) {
        alert("La tasa por segundo no puede ser menor a 0");
        return false;
    } else if (forma.montoactual.value < 0) {
        alert("El monto actual no puede ser menor a 0");
        return false;
    } else if (form1.claves.length == 0) {
        alert("Debe de ingresar al menos una clave");
        return false;
    }
    selAll(true, forma.claves);
    return true;
}
function agregar() {
    var clave = prompt("Introduzca la Clave :", "");
    if (clave != '') {
        opcion = new Option(clave, clave, "defauldSelected");
        fila = form1.claves.length
        document.forms.form1.claves.options[fila] = opcion;
    }
}
function eliminar() {
    var fila = document.form1.claves.selectedIndex
    if (fila != -1)
        document.forms.form1.claves.options[fila] = null;
}