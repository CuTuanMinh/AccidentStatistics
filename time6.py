import re
from pyvi import ViTokenizer, ViPosTagger
import polyglot
from polyglot.text import Text
import connectMYSQL
import datetime
import checkSame2

damage = ['tử vong','chết','thương','nguy kịch','nằm la liệt','nhập viện','thiệt mạng','cấp cứu']
vehicle = ['ô tô','ôtô','xích lô','taxi','công nông','container','xe','xe đầu kéo','tàu','phương tiện']
accident_verb = ['lao','đè','tông','đâm','cán','nghiến','va','rơi','đối đầu','đấu đầu','gặp nạn','lật','nghiêng','đổ','gãy','gây tai nạn','húc','cháy','leo','nằm','trèo','chèn','ép','kéo']
today = ['sáng nay','trưa nay','chiều nay','hôm nay','tối nay']
yesterday = ['đêm qua','tối qua','hôm qua']
unit = ['thị trấn', 'thị xã','xã', 'tỉnh', 'huyện', 'phường', 'thành phố','tp', 'TP', 'Tp', 'đèo', 'quận', 'hầm', 'cầu', 'ấp', 'thôn', 'xóm', 'ngách', 'ngõ', 'phố', 'khu tập thể', 'phường', 'làng', 'đường','cao tốc','quốc lộ','QL','Quốc lộ','cảng']
damage_died = ['tử vong','chết','thiệt mạng']
damage_hurt = ['trọng thương','bị thương','nhập viện','cấp cứu']

file = open('content.txt')
namepage = file.readline()
url = file.readline()
timeInit = file.readline()
titleOrigin = file.readline()
contentOrigin = file.read()
file.close()
title = titleOrigin.lower()
content = contentOrigin.lower()


def extractTime(var):
	ex_time = ' '
	code = Text(var)
	for m in code.sentences:
		k = str(m).lower()
		# check2 = re.search('gây ra|xảy ra',k)
		# check3 = re.search('vụ\s|tai nạn',k)
		# checkVec = False
		# checkAccVerb = False
		# for i in vehicle:
		# 	hasVehicle = re.search(i,k)
		# 	if hasVehicle:
		# 		checkVec = True
		# 		break

		# for j in accident_verb:
		# 	hasAccidentVerb = re.search('\s'+j+'\s',title)
		# 	if hasAccidentVerb:
		# 		checkAccVerb = True
		# 		break
		# if ((check2 != None) & (check3 != None)) | (checkVec == True) | (checkAccVerb == True):
		time = re.search('(.|khoảng.|vào.|lúc.)(\d{1,2}[h\:]\d{1,2}|\d{1,2}h|d{1,2}.giờ|\d{1,2}.giờ.\d{1,2}|' \
					  '\d{1,2}.giờ.\d{1,2}.phút)',k)
		if time:
			ex_time = time.group()
			break
	
	return ex_time

def extractDay(var,dayInit):
	ex_day = None
	code = Text(var)
	for m in code.sentences:
		k = str(m).lower()
		check2 = re.search('gây ra|xảy ra',k)
		check3 = re.search('vụ\s|tai nạn',k)
		checkVec = False
		checkAccVerb = False
		for i in vehicle:
			hasVehicle = re.search(i,k)
			if hasVehicle:
				checkVec = True
				break

		for j in accident_verb:
			hasAccidentVerb = re.search('\s'+j+'\s',k)
			if hasAccidentVerb:
				checkAccVerb = True
				break
		if ((check2 != None) & (check3 != None)) | (checkVec == True) | (checkAccVerb == True):
			checkday = re.search('(sáng.+|chiều.+|trưa.+|tối.+|nửa đêm.+|.)' \
			             '(ngày.(0?[1-9]|[12]\d|3[01])[\/\-\.](0?[1-9]|1[012])[\/\-\.]\d{4}|ngày.(0?[1-9]|[12]\d|3[01])[\/\-\.](0?[1-9]\D|1[012]\D)|' \
			             '(0?[1-9]|[12]\d|3[01])[\/\-\.](0?[1-9]|1[012])[\/\-\.]\d{4}|(0?[1-9]|[12]\d|3[01])[\/\-\.](0?[1-9]\D|1[012]\D))',k)

			if checkday:
				ex_day = checkday.group()
				break

	if ex_day == None:
		ex_day = dayInit
	return ex_day

def extractVehicle(district,veh,accverb):
	code = Text(district)
	for m in code.sentences:
		k = str(m).lower()
		for x in veh:
			for y in accverb:
				check = re.search(x+'.+'+y,k)
				if check:
					while check:
						a = check.group()
						b = check.group()
						a = a[:0] + a[1:]
						for z in vehicle:
							check = re.search(z+'.+'+y,a)
							if check:
								break
					data = b
					data_token = ViTokenizer.tokenize(data)
					data_token = data_token.split()
					# print(data_token)
					# print(2)
					for i in data_token:
						i = i.replace('_',' ')
						for j in vehicle:
							check2 = re.search(j,i)
							if check2:
								return i
	return ' '

def extractDamage(news,damageType):
	quantity_damage = ' '
	code = Text(news)
	done = False
	for m in code.sentences:
		k = str(m).lower()
		for i in damageType:
			check = re.search('(\d{1,2}|một|hai|ba|bốn|năm|sáu|bảy|tám|chín|mười|cặp)(.người|.+thanh niên|.+phụ nữ|' \
								'.+đàn ông|.+cô gái|.+nạn nhân|.+tài xế|.+bé|.+cháu|.+vợ chồng).+'+i+'|người.+'+i+'|thanh niên.+'+i+'|phụ nữ.+'+i+'|' \
								'đàn ông.+'+i+'|cô gái.+'+i+'|nạn nhân.+'+i+'|tài xế.+'+i+'|bé.+'+i+'|cháu.+'+i+'|vợ chồng.+'+i+'|khách.+'+i,k)
			if check:
				while check:
					a = check.group()
					b = check.group()
					while a[0] != ' ':
						a = a[:0] + a[1:]
					a = a[:0] + a[1:]
					a = a[:0] + a[1:]
					check = re.search('(\d{1,2}|một|hai|ba|bốn|năm|sáu|bảy|tám|chín|mười|cặp)(.người|.+thanh niên|.+phụ nữ|' \
								'.+đàn ông|.+cô gái|.+nạn nhân|.+tài xế|.+bé|.+cháu|.+vợ chồng).+'+i+'|người.+'+i+'|thanh niên.+'+i+'|phụ nữ.+'+i+'|' \
								'đàn ông.+'+i+'|cô gái.+'+i+'|nạn nhân.+'+i+'|tài xế.+'+i+'|bé.+'+i+'|cháu.+'+i+'|vợ chồng.+'+i+'|khách.+'+i,a)
				quantity_damage = b
				done = True
				break
		if done == True:
			break

	checklast = re.search("không",quantity_damage)
	if (checklast != None) | (quantity_damage == ' '):
		return 'không'
	else:
		return quantity_damage

def extractLocation(var,accverb):
	zen = Text(var)
	location = []
	location2 = []
	availableLocation = []
	for x in zen.sentences:
		check0 = False
		for entity in x.entities:
			if entity.tag == 'I-LOC':
				check0 = True
				break

		strType = str(x)
		strTypeLower = str(x).lower() 
		check1 = re.search('đến|\sra\s|tại|trên|\sở\s|đi qua|tới|qua',strTypeLower)
		check2 = re.search('gây ra|xảy ra',strTypeLower)
		check3 = re.search('vụ\s|tai nạn',strTypeLower)

		for y in accverb:
			check4 = re.search(y,strTypeLower)
			if check4:
				break

		if ((check4 != None) & (check1 != None) & (check0 == True)) | ((check1 != None) & (check2 != None) & (check3 != None) & (check0 == True)):
			# print(strType)
			# print(x.entities)	
		# if ((check4 != None) & (check0 == True)) | ((check0 != None) & (check2 != None) & (check3 != None)):
		# Đến đoạn vòng xuyến giao với đường Hồ Xuân Hương, xe máy của hai cha con va chạm với xe tải biển Đăk Nông 
		# do Võ Ngọc Dũng (33 tuổi, trú Quảng Nam) cầm lái, đang rẽ phải lên hướng cầu Tiên Sơn.
			check6 = re.search('(quốc lộ.|Quốc lộ.|QL|QL.)(\d{1,2}\s|\d{1,2}[a-zA-Z])',strType)
			if check6:
				location.append(check6.group())
				location2.append(check6.group())
			check7 = re.search('TPHCM',strType)
			if check7:
				location.append(check7.group())
				location2.append(check7.group())

			for i in x.entities:
				if i.tag == 'I-LOC':
					# print(i)
					customLocation = ' '.join(i)
					if (customLocation not in location2) & (customLocation.find(' ') != -1):
						location2.append(customLocation)
					for z in unit:
						check5 = re.search(z+'.'+customLocation,strType)
						if check5:
							customLocation = z+' '+customLocation
							break
					if (customLocation not in location) & (customLocation.find(' ') != -1):
						location.append(customLocation)
			break
	return [location,location2]


def extractLocationStandard(var):
	return ', '.join(var[0])


def customDamage(var):
	quantity = 0
	customQuantity = re.search('\d{1,2}(.người|.+thanh niên|.+phụ nữ|' \
							'.+đàn ông|.+cô gái|.+nạn nhân|.+tài xế|.+bé|.+cháu|.+vợ chồng|.+khách)',var)
	if customQuantity == None:
		continueCheck = re.search('(một|hai|ba|bốn|năm|sáu|bảy|tám|chín|mười|cặp)(.người|.+thanh niên|.+phụ nữ|' \
							'.+đàn ông|.+cô gái|.+nạn nhân|.+tài xế|.+bé|.+cháu|.+vợ chồng|.+khách)',var)
		if continueCheck:
			nextContinueCheck = re.search('một|hai|ba|bốn|năm|sáu|bảy|tám|chín|mười|cặp',continueCheck.group())
			if nextContinueCheck.group() == 'một':
				quantity = 1
			elif nextContinueCheck.group() == 'hai':
				quantity = 2
			elif nextContinueCheck.group() == 'ba':
				quantity = 3
			elif nextContinueCheck.group() == 'bốn':
				quantity = 4
			elif nextContinueCheck.group() == 'năm':
				quantity = 5
			elif nextContinueCheck.group() == 'sáu':
				quantity = 6
			elif nextContinueCheck.group() == 'bảy':
				quantity = 7
			elif nextContinueCheck.group() == 'tám':
				quantity = 8
			elif nextContinueCheck.group() == 'chín':
				quantity = 9
			elif nextContinueCheck.group() == 'cặp':
				quantity = 2
			else:
				quantity = 10
		else:
			if var != 'không':
				quantity = 1
	else:
		digit = re.search('\d{1,2}',customQuantity.group())
		quantity = int(digit.group())
	return quantity


# main--------------------------------------------------------------------------------
	
haveAccident = False
checkFirst = re.search('\:|vụ\s|sự việc|việc|\?',title)

if checkFirst:
	haveAccident = False
else:
	for i in vehicle:
		matchVehicle = re.search(i,title)
		if matchVehicle:
			for j in accident_verb:
				matchAccident = re.search('\s'+j+'\s',title)
				if matchAccident:
					haveAccident = True
					# print("DAU HIEU vehicle & verb: "+i+' '+j)
					break
			break

	if haveAccident != True:
		for i in vehicle:
			matchVehicle = re.search(i,title)
			if matchVehicle:
				for j in damage:
					matchDamage = re.search(j,title)
					if matchDamage:
						haveAccident = True
						break
				break
	if haveAccident != True:
		for i in damage:
			matchDamage = re.search(i,title)
			if matchDamage:
				for j in accident_verb:
					matchAccident = re.search('\s'+j+'\s',title)
					if matchAccident:
						haveAccident = True
						break
				break

if haveAccident == True:
	# print("Title: "+titleOrigin)
	exTime = extractTime(contentOrigin)
	exDay = extractDay(contentOrigin,timeInit)

	accident_vehicle = extractVehicle(titleOrigin,vehicle,accident_verb)
	if accident_vehicle == ' ':
		accident_vehicle = extractVehicle(contentOrigin,vehicle,accident_verb)
	
	quantity_died = extractDamage(titleOrigin,damage_died)
	if quantity_died == 'không':
		quantity_died = extractDamage(contentOrigin,damage_died)
	quantity_hurt = extractDamage(title,damage_hurt)
	if quantity_hurt == 'không':
		quantity_hurt = extractDamage(contentOrigin,damage_hurt) 

	accident_location0 = extractLocation(titleOrigin,accident_verb)
	accident_location = extractLocationStandard(accident_location0)
	if accident_location == '':
		accident_location0 = extractLocation(contentOrigin,accident_verb)
		accident_location = extractLocationStandard(accident_location0)
	# accident_locationEntity = ', '.join(accident_location0[1])
	
# custom data-------------------------------------------------------------------------
	customday = re.search('\d.+',exDay)
	
	newCustomDay = customday.group()
	checkSignal = re.findall('[\/\-\.]',newCustomDay)
	if checkSignal:
		for i in checkSignal:
			if i != '/' :
				newCustomDay = newCustomDay.replace(i,'/')
	

	checkSignal2 = re.findall('\D',newCustomDay)
	if checkSignal2:
		for i in checkSignal2:
			if i != '/':
				newCustomDay = newCustomDay.replace(i,'')

	checkCustomDay = re.search('\d{4}',newCustomDay)
	if checkCustomDay == None:
		newCustomDay = newCustomDay+'/2019'

	checkMonth = re.search('\/\d{1,2}\/',newCustomDay)
	if checkMonth:
		month = checkMonth.group()

	day = re.sub('\/.+','',newCustomDay)
	if len(day) == 1:
		day = '0'+day

	month = month.replace('/','')
	month_digit = int(month)
	if len(month) == 1:
		month = '0'+month

	customDay = day+'/'+month+'/'+'2019'
	

	for x in accident_verb:
		checkVehicle = re.search('\s'+x,accident_vehicle)
		if checkVehicle:
			accident_vehicle = accident_vehicle.replace(' '+x,'')
	if (accident_vehicle == 'ôtô') | (accident_vehicle == 'ô tô'):
		accident_vehicle = 'ô tô'
	if accident_vehicle == 'container':
		accident_vehicle = 'xe ' + accident_vehicle
	if (accident_vehicle == 'xe') | (accident_vehicle == 'phương tiện'):
		accident_vehicle = 'chưa xác định được'

	quantity_died2 = customDamage(quantity_died)
	quantity_hurt2 = customDamage(quantity_hurt)

	
	print("Namepage: "+namepage)
	print("EX DAY: "+exDay)
	print("Thời gian: "+exTime)
	print("Ngày: "+customDay)
	print("MOnth digit: ")
	print(month_digit)
	# if accident_vehicle != ' ':
	print("Phương tiện gây tai nạn: "+accident_vehicle)
	print("Tử vong: "+quantity_died)
	print("Tử vong:",quantity_died2,'người')
	print("Bị thương: "+quantity_hurt)
	print("Bị thương:",quantity_hurt2,'người')
	# if accident_location != '':
	print("Địa điểm: "+accident_location)
	# else:
		# print("location rong")
	# print("Entity of Địa điểm: "+accident_locationEntity)


	locationString = str(accident_location0[1])
	locationString = locationString.replace('[','')
	locationString = locationString.replace(']','')
	locationString = locationString.replace("'",'')
	locationString = locationString.replace(' ','')

	
	

# check Same ----------------------------------------------------------------------------
	if (accident_vehicle != ' ') & (accident_location != '') & (month_digit <= 5):
		accidentInfo = [titleOrigin,exTime,customDay,month_digit,accident_vehicle,quantity_died2,quantity_hurt2,accident_location,locationString,url]	
		# connMySQL = connectMYSQL.insert_accident(accidentInfo)
		select = connectMYSQL.selectDay(customDay)
		if select == ():
			connMySQL = connectMYSQL.insert_accident(accidentInfo)
			print("INSERTED1")
		else:
			# check location
			sameLocation = checkSame2.checkLocation(locationString,select)
			if sameLocation == False:
				connMySQL = connectMYSQL.insert_accident(accidentInfo)
				print("INSERTED2")
				# connMySQL = connectMYSQL.insert_accident(exTime,customDay,month_digit,accident_vehicle,quantity_died2,quantity_hurt2,accident_location)
			else:
				# check died
				sameDied = checkSame2.checkDied(quantity_died2,select)
				if sameDied == False:
					connMySQL = connectMYSQL.insert_accident(accidentInfo)
					print("INSERTED3")
				else:
					# check hurt
					sameHurt = checkSame2.checkHurt(quantity_hurt2,select)
					if sameHurt == False:
						connMySQL = connectMYSQL.insert_accident(accidentInfo)
						print("INSERTED4")
					else:
						# check vehicle		
						sameVehicle = checkSame2.checkAccVehicle(accident_vehicle,select)
						if sameVehicle == False:
							connMySQL = connectMYSQL.insert_accident(accidentInfo)
							print("INSERTED5")
						else:
							# check time
							sameTime = checkSame2.checkAccTime(exTime,select)
							if sameTime == True:
								print("Vụ giao thông này đã có")
							else:
								connMySQL = connectMYSQL.insert_accident(accidentInfo)
								print("INSERTED6")
							

	
	print("-----------------------------------------------------------------------")
	print("\n")	
else:
	print("Namepage: "+namepage)
	print("NOT ACCIDENT")
	print("-----------------------------------------------------------------------")
	print("\n")	

	

