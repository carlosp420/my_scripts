#!/usr/bin/perl

### This script pulls out statistics from the NSG database (non-public) and prints out in standard output
#   the information to be added manually to the file /common/carlos/archivos/art/NSG_db01.svg
#   and then upload the exported .png file to the utu servers.
#
#   carlos pena 2007-04-28


use DBI;
use strict;

my $db = 'filemaker';
my $host = 'localhost';
my $user = 'root';
my $pass = 'mysqlboriska';
my $sth;

my $total_bp;

my %query= (); # will hold all the queries we need

my $dbh = DBI->connect("dbi:mysql:database=$db;mysql_socket=/tmp/mysql.sock", $user, $pass) or die "Cannot connect to database: $DBI::errstr";
%query = (
		records => "SELECT id FROM vouchers",
		species => "SELECT DISTINCT genus, species FROM vouchers WHERE species IS NOT NULL AND genus != '?' AND species != '?' AND species != ''",
        sequences => "SELECT id FROM sequences",
        voucher_photos => "SELECT voucherImage from vouchers where voucherImage != 'na.gif'");

while ( my($key, $value) = each(%query) ) {
	$sth = $dbh->prepare($value);
    $sth->execute();
    my $num_rows = $sth->rows;
    print $key, "\t->\t", $num_rows, "\n";
}

my $query = "SELECT sequences FROM sequences";
$sth = $dbh->prepare($query);
$sth->execute();
while (my $mylen = $sth->fetchrow()) {
	$mylen =~ /([actg]+)/i;
#print "$1\n";
	my $unamb_len = length($1);
	$total_bp += $unamb_len;
}
print "DNA basepairs\t->\t$total_bp\n";

$sth->finish();
$dbh->disconnect;
