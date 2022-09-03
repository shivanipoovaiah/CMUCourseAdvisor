#!C:\Users\shiva\AppData\Local\Programs\Python\Python310\python.exe
print("Content-Type: text/html\n\n")

print("Hello world! Python works!")

import sys
sys.path.append("C:\\Users\\shiva\\AppData\\Local\\Programs\\Python\\Python310\\Lib\\site-packages")

import mysql.connector

db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="123",
    database="cmudb",
    auth_plugin='mysql_native_password'
)
cursor = db.cursor()
cursor.execute("SELECT * FROM `favourite`;")
for row in cursor:
    print("{0:10}\t{1:10}\t{2:10}\t{3:10}\n".format(row[0],row[1],row[2],row[3]))

cursor2 = db.cursor()
cursor2.execute("SELECT DISTINCT r.course_id AS Course,"+
"cname AS Name, r.andrew_id AS AndrewID, "+
"fname, lname, email, feedback AS Feedback, "+
"rating AS Rating, timestamp AS Timestamp, "+
    "r.review_id as ID, f.fav as fav"+
	"from review as r"+
		"inner join favourite as f"+
	"on r.review_id=f.review_id"+
		"join course as c"+
		"on c.course_id = r.course_id"+
		"join user as u on r.andrew_id = u.andrew_id;")
for row in cursor2:
    print("{0:10}\t{1:10}\t{2:10}\t{3:10}\n".format(row[0],row[1],row[2],row[3]))