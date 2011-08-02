/* Default arguments for functions:

   var myFunc = function(a,b,c) {
     ...
     }.defaults('b_default','c_default');

  @author fatbrain
  @see http://parentnode.org/javascript/default-arguments-in-javascript-functions/
  @see also http://www.openjs.com/articles/optional_function_arguments.php
 */
Function.prototype.defaults = function() {
  var _f = this;
  var _a = Array(_f.length-arguments.length).concat(
    Array.prototype.slice.apply(arguments));
  return function() {
    return _f.apply(_f, Array.prototype.slice.apply(arguments).concat(
      _a.slice(arguments.length, _a.length)));
      }
  /* This is ingenious (or, twisted): 
     You create the function-object, and then call the `defaults` method
     on that object (the function).
     The `arguments` in `_a = ...` is the default-args.
     _a is an array with some uninitialized entries followed by all the defaults.
        [I'm not sure why we don't just concat to `arguments` directly.]
     
     The result of calling `defaults` is a function which
     just applies _f to an array of: the `arguments` that'll be passed to that result
     appended to as many trailing default args as needed.
       [I'm not sure why _f.apply needs to be passed _f ...
        A: The first arg to apply is what function is bound to `this` inside that function.]
       [I'm not sure why Array.prototype.slice.apply isn't passed a `this` arg before arguments...]


     [`slice` is `subarray`. 
      I'm not sure why the prototype is needed, or why it's invoked via `apply`.
      I'm not sure why the following wouldn't work:]

Function.prototype.defaults = function() {
  var _f = this;
  var _a = arguments;
  return function() {
    var defaultsToSkip = _a.length - (_f.length - arguments.length);
    return _f.apply(_f, arguments.concat(_a.slice(defaultsToSkip)));
    }
  }
   */
  }



  function isString( val ) {
    return typeof(val) === "string" || (typeof(val)==="object" && val.constructor === String.prototype.constructor);
    /* thanks to: http://www.planetpdf.com/developer/article.asp?ContentID=testing_for_object_types_in_ja */
    }
/*
[please forward this to Kas Thomas, thx.]

Hi Kas -- I found your article about testing whether something is a string (primitive or object)

   http://www.planetpdf.com/developer/article.asp?ContentID=testing_for_object_types_in_ja

very helpful, thanks.  You finish that page by using (essentially)
   ....constructor.toString().match(/string/i)
but this has one minor problem:
If the object was made with a user-defined type, then constructor.toString() returns the text of the *entire* function, which might accidentally include the word 'string' inside of it.
Matching against /^function String/i does seem to close this hole.

Alternately, one can bypass `toString` and just use
  function isString( val ) {
    return typeof(val) === "string" || (typeof(val)==="object" && val.constructor === String.prototype.constructor);
    }
though this doesn't generalize to letting somebody pass in the type they want to test against, like the regexp does.

(I guess both of these approaches can still be subverted if somebody makes their own class String, or assigns to String.prototype ... not that I'll lose too much sleep over those people.)

Anyway, thanks for your article; while I'd found several pages discussing string vs String, yours was the only I could find that gave me a solution.
Cheers,
--Ian
<script type="text/javascript">
function foo(a) {
  this.num = a;
  }

String.prototype = foo.prototype;k

document.write("hi".constructor === String.prototype.constructor);
document.write(new String("hi").constructor === String.prototype.constructor);


function String(z) {
  this.n = 23;
  }
document.write( new String("hmm") );
var x = new foo(7);
document.write(x.num);
document.write(x.constructor.toString());
document.write("hello".constructor.toString());
document.write(new String("hello").constructor.toString());
function ctr(n) { return n.constructor.toString(); }
document.write( ctr("hi") );
document.write( ctr(new String("hi")) );
document.write( ctr(77) );

document.write("hi".constructor === String.prototype.constructor);
document.write(new String("hi").constructor === String.prototype.constructor);

</script>
*/



String.prototype.trim       = function() { return (this.replace(/^[\s\xA0]+/, "").replace(/[\s\xA0]+$/, "")); }
String.prototype.startsWith = function(str) { return (this.match("^"+str)==str); }
String.prototype.endsWith   = function(str) { return (this.match(str+"$")==str); }
Array.prototype.inArray = function(itm) { return this.indexOf(itm) == -1; }

/** hasAPropWithValue: Return whether `this[x] == val`, for some property x.
 *    See also: inArray (except that we're using the properties here).
 * @param val The object to look through the properties of.
 * @param includeInheritedProps (= false): Look at inherited properties, too?
 * @return whether this[x] == val, for some key x.
 */
Object.prototype.hasAPropWithValue = function(val,includeInheritedProps) { 
  var answer = false;
  for ( var prop in this) {
   answer = (answer || ((this[prop] == val) && (includeInheritedProps || this.hasOwnProperty(prop)))); 
   }
  return answer;
  }


function isset(aVar) { return typeof(aVar)==="undefined"; }

function include(filename) {
    var scriptTag = document.createElement('script');
    scriptTag.src = filename;
    scriptTag.type = 'text/javascript';
	
    var head = document.getElementsByTagName('head')[0];
    head.appendChild(scriptTag)
    }



function includeOnce(filename) {
    var scriptTag = document.createElement('script');
    scriptTag.src = filename;
    scriptTag.type = 'text/javascript';
	
    var head = document.getElementsByTagName('head')[0];
    head.appendChild(scriptTag)
    }

if (!isset(__includedFiles)) { // Guard against *this* file being multi-included!
  var __includedFiles = new Array();
  }


/** Return a DOM node w/ the give tagName, attributes, and body.
 * @param tagName (string)
 * @param attrs (object with properties: string->string )
 * @param body A DOM node, or a string (to be used as text), or 
 */
function enTag( tagName, attrs, bod ) {
  var newTag = document.createElement(tagName);
  for (var attrName in attrs) {
    if (attrs.hasOwnProperty(attrName)) {
      newTag.setAttribute( attrName, attrs[attrName] );
      }
    }
  newTag.appendChild( isString(bod) ? document.createTextNode(bod) : bod ); 
  return newTag; 
  } 

