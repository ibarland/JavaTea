#!/usr/bin/python
import cgi

form = cgi.FieldStorage()

print "Content-type:text/html\r\n\r\n"
print '<html>'
print '<head>'
print '<title>Hello Word - First CGI Program</title>'
print '</head>'
print '<body>'
print form["field1"].value
print form["result1"].value 
print form["field2"].value
print form["result2"].value
print '<h2>Hello Word! This is my first CGI program</h2>'
print '</body>'
print '</html>'
