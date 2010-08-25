<?php
include '../../init.php';
?>
<html>
 <head><title>JavaTea</title>
  <script type="text/javascript">
   function addRow() {
   var doc = document.getElementById("cases");

   var a = (document.getElementsByTagName("input").length - 5) / 2 + 1;

   var f = new Array();
   var r = new Array();   

   document.getElementById("numcases").value++;

   for(i=1;i<a;i++) {
    f.push(document.getElementById("field"+i+"-1").value);
    r.push(document.getElementById("result"+i).value);
   }
    
   doc.innerHTML += "double( <input type=\"text\" name=\"field"+a+"-1\" id=\"field"+a+"-1\"/>) == <input type=\"text\" name=\"result"+a+"\" id=\"result"+a+"\"/><br/>";
   for(i=1;i<a;i++) {
    document.getElementById("field"+i+"-1").value = f[i-1];
    document.getElementById("result"+i).value = r[i-1];
   }
   }
  </script>
 </head>
<?php
include '../../templates/header.inc.php';
?>
 <table align="center" width="70%">
   <tr><td><p>public int double(int number)</p><br/>
    <p>The function double takes in any integer and returns double its value</p>
   </td></tr>
   <tr><td>
    <form action="../../cgi-bin/process.php" method="post">
     <input type="hidden" name="probId" id="probId" value="j0001"/>
     <input type="hidden" name="numfields" id="numfields" value="1"/>
     <input type="hidden" name="numcases" id="numcases" value="1"/>
     Add test cases with this button.&nbsp;&nbsp;&nbsp;
     <input type="button" value="Add" onclick="addRow()"/><br/>
     <span id="cases">double( <input type="text" name="field1-1" id="field1-1"/>) == 
      <input type="text" name="result1" id="result1"/><br/>
     </span>
     <input type="submit" value="Submit"/>
    </form>  
   </td></tr>
  </table>
 </body>
</html>

