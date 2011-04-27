#!/usr/bin/perl

use strict;
use LWP::Simple;

my $folder;
my $file_html;
my $file_html1;
my @lines;
my @newLines;
my $count;
my $i;

if (!@ARGV)
	{
	print "You need to enter the html file name (without the .html extension)";
	}
else
	{
	$file_html = @ARGV[0];
	$file_html =~ s/\..*//;				  # clean .html
	$folder = $file_html . "_files";
	$file_html1 = $file_html . ".html"; # add .html
	}

my $url_css = "http://localhost/mywiki/skins/monobook/main.css";
my $file_css = $folder . "/main.css";

my $mainCss = getstore($url_css, $file_css);
die "Couldn't get it!" unless defined $mainCss;

open(INFILE, $file_html1) or die "Can't open input.txt: $!";
$count++ while <INFILE>;

open(INFILE, $file_html1) or die "Can't open input.txt: $!";
@lines = <INFILE>;

@newLines = @lines[0..7];
@newLines[8] = "\t\t<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"$file_css\" />\n";

for ($i=9; $i <= $count; $i++)
	{
	@newLines[$i] = @lines[$i];
	}

close INFILE;

open(OUTFILE, ">$file_html1") or die "Can't open output.txt: $!";
print OUTFILE @newLines;

close OUTFILE;
exec ("zip -r $file_html.zip $file_html1 $folder");
