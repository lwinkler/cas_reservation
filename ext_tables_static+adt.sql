
      CREATE TABLE `tx_casreservation_reservation` (
      `id` int(11) auto_increment ,
      `member_id` int(11) unsigned NOT NULL default '0',
      `room` int(3) unsigned NOT NULL  default '0',
      `label` varchar(100) default '',
      `material` int(3) default '0',
      `date_demand` date default '1970-01-01',
      `time_demand` char(5)  default '',
      `date_reserv` date default '1970-01-01',
      `time_reserv` int(11) default '0',
      `date_bill` date default '1970-01-01',
      `date_pay` date default '1970-01-01',
      `paid` float default '0',
      `status` int(11) default '0',
      `note` varchar(300)  default '',
      PRIMARY KEY (`id`)
      );
#--------
      CREATE TABLE `tx_casreservation_room` (
      `id` int(11) auto_increment ,
      `room_name` varchar(100) default '',
      PRIMARY KEY (`id`)
      );
#--------
      CREATE TABLE `tx_casreservation_dates_special` (
      `room_special` int(3) unsigned NOT NULL  default '0',
      `date_special` date default '1970-01-01',
      `time_special` int(11) default '0',
      `type_special` int(11) default '0',
      PRIMARY KEY (room_special,date_special,time_special)
      );
#--------
      CREATE TABLE `tx_casreservation_grid` (
      `room_grid` int(3) unsigned NOT NULL  default '0',
      `weekday_grid` int(11) default '0',
      `time_grid` int(11) default '0',
      `type_grid` int(11) default '0',
      `label_grid` varchar(100) default '',
      PRIMARY KEY (room_grid,weekday_grid,time_grid)
      );
#--------

      CREATE TABLE `tx_casreservation_codes` (
      `no_week` int(11) default '0',
      `room` int(11) default '0',
      `code` int(11) default '0',
      PRIMARY KEY (no_week)
      );
#--------
      CREATE TABLE `tx_casreservation_costs` (
      `room` int(3) unsigned NOT NULL  default '0',
      `nb_periods` int(11) default '0',
      `material` int(3) default '0',
      `price` int(11) default '0',
      PRIMARY KEY (room,nb_periods,material)
      );
#--------

      CREATE TABLE `tx_casreservation_email` (
      `id` int(11) unsigned NOT NULL auto_increment,
      `reservation_id` int(11) default '0',
      `status1` int(11) default '0',
      `status2` int(11) default '0',
      `price` float default '0',
      PRIMARY KEY (id)
      );
#      `member_id` int(11) unsigned NOT NULL  default '',
#--------

#CREATE VIEW tx_casreservation_view_reservation_full AS 
#SELECT id, login, date_demand, '%d.%m.%Y', time_demand, date_reserv, time_reserv ,status, note, material, date_bill, date_pay, paid, 
#(SELECT count(*) FROM tx_casreservation_reservation AS t2 WHERE t2.date_reserv=t1.date_reserv AND t2.material=t1.material AND t2.member_id=t1.member_id AND t2.status>=2 GROUP BY date_reserv,member_id,material) as nb_periodsAA, 
#(SELECT price/nb_periodsAA FROM tx_casreservation_costs WHERE tx_casreservation_costs.nb_periods=nb_periodsAA AND tx_casreservation_costs.material=t1.material) as price_to_pay, 
#(SELECT status FROM tx_casreservation_reservation WHERE (date_reserv=date_group and time_reserv=time_group) ORDER BY status DESC LIMIT 1) as occupation, 
#t1.label,
#(SELECT count(*) FROM tx_casreservation_reservation WHERE (date_reserv=date_group and time_reserv=time_group and status>0)) as conflict
#FROM tx_casreservation_reservation AS t1 JOIN tx_casreservation_members ON (t1.member_id=tx_casreservation_members.member_id)
#LEFT JOIN tx_casreservation_dates_group ON (date_reserv=date_group and time_reserv=time_group);

#--------



#
# Table structure for table 'be_groups'
#
#CREATE TABLE be_groups (
#	
#);


## INSERT

# Insertion des codes

INSERT INTO tx_casreservation_room VALUES(1,'Mur de grimpe');
INSERT INTO tx_casreservation_room VALUES(2,'Local section');
INSERT INTO tx_casreservation_room VALUES(3,'Local OJ');

# Insertion des codes
# ATTENTION : pas les bons code !!!

INSERT INTO tx_casreservation_codes VALUES(1,1,12345);
INSERT INTO tx_casreservation_codes VALUES(2,1,12345);
INSERT INTO tx_casreservation_codes VALUES(3,1,12345);
INSERT INTO tx_casreservation_codes VALUES(4,1,12345);
INSERT INTO tx_casreservation_codes VALUES(5,1,12345);
INSERT INTO tx_casreservation_codes VALUES(6,1,12345);
INSERT INTO tx_casreservation_codes VALUES(7,1,12345);
INSERT INTO tx_casreservation_codes VALUES(8,1,12345);
INSERT INTO tx_casreservation_codes VALUES(9,1,12345);
INSERT INTO tx_casreservation_codes VALUES(10,1,12345);
INSERT INTO tx_casreservation_codes VALUES(11,1,12345);
INSERT INTO tx_casreservation_codes VALUES(12,1,12345);
INSERT INTO tx_casreservation_codes VALUES(13,1,12345);
INSERT INTO tx_casreservation_codes VALUES(14,1,12345);
INSERT INTO tx_casreservation_codes VALUES(15,1,12345);
INSERT INTO tx_casreservation_codes VALUES(16,1,12345);
INSERT INTO tx_casreservation_codes VALUES(17,1,12345);
INSERT INTO tx_casreservation_codes VALUES(18,1,12345);
INSERT INTO tx_casreservation_codes VALUES(19,1,12345);
INSERT INTO tx_casreservation_codes VALUES(20,1,12345);
INSERT INTO tx_casreservation_codes VALUES(21,1,12345);
INSERT INTO tx_casreservation_codes VALUES(22,1,12345);
INSERT INTO tx_casreservation_codes VALUES(23,1,12345);
INSERT INTO tx_casreservation_codes VALUES(24,1,12345);
INSERT INTO tx_casreservation_codes VALUES(25,1,12345);
INSERT INTO tx_casreservation_codes VALUES(26,1,12345);
INSERT INTO tx_casreservation_codes VALUES(27,1,12345);
INSERT INTO tx_casreservation_codes VALUES(28,1,12345);
INSERT INTO tx_casreservation_codes VALUES(29,1,12345);
INSERT INTO tx_casreservation_codes VALUES(30,1,12345);
INSERT INTO tx_casreservation_codes VALUES(31,1,12345);
INSERT INTO tx_casreservation_codes VALUES(32,1,12345);
INSERT INTO tx_casreservation_codes VALUES(33,1,12345);
INSERT INTO tx_casreservation_codes VALUES(34,1,12345);
INSERT INTO tx_casreservation_codes VALUES(35,1,12345);
INSERT INTO tx_casreservation_codes VALUES(36,1,12345);
INSERT INTO tx_casreservation_codes VALUES(37,1,12345);
INSERT INTO tx_casreservation_codes VALUES(38,1,12345);
INSERT INTO tx_casreservation_codes VALUES(39,1,12345);
INSERT INTO tx_casreservation_codes VALUES(40,1,12345);
INSERT INTO tx_casreservation_codes VALUES(41,1,12345);
INSERT INTO tx_casreservation_codes VALUES(42,1,12345);
INSERT INTO tx_casreservation_codes VALUES(43,1,12345);
INSERT INTO tx_casreservation_codes VALUES(44,1,12345);
INSERT INTO tx_casreservation_codes VALUES(45,1,12345);
INSERT INTO tx_casreservation_codes VALUES(46,1,12345);
INSERT INTO tx_casreservation_codes VALUES(47,1,12345);
INSERT INTO tx_casreservation_codes VALUES(48,1,12345);
INSERT INTO tx_casreservation_codes VALUES(49,1,12345);
INSERT INTO tx_casreservation_codes VALUES(50,1,12345);
INSERT INTO tx_casreservation_codes VALUES(51,1,12345);
INSERT INTO tx_casreservation_codes VALUES(52,1,12345);

# Utiliser pour remplir la grille de couts de location

# mur de grimpe
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(1,1,0, 62);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(2,1,0,100);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(3,1,0,140);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(4,1,0,202);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(5,1,0,240);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(6,1,0,280);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(7,1,0,280);

INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(1,1,1, 80);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(2,1,1,130);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(3,1,1,180);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(4,1,1,260);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(5,1,1,310);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(6,1,1,360);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(7,1,1,360);

# salle section
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(1,2,0,150);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(2,2,0,150);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(3,2,0,150);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(4,2,0,150);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(5,2,0,150);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(6,2,0,150);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(7,2,0,150);

# salle OJ
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(1,3,0,150);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(2,3,0,150);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(3,3,0,150);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(4,3,0,150);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(5,3,0,150);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(6,3,0,150);
INSERT INTO tx_casreservation_costs (nb_periods, room, material, price) VALUES(7,3,0,150);


# Utiliser pour remplir la grille d'occupation d'une semaine
#
#0: libre
#1: non-disponible/abonnement pour le mur
#2: non-utilisé
#3: special

# Mur de grimpe
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,1,8,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,1,10,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,1,12,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,1,14,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,1,16,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,1,18,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,1,20,0,'');
		
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,2,8,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,2,10,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,2,12,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,2,14,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,2,16,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,2,18,3,'Travailleurs b&eacute;n&eacute;voles du mur,<br> Illford CIBA');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,2,20,3,'Travailleurs b&eacute;n&eacute;voles du mur,<br> Illford CIBA');
		
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,3,8,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,3,10,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,3,12,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,3,14,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,3,16,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,3,18,1,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,3,20,1,'');
	
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,4,8,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,4,10,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,4,12,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,4,14,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,4,16,1,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,4,18,1,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,4,20,1,'');

INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,5,8,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,5,10,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,5,12,1,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,5,14,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,5,16,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,5,18,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,5,20,0,'');

INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,6,8,1,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,6,10,1,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,6,12,1,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,6,14,1,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,6,16,1,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,6,18,1,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,6,20,1,'');

INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,7,8,1,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,7,10,1,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,7,12,1,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,7,14,1,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,7,16,1,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,7,18,1,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(1,7,20,1,'');

# Premiere salle du Stamm

INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,1,8,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,1,10,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,1,12,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,1,14,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,1,16,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,1,18,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,1,20,0,'');
		
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,2,8,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,2,10,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,2,12,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,2,14,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,2,16,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,2,18,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,2,20,0,'');
		
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,3,8,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,3,10,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,3,12,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,3,14,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,3,16,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,3,18,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,3,20,0,'');
	
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,4,8,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,4,10,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,4,12,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,4,14,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,4,16,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,4,18,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,4,20,0,'');

INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,5,8,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,5,10,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,5,12,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,5,14,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,5,16,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,5,18,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,5,20,0,'');

INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,6,8,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,6,10,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,6,12,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,6,14,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,6,16,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,6,18,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,6,20,0,'');

INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,7,8,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,7,10,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,7,12,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,7,14,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,7,16,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,7,18,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(2,7,20,0,'');

# Deuxieme salle du Stamm
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,1,8,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,1,10,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,1,12,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,1,14,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,1,16,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,1,18,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,1,20,0,'');
		
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,2,8,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,2,10,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,2,12,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,2,14,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,2,16,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,2,18,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,2,20,0,'');
		
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,3,8,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,3,10,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,3,12,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,3,14,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,3,16,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,3,18,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,3,20,0,'');
	
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,4,8,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,4,10,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,4,12,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,4,14,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,4,16,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,4,18,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,4,20,0,'');

INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,5,8,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,5,10,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,5,12,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,5,14,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,5,16,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,5,18,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,5,20,0,'');

INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,6,8,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,6,10,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,6,12,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,6,14,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,6,16,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,6,18,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,6,20,0,'');

INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,7,8,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,7,10,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,7,12,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,7,14,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,7,16,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,7,18,0,'');
INSERT INTO tx_casreservation_grid (room_grid, weekday_grid, time_grid, type_grid, label_grid) VALUES(3,7,20,0,'');


# Utiliser pour remplir la table de dates  special

# Attention, ne contient que les dates de 2010 : Utiliser le script python pour remplir la table completement 
#  commande : ./fill_dates_group.py >fill_dates_group.typo.sql

INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-01-02 ', 8 ,0);
INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-01-02 ', 10 ,0);
INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-02-06 ', 8 ,0);
INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-02-06 ', 10 ,0);
INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-03-06 ', 8 ,0);
INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-03-06 ', 10 ,0);
INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-04-03 ', 8 ,0);
INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-04-03 ', 10 ,0);
INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-05-01 ', 8 ,0);
INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-05-01 ', 10 ,0);
INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-06-05 ', 8 ,0);
INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-06-05 ', 10 ,0);
INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-07-03 ', 8 ,0);
INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-07-03 ', 10 ,0);
INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-08-07 ', 8 ,0);
INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-08-07 ', 10 ,0);
INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-09-04 ', 8 ,0);
INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-09-04 ', 10 ,0);
INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-10-02 ', 8 ,0);
INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-10-02 ', 10 ,0);
INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-11-06 ', 8 ,0);
INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-11-06 ', 10 ,0);
INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-12-04 ', 8 ,0);
INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,' 2010-12-04 ', 10 ,0);

