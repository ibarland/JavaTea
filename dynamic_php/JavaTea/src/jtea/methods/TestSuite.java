package jtea.methods;

import java.lang.reflect.*;
import java.util.Arrays;
import java.util.ArrayList;

public class TestSuite {

	Method m[];
	ArrayList<Object> args;
	Object obj;

	public TestSuite(String pid) throws Exception {
		Class c = Class.forName(pid);
		m = c.getDeclaredMethods();
		obj = c.newInstance();
	}

	public void addArg(String type, String o) {
		if(args == null) {
			args = new ArrayList<Object>();
		}
		args.add((Object)o);
	}

	public boolean[] run(String type, String ret) throws Exception {
		Object[] a = args.toArray();
		boolean[] b = new boolean[m.length];
		for(int i = 0;i < m.length;i++) {
			b[i] = ret.equals(m[i].invoke(obj, a));
		}
		args = null;
		return b;
	}
}
