<?php 
if(isset($login_error)) {
  echo "<font color='red' size='2'>";
  echo "<div align='center'>There was an error: $login_error; please try again.</div>";
  echo "</font>";
  }
?>

/* N.B. Currently, init.php does a session_commit ! */

<form name="login" method="post" action="login.php">
 <table align="center">
 <tr><td>email&nbsp;&nbsp;</td>
         <!-- It's arguably a slight security leak to have maxlength on the inputs,
              but since any personal effort would involve seeing what the 
              account-setup-requirements are, we'll live with it.
              However, we won't make the password sticky.
           -->
     <td><input name="uname" id="uname" type="text"
                maxlength="<?php echo $maxlengths['username']; ?>"
                value="<?php if(isset($_POST['uname'])) echo $_POST['uname']; ?>"
                >
     </td>
 </tr>
 <tr><td>Password&nbsp;&nbsp;</td>
     <td><input name="pass" id="pass" type="password"
                maxlength="<?php echo $maxlengths['password']; ?>"
                >
     </td>
 </tr>
 <tr><td></td>
     <td><input name="submit" type="submit" value="Login"></td>
 </tr>
</table>

<p align="center"><a href="register.php">Register</a>&nbsp; <a href="forgot.php">Forgot password?</a></p>
</form>

