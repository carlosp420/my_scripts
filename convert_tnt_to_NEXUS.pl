#!/usr/bin/perl

# input is a TNT file
# output is a NEXUS file
# you need to modify the number of total characters until being able to get it as an argument

if (!@ARGV[0]) {
	print "You need to enter the file name of the .csv file\n";
}
else {
	$filename = @ARGV[0];
	open(INFILE, $filename) or die "Can't open input.txt: $!";
}


$header = "#NEXUS\n\nBEGIN DATA;\n";
print $header;

foreach $line (<INFILE>) {
	if ($line =~ m/^\d+\s+/) {
		chomp $line;
		@ntax = split(" ", $line);
		print "DIMENSIONS NTAX=". $ntax[1], " NCHAR=", $ntax[0], ";\n";
		print "FORMAT INTERLEAVE DATATYPE=DNA MISSING=? GAP=-;\nMATRIX\n";
	}

	$le = length($line);

	if ( $line =~ m/\&\[dna\]/ ) {
		print "\n";
	}
	
	if ($le > 17) {
		$line =~ s/\t+/    /m;
		@inter = split(" ", $line);
		$inter[0] =~ s/-/_/g;
		print $inter[0], "      ", $inter[1], "\n";
	}
}

print ";\nEND;";
		
#inp = file (fileSource, "r")

#while True:
#X = inp.readline()
#if len(X) == 0:
#break
#a = string.rstrip(X)
#a = a.lstrip(">")
#a = a.replace(" ","")
#a = a.replace("-","_")

#Y = inp.readline()
#b = string.rstrip(Y)
#b = b.ljust(1500, "-");

#outp.write (a + "\t" + b + "\n")


#inp.close()
#outp.close()
