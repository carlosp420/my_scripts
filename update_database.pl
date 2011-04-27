#!/usr/bin/perl
# update NSG_DB database; input: mysql_backup_2007-0X-XX.sql

use strict;

my $sql_file = 'nymphalidae.sql';
my $bz2_sql_file = 'nymphalidae.sql.bz2';
my $backup_file;
my $db = 'filemaker';
my $user = 'root';
my $pass = 'mysqlboriska';


if (!@ARGV) {
	print "\nYou need to enter the mysql_backup_2007-XX-XX.sql file name\n";
}
else {
	print "\nThis script updates database filemaker\n";
	$backup_file = @ARGV[0];

	# prepare filemaker.sql to be uploaded to filemaker database
	system("mysql $db -u $user -p$pass < $backup_file ");
	
	print "\nDatabase filemaker is updated now...\n";
	print "\nExecute migrate_db.php\n";
}
