#! /usr/bin/env python

##/*****************************************************************************\
## This Python script gets Partition Congruence Index (PCI) and Bremer support
## values from the file pbs.out which is the output of using the "pbsup.run"
## script to get Partitioned Bremer Support (PBS) values in TNT
##
## 1) The PCI index is described in Brower, A.V.Z. 2006. Cladistics 22: 378-386.
## 2) Your PBS values should be the unmodified output of the script "pbsup.run"
## 3) The output file is called pci.out and it is actually a tree file in
##    parentethical notation. It will have all the PCI values attached to the
##    respective nodes.
## 4) There will be another output file called bremer.out which is a tree file
##    in parentethical notation. It will have all the Bremer support values
##    attached to the respective nodes.
## 
## Carlos Pena 2006-10-27
## last modified 2011-05-24
##\*****************************************************************************/

import string;
import sys;
 
if len(sys.argv) < 4:
	print "You need to enter more arguments.\n\nUsage: pci-bremer.py inputFile outPCIfile outBremerFile;\n";
	print "  inputFile\n\t\t pbs.out file obtained by runing pbsup.run script in TNT";
	print "  outPCIfile\n\t\t output filename to write PCI values to";
	print "  outBremerFile\n\t\t output finame to write Bremer values to";
	sys.exit();

inputFile  = open(sys.argv[1], "r");
outPCIfile = open(sys.argv[2], "w");
outFileBre = open(sys.argv[3], "w");

allLines = inputFile.readlines();
numberLines = len(allLines);

outPCIfile.write("tread\n'Tree with tags'\n");
tree = string.rstrip(allLines[2]);
outPCIfile.write(tree);
outPCIfile.write("\nttag !;");

#write file for Bremer support
outFileBre.write("tread\n'Tree with tags'\n");
tree = string.rstrip(allLines[2]);
outFileBre.write(tree);
outFileBre.write("\nttag !;");

i = 4;
while i < (numberLines-1):
	allLines_splitted = string.split(allLines[i]);

	#get pbs values and make sure are clean
	pbsValues_splitted = allLines_splitted[2].split(",");
	pbsValues_splitted.remove(";")
	
	#get BS
	BS=0 #Bremer support
	for item in pbsValues_splitted:
		a = string.atof(item) #get PBSi
		BS += a
	
	#get PBSi and absPBSi
	PBS = 0
	c = 0
	for item in pbsValues_splitted:
		PBSi = string.atof(item) #get PBSi
		absPBSi = abs(PBSi) #get absPBSi
		b = (absPBSi - BS)
		c += b; #Suma|PBSi| - BS
	
	if (BS != 0):
		pci2 = c/BS; #(Suma|PBSi| - BS)/BS
		pci = BS - pci2; #PCI as float number
		d = round(pci, 1);
		#d = "%g" % (pci);
		PCI = str(d); #PCI as string
		outPCIfile.write("\nttag " + allLines_splitted[1] + " " + PCI + ";");
	else:
		outPCIfile.write("\nttag " + allLines_splitted[1] + " " + "0" + ";");
	i += 1;
	BS = str(BS);
	outFileBre.write("\nttag " + allLines_splitted[1] + " " + BS + ";");
	
outPCIfile.write("\nproc/;");
outFileBre.write("\nproc/;");

inputFile.close();
outPCIfile.close();
outFileBre.close();

print '''
/-----------------------------------------------------\\
|                                                     |
|         The output files are "pci.out" and          |
|      "bremer.out" which are actually a TREEfiles    |
|      in parentethical notation.                     |
|                                                     |
|      Go to TNT, read your data, and then by         |
|      reading pci.out or bremer.out you can display  |
|      results with the "ttag;" command               |
|      (or selecting Trees/MultipleTags/ShowSave).    |
|                                                     |
|                                                     |
\-----------------------------------------------------/''';	 

