<?php if(isset($login_error)) { ?>
<font color="red" size="2">
<div align="center">There was an error: <?php echo $login_error; ?>, please try again</div>
</font>
<?php } ?>
<table align="center">
 <form name="login" method="post" action="login.php">
 <tr><td>Username&nbsp;&nbsp;</td>
     <td><input name="uname" id="uname" type="text"<?php if(isset($_POST['uname'])) { ?> value="<?php echo $_POST['uname'];?>"<?php } ?> ></td></tr>
 <tr><td>Password&nbsp;&nbsp;</td>
     <td><input name="pass" id="pass" type="password"></td></tr>
 <tr><td><input name="submit" type="submit" value="Login"></td>
     <td><a href="register.php">Register</a>&nbsp;
         <a href="forgot.php">Forgot password?</a></tr>
 </form>
</table>

