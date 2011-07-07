<?php require_once('functions-util.php'); ?>

<?php if(isset($reg_error)) { 
  echo "<font align='center' color='red' size='2'>\n";
  echo "<div align='center'>There was an error: $reg_error\n<br/>Please try again.</div>\n";
  echo "</font>\n";
  }
?>
  <br/>
  <table align="center">
   <form name="registration" method="post" action="register.php">
   <tr><td>Email:</td>
       <td><input name='uname' id="uname" type="text" 
                  size="<?php echo $maxlengths['username']; ?>"
                  maxlength="<?php echo $maxlengths['username']; ?>"
                  value="<?php echo get($_POST,'uname',''); ?>"
                 >
       </td>
   </tr>

   <tr><td>Password:</td>
       <td><input name="pass1" id="pass1" type="password" 
                  size="<?php echo $maxlengths['password']; ?>"
                  maxlength="<?php echo $maxlengths['password']; ?>"
                  >
       </td>
   </tr>
   <tr><td>Confirm password:</td>
       <td><input name="pass2" id="pass2" type="password" 
                  size="<?php echo $maxlengths['password']; ?>"
                  maxlength="<?php echo $maxlengths['password']; ?>"
                  >
       </td>
   </tr>
   <tr><td>Teacher E-mail:</td>
       <td><input name="temail" id="temail" type="text" 
                  size="<?php echo $maxlengths['username']; ?>"
                  maxlength="<?php echo $maxlengths['username']; ?>"
                  >
           <span class="additional-info">Optional.  This user will be able to see all your work.</span></tr>
        </td>
   <tr><td><input type="submit" name="submit" value="Register"></td></tr>
   </form>
  </table>


<?php include( "terms-of-use-inc.html" ); ?>

