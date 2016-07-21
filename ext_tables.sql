
      CREATE TABLE `tx_casreservation_reservation` (
      `id` int(11) NOT NULL auto_increment ,
      `member_id` int(11) unsigned NOT NULL default '0',
      `room` int(3) unsigned NOT NULL  default '0',
      `label` varchar(100) default '',
      `material` int(3) default '0',
      `date_demand` date default '0000-00-00',
      `time_demand` char(5)  default '',
      `date_reserv` date default '0000-00-00',
      `time_reserv` int(11) default '0',
      `date_bill` date default '0000-00-00',
      `date_pay` date default '0000-00-00',
      `paid` float default '0',
      `status` int(11) default '0',
      `note` varchar(300)  default '',
      PRIMARY KEY (`id`)
      );
#--------
      CREATE TABLE `tx_casreservation_room` (
      `id` int(11) NOT NULL auto_increment ,
      `room_name` varchar(100) default '',
      PRIMARY KEY (`id`)
      );
#--------
      CREATE TABLE `tx_casreservation_dates_special` (
      `room_special` int(3) unsigned NOT NULL  default '0',
      `date_special` date NOT NULL default '0000-00-00',
      `time_special` int(11) NOT NULL default '0',
      `type_special` int(11) NOT NULL default '0',
      PRIMARY KEY (room_special,date_special,time_special)
      );
#--------
      CREATE TABLE `tx_casreservation_grid` (
      `room_grid` int(3) unsigned NOT NULL  default '0',
      `weekday_grid` int(11) NOT NULL default '0',
      `time_grid` int(11) NOT NULL default '0',
      `type_grid` int(11) NOT NULL default '0',
      `label_grid` varchar(100) default '',
      PRIMARY KEY (room_grid,weekday_grid,time_grid)
      );
#--------

      CREATE TABLE `tx_casreservation_codes` (
      `no_week` int(11) NOT NULL default '0',
      `room` int(11) NOT NULL default '0',
      `code` int(11) NOT NULL default '0',
      PRIMARY KEY (no_week)
      );
#--------
      CREATE TABLE `tx_casreservation_costs` (
      `room` int(3) unsigned NOT NULL  default '0',
      `nb_periods` int(11) NOT NULL default '0',
      `material` int(3) NOT NULL default '0',
      `price` int(11) NOT NULL default '0',
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
