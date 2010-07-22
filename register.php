<html>
 <head><title>JavaTea</title>
  <script text="javascript">
   function checkPass() {
     var one = document.getElementById("pass1").value;
     var two = document.getElementById("pass2").value;
     if(one.length != 0 && two.length != 0 && one != two) {
       alert('passwords do not match');
       document.getElementById("pass2").value="";    
     }
   }
   function checkForm() {
     var uname = document.getElementById('uname');
     var pass1 = document.getElementById('pass1');
     var pass2 = document.getElementById('pass2');
     if(uname.value == "") {
       alert("Username is blank");
     } 
     else if(pass1.value == "" || pass2.value == "") {
       alert("Password is blank or incomplete");
     }
     else if(pass1.value != pass2.value) {
       alert("Passwords do not match");
       pass2.value="";
     }
   }
  </script>
 </head>
 <body>
  <div align="center">
   <a href="/JavaTea">Home</a>&nbsp;&nbsp;&nbsp;
   <a href="FAQ.php">FAQ</a>&nbsp;&nbsp;&nbsp;
   <a href="login.php">Login</a>
  </div>
  <br/>
  <br/>
  <table align="center">
   <form name="registration" method="post" action="create_user.php">
   <tr><td>Username:</td><td><input id="uname" type="text"></td></tr>
   <tr><td>Password:</td>
       <td><input name="pass1" id="pass1" type="password" onblur="checkPass()"></td></tr>
   <tr><td>Confirm password:</td>
       <td><input name="pass2" id="pass2" type="password" onblur="checkPass()"></td></tr>
   <tr><td>Teacher E-mail*:</td>
       <td><input name="temail" id="temail" type="text"></td></tr>
   <tr><td><input type="submit" value="Register" onmouseover="checkForm()"></td></tr>
   </form>
  </table>
 </body>
</html>
