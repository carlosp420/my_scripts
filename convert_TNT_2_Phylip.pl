#!/usr/bin/perl

# input is a TNT file
# output is a Phylip file
# you need to modify the number of total characters until being able to get it as an argument


if (!@ARGV[0]) {
	print "You need to enter the file name of the .csv file\n";
}
else {
	$filename = @ARGV[0];
	open(INFILE, $filename) or die "Can't open input.txt: $!";
	open(OUTFILE, ">data.phylip");
}

$count = 0;
foreach $line (<INFILE>) {
	if ( $line =~ m/^\d+\s+/ ) {
		chomp $line;
		@ntax = split(" ", $line);
		print $ntax[1], " ", $ntax[0], "\n";
	}
	chomp $line;
	$line =~ s/\t+/    /m;

	$le = length($line);
	if ( $line =~ m/\&\[dna\]/ ) {
		$count++;
		if ( $count > 1 ) {
			print "\n";
		}
	}
	if ($le > 17 && $count eq 1) {
		if ( $line =~ m/\&\[dna\]/ ) {
			print "";
		}
		else {
			print $line, "\n";
		}
	}
	elsif ($le > 17 && $count > 1) {
		@inter = split(" ", $line);
		print $inter[1], "\n";
	}
}

#lines = inp.readlines()
#ntax = len(lines)/2
#ntax = str(ntax)
#outp.write(ntax)
#outp.write(" 4447\n")
#
#inp.close()
#inp = file (fileSource, "r")
#
#while True:
#X = inp.readline()
#if len(X) == 0:
#break
#a = string.rstrip(X)
#a = a.lstrip(">")
#a = a.replace(" ","")
#a = a.replace("-","_")
#
#p = re.compile(a)
#if (re.compile("nstates").search(a, 1)):
#print "YES"
#
#Y = inp.readline()
#b = string.rstrip(Y)
#b = b.ljust(1500, "-");
#
#outp.write (a + "\t" + b + "\n")
#
#outp.write(";\nEND;")
#
#inp.close()
#outp.close()
