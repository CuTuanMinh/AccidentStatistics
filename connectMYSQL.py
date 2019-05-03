import pymysql

def insert_accident(accidentInfo):
	connection = pymysql.connect(host='localhost',user='root',password='ahihi',db='Goutte',charset='utf8')
	try:
		with connection.cursor() as cursor:
			sql = "INSERT INTO accidents(title,timeHappen,dayHappen,monthHappen,vehicle,died,hurt,location,listEntity,url) VALUES" \
				  "(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
			result_count = cursor.execute(sql,(accidentInfo[0],accidentInfo[1],accidentInfo[2],accidentInfo[3],accidentInfo[4],accidentInfo[5],accidentInfo[6],accidentInfo[7],accidentInfo[8],accidentInfo[9]))
			connection.commit()
	finally:
		connection.close()

def selectDay(day):
	connection = pymysql.connect(host='localhost',user='root',password='ahihi',db='Goutte',charset='utf8')
	try:
		with connection.cursor() as cursor:
			sql = "SELECT * FROM accidents WHERE dayHappen = \""+day+"\""
			result_count = cursor.execute(sql)
			text = cursor.fetchall()
			return text
	finally:
		connection.close()

def accidentStatistic():
	connection = pymysql.connect(host='localhost',user='root',password='ahihi',db='Goutte',charset='utf8')
	try:
		with connection.cursor() as cursor:
			sql = "SELECT monthHappen, COUNT(*) AS accidentQuantity, SUM(died) AS diedQuantity, SUM(hurt) AS hurtQuantity " \
					"FROM accidents GROUP BY monthHappen"
			result_count = cursor.execute(sql)
			text = cursor.fetchall()
			return text
	finally:
		connection.close()

def insertStatistic(var):
	connection = pymysql.connect(host='localhost',user='root',password='ahihi',db='Goutte',charset='utf8')
	try:
		with connection.cursor() as cursor:
			sql = "INSERT INTO statistics(month,accidentQuantity,diedQuantity,hurtQuantity) VALUES" \
				  "(%s,%s,%s,%s)"
			result_count = cursor.execute(sql,(var[0],var[1],var[2],var[3]))
			connection.commit()
	finally:
		connection.close()

def checkMonthExsit(var):
	listMonth = []
	connection = pymysql.connect(host='localhost',user='root',password='ahihi',db='Goutte',charset='utf8')
	try:
		with connection.cursor() as cursor:
			sql = "SELECT month FROM statistics GROUP BY month"
			result_count = cursor.execute(sql)
			text = cursor.fetchall()
	finally:
		connection.close()
	for i in text:
		listMonth.append(i[0])
	if var not in listMonth:
		return False
	else:
		return True