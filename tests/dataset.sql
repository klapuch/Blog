INSERT INTO articles (title, content, `date`, user_id) VALUES
("fooTitle", "fooContent", "2005-01-01 01:01:01", 2),
("barTitle", "barContent", "2006-01-01 01:01:01", 1);

INSERT INTO roles (name) VALUES
("creator"),
("administrator"),
("member");

INSERT INTO users (username, role_id, `password`) VALUES
("facedown", 1, "hashed"),
("secondUser", 3, "hashed");

INSERT INTO comments (author, article_id, `date`, content, visible) VALUES
("facedown", 1, NOW(), "someContent", 1),
("someone", 1, NOW() - INTERVAL 1 DAY, "someoneContent", 1),
("someoneelse", 1, NOW() - INTERVAL 1 DAY, "someoneelseContent", 0),
("facedown", 2, NOW(), "someContent2", 1);

INSERT INTO inbox (sender, subject, content, `date`, state) VALUES
("some Sender", "some Subject", "some Content", NOW(), "spam"),
("fooSender", "fooSubject", "fooContent", NOW() - INTERVAL 1 DAY, "read"),
("fooSender", "fooSubject", "fooContent", NOW(), "unread"),
("fooSender", "fooSubject", "fooContent", NOW() - INTERVAL 2 DAY, "unread");