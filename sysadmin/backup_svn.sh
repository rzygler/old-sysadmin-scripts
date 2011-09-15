#!/bin/bash

### vars to hold program locations
PROG_CAT="/bin/cat"
PROG_CHMOD="/bin/chmod"
PROG_CHGRP="/bin/chgrp"
PROG_CUT="/usr/bin/cut"
PROG_DATE="/bin/date"
PROG_SVNADMIN="/usr/bin/svnadmin"
PROG_GZIP="/bin/gzip"

### other vars
DATE=`$PROG_DATE +%Y%m%d%H%M%S`
GRP="rzygler"
DEST_ROOT="/path/to/backups/svn"
SVN_LOC="/usr/local/svn/repos"
DATA_FILE="/path/to/svn_to_backup.txt"
LOG_FILE="/path/to/logs/backup_svn/${DATE}_backup_svn.log"


echo "started on " `$PROG_DATE +%Y%m%d%H%M%S` > ${LOG_FILE}

for REPO in `$PROG_CAT $DATA_FILE`
do
	DEST_FILE=${DEST_ROOT}/${DATE}_svn_${REPO}.dump
	echo "dumping ${REPO} to {$DEST_FILE}" >> $LOG_FILE
	$PROG_SVNADMIN dump -q ${SVN_LOC}/${REPO} > $DEST_FILE
	$PROG_CHMOD 750 $DEST_FILE
	$PROG_CHGRP $GRP $DEST_FILE
	$PROG_GZIP $DEST_FILE	
done

echo "ended on " `$PROG_DATE +%Y%m%d%H%M%S` >> $LOG_FILE


