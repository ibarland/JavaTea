install: install-120

# copy files from ~itec120/src/JavaTea/ (which is also the git repo)
# to ~itec120/{dynamic_php,public_html}/JavaTea as appropriate.
#
install-120:
	ssh itec120@rucs.radford.edu rm -rf dynamic_php/JavaTea
	scp -prv dynamic_php/JavaTea/ itec120@rucs.radford.edu:dynamic_php/JavaTea
	ssh itec120@rucs.radford.edu rm -rf public_html/JavaTea
	scp -prv public_html/JavaTea/ itec120@rucs.radford.edu:public_html/JavaTea
#
# NOTE: It doesn't matter for us, since we rm the dir before we scp it, but:
# scp works differently depending on the trailing-slash of its first arg:
#  "scp dirA/ dirB" is like "scp dirA/* dirB"  if dirB/ already exists.
#  
# Also, the -r (recur) flag is already the default(?); we keep it as reminder.


# We are running this from ~/Projects/JavaTea/Makefile:
#
install-local:
	\rm -rf ../../public_html/JavaTea
	cp -pr public_html/JavaTea/ ../../public_html/JavaTea
	\rm -rf ../../dynamic_php/JavaTea
	cp -pr dynamic_php/JavaTea/ ../../dynamic_php/JavaTea


%.html: %.ixml
	@${MZSCHEME} ${MZ_FLAGS} ${MZ_REQUIRE} ${MZ_PROCESS} -- $<
	/Users/ibarland/Bin/make.sh --keep-going sync-batch




sync-batch:
	${UNISON_CMD} -batch

sync-interactive:
	${UNISON_CMD}

#sync-b:        sync-batch
sync-i: sync-interactive
sync:   sync-interactive

clean:
	-rm *~ *#unisondiff- *.class *.ctxt


MZSCHEME   := /Applications/PLT/bin/mzscheme
MZ_FLAGS   :=
MZ_REQUIRE := --lib scheme --lib "Ian/Xml/ixml2html"
MZ_SOLN_FLAG := --eval "(include-solutions true)"
MZ_PROCESS := --eval "(for-each (l1 make __ true) (vector->list (current-command-line-arguments)))"



UNISON_FLAGS :=
UNISON_PREFS_FILE := "unison-prefs-javaTea-ibarland"
# N.B. unison's prefs file is *not* a path -- unison looks inside the prefs dir (mac: ~/Library/Application\ Support/Unison)
UNISON_CMD   := unison-2.13.16-text ${UNISON_PREFS_FILE} ${UNISON_FLAGS}

