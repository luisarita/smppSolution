function bodyLoaded(){
 var msg = getUrlVars()["msg"];
 try {
  if (window.self !== window.top){
   //window.top.location = window.self.location;
  } else {
   switch (msg){
    case "suscripcionCreada":
     alert("La actividad ha sido creada");
   }
  }
 } catch (e) {
  return true;
 }
}

function getUrlVars() {
 var vars = {};
 var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
  vars[key] = value;
 });
 return vars;
}
