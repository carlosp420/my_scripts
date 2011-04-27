#!/usr/bin/python

""" 
	This script creates a dataset from a list of codes in a file named my_list.csv
	If there is no sequence it will fill in with ????
	Itworks for these genecodes:
        COI
        EF1a
        Wingless
        RpS5
        GAPDH
        """

import MySQLdb
db = MySQLdb.connect(host="localhost", user="root", passwd="mysqlborisk!!a", db="filemaker", unix_socket='/tmp/mysql.sock');

geneCodes = ["COI", "EF1a", "wingless", "RpS5", "GAPDH"]
seq_string = "?" #this will be a replacement for NULL sequences
bp = 0 #number of base pairs
for item in geneCodes:
    if(item == "COI"):
        bp = bp + 1487
    if(item == "EF1a"):
        bp = bp + 1240
    if(item == "wingless"):
        bp = bp + 412
    if(item == "RpS5"):
        bp = bp + 617
    if(item == "GAPDH"):
        bp = bp + 691


codes = []
f = open('my_list.csv')

for line in f:
	line = line.replace('"', '')
	line = line.strip(" ")
	codes.append(line)

number_of_taxa = len(codes)

print "nstates dna;\nxread\n", bp, " ", number_of_taxa

taxa = []
for gene in geneCodes:
	print "\n&[dna]"
	for item in codes:
		item = item.rstrip('\n')
		a = "SELECT code, genus, species FROM vouchers WHERE code='" + item + "'"
	
		cursor = db.cursor()
		cursor.execute(a)
		result = cursor.fetchall()
		for record in result:
			seq = "?"
			if(gene=="COI"):
				seq = seq.ljust(1487, "?")
			if(gene=="EF1a"):
				seq = seq.ljust(1240, "?")
			if(gene=="wingless"):
				seq = seq.ljust(412, "?")
			if(gene=="RpS5"):
				seq = seq.ljust(617, "?")
			if(gene=="GAPDH"):
				seq = seq.ljust(691, "?")
			if record[2] == None:
				taxon = record[0] + "_" + record[1] + "_"
				taxon = taxon.replace(" ", "_")
				taxon = taxon.replace(".", "")
				taxa.append(taxon)
			else:
				taxon = record[0] + "_" + record[1] + "_" + record[2]
				taxon = taxon.replace(" ", "_")
				taxon = taxon.replace(".", "")
				taxa.append(taxon)
			
			b = "SELECT sequences FROM sequences WHERE code='" + item + "' AND geneCode='" + gene + "'"
			cursor.execute(b)
			result_b = cursor.fetchall()
			
			if(result_b):
				for record_b in result_b:
					seq = record_b[0].replace('-', '?')
					if(gene=="COI"):
						seq = seq.ljust(1487, "?")
					if(gene == "EF1a"):
						seq = seq.ljust(1240, "?")
					if(gene == "wingless"):
						seq = seq.ljust(412, "?")
					if(gene == "RpS5"):
						seq = seq.ljust(617, "?")
					if(gene=="GAPDH"):
						seq = seq.ljust(691, "?")
					print taxon + "\t\t\t\t" + seq
			else:
				seq = '?'
				if(gene=="COI"):
					seq = seq.ljust(1487, "?")
				if(gene == "EF1a"):
					seq = seq.ljust(1240, "?")
				if(gene == "wingless"):
					seq = seq.ljust(412, "?")
				if(gene == "RpS5"):
					seq = seq.ljust(617, "?")
				if(gene=="GAPDH"):
					seq = seq.ljust(691, "?")
				print taxon + "\t\t\t\t" + seq
print ";\nproc/;"
