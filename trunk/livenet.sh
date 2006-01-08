#!/bin/bash

function upgrade {
	for f in `ls $1`; do
		echo $f | grep php && cat $f | sed s/\\.php/\\.php5/ > $f && mv $f `echo $f | sed s/php/php5/`;
	done;
}

upgrade .
upgrade templates

echo "Done";

