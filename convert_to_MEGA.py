#!/usr/bin/python

import string

fileSource= raw_input("Enter the name of file to convert (fasta file): ")
inp = file (fileSource, "r")

outp = file ("data.meg", "w")

header = "#MEGA\n!TITLE blablabla;\n\n"
outp.write(header)

while True:
    X = inp.readline()
    if len(X) == 0:
        break
    a = string.rstrip(X)
    a = a.lstrip(">")
    a = a.replace(" ","")

    Y = inp.readline()
    b = string.rstrip(Y)
    b = b.ljust(1600, "-");

    outp.write ("#" + a + "\t" + b + "\n")

inp.close()
outp.close()
