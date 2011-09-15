#!/bin/bash

### vars to hold program locations
PROG_CAT="/bin/cat"
PROG_CHMOD="/bin/chmod"
PROG_CHGRP="/bin/chgrp"
PROG_CUT="/usr/bin/cut"
PROG_DATE="/bin/date"
PROG_TAR="/bin/tar"
PROG_RM="/bin/rm"
PROG_GZIP="/bin/gzip"
PROG_MV="/bin/mv"

### other vars
DATE_STR="+%Y%m%d%H%M%S"
GRP="rzygler"
ORIG_PATH="/var/www/"
DEST_ROOT="/path/to/backups/www"
DATA_FILE="/path/to/webdirs_to_backup.txt"

echo "started on " `$PROG_DATE +%Y%m%d%H%M%S`

cd $DEST_ROOT
#$PROG_RM *.gz

for LINE in `$PROG_CAT $DATA_FILE`
do
	# copy older files
	$PROG_MV ${LINE}.tar.gz.5 ${LINE}.tar.gz.6
	$PROG_MV ${LINE}.tar.gz.4 ${LINE}.tar.gz.5
	$PROG_MV ${LINE}.tar.gz.3 ${LINE}.tar.gz.4
	$PROG_MV ${LINE}.tar.gz.2 ${LINE}.tar.gz.3
	$PROG_MV ${LINE}.tar.gz.1 ${LINE}.tar.gz.2
	$PROG_MV ${LINE}.tar.gz ${LINE}.tar.gz.1
	
	$PROG_TAR -cf ${LINE}.tar ${ORIG_PATH}${LINE}
	$PROG_GZIP ${LINE}.tar
done

$PROG_CHMOD -R 750 *
$PROG_CHGRP -R $GRP *

echo "ended on " `$PROG_DATE +%Y%m%d%H%M%S`



