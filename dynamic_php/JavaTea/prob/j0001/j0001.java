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
		for(int i = 0;i < numcases;i++) {
			Object[] list = new Object[numfields+1];
			for(int j = 0;j < list.length;j++) {
				list[j] = args[4+list.length*i+j];
			}
			if(verify(list)) {
				printCase(list, eval(list));
			}
		}
		printFooter();
	}

	private static boolean eval(Object[] list) {
		return Integer.parseInt((String)list[0])*2 == Integer.parseInt((String)list[1]);
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

	private static void printCase(Object[] list, boolean eval) {
		String print = "<tr>";
		for(Object o : list) {
			print += "<td>"+o.toString()+"</td>";
		}
		print += "</tr>";
		System.out.println(print);
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
