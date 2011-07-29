<?php


function rand_letter() {
  return chr( rand(ord('A'),ord('Z')) + 32*rand(0,1) ); 
  }

function gen_salt() {
    $salt = "";
    global $maxLengths;
    for($i = 0;  $i < $maxLengths['salt'];  $i++) {
      $salt .= rand_letter();
      }
    return $salt;
}

function create_user($username, $password, $temail) {
  $usernameSafe = mysql_real_escape_string($username);
  $temailSafe = mysql_real_escape_string($temail);
  $salt = gen_salt();
  $encrypted = sha256(sha256($password).$salt);
  $query = "INSERT INTO Users(username, password, teacher, salt, joined)
            VALUES('$usernameSafe', '$encrypted', '$temailSafe', '$salt', now())";
  mysql_query($query) or die('Could not create user.');
  }

function debug_query($q) {
  echo "making query: ". htmlspecialchars($q). "<br />";
  $result = mysql_query($q);  
  // while ($row = mysql_fetch_array($result,MYSQL_ASSOC)) { ... }
  echo "result is: ". SQLTableToHTML($result). "<br />";
  return $result;
  }


function username_used($username) {
  $usernameSafe = mysql_real_escape_string($username);
  $query = "SELECT * FROM Users WHERE username='$usernameSafe'";
  $result = mysql_query($query);
  return (mysql_num_rows($result) > 0);
  }

function log_attempt($user, $pid) {
    $query = "INSERT INTO Attempts(username, problem_id, submit_time) 
              VALUES('$user', '$pid', now())";
    mysql_query($query) or die("Could not log attempt");
}

function log_input($attempt_id, $case_id, $field, $input) {
    $query = "INSERT INTO Inputs(attempt_id, case_id, field, input)
              VALUES($attempt_id, $case_id, $field, '$input')";
    mysql_query($query) or die("Could not log attempt");
}

function log_output($attempt_id, $case_id, $output) {
    $query = "INSERT INTO Outputs(attempt_id, case_id, output)
              VALUES('$attempt_id', '$case_id', '$output')";
    mysql_query($query) or die("Could not log attempt");
}

function user_login($username, $password) {
    $usernameSafe = mysql_real_escape_string($username);

    $query = "SELECT * FROM Users WHERE username='$usernameSafe'";
    $result = mysql_query($query);
    $userRow = mysql_fetch_array($result,MYSQL_ASSOC);
    $encrypted = sha256( sha256($password).$userRow['salt'] );

    if ($encrypted === $userRow['password']) {
        $_SESSION['username'] = $username;
        $_SESSION['encrypted_name'] = sha256($username);
        return true;
    } else {
        return false;
    }
}

function is_authed() {
  return (isset($_SESSION['username']) && (hash('sha256', $_SESSION['username']) == $_SESSION['encrypted_name']));
  // Guard against somebody attacking $_SESSION['username']  (Is this really necessary?).
  }


  /** Start a session.
   * @param $sessName The name to use for the session (optional; server probably defaults to 'PHPSESSID').
   * @return (void)
   */
  function my_session_start($sessName = '') {
    if ($sessName) { session_name($sessName); }
    session_start();
    session_regenerate_id(true);
    // Remember: you can call session_commit as soon as you're done writing session vars.
    }

  /** Remove all data associated with the current session.
   * (Note that session_start gets called, even if it's already been opened.)
   * @precondition Must be called before the http header is sent (since it resets the session cookie).
   */
  function my_session_destroy() {
    session_start();
    $scp = session_get_cookie_params();  /* scp ~ session-cookie-params */
    setcookie( $session_name(), '', 1, $scp['path'], $scp['domain'], $scp['secure'], $scp['httponly'] ); 
    /* N.B. the last two params could be omitted, no prob, since we're destroying the cookie. */
    session_unset();   /* Unset the contents of $_SESSION */
    session_destroy(); /* Delete the server's file of data. */
    }


?>
