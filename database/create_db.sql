DROP DATABASE IF EXISTS school;

CREATE DATABASE IF NOT EXISTS school;
USE school;

CREATE TABLE IF NOT EXISTS items
(
    guid          VARCHAR(36) PRIMARY KEY,
    name          VARCHAR(255) NOT NULL,
    rating        INT          NOT NULL,
    aliases       VARCHAR(255) NOT NULL,
    related_items VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS users
(
    guid     VARCHAR(36) PRIMARY KEY,
    name     VARCHAR(128) NOT NULL,
    surname  VARCHAR(128) NOT NULL,
    username VARCHAR(128) NOT NULL,
    email    VARCHAR(128) NOT NULL,
    password VARCHAR(128) NOT NULL
);

CREATE TABLE IF NOT EXISTS sessions
(
    user       VARCHAR(36) PRIMARY KEY REFERENCES users (guid),
    last_login DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS shopping_carts
(
    user VARCHAR(36) REFERENCES users (guid),
    item varchar(36) references items (guid),
    PRIMARY KEY (user, item)
);