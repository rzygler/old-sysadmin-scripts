#!/bin/bash

### Dump table structure and data from db
###
### @author Rich Zygler 
###

### vars to hold program locations

PROG_CAT="/bin/cat"
PROG_DATE="/bin/date"
PROG_GZIP="/bin/gzip"
PROG_MYSQLDUMP="/usr/bin/mysqldump"

### other vars
DATA_FILE="/path/to/dbs_to_backup.txt"
IGNORE_FILE="/path/to/tables_to_ignore.txt"

DATE=`$PROG_DATE +%Y%m%d%H%M%S`

#### fill these in
HOST=""

### can use local .my.cnf file with u/p that looks like this:
# [mysqldump]
# user = test
# password = test

USERNAME=""
PASSWORD=""


for db in `$PROG_CAT $DATA_FILE`;
do
    # reset DB name
    DB=${db}

    # reset the file to save into
    DEST_FILE="${0%/*}/data/${DATE}_${db}.sql"

    # reset tables var
    IGNORES=""

    for table in `$PROG_CAT $IGNORE_FILE`
    do
        # append ignore statement to table
        IGNORES="${IGNORES} --ignore-table=${db}.${table} "
    done

    # lets dump  the table
	#  echo $IGNORES
	#  echo $DEST_FILE
    echo "$PROG_MYSQLDUMP -u $USERNAME -h $HOST $DB $IGNORES > ${DEST_FILE} "
    $PROG_MYSQLDUMP -u $USERNAME -p${PASSWORD} -h $HOST $DB $IGNORES > ${DEST_FILE} 
    $PROG_GZIP $DEST_FILE
done


