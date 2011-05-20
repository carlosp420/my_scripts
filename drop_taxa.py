#! /usr/bin/env python

## This script reads a TNT formated dataset and randomly deletes a number of taxa at a time, while writing remainder of the dataset into a file
## deletes from 1 to ntax-2 taxa

import sys;
import random;

random.seed();

if len(sys.argv) < 5:
	print "You need more arguments.\nusage: command datafile ntax nchar ntaxaToDelete\n\n"
	sys.exit()

datafile = open(sys.argv[1], "r");
ntax  = int(sys.argv[2]);
nchar = int(sys.argv[3]);
k = int(sys.argv[4]);

# get outgroup
tmp = [];
for line in datafile:
	i = 0;
	if len(line) > 60:
		tmp.append(line);

datafile.close()
outgroup = tmp[0].split();
outgroup = outgroup[0];
print "outgroup: ", outgroup, "\n";

# get list containing only unique taxa
datafile = open(sys.argv[1], "r");
tmp = [];
for line in datafile:
	if( len(line) > 60 ):
		a = line.split();
		tmp.append(a[0]);
datafile.close()

# read original dataset into list of lines
mydata = [];
datafile = open(sys.argv[1], "r");
for line in datafile:
	mydata.append(line);
datafile.close()

#remove duplicates
taxa = []
for e in tmp:
	if e not in taxa:
		taxa.append(e);


for i in range(0, ntax):
	# remove outgroup from this list
	taxa.remove(outgroup); 

	if len(taxa) < k + 1:
		sys.exit();

	# remove k random taxa from taxa list
	to_remove = random.sample(taxa, k);

	print "deleting ", k, "taxa";
	for i in to_remove:
		if i in taxa:
			taxa.remove(i);
		
	
	# create a dataset excluding the just deleted taxa
	filename = "dataset_" + str(len(taxa) + 1) + ".tnt";

	print "writing into file name: ", filename, "\n";

	f = open(filename, "w");

	counter = 0;
	output = "";

	# add outgroup to taxa
	taxa.insert(0, outgroup);

	for item in mydata:
	#print item
		if len(item) > 60:
			# if item is in taxa
			for taxon in taxa:
				if taxon in item:
					if taxon == outgroup:
						output += "\n&[dna]\n";
						output += item;
						counter = counter + 1;
					else:
						output += item;
						counter = counter + 1;
	

	header1 = "nstates dna;\nxread\n";
	header2 = str(nchar) + " " + str(counter/4) + "\n";
	
#print str(len(taxa)), "\n";

	f.write(header1);
	f.write(header2);
	f.write(output);
	f.write(";\nproc/;");
	f.close();
