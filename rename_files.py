#! /usr/bin/env python

# This script is rename batch of files
# You need to have a text file with a list of files to proccess
# now it changes from .htm into .html
# Carlos Pena - 2006-09-16
#

import string

# open and read list of files to proccess
fo = open("lista", "r");
f1 = fo.readlines()

# proccess one file at a times
for line in f1:
	file_name = line.rstrip()
	# open .html file source
	f=open(file_name, "r")
	# read whole source file and close it
	tags = (f.readlines())
	f.close()
	
	# open .html file target
	new_file_name = str(file_name) + "l"
	fr=open(new_file_name, "w")
	
	for item in tags:
		a = str( item );
		fr.write( a );
	fr.close()
fo.close()