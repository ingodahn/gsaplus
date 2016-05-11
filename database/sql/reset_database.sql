# Variables cannot be used as database names
# See: https://dev.mysql.com/doc/refman/5.5/en/user-variables.html
# A workaround is using prepared statements, as mentioned in the manual or here:
# http://stackoverflow.com/a/701007

SET @query = CONCAT('DROP DATABASE IF EXISTS ', @db_database);
PREPARE statement FROM @query;
EXECUTE statement;
DEALLOCATE PREPARE statement;

SET @query = CONCAT('CREATE DATABASE IF NOT EXISTS ', @db_database);
PREPARE statement FROM @query;
EXECUTE statement;
DEALLOCATE PREPARE statement;
