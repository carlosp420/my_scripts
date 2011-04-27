#!/usr/bin/python

""" It only works when taking into account all 5 genecodes:
        COI
        EF1a
        Wingless
        RpS5
        GAPDH
        """

import MySQLdb
db = MySQLdb.connect(host="localhost", user="root", passwd="mysqlborisk!!a", db="filemaker", unix_socket='/tmp/mysql.sock');

geneCodes = ["COI", "EF1a", "wingless", "RpS5", "GAPDH"]
bp = 0 #number of base pairs
for item in geneCodes:
    if(item == "COI"):
        bp = bp + 1487
    if(item == "EF1a"):
        bp = bp + 1240
    if(item == "wingless"):
        bp = bp + 412
    if(item == "RpS5"):
        bp = bp + 614
    if(item == "GAPDH"):
        bp = bp + 691
print bp

queries= []
for item in geneCodes:
   a = "SELECT sequences.code, vouchers.genus, vouchers.species, sequences.sequences FROM sequences,vouchers WHERE sequences.code=vouchers.code and subtribe='Euptychiina' AND sequences.sequences!='' and sequences.sequences is not NULL AND geneCode='" + item + "'"
   queries.append(a)

cursor = db.cursor()

i = 0
for item in queries:
   cursor.execute(item)
   result = cursor.fetchall()
   
   outFileName = geneCodes[i]
   outFileName = outFileName.strip("'")
   outFileName = outFileName + ".txt"
   outFile = open (outFileName, "w")
   for record in result:
      name = record[0] + "_" + record[1]
      if(record[2]):
         name = name + "_" + record[2]
      else:
         name = name
      print >> outFile, name, "\t", record[3]
   i = i+1

outFile.close()
lists = [[] for i in range(5)]

# get a list of lists of each taxa per file (datasets)
i = 0
for item in geneCodes:
   fileName  = item  + ".txt"

   infile = open(fileName, "r")

   for line in infile:
      line = line.split("\t")
      name = line[0]
      name = name.strip()
      lists[i].append(name)
   i = i + 1
   infile.close()
   
# do comparisons between datasets
common_taxa = [] # this will hold all the taxa that appear in all datasets
for item in lists[0]:
    a = item
    # 5 is the number of partitions
    if a in lists[1]:
        if a in lists[2]:
            if a in lists[3]:
                if a in lists[4]:
                    common_taxa.append(a)

#clean names to get code
codes = []  #this will hold all the common codes
for item in common_taxa:
    item = item.split("_")
    code = item[0]
    codes.append(code)

#query database for all sequences for this codes
outFileName = "dataset.txt"
outFile = open(outFileName, "w")

print >> outFile, "nstates dna;\nxread"
print >> outFile, bp, len(codes)
i = 0
for item in geneCodes:
    geneCode = item
    print >> outFile, "\n&[dna]"
    query = "SELECT sequences.code, vouchers.genus, vouchers.species, sequences.sequences FROM sequences,vouchers WHERE sequences.code=vouchers.code AND sequences.sequences != '' AND sequences.sequences is not NULL AND geneCode='" + item + "'"
    for item in codes:
         myquery = query + " AND sequences.code='" + item + "'"
         #print myquery
         cursor.execute(myquery)
         result = cursor.fetchall()

         for record in result:
             name = record[0] + "_" + record[1]
             if (record[2]):
                 species = record[2].replace(" ", "")
                 name = name + "_" + species
             else:
                 name = name
             if (geneCode == "COI"):
                 name  = name + "\t" + record[3].ljust(1487, "-")
             elif (geneCode == "EF1a"):
                 name  = name + "\t" + record[3].ljust(1240, "-")
             elif (geneCode == "RpS5"):
                 name  = name + "\t" + record[3].ljust(617, "-")
             elif (geneCode == "GAPDH"):
                 name  = name + "\t" + record[3].ljust(691, "-")
             elif (geneCode == "wingless"):
                 name  = name + "\t" + record[3].ljust(412, "-")
             print >> outFile, name
print >> outFile, ";\nproc/;\n"
outFile.close()
#sequences.code='" + item + "' AND
