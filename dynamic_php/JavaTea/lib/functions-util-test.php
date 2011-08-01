<?php

require_once('functions-util.php');


echo "<table border='1'>";
echo   arrayToHTMLRow( array('apple', 'dessert' => 'banana','chiapas'), true );
echo   arrayToHTMLRow( array('apple', 'dessert' => 'banana','chiapas'), false );
echo "</table>","\n";


//echo SQLTableToHTML( mysql_query('select * from Users') );

function fudge($str) { return $str; } // or: if supporting objects as stand-alone exprs: return "[".$str."][0]";

test( toJsString(false), "false");
test( toJsString(true), "true");
test( toJsString(0), "0");
test( toJsString(43), "43");
test( toJsString(""), '""');
test( toJsString("hi"), '"hi"');
test( toJsString("h\"i"), '"h\\"i"');  // 6 chars long
test( toJsString("h'i"), '"h\\\'i"');  // 6 chars long
test( toJsString(2.34), '2.34');
test( toJsString(1e3), '1000');
test( toJsString( array() ), fudge('{}'));
test( toJsString( array(2=>99) ), fudge('{2:99}'));
test( toJsString( array(2=>99, "ab"=>98, 3=>"abc", "abcd" => "wxyz") ),
      fudge('{2:99, ab:98, 3:"abc", abcd:"wxyz"}'));
test( toJsString( array(3=>array()) ), fudge('{3:'.fudge('{}').'}'));
test( toJsString( array(3 => array(2=>99, "ab"=>98, 3=>"abc", "abcd" => "wxyz"),
                        4 => "yowza" ) ),
      fudge('{3:'.fudge('{2:99, ab:98, 3:"abc", abcd:"wxyz"}') . ','
           .' 4:"yowza"}'));
test( toJsString( array( 4 => "yowza",
                         3 => array(2=>99, "ab"=>98, 3=>"abc", "abcd" => "wxyz") ) ),
      fudge('{4:"yowza",'
          . ' 3:' . fudge('{2:99, ab:98, 3:"abc", abcd:"wxyz"}') . '}'));

test( toJsString( array(3 => array(2=>99, "ab"=>98, 3=>"abc", "abcd" => "wxyz"),
                        5 => array(7=>77, "AB"=>76, 8=>"ABC", "ABCD" => "WXYZ") ) ),
      fudge('{3:'.fudge('{2:99, ab:98, 3:"abc", abcd:"wxyz"}') . ','
          . ' 5:' . fudge('{7:77, AB:76, 8:"ABC", ABCD:"WXYZ"}') . '}'));

print "\n";




//  `test` doesn't (yet) work for arrays...
//test( array2D_map( sqrt, array(array(1,4,9), array(4,16,36)) ),
//      array(array(1,2,3), array(2, 4, 6)) );

echo "Actual:";
print_r( array2D_map( sqrt, array(array(1,4,9), array(4,16,36)) ) );
echo "Expected:";
print_r( array(array(1,2,3), array(2, 4, 6)) );
?>