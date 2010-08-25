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
   <tr><td><p>Signature: <code>public int FahrToCelc(int number)</code></p><br/>
    <p>Convert a temperature f from degrees Fahrenheit to degrees Celcius.</p> 
    <p>Precondition: f cannot be below absolute zero (-471.36 F), 
    and won't be above the planck temperature (~2.5e32 F)
    (<a href="http://www.straightdope.com/columns/read/807/what-is-the-opposite-of-absolute-zero">Planck temperature info</a>).
	</p>

   </td></tr>
   <tr><td>
    <form action="../../cgi-bin/process.php" method="post">
     <input type="hidden" name="probId" id="probId" value="fahrToCelc"/>
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

