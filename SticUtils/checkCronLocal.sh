# sqlCommand="use suitecrm;update schedulers set last_run=NULL,catch_up=0 where id='$1';delete from job_queue where scheduler_id='$1'"
sqlCommand="use suitecrm;update schedulers set last_run=NULL,catch_up=0,job_interval='*::*::*::*::*', status='Active' where id='$1';"
​
echo $sqlCommand
​
mysql -u root -h 172.17.0.1 --port 2002 -pstic  -e "$sqlCommand"
wget --delete-after http://localhost:2000/SticCron.php
​
mysql -u root -h 172.17.0.1 --port 2002 -pstic  -e "use suitecrm;select name, status, resolution from job_queue where scheduler_id='$1' order by date_entered desc"
