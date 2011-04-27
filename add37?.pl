#!/usr/bin/perl

### This file append strings to the beginning of sequences in order to complete older sequences
### in the case we are using the same gene, but differente primers so our new sequeces become longer
### modific according to your needs when pulling your sequeces to modify from the database
#   The output is standard output almost ready to upload to genbank
#   carlos pena 2007-04-26


use DBI;
use strict;

my ($Id,$Code,$Seq,$Len);
my $db = 'filemaker';
my $host = 'localhost';
my $user = 'root';
my $pass = 'mysqlborisk!!a';
my $string = '?????????????????????????????????????';

my $dbh = DBI->connect("dbi:mysql:database=$db;mysql_socket=/tmp/mysql.sock", 'root', 'mysqlborisk!!a') or die "Cannot connect to database: $DBI::errstr";
my $query = "SELECT id,
					code,
                    sequences,
                    CHAR_LENGTH(sequences) as mylen
				FROM sequences
				WHERE geneCode='coi'
				  AND CHAR_LENGTH(sequences) < 1453
			      AND CHAR_LENGTH(sequences) > 670
				  ORDER by id";
my $sth = $dbh->prepare($query);
$sth->execute();

$sth->bind_columns(\$Id, \$Code, \$Seq, \$Len);

while($sth->fetch()) {
	my $seq = lc $Seq;
	my $index = index $seq, 'tga', 35;
	my $index2 = index $seq, $string;
	if( (($index == 37) && ($index2 == 0)) && ( ($seq =~ /^\?/) || ($seq =~ /^-/)) ) {
		next; #print "Nothing to do for record code: $Code\n";
	}

	if( (($seq =~ /^tga/) || ($seq =~ /^\?/) || ($seq =~ /^-/)) && ($index != 37)) {
		if(( length($seq) < 1453 )  && ($index2 != 0)) {
			$seq = $string . $seq;
			$seq = uc($seq);
		print "update sequences set sequences='$seq' where id='$Id';\n";
#		print $Code , "\n";
		}
	}
}

$sth->finish();
$dbh->disconnect;
