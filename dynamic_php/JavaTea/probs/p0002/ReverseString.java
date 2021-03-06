class ReverseString {

  static String reverseString( String s ) {
    String result = "";
    for (int i=0;  i < s.length();  ++i) {
      result += s.charAt(s.length()-i-1);
      }
    return result;
    }

  static String reverseString_bug_A( String s ) {
    // Returns the empty string.
    String result = "";
    for (int i=0;  i > s.length();  ++i) {
      result += s.charAt(s.length()-i);
      }
    return result;
    }

  static String reverseString_bug_B( String s ) {
    // Returns the same string, non-reversed.
    String result = "";
    for (int i=0;  i < s.length();  ++i) {
      result += s.charAt(i);
      }
    return result;
    }

  static String reverseString_bug_C( String s ) {
    // Returns the reverse, but 'shifted'.
    String result = "";
    result += (s.length()==0) ? 'z' : s.charAt( s.length() -1 );
    for (int i=1;  i < s.length();  ++i) {
      result += s.charAt(s.length()-i);
      }
    return result;
    }

  static String reverseString_bug_D( String s ) {
    // Fails on odd-length strings: misses the last char
    String firstHalf  = "";
    String secondHalf = "";
    int halfLen = s.length()/2;
    for (int i=0;  i < halfLen;  ++i) {
      firstHalf  += s.charAt(s.length()-1-i);
      secondHalf += s.charAt(s.length()-1-(i+halfLen));
      }
    return firstHalf + secondHalf;
    }

  static String reverseString_bug_E( String s ) {
    // Fails on even-length strings (but not for want of trying!)
    String firstHalf  = "";
    String secondHalf = "";
    String missingLast = "";
    int halfLen = (s.length())/2;
    for (int i=0;  i < halfLen;  ++i) {
      firstHalf  += s.charAt(s.length()-1-i);
      secondHalf += s.charAt(s.length()-1-(i+halfLen));
      }
    try {
      missingLast += s.charAt( 0 );
      }
    catch (StringIndexOutOfBoundsException e) {
      missingLast += 'z';  // Replace the crash with a wrong result.
      }
    return firstHalf + secondHalf+ missingLast;
    }

  static String reverseString_bug_F( String s ) {
    // Works on anything but empty-string.
    String result = "";
    try {
      result += s.charAt( s.length() -1 );
      }
    catch (StringIndexOutOfBoundsException e) {
      result += 'z';  // Replace the crash with a wrong result.
      }

    for (int i=1;  i < s.length();  ++i) {
      result += s.charAt(s.length()-i-1);
      }
    return result;
    }

  }
