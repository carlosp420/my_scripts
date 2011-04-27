#!/usr/bin/perl

# This file is to produce a script in order to convert an audio file to mp3 format

use strict;


my $file;
my $file_no_ext;

system("ls *wma > list");

$file = "list";
open(INFILE, $file) or die "Cant open file $!";
open(OUTFILE, ">list.sh");

foreach my $line (<INFILE>) {
	chomp($line);
	$line =~ s/\..+$//g;
	print OUTFILE "ffmpeg -i '$line.wma' -ab 128 '$line.mp3'\n";
	print  "ffmpeg -i $line.wma -ab 128 $line.mp3\n";
}

system("sudo chmod a+x list.sh");
system("rm list");

print "\n Do chmod a+x list.sh\n Do ./list.sh\n\n";

system("./list.sh");


close(INFILE);
close(OUTFILE);

