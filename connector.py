import cv2
import mysql.connector
import numpy as np
from pyzbar.pyzbar import decode
import winsound
from datetime import datetime
import time

mydb=mysql.connector.connect(
	host     = "localhost",
	user     = "root",
	password = "password",
	database = "library",
	port     =  3307
)

cursor=mydb.cursor(buffered=True)

def punch_in(date,roll_no,name,designation,check_in):
	cursor.execute('insert into log values(null, %s, %s, %s, %s , %s,null,null)',(date,roll_no,name,designation,check_in))
	mydb.commit()
def punch_out(roll_no,check_out,time_spent):
	cursor.execute('update log set check_out= %s where roll_no= %s and check_out is null',(check_out,roll_no))
	cursor.execute('update log set time_spent= %s where roll_no= %s and time_spent is null',(time_spent,roll_no))
	mydb.commit()


cap = cv2.VideoCapture(0)
cap.set(3,640)
cap.set(4,480)


while True:
	
	success,img = cap.read()	
	
	for barcode in decode(img):
		
		roll_no = barcode.data.decode('utf-8')
		cursor.execute('select * from log where time_spent is null and roll_no = %s', (roll_no,))
		check=cursor.fetchone()
		
		if check is None:
			
			cursor.execute('select name,designation from info where roll_no = %s', (roll_no,))
			info             =  cursor.fetchone()	

			if info==None:
				print("Data not found!!!")
				winsound.Beep(800,1200)
				time.sleep(2)
				continue

			date             =  datetime.today()
			date             =  date.strftime("%Y-%m-%d")
			check_in         =  datetime.now()
			check_in         =  check_in.strftime("%H:%M:%S")
			name,designation =  info
			
			punch_in(date,roll_no,name,designation,check_in)
			print(roll_no,"CHECKED IN")
			winsound.Beep(1500,1200)
			winsound.Beep(1900,200)
			time.sleep(1)
		

		else:
			
			cursor.execute('select check_in from log where roll_no = %s and check_out is null', (roll_no,))
			info = cursor.fetchone()
			
			
			
			check_in        =  str(info[0])
			check_out       =  datetime.now()
			check_out       =  check_out.strftime("%H:%M:%S")
			entry_h,entry_m =  (check_in.split(":"))[:2]
			exit_h,exit_m   =  (check_out.split(":"))[:2]
			h               =  (int(exit_h)-int(entry_h))*60
			m               =   int(exit_m)-int(entry_m)
			total_time      =   h+m
			
			punch_out(roll_no,check_out,total_time)
			print(roll_no,"CHECKED OUT")
			winsound.Beep(1500,500)
			time.sleep(1)
	
	cv2.imshow('Scanner',img)
	cv2.waitKey(1)
