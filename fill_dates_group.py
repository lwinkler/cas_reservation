#! /usr/bin/python
# Utilise pour remplir la table contenant les dates possibles de reservation
import time
import datetime

diff = datetime.timedelta(days=1)
diff2 = datetime.timedelta(weeks=1)

dlu = datetime.date(2010, 8, 16)
dend = datetime.date(2100, 12, 31)
#print "use `typo3_cas-moleson-ch`;"

while dlu<dend:
	#samedi
	dsa=dlu+5*diff
	if (dsa.day<=7):
		print "INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,'",dlu+5*diff,"',",8,",0);"
		print "INSERT INTO tx_casreservation_dates_special (room_special, date_special,time_special,type_special) VALUES(1,'",dlu+5*diff,"',",10,",0);"
	dlu=dlu+diff2
