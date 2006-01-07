#!/bin/bash

#rename files
for f in `find .`; do
	echo $f | grep php && mv $f `echo $f | sed s/php/php5/`;
done;

