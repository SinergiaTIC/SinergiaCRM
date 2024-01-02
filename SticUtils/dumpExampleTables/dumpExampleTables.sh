# Path of the SQL file to be generated.
SQLFILE=../../SticInclude/data/InsertSticData.sql

# File containing the list of db tables to be dumped in the SQL file.
TABLES=$(cat tableList.txt)

# Database dump to the SQL file. In case of using a different data source, connection data must be properly changed.
mysqldump -h juansuite.sinergiacrm.org -u juan -pRomerilla01 -tNc --disable-keys --replace --skip-comments --lock-tables=false --skip-add-locks --skip-lock-tables --skip-create-options myjuan $TABLES >$SQLFILE

# Fix IGNORE SPACES
sed -i 's/INSERT  IGNORE/INSERT IGNORE/' $SQLFILE

# Remove comments
sed -i '/^\/\*/d' $SQLFILE

# Remove blank lines
sed -i '/^ *$/d' $SQLFILE

# Create a fake user with id 9
echo 'REPLACE INTO `users` (`id`, `user_name`, `user_hash`, `system_generated_password`, `pwd_last_changed`, `authenticate_id`, `sugar_login`, `first_name`, `last_name`, `is_admin`, `external_auth_only`, `receive_notifications`, `description`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `title`, `photo`, `department`, `phone_home`, `phone_mobile`, `phone_work`, `phone_other`, `phone_fax`, `status`, `address_street`, `address_city`, `address_state`, `address_country`, `address_postalcode`, `deleted`, `portal_only`, `show_on_employees`, `employee_status`, `messenger_id`, `messenger_type`, `reports_to_id`, `is_group`, `factor_auth`, `factor_auth_interface`) VALUES ("9", "sintest", "$2y$10$b2sbPQ74K/uFNtwpDa5.kuKDGJikD3ZVebKdeAWnJ1BvZ.dSqvSte", 0, "2022-06-03 11:08:00", NULL, 1, NULL, "SinergiaCRM-TEST", 0, 0, 1, NULL, "2022-06-03 11:08:31", "2022-06-03 11:11:36", "9", "2", "SinergiaCRM-TEST", NULL, NULL, NULL, NULL, NULL, NULL, NULL, "Inactive", NULL, NULL, NULL, NULL, NULL, 0, 0, 1, "Active", NULL, NULL, "", 0, 0, NULL);' >> $SQLFILE

echo File $SQLFILE created.
echo Please check that all dumped records are created by the user with id 9.
