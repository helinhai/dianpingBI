create table `user` (
	`user_name` varchar(12) CHARACTER SET utf8 NOT NULL PRIMARY KEY,
	`user_pw` varchar(12) CHARACTER SET utf8 NOT NULL,
	`user_mail` varchar(40) CHARACTER SET utf8 NOT NULL
);
INSERT INTO user (user_name, user_pw, user_mail) VALUES ('32011070091', '199371helu' , 'he_linhai@sina.com');
INSERT INTO user (user_name, user_pw, user_mail) VALUES ('helinhai', '199371helu' , 'he_linhai@sina.com');
