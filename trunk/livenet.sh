#!/bin/bash

# main files
for f in `ls .`; do
	echo $f | grep php && cat $f | sed s/\\.php/\\.php5/ > $f && mv $f `echo $f | sed s/php/php5/`;
done;

# contacts index file
f="contacts/index.php";
cat $f | sed s/[^libgmailer]\\.php/\\.php5/ > $f;

echo "Done";

