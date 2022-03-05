import mysql.connector

mydb=mysql.connector.connect(
	host="localhost",
	user="root",
	password="password",
	database="LIBRARY",
	port=3307
)

cursor=mydb.cursor(buffered=True)

def punch_in(date,Roll,Name,Desig,time_in):
	cursor.execute('select Roll_no from log where name= %s', (Name,))
	cursor.execute('insert into log values( %s, %s, %s, %s , %s,"00:00:00",69)',(date,Roll,Name,Desig,time_in))
	mydb.commit()
def punch_out(Roll,time_out):
	cursor.execute('update log set check_out= %s where roll_no= %s and check_out="00:00:00"',(time_out,Roll))
	mydb.commit()