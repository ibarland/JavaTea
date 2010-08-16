

install: install-120

# copy files from ~itec120/src/JavaTea/ (which is also the git repo)
# to ~itec120/{dynamic_php,public_html}/JavaTea as appropriate.
#
install-120:
	ssh itec120@rucs.radford.edu rm -rf public_html/JavaTea
	scp -prv public_html/JavaTea/ itec120@rucs.radford.edu:public_html/JavaTea
	ssh itec120@rucs.radford.edu rm -rf dynamic_php/JavaTea
	scp -prv dynamic_php/JavaTea/ itec120@rucs.radford.edu:dynamic_php/JavaTea
#
# NOTE: It doesn't matter for us, since we rm the dir before we scp it, but:
# scp works differently depending on the trailing-slash of its first arg:
#  "scp dirA/ dirB" is like "scp dirA/* dirB"  if dirB/ already exists.
#  
# Also, the -r (recur) flag is already the default(?); we keep it as reminder.


# We are running this from ~/src/JavaTea/Makefile:
#
install-local:
	\rm -rf ../../public_html/JavaTea
	cp -pr public_html/JavaTea/ ../../public_html/JavaTea
	\rm -rf ../../dynamic_php/JavaTea
	cp -pr dynamic_php/JavaTea/ ../../dynamic_php/JavaTea
