// JavaScript Document

function updateMonto(){
 blinkNumbers();
 if (!monto) return;

 var now = (new Date()).getTime();
 var i;
 for (i = 0; i < CP.length; i++) {
  if (now < CP[i][0]) break;  
 }

 if (i == 0) {
  setTimeout(updateMonto, 1000); 
 } else if (i == CP.length) {
  monto.innerHTML = CP[i - 1][1];
 } else {
  var ts = CP[i - 1][0];
  var bs = CP[i - 1][1];
  var ad = new Number(adicional.value);
  monto.innerHTML = format(((now-ts) / (CP[i][0]-ts) * (CP[i][1]-bs)) + bs + ad); 
  setTimeout(updateMonto, 1000); 
 } 
} 
 
function el(id){
 if (document.getElementById){
  return document.getElementById(id);
 } else if (window[id]) {
  return window[id];
 }
 return null;
}

function OnLoad(){
 if (!monto) {
  monto     = el("monto");
  adicional = el("adicional");
  updateMonto();
 }
}
 
function addCommas(nStr){
 nStr += '';
 x = nStr.split('.');
 x1 = x[0];
 x2 = x.length > 1 ? '.' + x[1] : '';
 var rgx = /(\d+)(\d{3})/;
 while (rgx.test(x1)) {
  x1 = x1.replace(rgx, '$1' + ',' + '$2');
 }
 return x1 + x2;
}

function format(num) { 
 var str = String(num); 
 var str= addCommas(str);
 var dot = str.indexOf('.'); 
 if (dot < 0) { 
  return str + PAD; 
 }
 if (PAD.length > (str.length - dot)) {
  return str + PAD.substring(str.length - dot);
 } else {
  return str.substring(0, dot + PAD.length);
 }
}