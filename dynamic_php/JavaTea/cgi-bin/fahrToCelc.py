#!/usr/bin/python

import cgi
import cgitb
# enable error-message logging (to stdout):
cgitb.enable()

import os.path

# For rucs, make sure ~/lib/../jpype found:
from sys import path as syspath
syspath.append("/home/itec120/lib/python2.5/site-packages/")
from jpype import *   





form = cgi.FieldStorage()
jarpath = os.path.join(os.path.abspath('/var/www/JavaTea/'), 'build/classes')
startJVM( getDefaultJVMPath(), "-Djava.class.path=%s" % jarpath )
java.lang.System.out.println("hello woild (JPype is working)")
TestCases = JClass('jtea.methods.TestCases')
InputChecker = JClass('jtea.verify.InputChecker')
t = TestCases()
check = InputChecker()
def double(init, result):
    if(check.isValid(init, "int") and check.isValid(result, "int")):
        str = "<tr><td>double("+init+") == "+result+"</td>"
        if t.doubleNumber(int(init)) == int(result):
            return str + "<td>Works</td></tr>"
        else:
            return str + "<td>Doesn't work</td></tr>"
    else:
        return "<tr><td colspan=2>Invalid input</td></tr>"
print "Content-type:text/html\r\n\r\n"
print '<html>'
print '<head>'
print '<title></title>'
print '</head>'
print '<body>'
print '<table align="center">'
a = len(form) / 2
for i in range(1, a+1):
    print double(form.getvalue('field'+`i`), form.getvalue('result'+`i`))   
print '</table>'
print '</body>'
print '</html>'
shutdownJVM()
