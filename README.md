# CMUCourseAdvisor

For this project we used the following resources:

1) PHP and MySQL for Dynamic Web Sites by Larry Ullman
2) Google.com

The database we created is called cmudb.

The database tables were created are: user, badge, course and review.

The following queries were used to create the tables:

cd C:\Users\shiva\Desktop\MISM\Spring2022\Enterprise Web Development\Project\mysql\bin

mysql -u root

SET PASSWORD FOR 'root'@'localhost' = PASSWORD('123');

mysql -u root -p

USE cmuDB;

DROP TABLE review ;
DROP TABLE user ;

CREATE TABLE user (
andrew_id VARCHAR(15) NOT NULL,
fname VARCHAR(20) NOT NULL,
lname VARCHAR(40) NOT NULL,
pass CHAR(40) NOT NULL,
addr VARCHAR(100),
phone VARCHAR(20),
email VARCHAR(50),
badge_id INT NOT NULL,
PRIMARY KEY (andrew_id),
FOREIGN KEY (badge_id) REFERENCES badge(badge_id)
);

CREATE TABLE badge (
badge_id INT NOT NULL,
bname VARCHAR(20) NOT NULL,
PRIMARY KEY (badge_id));

CREATE TABLE course (
course_id INT(5) NOT NULL,
cname VARCHAR(50) NOT NULL,
prof_name VARCHAR(30) NOT NULL,
category VARCHAR(50),
PRIMARY KEY (course_id)
);

CREATE TABLE favourite (
fav INT NOT NULL,
review_id INT NOT NULL,
course_id INT NOT NULL,
andrew_id VARCHAR(15) NOT NULL,
PRIMARY KEY (fav,review_id, andrew_id),
FOREIGN KEY(review_id) REFERENCES review(review_id),
FOREIGN KEY(course_id) REFERENCES review(course_id),
FOREIGN KEY(andrew_id) REFERENCES review(andrew_id)
);

CREATE TABLE reviewtag (
review_id INT NOT NULL,
course_id INT(5) NOT NULL,
tag_name VARCHAR(20) NOT NULL,
andrew_id VARCHAR(15) NOT NULL,
PRIMARY KEY (review_id, tag_name,andrew_id),
FOREIGN KEY(review_id) REFERENCES review(review_id),
FOREIGN KEY(course_id) REFERENCES review(course_id),
FOREIGN KEY(andrew_id) REFERENCES user(andrew_id)
);

INSERT INTO tag VALUES('Development','95-882','uln');

CREATE TABLE review (
review_id INT NOT NULL AUTO_INCREMENT,
course_id INT(5) NOT NULL,
andrew_id VARCHAR(15) NOT NULL,
feedback VARCHAR(100) NOT NULL,
rating DECIMAL(2,1) NOT NULL,
timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (review_id),
FOREIGN KEY(course_id) REFERENCES course(course_id),
FOREIGN KEY(andrew_id) REFERENCES user(andrew_id)
);

INSERT INTO user VALUES('skorgaon', 'Saurabh', 'Korgaonkar','wordsworth',NULL,NULL,NULL,1);
INSERT INTO user VALUES('sajjikut', 'Shivani', 'Ajjikutira','frost',NULL,NULL,NULL,1);
INSERT INTO user VALUES('meets', 'Meet', 'Shah','defoe',NULL,NULL,NULL,1);
INSERT INTO user VALUES('fkd', 'Femin', 'Dharamshi','kipling',NULL,NULL,NULL,1);

INSERT INTO course VALUES(95882, 'Enterprise Web Development', 'Michael Bigrigg', 'Dev');
INSERT INTO course VALUES(95712, 'Object Oriented Programming in JAVA', 'Neelam Dviwedi', 'Dev');
INSERT INTO course VALUES(95746, 'Cloud Security', 'George Werbache', 'Security');
INSERT INTO course VALUES(95703, 'Database management', 'Janusz Szczypula', 'Data');
INSERT INTO course VALUES(95888, 'Data Focused Python', 'John Ostlund', 'Data');
INSERT INTO course VALUES(67262, 'Database Design and Development', 'Raja Sooriamurthi', 'Data');


INSERT INTO review VALUES('95-882', 'sajjikut', 'Great course', 4.0, CURRENT_TIMESTAMP);
INSERT INTO review VALUES('95-882', 'uln', 'Amazing', 5.0, CURRENT_TIMESTAMP);
INSERT INTO review VALUES('95-882', 'fkd', 'Great course', 4.0, CURRENT_TIMESTAMP);
INSERT INTO review VALUES('95-712', 'fkd', 'Great cloud course', 4.0, CURRENT_TIMESTAMP);

INSERT INTO badge VALUES(1, 'Bronze');
INSERT INTO badge VALUES(2, 'Silver');
INSERT INTO badge VALUES(3, 'Gold');
INSERT INTO badge VALUES(4, 'Platinum');
INSERT INTO badge VALUES(5, 'Diamond');

The images of database tables are in the database folder attached.
