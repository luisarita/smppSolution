// JavaScript Document
if (!window.webkitNotifications) {
 alert('Su navegador no acepta notificaciones. Favor utilice uno que si, como Google Chrome');
}
function requestPermission (callback){
 window.webkitNotifications.requestPermission(callback);
}

function notification (){
 if (window.webkitNotifications.checkPermission() > 0) {
  RequestPermission(notification);
 }
 
 var popup = window.webkitNotifications.createHTMLNotification(url);
  popup.show();
  setTimeout(function(){
  popup.cancel();
 }, '15000');
} 