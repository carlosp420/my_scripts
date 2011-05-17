#!/usr/bin/python

## It takes a file.trees output from BEAST and does a burnin
## script.py burnin input.trees output.trees

import sys;
import re;

if len(sys.argv) < 4:
	print "You need more arguments";
	print "Usage: script.py burnin input.trees output.trees";
	print "burnin has to be the number of trees to burn\n";
	sys.exit();

try:
	a = int(sys.argv[1]);
except:
	print "Your burnin has to be a number";
	exit(1);

burnin = int(sys.argv[1]);
print "Burning ", burnin, " trees";

inputfile = open(sys.argv[2], "r");
outputfile = open(sys.argv[3], "w");

i = 0;
# read trees and fwrite them without the burned trees
for line in inputfile:
	m = re.search('^tree STATE', line);
	if(m != None):
		i += 1;
		if ( i > burnin ): 
			outputfile.write(line);
	else:
		outputfile.write(line);

inputfile.close();
outputfile.close();
