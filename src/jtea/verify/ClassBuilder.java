package jtea.verify;

import java.net.URI;
import java.util.Iterator;
import java.util.NoSuchElementException;
import javax.tools.JavaCompiler;
import javax.tools.JavaFileObject;
import javax.tools.SimpleJavaFileObject;
import javax.tools.ToolProvider;
import javax.tools.DiagnosticCollector;

/**
  *  Takes any java source code from a String compiles it and writes it to
  *  a file of a given name.
  */
public class ClassBuilder {

	private JavaCompiler compiler;
	private DiagnosticCollector<JavaFileObject> feedback;
	private Iterable<? extends JavaFileObject> fileObjects;	
	
	public ClassBuilder() {
		compiler = ToolProvider.getSystemJavaCompiler();
		feedback = new DiagnosticCollector<JavaFileObject>();	
	}
	/**
	Builds a .class file given a name and source code.
	*/
	public boolean build(String name, String source) {
		buildSourceFile(name, source);
		return buildClassFile();
	}

	/**
	Private method that envokes the java compiler to build the .class file.
	*/
	private boolean buildClassFile() {
		try {
			if(fileObjects != null) {
				compiler.getTask(null, null, feedback, null, null, fileObjects).call();
				if(feedback.getDiagnostics().isEmpty()) {
					return true;
				} else {
					return false;
				}
			}
		} catch(Exception e) {
			e.printStackTrace();
		}
	}
	
	/**
	Private method that converts a String into java source code.
	*/
	private void buildSourceFile(String name, String source) {
	    final StringFile sf = new StringFile(name, source);
	    fileObjects = new Iterable<StringFile>() {
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
}
/**
  *  Class used to convert a String into a JavaFileObject to be interpreted by
  *  the JavaCompiler.
  */
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
