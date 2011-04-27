#!/usr/bin/perl

use strict;

my $line;
my $file = "fasta.fst";
my @ids;
my @seqs;

sub trim($)
{
	my $string = shift;
	$string =~ s/^\s+//;
	$string =~ s/\s+$//;
	return $string;
}

open( FILE, $file) or die "Can't open $file : $!";

while( $line = <FILE> ) {
	if ( $line =~ /^>/ ) {
		$line =~ s/_/ /;
		$line =~ s/_/ /;
		if ( $line =~ /(\s.{1,5}-.{1,5}$)/ ) {
			$line = $1;
			$line =~ s/ //;
			chomp $line;
			push (@ids, $line);
		}
		else {
			print "$line\n";
		}
	}
	else {
		chomp $line;
		push (@seqs, $line);
	}
}
close(FILE);

#create .csv file to MySQL
#open(OUTFILE, ">fasta.csv");
my $count = @seqs;
for (my $i=0; $i<$count; $i++) {
	print "update sequences set sequences='", @seqs[$i], "' where code='", trim(@ids[$i]), "' and geneCode='coi';\n";
}
