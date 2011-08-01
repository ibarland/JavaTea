/** A test-harness function: if actual and desired aren't identical,
 * print a message to that effect (else just print ".").
 * @param $actual
 */
function test($actual,$desired) {
  if ($actual==$desired) {
    document.write(".");
    }
  else {
    document.write("<pre>\nTest failed:\nactual: "+$actual+"\ndesired: " +$desired+"\n</pre>");
    }
  }

obj = {};
obj[3] = "howdy";
obj["three"] = "hello";

test( obj.hasAPropWithValue("howdy"), true);
test( obj.hasAPropWithValue("hello"), true);
test( obj.hasAPropWithValue(99), false );
test( obj.hasAPropWithValue("ninety-nine"), false );

test( obj.hasAPropWithValue(3), false);
test( obj.hasAPropWithValue("three"), false);


Object.prototype["derp"] = "ack";
test( obj.hasAPropWithValue("ack"), false );
test( obj.hasAPropWithValue("ack",false), false );
test( obj.hasAPropWithValue("ack",true), true );
test( obj.hasAPropWithValue("eeeek",true), false);

// Make sure that passing 'false' explicitly still retains original behavior:
test( obj.hasAPropWithValue("howdy",false), true);
test( obj.hasAPropWithValue("hello",false), true);
test( obj.hasAPropWithValue(99,false), false );
test( obj.hasAPropWithValue("ninety-nine",false), false );

test( obj.hasAPropWithValue(3,false), false);
test( obj.hasAPropWithValue("three",false), false);
