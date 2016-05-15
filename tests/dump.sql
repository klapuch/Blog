CREATE TABLE articles (user_id INT DEFAULT NULL, ID INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, content LONGTEXT NOT NULL, `date` DATETIME NOT NULL, UNIQUE INDEX UNIQ_BFDD31682B36786B (title), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci ENGINE = MyISAM;
CREATE TABLE comments (author VARCHAR(50), article_id INT DEFAULT NULL, ID INT AUTO_INCREMENT NOT NULL, `date` DATETIME NOT NULL, content LONGTEXT NOT NULL, visible TINYINT(1) DEFAULT '1' NOT NULL, PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci ENGINE = MyISAM;
CREATE TABLE roles (ID INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_B63E2EC75E237E06 (name), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci ENGINE = MyISAM;
CREATE TABLE users (role_id INT DEFAULT NULL, ID INT AUTO_INCREMENT NOT NULL, username VARCHAR(50) NOT NULL, `password` VARCHAR(160) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci ENGINE = MyISAM;
CREATE TABLE inbox (ID INT AUTO_INCREMENT NOT NULL, subject VARCHAR(100) NOT NULL, content LONGTEXT NOT NULL, date DATETIME NOT NULL, sender VARCHAR(200) NOT NULL, state VARCHAR(50) NOT NULL, PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci ENGINE = MyISAM;
CREATE TABLE article_slugs (origin INT DEFAULT NULL, ID INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_CF9D20F55E237E06 (name), UNIQUE INDEX UNIQ_CF9D20F5DEF1561E (origin), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci ENGINE = MyISAM;
CREATE TABLE article_tags (article INT DEFAULT NULL, ID INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, pinned TINYINT(1) DEFAULT '0', UNIQUE INDEX UNIQ_6FBC94265E237E0623A0E66 (name, article, pinned), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci ENGINE = MyISAM;