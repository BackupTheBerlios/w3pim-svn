#!/bin/bash

for f in `find .`; do
	echo $f | grep php && cat $f | sed s/\\.php/\\.php5/ > $f && mv $f `echo $f | sed s/php/php5/`;
done;

echo "";

