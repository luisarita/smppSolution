function validarForm() {
    if (document.getElementById('mensaje').value.length > 0 && document.getElementById('mensaje').value.length <= 180) {
        var numero = document.getElementById('numeroP');
        if (!numero.disabled) {
            if (numero.value.length == 11 && numero.value.substring(0, 3) == '504' && numero.value.substring(3, 4) == '9' && IsNumeric(numero.value)) {
                return true;
            } else {
                alert("El numero ingresado es incorrecto, el formato correcto es: 50499999999");
                return false;
            }
        } else {
            return true;
        }
    } else {
        alert("El mensaje no puede estar vacio  y/o es demasiado largo");
        return false;
    }
}

/* inicio de funciones para agregar, eliminar y armar las claves */
function armarClaves() {
    var sel = document.getElementById("listClaves");
    var claves = "";
    for (var i = 0; i < sel.length; i++) {
        var opt = sel[i];
        claves += opt.value + ",";
    }
    document.getElementById("txtClaves").value = claves.slice(0, -1);
}

function agregarOpcion() {
    var claves = document.getElementById("listClaves");
    var clave = prompt("Ingrese la clave");
    if (clave != null) {
        var opcion = new Option(clave, clave);
        claves.options[claves.options.length] = opcion;
        armarClaves();
    }
}

function eliminarOpcion() {
    var claves = document.getElementById("listClaves");
    if (claves.selectedIndex != -1) {
        claves.options[claves.selectedIndex] = null;
        armarClaves();
    } else {
        alert("Seleccione la clave que desea eliminar.");
    }
}

/* fin de funciones para agregar, eliminar y armar las claves */
function validarRecordatorioCrear() {
    var foto = document.getElementById("imagen");
    var extensionI = (foto.value.substring(foto.value.lastIndexOf("."))).toLowerCase();
    var clave = document.getElementById('txtClaves');
    extensionesI = new Array(".jpg", ".jpeg");

    if (document.getElementById('nombreCrear').value.length < 1) {
        alert("El campo \"Nombre\" no puede estar vacio");
        document.getElementById('nombreCrear').focus();
        return false;
    }

    if (document.getElementById('usuario').value.length < 1) {
        alert("El campo \"Usuario\" es obligatorio");
        document.getElementById('usuario').focus();
        return false
    }
    if (document.getElementById('pass').value.length < 1) {
        alert("El campo \"Contraseña\" es obligatorio");
        document.getElementById('pass').focus();
        return false
    }
    if (document.getElementById('passAdmin').value.length < 1) {
        alert("El campo \"Contraseña Admin\" es obligatorio");
        document.getElementById('passAdmin').focus();
        return false
    }

    if (document.getElementById('msjParticipante').value.length < 1 || document.getElementById('msjParticipante').value.length > 180) {
        alert("El campo \"Mensaje participante\" es obligatorio  y/o es demasiado largo");
        document.getElementById('msjParticipante').focus();
        return false
    }

    if (document.getElementById('msjAdicional').value.length > 180) {
        alert("El campo \"Mensaje Adicional\" es demasiado largo");
        document.getElementById('msjAdicional').focus();
        return false
    }

    if (clave.value.length < 1) {
        alert("El campo \"Claves\" es obligatorio");
        document.getElementById('claves').focus();
        return false
    }
    if (extensionesI[0] != extensionI && extensionesI[1] != extensionI) {
        alert("Por favor seleccione un archivo valido para el logo, solo se acepta el formato \"JPG\"");
        return false;
    }

    return true;
}

function validarRecordatorioEditar() {
    var foto = document.getElementById("imagen");
    var extensionI = (foto.value.substring(foto.value.lastIndexOf("."))).toLowerCase();
    var clave = document.getElementById("txtClaves");
    extensionesI = new Array(".jpg", ".jpeg");

    if (document.getElementById('nombreEditar').value.length < 1) {
        alert("El campo \"Nombre\" no puede estar vacio");
        document.getElementById('nombreEditar').focus();
        return false;
    }

    if (document.getElementById('usuario').value.length < 1) {
        alert("El campo \"Usuario\" es obligatorio");
        document.getElementById('usuario').focus();
        return false
    }
    if (document.getElementById('pass').value.length < 1) {
        alert("El campo \"Contraseña\" es obligatorio");
        document.getElementById('pass').focus();
        return false
    }
    if (document.getElementById('passAdmin').value.length < 1) {
        alert("El campo \"Contraseña Admin\" es obligatorio");
        document.getElementById('passAdmin').focus();
        return false
    }

    if (document.getElementById('msjParticipante').value.length < 1 || document.getElementById('msjParticipante').value.length > 180) {
        alert("El campo \"Mensaje participante\" es obligatorio  y/o es demasiado largo");
        document.getElementById('msjParticipante').focus();
        return false
    }

    if (document.getElementById('msjAdicional').value.length > 180) {
        alert("El campo \"Mensaje adicional\"  es demasiado largo");
        document.getElementById('msjAdicional').focus();
        return false
    }

    if (clave.value.length < 1) {
        alert("El campo \"Claves\" es obligatorio");
        document.getElementById('claves').focus();
        return false
    }

    if (extensionesI[0] != extensionI && extensionesI[1] != extensionI && extensionI != '') {
        alert("Por favor seleccione un archivo valido para el logo, solo se acepta el formato \"JPG\"");
        return false;
    }

    return true;
}

function IsNumeric(expression) {
    return (String(expression).search(/^\d+$/) != -1);
}


function bloquearText(name) {
    if (name == 'enviarTodos') {
        document.getElementById('numeroP').disabled = true;
        document.getElementById('numeroP').value = '';
    } else {
        document.getElementById('numeroP').disabled = false;
    }
}

function activaDesactiva(id) {
    if (document.getElementById(id).checked) {
        bloquear();
        desbloquear(document.getElementById(id).value);
    }
}

function desbloquear(tipo) {
    if (tipo == 'porHora') {
        document.getElementById('minuto').disabled = false;
    } else if (tipo == 'porDia') {
        document.getElementById('hora').disabled = false;
        document.getElementById('minuto1').disabled = false;
    } else if (tipo == 'porSemana') {
        document.getElementById('dia').disabled = false;
        document.getElementById('hora1').disabled = false;
        document.getElementById('minuto2').disabled = false;
    } else if (tipo == 'porMes') {
        document.getElementById('dia1').disabled = false;
        document.getElementById('hora2').disabled = false;
        document.getElementById('minuto3').disabled = false;
    }
}

function bloquear() {
    document.getElementById('minuto').disabled = true;
    document.getElementById('minuto1').disabled = true;
    document.getElementById('minuto2').disabled = true;
    document.getElementById('minuto3').disabled = true;
    document.getElementById('hora').disabled = true;
    document.getElementById('hora1').disabled = true;
    document.getElementById('hora2').disabled = true;
    document.getElementById('dia').disabled = true;
    document.getElementById('dia1').disabled = true;
}