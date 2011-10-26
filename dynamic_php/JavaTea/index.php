<?php
  $depthThisFile = substr_count($_SERVER["PHP_SELF"],DIRECTORY_SEPARATOR);
  $depthProjRoot = substr_count('/~itec120/JavaTea/',DIRECTORY_SEPARATOR);
  $projRootDir = str_repeat('../', $depthThisFile - $depthProjRoot );
  $projRootDir = ($projRootDir ? rtrim($projRootDir,'/') : '.');
//require_once( "$projRootDir/templates/init.php" );
//require_once( "templates/init.php" );
?>

<html>
  <head><title>JavaTea</title></head>

<body>
<?php
include('templates/header-inc.php');
?>

  <table align="center" width="70%">
   <tr>
    <td><img src="images/javatealogo_small.png"/></td>
    <td>
<h2>JavaTea</h2>
<h4>A web-based tool for teaching beginning programmers to write good (unit) test cases.</h4>

<!--
<p>
  <font color="red">JavaTea is down for maintenance, 2011.Oct.25.</font>
  <font size="-1">(Installing a javac compatible with upgraded hardware platform.)</font>
</p>
-->

<p>
     The JavaTea site is not yet ready for full use; it is currently a proof-of-concept.
     It is open for pre-release (non-robust) use since 2011.Sep; 
     support for logins, assignments, and reasonable error messages will be
     implemented in early 2012.
     Feedback is welcome; a feedback form will be added.
</p>
<p>
     Future features include 
     developing a reaonsable-sized set of problems,
     performance improvments,
     support for python and scheme syntax,
     and providing executable test-suites as output.
</p>

<!--
<p>
If you are interested in donating problems (a problem statement, 
a correct Java solution, and several faulty implementations),
please contact me.  &mdash; <tt>ib ar la nd AT ra df or d DOT ed u</tt>
</p>
-->

</td>
   <tr>  
  </table>

<h3>Sample problems</h3>
<ul>
  <li>
    <a href="probs/p0004/"><code>pluralize</code></a>
  </li>
  <li>
    <a href="probs/p0003/"><code>isPalindrome</code></a>
  </li>
  <li>
    <a href="probs/p0002/"><code>reverse</code></a>
  </li>
</ul>

  <!--<form action="/cgi-bin/test.py" method="post">
   <input type="submit" value="Go">
  </form>-->

<body>
</html>
