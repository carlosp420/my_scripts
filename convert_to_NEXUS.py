#!/usr/bin/python

# input is a TNT file
# output is a Phylip file
# you need to modify the number of total characters until being able to get it as an argument


import string

fileSource= raw_input("Enter the name of file to convert (fasta file): ")
inp = file (fileSource, "r")

outp = file ("data.nex", "w")

header = "#NEXUS\n\nBEGIN DATA;\n"
outp.write(header)

lines = inp.readlines()
ntax = len(lines)/2
ntax = str(ntax)
outp.write("DIMENSIONS NTAX=");
outp.write(ntax)
outp.write(" NCHAR=1500;\nFORMAT MISSING=? GAP=- DATATYPE=DNA;\n\nMATRIX\n")

inp.close()
inp = file (fileSource, "r")

while True:
    X = inp.readline()
    if len(X) == 0:
        break
    a = string.rstrip(X)
    a = a.lstrip(">")
    a = a.replace(" ","")
    a = a.replace("-","_")

    Y = inp.readline()
    b = string.rstrip(Y)
    b = b.ljust(1500, "-");

    outp.write (a + "\t" + b + "\n")

outp.write(";\nEND;")

inp.close()
outp.close()
