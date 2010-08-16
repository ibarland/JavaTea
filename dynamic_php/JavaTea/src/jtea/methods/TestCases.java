package jtea.methods;

public class TestCases {

	public TestCases() {}

	public int doubleNumber(int number) {
		return number*2;
	}
	
	public String reverseString(String str) {
		if(str.length() == 0) return "";
		else {
			return str.charAt(str.length()-1) + reverseString(str.substring(0, str.length()-1));
		}
	}
}
