import os.path
import sys

from jpype import *

classpath = os.path.abspath('.')
jvmpath = "/usr/lib/jvm/java-6-sun-1.6.0.20/jre/lib/i386/client/libjvm.so"
#jvmpath = getDefaultJVMPath()
startJVM(jvmpath, "-Djava.class.path=%s" % classpath)

def check(data_list, pid):
    pack = JPackage('jtea').verify
    InputChecker = pack.InputChecker
    check = InputChecker()
    print "<html>"
    print " <body>"
    for case in data_list:
        flag = 1
        for item in case:
            type, data = item
            flag = flag and check.isValid(type, data)
        if flag:
            print "   <p align=\"center\">Running tests..."
            print case
            run(pid, case)
            print "   </p><br\>"
        else:
            print "   <p align=\"center\">Improper input."
            print case
            print "   </p><br\>"
    print " </body>"
    print "</html>" 

def run(pid, case):
    pack = JPackage('jtea').methods
    TestSuite = pack.TestSuite
    t = TestSuite("jtea.methods."+pid)
