CREATE TABLE users (
    username VARCHAR(50) NOT NULL,
    pwd_hash VARCHAR(50) NOT NULL,
    email_address VARCHAR(50) NOT NULL,
    PRIMARY KEY (username)
) engine = INNODB DEFAULT character SET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE events (
    pk_event_ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title TINYTEXT NOT NULL,
    description TEXT NOT NULL,
    datetime DATETIME NOT NULL,
    username VARCHAR(50) NOT NULL,
    tag VARCHAR(64) DEFAULT 'Other',
    priority ENUM('low', 'medium', 'high', 'critical') NOT NULL,
    PRIMARY KEY (pk_event_ID),
    FOREIGN KEY (username) REFERENCES users (username) ON DELETE CASCADE
) engine = INNODB DEFAULT character SET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE tags (
    title VARCHAR(64) NOT NULL,
    PRIMARY KEY (title)
) engine = INNODB DEFAULT character SET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE records (
    pk_record_ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title TINYTEXT NOT NULL,
    description TEXT NOT NULL,
    original_image TEXT,
    image TEXT NOT NULL,
    thumbnail TEXT NOT NULL,
    createtime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    username VARCHAR(50) NOT NULL,
    area INT UNSIGNED NOT NULL,
    PRIMARY KEY (pk_record_ID),
    FOREIGN KEY (username) REFERENCES users (username) ON DELETE CASCADE
) engine = INNODB DEFAULT character SET = utf8 COLLATE = utf8_general_ci;
