#!/usr/bin/python
from xml.dom.minidom import parseString
import sys
import run
import verify

dom = parseString(reduce(lambda x, y: x + " " + y, sys.argv[2:]))

def getText(nodelist):
    rc = []
    for node in nodelist:
        if node.nodeType == node.TEXT_NODE:
            rc.append(node.data)
    return ''.join(rc)

def parseCases(top):
    return parseCase(top.getElementsByTagName("testCases").item(0))

def parseCase(cases):
    rc = []
    for case in cases.getElementsByTagName("testCase"):
        rc.append(parseArgs(case))
    return rc

def parseArgs(case):
    rc = []
    for arg in case.getElementsByTagName("argument"):
        rc.append(parseData(arg))
    for ret in case.getElementsByTagName("return"):
        rc.append(parseData(ret))
    return rc

def parseData(s):
    return (getText(s.getElementsByTagName("type").item(0).childNodes), getText(s.getElementsByTagName("value").item(0).childNodes))

print parseCases(dom)
verify.check(sys.argv[1], parseCases(dom))
