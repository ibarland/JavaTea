class IsPalindrome {

  static boolean isPalindrome( String s ) {
    boolean palSoFar = true;
    for (int i=0;  i < s.length()/2;  ++i) {
      palSoFar = palSoFar && (s.charAt(i) == s.charAt(s.length()-i-1));
      }
    return palSoFar;
    }

  static boolean isPalindrome_bug_A( String s ) {
    boolean palSoFar = true;
    for (int i=0;  i < s.length()/2;  ++i) {
      palSoFar = palSoFar || (s.charAt(i) == s.charAt(s.length()-i-1));
      }
    return palSoFar;
    }

  static boolean isPalindrome_bug_B( String s ) {
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
    boolean palSoFar = false;
    for (int i=0;  i < (s.length()-1)/2;  ++i) {
      palSoFar = palSoFar && (s.charAt(i) == s.charAt(s.length()-i-1));
      }
    return palSoFar;
    }

  static boolean isPalindrome_bug_E( String s ) {
    boolean palSoFar = false;
    for (int i=0;  i < (s.length()-1)/2;  ++i) {
      palSoFar = palSoFar && (s.charAt(i) == s.charAt(s.length()-i-1));
      }
    return palSoFar;
    }


  }
