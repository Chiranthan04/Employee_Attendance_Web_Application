

step 1 - install xampp 
        open the xampp control panel
        start - Apache server and mysql server


step 2- past Projetct_A folder in the location - C:\xampp\htdocs (before pasting delete all the existing files there)

step 3 - 
        open web browser - paste this URL
        database url = http://localhost/phpmyadmin

step 4 - in phpmyadmin > create new table emp

step 5 - goto  phpmyadmin > SQl > paste the below query > press GO

    copy this and paste in phpmysql- 

    CREATE TABLE `emp`.`userdata` (
        `name` VARCHAR(30) NOT NULL ,
        `PBno` VARCHAR(5) NOT NULL , 
        `password` VARCHAR(100) NOT NULL , 
        PRIMARY KEY (`PBno`)
        ) ENGINE = InnoDB; 


    CREATE TABLE `emp`.`attendance_data` (
        atid VARCHAR(50) ,
        PBno VARCHAR(5) NOT NULL,
        year INT(4) NOT NULL,
        month CHAR(10) NOT NULL,
        osdDate FLOAT,
        trainingDate FLOAT,
        leaves FLOAT,
        PRIMARY KEY (`atid`),
        FOREIGN KEY (PBno) REFERENCES `emp`.`userdata`(PBno)
    )ENGINE = InnoDB;


step 6 - website is ready to run . Run the below URL in the browser

        website url = https://localhost/Projetct_A/login.php

