class IsPalindrome {

  static boolean isPalindrome( String s ) {
    boolean palSoFar = true;
    for (int i=0;  i < s.length()/2;  ++i) {
      palSoFar = palSoFar && (s.charAt(i) == s.charAt(s.length()-i-1));
      }
    return palSoFar;
    }

  static boolean isPalindrome_bug_A( String s ) {
    // always return true
    boolean palSoFar = true;
    for (int i=0;  i < s.length()/2;  ++i) {
      palSoFar = palSoFar || (s.charAt(i) == s.charAt(s.length()-i-1));
      }
    return palSoFar;
    }

  static boolean isPalindrome_bug_B( String s ) {
    // return true iff *some* char is palindrom-ic
    boolean palSoFar = false;
    for (int i=0;  i < s.length()/2;  ++i) {
      palSoFar = palSoFar || (s.charAt(i) == s.charAt(s.length()-i-1));
      }
    return palSoFar;
    }

  static boolean isPalindrome_bug_C( String s ) {
    return false;
    }

  static boolean isPalindrome_bug_D( String s ) {
    // Fails to identify odd-length palindromes (of length>1).
    // It computes the middle index as an int (which rounds down),
    // and then uses 2*(middle-index) rather than the actual length.
    boolean palSoFar = true;
    int halfLen = s.length()/2;
    for (int i=0;  i < halfLen;  ++i) {
      palSoFar = palSoFar && (s.charAt(i) == s.charAt(2*halfLen-1-i));
      }
    return palSoFar;
    }

    
  static boolean isPalindrome_bug_E( String s ) {
    // works only for odd-length strings, or length 0,2:
    boolean palSoFar = true;
    int halfLen = (s.length()-1)/2;
    for (int i=0;  i < halfLen;  ++i) {
      palSoFar = palSoFar && (s.charAt(i) == s.charAt(2*halfLen-i));
      }
    return palSoFar;
    }


  static boolean isPalindrome_bug_F( String s ) {
    // fails on empty string; works otherwise
    boolean palSoFar;
    try {
      palSoFar = (s.charAt(0) == s.charAt(s.length()-1));
      }
    catch( StringIndexOutOfBoundsException e ) {
      // Swallow the run-time error 
      palSoFar = false;
      }
    for (int i=1;  i < (s.length())/2;  ++i) {
      palSoFar = palSoFar && (s.charAt(i) == s.charAt(s.length()-i-1));
      }
    return palSoFar;
    }

    
  static boolean isPalindrome_bug_G( String s ) {
    // A tougher one: correct except that it doesn't check first/last char:
    boolean palSoFar = true;
    for (int i=1;  i < s.length()/2;  ++i) {
      palSoFar = palSoFar && (s.charAt(i) == s.charAt(s.length()-i-1));
      }
    return palSoFar;
    }
  static boolean isPalindrome_bug_H( String s ) {
    // A tougher one: correct except that it doesn't check middle pair of even:
    boolean palSoFar = true;
    for (int i=0;  i < s.length()/2-1;  ++i) {
      palSoFar = palSoFar && (s.charAt(i) == s.charAt(s.length()-i-1));
      }
    return palSoFar;
    }
    

  }
