CREATE DATABASE IF NOT EXISTS AskNicelyDB;
USE AskNicelyDB;

-- Grant all privileges to the 'Developer' user on the AskNicelyDB database
GRANT ALL PRIVILEGES ON AskNicelyDB.* TO 'Developer'@'%';

-- Flush the privileges to apply the changes
FLUSH PRIVILEGES;