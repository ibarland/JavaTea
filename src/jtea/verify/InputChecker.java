import java.util.Scanner;
import java.net.URI;
import java.util.Iterator;
import java.util.NoSuchElementException;
import javax.tools.JavaCompiler;
import javax.tools.JavaFileObject;
import javax.tools.SimpleJavaFileObject;
import javax.tools.ToolProvider;

public class InputChecker {
	
	public static void main(String[] args) {
		InputChecker ic = new InputChecker();
		Scanner rdr = new Scanner(System.in);
		System.out.println("Enter a list of variables seperated by spaces. End the list with a new line. On the next line enter the type for the variables.");
		ic.isValid(rdr.nextLine().split(" "), rdr.next());
	}

	public boolean isValid(String[] input, String type) {
		if(type.equals("String")    || type.equals("int")    ||
		   type.equals("Integer")   || type.equals("char")   ||
		   type.equals("Character") || type.equals("double") ||
		   type.equals("Double")    || type.equals("boolean")||
		   type.equals("Boolean")) {
			try {
				//compile string returned from
				JavaCompiler compiler = ToolProvider.getSystemJavaCompiler();
				Iterable<? extends JavaFileObject> fileObjects;
				fileObjects = makeFile(format(input, type));
				compiler.getTask(null, null, null, null, null, fileObjects).call();				
			} catch(Exception e) {
				e.printStackTrace();
			}				
		}
		return false;
	}

	public Iterable<StringFile> makeFile(String program) {
		final StringFile sf = new StringFile("A", program);
		return new Iterable<StringFile>() {
		   public Iterator<StringFile> iterator() {
		      return new Iterator<StringFile>() {
		         boolean isNext = true;

			 public boolean hasNext() {
			    return isNext;
			 }

			 public StringFile next() {
			    if(!isNext)
			       throw new NoSuchElementException();
			    isNext = false;
			    return sf;
			 }

			 public void remove() {
			    throw new UnsupportedOperationException();
			 }
		      };
		   }
		};
				
	}

	public String format(String[] list, String type) {
		String input = "{";
		for(String s : list) {
			input += s +",";
		}
		input = input.substring(0,input.length()-1)+"}";
		return "public class A {\n private static "+type+"[] a ="
			+input+";\n"+
		       "public static void main(String[] args) {\n"+
		          "System.out.println(a);\n}\n }";
	}
}

class StringFile extends SimpleJavaFileObject {
	final String code;

	StringFile(String name, String code) {
		super(URI.create("string:///"+name.replace('.','/')+Kind.SOURCE.extension), Kind.SOURCE);
		this.code = code;
	}

	public CharSequence getCharContent(boolean ignoreEncodingErrors) {
		return code;
	}
}
