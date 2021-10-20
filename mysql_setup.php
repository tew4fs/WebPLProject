<?php
    include("database_credentials.php");

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $db = new mysqli($dbhost, $dbusername, $dbpasswd, $dbname);
    
    $db->query("drop table if exists user;");
    $db->query("create table user (
        id int not null auto_increment,
        name text not null,
        password text not null,
        username text not null,
        primary key (id));");

    $db->query("drop table if exists class;");
    $db->query("create table class (
        id int not null auto_increment,
        name text not null,
        primary key (id));");

    $db->query("drop table if exists user_class;");
    $db->query("create table user_class (
        user_id int not null,
        class_id int not null);");

    $db->query("drop table if exists assignment;");
    $db->query("create table assignment (
        id int not null auto_increment,
        title text not null,
        description text,
        class_id int not null,
        due_date date not null,
        primary key (id));");
    
    $db->query("drop table if exists bookmark;");
    $db->query("create table bookmark (
        id int not null auto_increment,
        name text not null,
        url text not null,
        class_id int not null,
        primary key (id));");