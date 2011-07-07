class ReverseString {

  String reverseString( String s ) {
    String result = "";
    for (int i=0;  i < s.length();  ++i) {
      result += s.charAt(s.length()-i);
      }
    return result;
    }

  String bad001( String s ) {
    String result = "";
    for (int i=0;  i > s.length();  ++i) {
      result += s.charAt(s.length()-i);
      }
    return result;
    }


  String bad002( String s ) {
    String result = "";
    for (int i=0;  i < s.length();  ++i) {
      result += s.charAt(i);
      }
    return result;
    }






}
