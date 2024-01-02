#!/bin/bash
BDLOCALNAME=suitecrm
BDLOCALUSER=root
BDLOCALPWD=stic
BDLOCALHOST=127.0.0.1
BDLOCALPORT=2002


# hacemos el volcado de la tabla fields_metadata
echo Haciendo volcado de la tabla fields_metadata
mysqldump -u $BDLOCALUSER -p$BDLOCALPWD -h $BDLOCALHOST --port $BDLOCALPORT --skip-extended-insert -t suitecrm fields_meta_data > ../SticInstall/sinergiacrm_fields_metadata.sql

