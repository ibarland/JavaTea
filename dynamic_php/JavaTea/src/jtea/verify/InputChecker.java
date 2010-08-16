package jtea.verify;

import java.util.Scanner;
import java.net.URI;
import java.util.Iterator;
import java.util.NoSuchElementException;
import javax.tools.JavaCompiler;
import javax.tools.JavaFileObject;
import javax.tools.SimpleJavaFileObject;
import javax.tools.ToolProvider;
import javax.tools.DiagnosticCollector;
import javax.tools.StandardJavaFileManager;

public class InputChecker {

	public static void main(String[] args) {
		Scanner rdr = new Scanner(System.in);
		InputChecker ic = new InputChecker();
		while(rdr.hasNext()) {
			System.out.println(ic.isValid(rdr.next(), rdr.next()));
		}
	}
	
	public boolean isValid(String type, String input) {
	   if(type.equals("String")) {
	   	return validString(input);
	   }   
	   else if(type.equals("int") || type.equals("Integer")) {
		return validInt(input);
	   }
	   else if(type.equals("char") || type.equals("Character")) {
	   	return validChar(input);
	   }
	   else if(type.equals("double") || type.equals("Double")) {
		return validDouble(input);
	   }
	   else if(type.equals("boolean") || type.equals("Boolean")) {
	   	return validBoolean(input);
	   } else {
		return false;
	   }
	}

	private boolean validString(String s) {
		if(s.matches("\"[\\u0000-\\uffff]\"")) {
			//Still need to check for escape characters
			return true;
		}	
		else return false;
	}

	private boolean validInt(String s) {
		try {
			Integer.parseInt(s);
		} catch(NumberFormatException e) {
			return false;
		} catch(Exception e) {
			e.printStackTrace();
		}
		return true;
	}

	private boolean validChar(String s) {
		if(s.matches("'[\\u0000-\\uffff]'")) return true;
		else if(s.matches("'\\[btnfr'\\]'")) return true;
		else if(s.matches("'\\u[0-9a-fA-F]{4}'")) return true;
		else return false;
	}

	private boolean validDouble(String s) {
		try {
			Double.parseDouble(s);
		} catch(NumberFormatException e) {
			return false;
		} catch(Exception e) {
			e.printStackTrace();
		}
		return true;
	}

	private boolean validBoolean(String s) {
		return s.equals("true") || s.equals("false");
	}	

	public String format(String input, String type) {
		return "public class A {\n private static "+type+" a ="
			+input+";\n"+
		       "public static void main(String[] args) {\n"+
		          "System.out.println(a);\n}\n }";
	}
}
