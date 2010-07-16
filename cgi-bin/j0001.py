#!/usr/bin/python
import cgi

form = cgi.FieldStorage()

def double(init, result):
    str = "<tr><td>double("+init+") == "+result+"</td>"
    if int(init)*2 == int(result):
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

