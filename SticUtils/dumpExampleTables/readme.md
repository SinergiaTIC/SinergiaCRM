# How to create a SQL file with sample data for SinergiaCRM

## Involved files 
- `tableList.txt` Contains a list of the db tables that must be dumped into the SQL file. These tables must not include records created by a user with id different than 9. In that case, these records wouldn't be properly deleted when needed.

- `dumpExampleTables.sh` The dump script. By default it gets data from juansuite.sinergiacrm.org instance but can be changed if needed. The script also removes some unneeded lines and solves a syntax error.

## How to run it
```bash
bash dumpExampleTables.sh
```

# SQL file path
Created file must be saved in SticInclude/data/InsertSticData.sql (as stated in STIC#751).
