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
    name     VARCHAR(255) NOT NULL,
    surname  VARCHAR(255) NOT NULL,
    nickname VARCHAR(255) NOT NULL,
    email    VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS sessions
(
    guid       VARCHAR(36) PRIMARY KEY,
    last_login DATETIME NOT NULL
);