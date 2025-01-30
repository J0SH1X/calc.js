
# Simple js calculator

## Prerequisites

-  **MySQL** or **MariaDB** server installed
## Setting Up the Database

  

### 1. Create the Database
```sql
CREATE  DATABASE  calculator_db;
```
### 2. Create the `calculations` Table and the User
```sql
USE calculator_db;

CREATE  TABLE  calculations (
id INT AUTO_INCREMENT PRIMARY KEY,
browser_id VARCHAR(255) NOT NULL,
expression TEXT  NOT NULL,
result TEXT  NOT NULL,
timestamp  TIMESTAMP  DEFAULT CURRENT_TIMESTAMP
);

CREATE USER 'calc_user'@'localhost' IDENTIFIED BY 'verysavepw';
GRANT SELECT, INSERT ON calculator_db.calculations TO 'calc_user'@'localhost';
```
### 3. Update `backend.php` db credentials

```php
$servername = "localhost";
$username = "calc_user";
$password = "verysavepw";
$dbname = "calculator_db";
```