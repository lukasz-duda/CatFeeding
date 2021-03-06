create user if not exists 'home'@'%' identified by 'home';

create database if not exists home;

create database if not exists home_test;

grant create, drop, select, insert, update, delete, references, index, alter on home.* to 'home'@'%';

grant create, drop, select, insert, update, delete, references, index, alter on home_test.* to 'home'@'%';