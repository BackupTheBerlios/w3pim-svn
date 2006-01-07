#!/bin/bash

for f in `ls .`; do
	echo $f | grep php && cat $f | sed s/\\.php/\\.php5/ > $f && mv $f `echo $f | sed s/php/php5/`;
done;
f = "contacts/index.php";
cat $f | sed s/[^libgmailer]\\.php/\\.php5/ > $f;

echo "Done";

