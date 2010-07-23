<?php if(isset($reg_error)) { ?>
 <font align="center" color="red" size="2">
  <div align="center">There was an error: <?php echo $reg_error; ?>, please try again.</div>
 </font>
<?php } ?>
  <br/>
  <table align="center">
   <form name="registration" method="post" action="register.php">
   <tr><td>Username:</td>
       <td><input name='uname' id="uname" type="text" size="30" maxlength="30"<?php if (isset($_POST['uname'])) { ?> value="<?php echo $_POST['uname']; ?>" <?php } ?>></td></tr>
   <tr><td>Password:</td>
       <td><input name="pass1" id="pass1" type="password" size="30" maxlength="30"></td></tr>
   <tr><td>Confirm password:</td>
       <td><input name="pass2" id="pass2" type="password" size="30" maxlength="30"></td></tr>
   <tr><td>Teacher E-mail*:</td>
       <td><input name="temail" id="temail" type="text" size="30" maxlength="30"></td></tr>
   <tr><td><input type="submit" name="submit" value="Register"></td></tr>
   </form>
  </table>
