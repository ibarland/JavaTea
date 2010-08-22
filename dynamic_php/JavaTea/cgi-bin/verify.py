import os.path

from jpype import *

def check(data_list):
    classpath = os.path.join(os.path.abspath('/home/itec120/'), 'build/classes')
    startJVM( getDefaultJVMPath(), "-Djava.class.path=%s" % classpath)
    InputChecker = JClass('jtea.methods.InputChecker')
    check = InputChecker()
    ret = 1
    for item in data_list:
        type, data = item
        if check.isValid(data, type):
            print "It's all good"
        else:
            print "Nope"
    shutdownJVM()
    return ret

print check([[(u'int', u'1'), (u'int', u'2')]])
