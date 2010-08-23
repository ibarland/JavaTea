import os.path

from jpype import *

def check(data_list):
    classpath = os.path.abspath('.')
    startJVM( getDefaultJVMPath(), "-Djava.class.path=%s" % classpath)
    pack = JPackage('jtea').verify
    InputChecker = pack.InputChecker
    check = InputChecker()
    for case in data_list:
        for item in case:
            type, data = item
            if check.isValid(type, data):
                print "It's all good"
            else:
                print "Nope"
    shutdownJVM()

check([[(u'int', u'1'), (u'int', u'2')]])
