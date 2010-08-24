package jtea.methods;

import java.lang.reflect.*;

public class TestSuite {

	Method m[];

	public TestSuite(String pid) throws Exception {
		Class c = Class.forName(pid);
		m = c.getDeclaredMethods();
	}

	public static void main(String[] args) {
		try {
		TestSuite t = new TestSuite("jtea.methods.j0001");
		System.out.println(t.run());
		} catch(Exception e) {
			e.printStackTrace();
		}
	}

	public String run() throws Exception {
		String s = "";
		for(int i = 0;i < m.length;i++) {
			s += m[i].toString();
		}
		return s;
	}
}
