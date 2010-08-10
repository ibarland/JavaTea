function addRow() {
   var doc = document.getElementById("cases");

   var a = (document.getElementsByTagName("input").length - 2) / 2 + 1;

   var f = new Array();
   var r = new Array();   

   for(i=1;i<a;i++) {
    f.push(document.getElementById("field"+i).value);
    r.push(document.getElementById("result"+i).value);
   }
    
   doc.innerHTML += "double( <input type=\"text\" name=\"field"+a+"\" id=\"field"+a+"\"/>) == <input type=\"text\" name=\"result"+a+"\" id=\"result"+a+"\"/><br/>";
   for(i=1;i<a;i++) {
    document.getElementById("field"+i).value = f[i-1];
    document.getElementById("result"+i).value = r[i-1];
   }
}
