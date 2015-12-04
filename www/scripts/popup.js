// JavaScript Document
function popUp(URL,desde,hasta) {
 day = new Date();
 id  = day.getTime();
 URL = URL +'?desde=' + desde + '&hasta=' + hasta;
 window.open(URL, 'sorteo','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=1200,height=600,left=20,top=50');
}

function popUpNumero(URL,desde,hasta,numero) {
 day = new Date();
 id  = day.getTime();
 joinSymbol = '?';
 if (URL.indexOf("?") >= 0) joinSymbol = '&';
 URL = URL + joinSymbol + 'desde=' + desde + '&hasta=' + hasta + '&numero=' + numero;
 window.open(URL, 'sorteo','toolbar=0, scrollbars=1, location=0, statusbar=0,menubar=0,resizable=0,width=1200,height=600,left=20,top=50');
}

function popUpChat(URL) {
 day = new Date();
 id  = day.getTime();
 window.open(URL, 'chat' + id,'toolbar=0, scrollbars=1, location=0, statusbar=0, menubar=0, resizable=1, width=400, height=300, left=340, top=-2488');
}

function popUpURL(URL) {
 window.open(URL, 'sorteo','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=1200,height=600,left=20,top=50');
}