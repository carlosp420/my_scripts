#!/usr/bin/perl

#get sequences from genBank based on accession numbers and output MySQL commands for uploading to a MySQL database
# Carlos Peña 2011-03-19
# infile
# code\taccession\tgeneCode

use Bio::DB::GenBank;
use Bio::SeqIO;
use Bio::Root::Exception;
use Error qw(:try);

my $usage = "script.pl INFILE \n";
my $infile = shift or die $usage;

open (inputfile, "<", $infile) or die $!;

open (output, ">", "get_sequences_from_genbank_output.txt") or die $!;
open (output_failed, ">", "get_sequences_from_genbank_output_failed.txt") or die $!;

print output "\nset names utf8";
while( <inputfile> ) {
	my $line = $_;
	chomp($line);
	print $line;
	my @values = split("\t", $line);
	my $code = $values[0];
	my $accession = $values[1];
	my $geneCode = $values[2];

	$db_obj = Bio::DB::GenBank->new;

	try { 
		$seq_obj = $db_obj->get_Seq_by_acc($accession);
	}
	catch Bio::Root::Exception with {
		my $err = shift;
		print "\nA Bioperl exception ocurred:\n$err\n";
		print output_failed "\n$code\t$accession\t$geneCode";
	}
	finally {
		if($seq_obj) {
			my $seq = $seq_obj->seq;
			print output "\nupdate sequences SET sequences=\"$seq\", timestamp=now(), dateCreation=now() WHERE code=\"$code\" and geneCode=\"$geneCode\";";
		}
	};
}

close(inputfile);
close(output);
close(output_failed);
