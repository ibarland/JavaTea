#!/usr/bin/python
import cgi
from jpype import *
import os.path

form = cgi.FieldStorage()
jarpath = os.path.join(os.path.abspath('/var/www/JavaTea/'), 'build/jar')
startJVM("/usr/lib/jvm/java-6-sun-1.6.0.20/jre/lib/i386/client/libjvm.so", "-Djava.ext.dirs=%s" % jarpath)
TestCases = JClass('jtea.methods.TestCases')
t = TestCases()
def double(init, result):
    str = "<tr><td>double("+init+") == "+result+"</td>"
    if t.doubleNumber(int(init)) == int(result):
        return str + "<td>Works</td></tr>"
    else:
        return str + "<td>Doesn't work</td></tr>"

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
