public class j0001 {

	public static void main(String[] args) {
		args = args[0].split("%1B");
		//1 is probId
		//2 is numfields
		int numfields = Integer.parseInt(args[2]);
		//3 is numcases
		int numcases = Integer.parseInt(args[3]);
		//4-? is input
		//Check if input entered for all fields
		if(args.length == numfields * numcases + 4) {
			printError("Field left blank");
			return;
		}
		printHeader();
		int numTests = 0;
		int testPassed = 0;
		for(int i = 0;i < numcases;i++) {
			Object[] list = new Object[numfields+1];
			for(int j = 0;j < list.length;j++) {
				list[j] = args[4+list.length*i+j];
			}
			if(verify(list)) {
				testPassed += printCase(list, eval(list));
				numTests++;
			}
		}
		printPassRate(numTests, testPassed);
		printFooter();
	}

	private static boolean eval(Object[] list) {
		return Integer.parseInt((String)list[0])*2 == Integer.parseInt((String)list[1]);
	}

	private static int doubleNumber(int num) {
		return num*2;
	}

	private static boolean verify(Object[] list) {
		try {
			Integer.parseInt((String)list[0]);
			Integer.parseInt((String)list[1]);
		} catch(Exception e) {
			printCaseError(e.getMessage());
			return false;
		}
		return true;
	}

	private static void printPassRate(int total, int passed) {
		String print = "";
		print += "<br/>";
		print += "<p align=\"center\">";
		print += (int)((float)passed / (float)total * 100.0);
		print += "% of test cases passed";
		print += "</p>";
		System.out.println(print);
	}

	private static int printCase(Object[] list, boolean eval) {
		String print = "";
		if(eval) {
			print = "<tr>";
			print += "<td>System.out.println(\"Actual value: \" + doubleNumber(" + (String)list[0] + ") + \" , Expected value: " + doubleNumber(Integer.parseInt((String)list[0])) + ");";
			print += "</tr>";
			System.out.println(print);
			return 1;
		} else {
			print = "<tr>";
			print += "<td>Test case failed</td>";
			print += "</tr>";
			System.out.println(print);
			return 0;
		}
	}

	private static void printHeader() {
		System.out.println("<html>");
		System.out.println("<body>");
		System.out.println("<table align=\"center\">");
	}

	private static void printFooter() {
		System.out.println("</table>");
		System.out.println("</body>");
		System.out.println("</html>");
	}

	private static void printCaseError(String mess) {
		System.out.println("<tr><td colspan=2>"+mess+"</td></tr>");
	}

	private static void printError(String mess) {
		System.out.println("<html>");
		System.out.println("<body>");
		System.out.println("<p align=\"center\">");
		System.out.println("<font color=\"BLACK\" size=4>");
		System.out.println(mess+"<br/>");
		System.out.println("Please go back and check you input.");
		System.out.println("</font>");
		System.out.println("</p>");
		System.out.println("</body>");
		System.out.println("</html>");
	}
}
