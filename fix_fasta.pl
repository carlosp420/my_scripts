#!/usr/bin/perl -w

use Math::Algebra::Symbols;
use Bio::Seq;
use Bio::SeqIO;

$file_w = Bio::SeqIO->new(-file => '>sequence.fas', -format =>"fasta");

if( !$ARGV[0]) {
	print "\nYou need to enter the filename of the input fasta file to fix\n";
}
else {
	$file_o = Bio::SeqIO->new(-file => $ARGV[0], -format =>"fasta");
	$seq_obj = $file_o->next_seq;

	while ($seq_obj = $file_o->next_seq) {
		# find total seq length
		$t_len = $seq_obj->length();

		# find length ???
		$str = $seq_obj->seq();
		while( $str =~ m/[^\?+]/ ) {
			$str =~ s/[^\?+]//;
		}
		$q_len = length $str;

		# find percentage of ??? in sequence
		if( $t_len eq $q_len ) {
			$percentage = 100;
		}
		else {
			$percentage = ($q_len)*($t_len)/(100);
		}

		# if length ??? < 80%  include in final file
		if( $percentage < 80 ) {
			print "T length $t_len\t Q length $q_len \t";
			$file_w->write_seq($seq_obj);
			print $seq_obj->display_id, "\t", $percentage, "%\n";
		}
	}
}

print "\nOutput file is sequence.fas\n";

