#!/bin/bash
BDLOCALNAME=suitecrm
BDLOCALUSER=root
BDLOCALPWD=stic
BDLOCALHOST=127.0.0.1
BDLOCALPORT=2002


echo "Ejecutando SticCron.php..."
mysql -u $BDLOCALUSER -p$BDLOCALPWD -h $BDLOCALHOST --port $BDLOCALPORT suitecrm -e "use suitecrm;truncate table job_queue;update schedulers set last_run=NULL WHERE 1;" 
rm -f cache/modules/Schedulers/lastrun
wget --delete-after http://localhost:2000/SticCron.php


