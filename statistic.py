import connectMYSQL

stat = connectMYSQL.accidentStatistic()
for i in stat:
	check = connectMYSQL.checkMonthExsit(i[0])
	if check == False:
		insert = connectMYSQL.insertStatistic(i)
		