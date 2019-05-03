import re
from pyvi import ViTokenizer, ViPosTagger
import polyglot
from polyglot.text import Text
import connectMYSQL
import datetime


def checkLocation(accident_location,select):
	# True: co trung
	# False: ko trung
	countNotSame = 0 
	locationEntity = accident_location.split(',')
	# print('locationEntity: ',locationEntity)
	for i in select:
		locationEntityInDB = i[9].split(',')
		# print('locationEntityInDB: ',locationEntityInDB)
		if len(locationEntity) < len(locationEntityInDB):
			for j in locationEntity:
				if j not in locationEntityInDB:
					# print('HELLO: ',j)
					countNotSame = countNotSame+1
					break
		elif len(locationEntity) > len(locationEntityInDB):
			for j in locationEntityInDB:
				if j not in locationEntity:
					# print('HELLO: ',j)
					countNotSame = countNotSame+1
					break
		else:
			for j in locationEntity:
				if j not in locationEntityInDB:
					# print('HELLO: ',j)
					countNotSame = countNotSame+1
					break

	if countNotSame == len(select):
		return False
	else: 
		return True


def checkDied(quantity,select):
	countNotSame = 0
	for i in select:
		if quantity != i[6]:
			countNotSame = countNotSame + 1
	if countNotSame == len(select):
		return False
	else:
		return True


def checkHurt(quantity,select):
	countNotSame = 0
	for i in select:
		if quantity != i[7]:
			countNotSame = countNotSame + 1
	if countNotSame == len(select):
		return False
	else:
		return True


def checkAccVehicle(vehicle,select):
	countNotSame = 0
	for i in select:
		if vehicle != i[5]:
			countNotSame = countNotSame + 1
	if countNotSame == len(select):
		return False
	else:
		return True


def getTimeDigit(time):
	timeDigit = []
	check = re.search('\d{1,2}',time)
	if check:
		hour = int(check.group())
		timeDigit.append(hour)
		time2 = time.replace(check.group(),'')
		check2 = re.search('\d{1,2}',time2)
		if check2:
			minute = int(check2.group())
			timeDigit.append(minute)
		else:
			timeDigit.append(' ')
	else:
		timeDigit.append(' ')
		timeDigit.append(' ')
	return timeDigit


def checkAccTime(time,select):
	timeDigitcurrent = getTimeDigit(time)	
	for i in select:
		timeDigitcurrentInDB = getTimeDigit(i[2])
		if (timeDigitcurrent[0]==timeDigitcurrentInDB[0]) & (timeDigitcurrent[1]==timeDigitcurrentInDB[1]):
			return True
	return False
		
	
					
				