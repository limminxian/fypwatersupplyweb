<?php
function getdb(){
$servername = "us-cdbr-east-06.cleardb.net";
$username = "bbd12ae4b2fcc3";
$password = "df9ea7aa";
$db = "heroku_80d6ea926f679b3"; 

/* $servername = "ec2-3-225-213-67.compute-1.amazonaws.com:5432";
$username = "lbftgzbfbhpkxk";
$password = "7730fd74a05533e54625120ba59d494a060111ce887ccd836c95a9d7494ed0b2";
$db = "d1rhm1e7kg5b5e"; 
 */

/* $servername = "localhost";
$username = "root";
$password = "";
$db = "fyp"; */

try {
   
    $conn = mysqli_connect($servername, $username, $password, $db);
     //echo "Connected successfully"; 
    }
catch(exception $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
    return $conn;
}

function createTables(){

// sql to create table
$createTables = "
CREATE TABLE IF NOT EXISTS ROLE (
ID INT UNSIGNED AUTO_INCREMENT,
NAME VARCHAR(30),
DESCRIPTION VARCHAR(50),
REGISTER BOOLEAN,	
PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS USERS (
ID INT UNSIGNED AUTO_INCREMENT,
NAME VARCHAR(30) unique,
NUMBER VARCHAR(30),
EMAIL VARCHAR(50) unique,
PASSWORD VARCHAR(255),
TYPE INT UNSIGNED,
STATUS VARCHAR(10),
PRIMARY KEY (ID),
FOREIGN KEY (TYPE) REFERENCES ROLE(ID)
);

CREATE TABLE IF NOT EXISTS COMPANY (
ID INT UNSIGNED AUTO_INCREMENT,
NAME VARCHAR(30),
STREET VARCHAR(30),
POSTALCODE INT,
DESCRIPTION VARCHAR(50),
ACRAPATH VARCHAR(50),
ADMIN INT UNSIGNED,
PRIMARY KEY (ID),
NOOFSTAR INT,
NOOFRATE INT,
FOREIGN KEY (ADMIN) REFERENCES USERS(ID)
);

CREATE TABLE IF NOT EXISTS STAFF (
ID INT UNSIGNED AUTO_INCREMENT,
WORKLOAD INT,
COMPANY INT UNSIGNED,
PRIMARY KEY (ID),
FOREIGN KEY (ID) REFERENCES USERS(ID),
FOREIGN KEY (COMPANY) REFERENCES COMPANY(ID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS HOMEOWNER (
ID INT UNSIGNED AUTO_INCREMENT,
STREET VARCHAR(30),
BLOCKNO VARCHAR(5),
UNITNO VARCHAR(6),
POSTALCODE INT,
AREA VARCHAR(10),
HOUSETYPE VARCHAR(50),
NOOFPEOPLE INT,
CODE VARCHAR(6),
CARD VARCHAR(4),
SUBSCRIBE INT UNSIGNED,
PRIMARY KEY (ID),
FOREIGN KEY (ID) REFERENCES USERS(ID),
FOREIGN KEY (SUBSCRIBE) REFERENCES COMPANY(ID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS TICKETTYPE (
ID INT UNSIGNED AUTO_INCREMENT,
NAME VARCHAR(50),
DESCRIPTION VARCHAR(100),
TOTECH BOOLEAN,
COMPANY INT UNSIGNED,
PRIMARY KEY (ID),
FOREIGN KEY (COMPANY) REFERENCES COMPANY(ID)
);


CREATE TABLE IF NOT EXISTS TICKET (
DATE DATETIME DEFAULT CURRENT_TIMESTAMP,
ID INT UNSIGNED AUTO_INCREMENT,
HOMEOWNER INT UNSIGNED,
CUSTOMERSERVICE INT UNSIGNED,
STATUS VARCHAR(10),
TYPE INT UNSIGNED,
DESCRIPTION VARCHAR(50),
PRIMARY KEY (ID),
FOREIGN KEY (HOMEOWNER) REFERENCES HOMEOWNER(ID),
FOREIGN KEY (CUSTOMERSERVICE) REFERENCES STAFF(ID),
FOREIGN KEY (TYPE) REFERENCES TICKETTYPE(ID)
);

CREATE TABLE IF NOT EXISTS CHAT (
DATE DATETIME DEFAULT CURRENT_TIMESTAMP,
TICKET INT UNSIGNED,
SENDER INT UNSIGNED,
TEXT VARCHAR(500),
PRIMARY KEY (DATE,TICKET),
FOREIGN KEY (TICKET) REFERENCES TICKET(ID),
FOREIGN KEY (SENDER) REFERENCES USERS(ID)
);

CREATE TABLE IF NOT EXISTS TASK (
ID INT UNSIGNED AUTO_INCREMENT,
DESCRIPTION VARCHAR(50),
SERVICEDATE DATETIME,
DURATION INT,
TECHNICIAN INT UNSIGNED,
TICKET INT UNSIGNED,
PRIMARY KEY (ID),
FOREIGN KEY (TECHNICIAN) REFERENCES STAFF(ID),
FOREIGN KEY (TICKET) REFERENCES TICKET(ID)
);

CREATE TABLE IF NOT EXISTS CHEMICAL (
ID INT UNSIGNED AUTO_INCREMENT,
NAME VARCHAR(30),
DESCRIPTION VARCHAR(30),
AMOUNT INT,
USAGETIME INT,
COMPANY INT UNSIGNED,
PRIMARY KEY (ID),
FOREIGN KEY (COMPANY) REFERENCES COMPANY(ID)
);

CREATE TABLE IF NOT EXISTS CHEMICALUSED (
CHEMICAL INT UNSIGNED,
AMOUNT INT,
USEDATE DATETIME DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (USEDATE,CHEMICAL),
FOREIGN KEY (CHEMICAL) REFERENCES CHEMICAL(ID)
);

CREATE TABLE IF NOT EXISTS EQUIPTYPE (
ID INT UNSIGNED AUTO_INCREMENT,
NAME VARCHAR(30),
DESCRIPTION VARCHAR(30),
AMOUNT INT,
REPLACEMENTPERIOD INT,
DEVICEGUARANTEE INT,
COMPANY INT UNSIGNED,
PRIMARY KEY (ID),
FOREIGN KEY (COMPANY) REFERENCES COMPANY(ID)
);

CREATE TABLE IF NOT EXISTS EQUIPSTOCK (
TYPE INT UNSIGNED,
SERIAL VARCHAR(20),
PURCHASEDATE DATE,
COMPANY INT UNSIGNED,
PRIMARY KEY (SERIAL),
FOREIGN KEY (TYPE) REFERENCES EQUIPTYPE(ID),
FOREIGN KEY (COMPANY) REFERENCES COMPANY(ID)
);

CREATE TABLE IF NOT EXISTS EQUIPMENT (
ID INT UNSIGNED AUTO_INCREMENT,
EQUIPMENT VARCHAR(20),
INSTALLATIONDATE DATE,
UNINSTALLATIONDATE DATE,
HOMEOWNER INT UNSIGNED,
TASK INT UNSIGNED,
PRIMARY KEY (ID),
FOREIGN KEY (EQUIPMENT) REFERENCES EQUIPSTOCK(SERIAL),
FOREIGN KEY (HOMEOWNER) REFERENCES HOMEOWNER(ID),
FOREIGN KEY (TASK) REFERENCES TASK(ID)
);

CREATE TABLE IF NOT EXISTS SERVICETYPE (
ID INT UNSIGNED AUTO_INCREMENT,
NAME VARCHAR(30),
DESCRIPTION VARCHAR(100),
CREATEDBY INT UNSIGNED,
PRIMARY KEY (ID),
FOREIGN KEY (CREATEDBY) REFERENCES USERS(ID)
);

CREATE TABLE IF NOT EXISTS SERVICERATE (
EFFECTDATE DATE,
SERVICE INT UNSIGNED,
RATE DOUBLE,
COMPANY INT UNSIGNED,
PRIMARY KEY (EFFECTDATE,SERVICE,COMPANY),
FOREIGN KEY (SERVICE) REFERENCES SERVICETYPE(ID),
FOREIGN KEY (COMPANY) REFERENCES USERS(ID)
);

CREATE TABLE IF NOT EXISTS WATERUSAGE (
RECORDDATE DATE,
HOMEOWNER INT UNSIGNED,
WATERUSAGE INT,
PRIMARY KEY (RECORDDATE,HOMEOWNER),
FOREIGN KEY (HOMEOWNER) REFERENCES HOMEOWNER(ID)
);

CREATE TABLE IF NOT EXISTS PAYMENT (
ID INT UNSIGNED AUTO_INCREMENT,
METHOD VARCHAR(30),
HOMEOWNER INT UNSIGNED,
CARDNO INT,
CVV INT,
BILLINGADDRESS VARCHAR(100),
PRIMARY KEY (ID),
FOREIGN KEY (HOMEOWNER) REFERENCES HOMEOWNER(ID)
);

CREATE TABLE IF NOT EXISTS BILL (
PAYMENTDATE DATE,
HOMEOWNER INT UNSIGNED,
SERVICE INT UNSIGNED,
AMOUNT INT,
PAYMENT INT UNSIGNED,
PRIMARY KEY (PAYMENTDATE,HOMEOWNER),
FOREIGN KEY (HOMEOWNER) REFERENCES HOMEOWNER(ID),
FOREIGN KEY (SERVICE) REFERENCES SERVICERATE(SERVICE),
FOREIGN KEY (PAYMENT) REFERENCES PAYMENT(ID)
);

CREATE TABLE IF NOT EXISTS COMMENT (
COMPANY INT UNSIGNED,
HOMEOWNER INT UNSIGNED,
RATING INT,
COMMENT VARCHAR(300),
PRIMARY KEY (COMPANY),
FOREIGN KEY (COMPANY) REFERENCES COMPANY(ID),
FOREIGN KEY (HOMEOWNER) REFERENCES HOMEOWNER(ID)
);

CREATE TABLE IF NOT EXISTS SUBSCRIBE (
COMPANY INT UNSIGNED,
HOMEOWNER INT UNSIGNED,
SUBSCRIBEDATE DATE,
UNSUBSCRIBEDATE DATE,
PRIMARY KEY (HOMEOWNER,SUBSCRIBEDATE),
FOREIGN KEY (COMPANY) REFERENCES COMPANY(ID),
FOREIGN KEY (HOMEOWNER) REFERENCES HOMEOWNER(ID)
);
";
$con=getdb();
mysqli_multi_query($con, $createTables);
}

function createSuperadmin(){
	
$hashed = password_hash("123Admin.",PASSWORD_DEFAULT);
$createAdmin ="

INSERT INTO `ROLE` (`NAME`,`DESCRIPTION`,`REGISTER`)
    SELECT 'superadmin','website owner',FALSE
	FROM DUAL
    WHERE NOT EXISTS
        (SELECT ID FROM `ROLE` WHERE NAME = 'superadmin');
		
INSERT INTO `USERS` (`NAME`,`EMAIL`,`PASSWORD`,`TYPE`, `STATUS`)
    SELECT 'Admin1','admin@gmail.com','".$hashed."',R.ID, 'ACTIVE'
	FROM ROLE R
    WHERE NOT EXISTS
        (SELECT id FROM `USERS` WHERE EMAIL = 'admin@gmail.com')
		AND R.NAME='superadmin';

";
$con=getdb();
mysqli_multi_query($con, $createAdmin);

// INSERT INTO `ROLE` (`NAME`, `DESCRIPTION`) 
// VALUES ('superadmin', 'superadmin'), ('companyadmin', 'companyadmin'),('technician', 'technician'), ('customerservice', 'customerservice'),('homeowner', 'homeowner');;ho mysqli_error($con);
}
?>
