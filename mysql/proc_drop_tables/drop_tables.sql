-- --------------------------------------------------------------------------------
-- Routine DDL
-- --------------------------------------------------------------------------------
DELIMITER $$

CREATE PROCEDURE `drop_tables`( db varchar( 100 ), pattern varchar(100))
BEGIN
  SELECT @str_sql:=concat('drop table ', group_concat( concat(db, '.', table_name)) )
	FROM information_schema.tables
	WHERE table_schema = db AND table_name LIKE pattern;

	PREPARE stmt FROM @str_sql;
	EXECUTE stmt;
END

/*
usage:   call drop_tables( 'test', 'jjj%' );  
*/
