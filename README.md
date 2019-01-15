# User_Management

STORIES
============
• As an admin I can add users — a user has a name.

• As an admin I can delete users.

• As an admin I can assign users to a group they aren’t already part of.

• As an admin I can remove users from a group.

• As an admin I can create groups.

• As an admin I can delete groups when they no longer have members.

REQUIREMENTS
============
• Symfony 4.2. (https://symfony.com/download)

• Apache WEB server, version 2.0 or higher. ( http://httpd.apache.org/download.cgi )

• PHP 7.2. ( http://www.php.net/ )

• MySQL 5.6 or higher. ( http://dev.mysql.com/downloads/ )

• IDE (PhpStorm)

INSTALLATION
============
1- Download and Extract the Source Code.

2- Create DataBase:
   - in the terminal of the IDE type:
   
      `php bin/console doctrin:database:create`

3- Create Table:
    -in the terminal of the IDE type these commands to create tables for the entities of the project:
    
        a. php bin/console doctrine:migrations:diff
        
        b. php bin/console doctrine:migrations:migrate

User Guides
============
1- The project consists of two parts: 

    1-1- Web part
  
    1-2- REST API part

**1-1 Web part:**
      
      a. users have to register 
      
      b. THE FIRST USER WHO RWGISTERS WILL BE KNOWN AS ADMIN 
      
      c. PLEASE NOTE THAT ONLY ADMIN CAN DO THE FOLLOWING ACTIONS:
      
           I)   CREATE GROUP 

           II)  DELETE GROUP

           III) ADD USER TO A GROUP

           IV)  REMOVE USER FROM A GROUP

           V)   DELETE USER
        
 **1-2 REST API part:**  
      
        a.  In the registration process a unique token is created for each user in order to identify the senders of the REST requests
        
            I)   token is stored in token column of the user table 
            
            II)  Each user can see only his/her own token in the user list of web version
            
            // TODO  separte table with the userId column, user token column, and also expiration time column must be implemented, etc.
        
        b.  REST REQUESTS MUST START WITH /api/. Such as THE FOLLOWING SAMPLE URL:
        
            `HOMEURL/api/request
            
         
        

  

