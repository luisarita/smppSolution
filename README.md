# Dependencies
vnframework  
 - an open source .net framework that can be found here https://github.com/luisarita/vnframework  

# Included Projects  
library  
 A .Net class project containing all the logic to interact with the esme java app  

service  
 A .Net Windows service that acts as a loader to the library logic. Having the service logic separated facilitates debugin  

smppMonitor  
 A .Net Windows service that helps monitor the SMPP Instances and do corresponding restarts 

smppManager  
 A .Net WIndows Forms project to manage diffrent activities in the platform  

tester  
 A .Net Windows Forms project that loads up the server logic to facilitate debuging