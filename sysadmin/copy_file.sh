#!/bin/bash

###
### Copy files from source to target
###
### @author Rich Zygler 
###

### vars to hold program locations

PROG_CAT="/bin/cat"
PROG_CP="/bin/cp"
PROG_DATE="/bin/date"

### other vars
DATA_FILE="/path/to/files_to_copy.txt"

DIR_SOURCE="/path/to/source/dir"
DIR_TARGET="/path/to/target/dir"

for line in `$PROG_CAT $DATA_FILE`; 
do
  CP_OPT=""

  # check if it ends in slash in which case its a whole directory
  # we do this copy recursively
  if [[ $line =~ /$ ]]
    then
      CP_OPT="-R"
  fi
  sudo $PROG_CP $CP_OPT ${DIR_SOURCE}$line ${DIR_TARGET}$line
done


