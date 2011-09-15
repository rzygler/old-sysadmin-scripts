#!/bin/bash

###
### Dump table structure and data 
###
### @author Rich Zygler 
###

### vars to hold program locations

PROG_CAT="/bin/cat"
PROG_DATE="/bin/date"
PROG_MYSQLDUMP="/usr/bin/mysqldump"

### other vars
DATA_FILE="/home/rzygler/scripts/db_tables_to_export.txt"
OUT_FILE="/home/rzygler/scripts/export.sql"

### fill these in for the database we are dumping from
DB=""
USERNAME=""
PASSWORD=""
HOST=""


### 
TABLES=""

for line in `$PROG_CAT $DATA_FILE`; 
do
  TABLES="${TABLES} ${line}" 
done

# --no-data

$PROG_MYSQLDUMP -u $USERNAME -p${PASSWORD} -h $HOST $DB $TABLES > $OUT_FILE




