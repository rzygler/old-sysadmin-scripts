#!/bin/bash

### vars to hold program locations
PROG_CAT="/bin/cat"
PROG_CHMOD="/bin/chmod"
PROG_CHGRP="/bin/chgrp"
PROG_CUT="/bin/cut"
PROG_DATE="/bin/date"
PROG_GZIP="/bin/gzip"
PROG_MAIL="/usr/bin/mail"
PROG_RUBY="/usr/bin/ruby"

EMAIL_TO=""

### other vars
DATE=`$PROG_DATE +%Y%m%d%H%M%S`
LOG_FILE="/path/to/logs/s3sync/${DATE}_s3_sync.log"
S3_BUCKET="domain.com.backup"
DIR_PROG_S3SYNC="/path/to/s3sync"
PROG_S3SYNC="${PROG_RUBY} s3sync.rb"
PROG_S3CMD="${PROG_RUBY} s3cmd.rb"

DIR_SVN="/path/to/backups/svn"
S3_SVN_KEY="svn"
S3_SVN_STORE=${S3_BUCKET}:${S3_SVN_KEY}
DIR_SQL="/path/to/backups/sql"
S3_SQL_KEY="sql"
S3_SQL_STORE=${S3_BUCKET}:${S3_SQL_KEY}
DIR_WWW="/path/to/backups/www"
S3_WWW_KEY="www"
S3_WWW_STORE=${S3_BUCKET}:${S3_WWW_KEY}

# s3sync isn't globally aware yet so you need
# to change into its dir to run

echo "started on " `$PROG_DATE +%Y%m%d%H%M%S` > ${LOG_FILE}

echo "cd $DIR_PROG_S3SYNC" >> $LOG_FILE
cd $DIR_PROG_S3SYNC

#### svn backup below

# -r = recursive
# -s = over ssl
# -v = verbose
# -n dryrun
# --delete

echo " $PROG_S3SYNC -r -s -v --delete $DIR_SVN $S3_SVN_STORE " >> $LOG_FILE
$PROG_S3SYNC -r -s -v --delete $DIR_SVN $S3_SVN_STORE >> $LOG_FILE

#### sql backup below
echo " $PROG_S3SYNC -r -s -v --delete $DIR_SQL $S3_SQL_STORE " >> $LOG_FILE
$PROG_S3SYNC -r -s -v --delete $DIR_SQL $S3_SQL_STORE >> $LOG_FILE

echo " $PROG_S3SYNC -r -s -v --delete $DIR_WWW $S3_WWW_STORE " >> $LOG_FILE
$PROG_S3SYNC -r -s -v --delete $DIR_WWW $S3_WWW_STORE >> $LOG_FILE

echo "ended on " `$PROG_DATE +%Y%m%d%H%M%S` >> $LOG_FILE

$PROG_CAT $LOG_FILE | $PROG_MAIL -s "s3 sync results" $EMAIL_TO



