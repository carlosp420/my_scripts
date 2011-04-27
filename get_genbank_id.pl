#!/usr/bin/perl -w

use Bio::DB::GenBank;

$gb = new Bio::DB::GenBank;

open(MYFILE, "list_of_accessions");

if( -e "get_genbank_ERROR" )
	{
	open(ERRORFILE, ">>get_genbank_ERROR");
	}
else
	{
	open(ERRORFILE, ">get_genbank_ERROR");
	}

if( -e "accession_and_id")
	{
	open(TOFILE, ">>accession_and_id");
	}
else
	{
	open(TOFILE, ">accession_and_id");
	}

print TOFILE "use filemaker;\n";

while( $line = <MYFILE> )
	{
	chomp($line);
	print $line, "\n";
	$seq = $gb->get_Seq_by_acc($line);
	if( $seq )
		{
		$gi = $seq->primary_id();
		print TOFILE "update sequences set genbank_id='", $gi, "' where accession='", $line, "';\n";
		}
	else
		{
		print ERRORFILE $line, "\n";
		}
	}

close(MYFILE);
close(TOFILE);
close(ERRORFILE);