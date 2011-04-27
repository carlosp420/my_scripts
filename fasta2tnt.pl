#!/usr/bin/perl

##
## This script converts sequences in fasta format from NSG's database into files of TNT format
## ready to run, but you will need to edit it first, beacuse you will want to add an outgroup?
## 
## Takes as input a file 'myfasta' having all the sequences, output file is 'mydataset.tnt'
##
##
## Carlos Pena, 2007-04-24
##

use Bio::Perl;
use Bio::Seq;
use Data::Dumper;

use strict;

my @my_all_seq;
my @all_seqs;
my @lengths;
my $number_chars;
my $single_seq;
my $to_add;
my $taxon;
my $my_id;
my $my_seq;
my $seq;
my $diff;

@my_all_seq = read_all_sequences("<myfasta", 'fasta');

#get the number of taxa
my $count = `grep -c '>' myfasta`; 

if (-e "mydataset.tnt") {
	unlink("mydataset.tnt");
}

open (MyConvertedFile, '>>mydataset.tnt');
print MyConvertedFile "nstates dna;\nxread\n";

foreach $taxon (@my_all_seq) {
	#make my seq object
	$my_id  = $taxon -> primary_id;
	$my_seq = $taxon -> seq;
	$seq = Bio::Seq -> new ( -display_id => $my_id,
	 		                    -seq => $my_seq); 
	#get lenght of sequences 
	push ( @lengths, $seq -> length() ); 

}

#get the longest length (number of characters)
@lengths = sort {$b <=> $a} @lengths;
$number_chars = @lengths[0];

#make all sequences to have the same length
foreach $taxon (@my_all_seq) {
	undef $to_add;

	$my_id  = $taxon -> primary_id;
	$my_seq = $taxon -> seq;
	$seq = Bio::Seq -> new ( -display_id => $my_id,
			                 -seq => $my_seq);
	#if different, how much of difference?
	if ($seq -> length() < $number_chars) {
		$diff = $number_chars - $seq -> length();
		for (my $i=0; $i<$diff; $i++) {
			$to_add .= '?';
		} 
		$my_seq = $seq -> seq . $to_add;
	}
	else {
		$my_seq = $seq -> seq;
	}

	#get all ids and sequences in one string
	$single_seq = $seq -> display_id . "\t" . $my_seq . "\n";
	push ( @all_seqs, $single_seq );
}

@all_seqs = sort @all_seqs;

#now print my TNT file
print MyConvertedFile $number_chars, " ", $count, "\n\&\[dna\]\n", @all_seqs, ";\nproc /;"; 
close(MyConvertedFile);
