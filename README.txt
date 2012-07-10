CAS_RESERVATION 
===============

Author : Laurent Winkler, laurent.winkler@bluewin.ch
Date : April 2010

Management of reservation for the site of CAS : www.cas-moleson.ch

This plug-in allows your web site to manage bookings for different rooms. Registered users can make a demand to book a room. Those demands must be approved by an admin. 

This extension includes :
 - a plug-in to display the availability of any room to the public (grid layout)
 - a plug-in to make a new reservations (registered users)
 - a plug-in to manage reservations for the reservation administrator

Also implemented :
 - Reservations for several rooms
 - Information is sent by e-mail to the user
 - Reservations can generate bills and marked as paid
 - This plug-in can be included on a web site as many time as needed for different users
 - Statistics for each year using jpgraph plots


INSTALLATION : 

1. Install the extension in typo3 extension manager: activate the extention and create all the listed tables.
2. In typo3 backend, go to template -> (info/modify) -> Edit the whole template record -> Include static : add 'cas_reservation'
 - Also check: "Include Static Templates After Basis Templates:" 

3. Fill SQL tables : tables can be filled after extension installation with file ext_tables_static+adt.sql

command: mysql -u typo3 -ptypo3 TYPO3 < ext_tables_static+adt.sql

Except for tables :
	- codes : Use the good script. Not included in plugin for security reasons
	- dates_special : Use the python script to generate the full table : python fill_dates_group.py >fill_dates_group.typo.sql

4. Create front end user groups : (names are given as examples)
	- AdminReservationX : Admin group
	- AllowedUsersX : Members that can ask for reservations
	
5. Insert the 3 plugins on 3 different pages
 - Display week grid
 - create new reservation (page must be restricted to AllowedUsersX)
 - administration of reservations (page must be restricted to AllowedUsersX)

6. Setup the 3 plugins on typo3 via Page, (edit plugin), Plugin. Up to 4 settings can be set. 
 - Admin group number : id of the admin group (as created in typo3 backend) here : AdminReservationX
 - Room to be rent : id of the room to rent separated with a comma. This must correspond with table tx_casreservation_room.
 - Send notification e-mail : 0 or 1 : 1 to send e-mails
 - Email address to send notification e-mails

7. Change text files containing the contents of e-mails and bills. 
 - Files can be found inside the plugin in pimanage directory.
 - For each insertion of the plugin there should be 6 files 
    * head_mail_room<id>.txt
    * line_mail_room<id>.txt
    * foot_mail_room<id>.txt
    * head_bill_room<id>.txt
    * line_bill_room<id>.txt
    * foot_bill_room<id>.txt
    (<id> must be equal to the id of the first room (as given in flexform parameters of plugin configuration in backend))
    e.g. if the plugin is inserted to manage room 3,4 and 5 : <id> = 3
