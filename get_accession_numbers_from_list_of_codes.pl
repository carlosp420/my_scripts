#!/usr/bin/perl

# USE IT TO GET ACCESSION NUMBERS FROM FILEMAKER DB FROM A LIST OF CODES where there is no sequence in the database
# need two arguments, a list of codes and the geneCode you want to process
# 
# the list of codes should be a file like this:

# my_code1
# my_code2
#
# carlos pena 2008-03-04
#

use DBI;
use strict;

my $db = 'filemaker';
my $host = 'localhost';
my $user = 'root';
my $pass = 'mysqlboriska';
my $filename;
my $line;
my @row;
my $geneCode;

if (!@ARGV[0] or !@ARGV[1]) {
	print "You need to enter the file name of the .csv file\n";
	print "also include as second argument the geneCode you want to process\n";
}
else {
	$filename = @ARGV[0];
	$geneCode = @ARGV[1];
	open(INFILE, $filename) or die "Can't open input.txt: $!";
	open(OUTFILE, ">todb");

	my $dbh = DBI->connect("dbi:mysql:database=$db;mysql_socket=/tmp/mysql.sock", 'root', $pass) or die "Cannot connect to database: $DBI::errstr";

	foreach $line (<INFILE>) {
		# clean quotes
		$line =~ s/"//g;
		$line =~ s/\|//g;
		chomp $line;
		$line =~ s/^\s+//;
		$line =~ s/\s+$//;
		my $code = $line;

		my $query = "SELECT id, code, geneCode, accession FROM sequences WHERE geneCode='$geneCode' AND code='$code' AND sequences is null OR sequences ='' OR sequences ='null'";
		my $sth = $dbh->prepare($query);
		$sth->execute();

		while(my @data = $sth->fetchrow_array()) {
			if( $data[3] ) {
				print OUTFILE $data[1], "\t", $data[3], "\t", $data[2], "\n";
			}
		}
	}

	close(INFILE);
	close(OUTFILE);
}
