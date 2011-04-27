#!/usr/bin/perl

# USE IT TO UPDATE ARBITRARY COLUMNS WITHOUT AFFECTING THE DATA, JUST BY APPENDING IT
# need a file called 'list.csv' that should contain the info:
# The comma to divide the publication "codes" will be included by this script
# 
# "code"
# "my_code"
#
# carlos pena 2007-04-26
#
# TODO: have to check for records that might not be already in the database

use DBI;
use Digest::MD5 'md5_hex';
use strict;

my $db = 'ref_db';
my $host = 'localhost';
my $user = 'root';
my $pass = 'mysqlborisk!!a';
my $filename;
my $line;
my $publishedIn;
my @row;

if (!@ARGV) {
	print "You need to enter the file name of the .csv file\n";
}
else {
	$filename = @ARGV[0];
	open(INFILE, $filename) or die "Can't open input.txt: $!";
	open(OUTFILE, ">todb");

	my $dbh = DBI->connect("dbi:mysql:database=$db;mysql_socket=/tmp/mysql.sock", 'root', 'mysqlborisk!!a') or die "Cannot connect to database: $DBI::errstr";

	# start writing file to upload to MySQL
	print OUTFILE "use ref_db;\n";
	foreach $line (<INFILE>) {
		# clean quotes
		$line =~ s/"//g;
		$line =~ s/\|//g;
		chomp $line;
		$line =~ s/^\s+//;
		$line =~ s/\s+$//;
		my $code = $line;

		my $url = "http://www.satyrus.net/ref_db/story.php?id=" . $code;
		my $hash = md5_hex($url);

		print OUTFILE "update publication set guid=\"", $hash, "\" where id=\"", $code, "\";\n";
	}

	close(OUTFILE);
	close(INFILE);
	print "\n\n\tThe file 'todb' is ready to be uploaded to the MySQL database:\n\t\tdo: mysql -u root -p < todb\n\n";
}
