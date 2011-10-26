.PHONY: sync sync-interactive sync-i sync-batch sync-b

# A function, to list .html for each .ixml file in the given directory, $(1).
allFilesIn = $(patsubst %.ixml,%.html,$(wildcard $(1)/*.ixml))
# Note that we don't (currently) auto-instigate building on %-soln.html.


sync-batch:
	${UNISON_CMD} -batch

sync-interactive:
	${UNISON_CMD}

#sync-b:	sync-batch
sync-i: sync-interactive
sync:   sync-interactive

clean:
	-rm *~ *#unisondiff- *.class *.ctxt




UNISON_FLAGS := 
UNISON_PREFS_FILE := "unison-prefs-javaTea-ibarland"
# N.B. unison's prefs file is *not* a path -- unison looks inside the prefs dir (mac: ~/Library/Application\ Support/Unison)
UNISON_CMD   := unison-2.13.16-text ${UNISON_PREFS_FILE} ${UNISON_FLAGS}
