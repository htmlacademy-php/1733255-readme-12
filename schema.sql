CREATE DATABASE readme
       DEFAULT CHARACTER SET utf8
       DEFAULT COLLATE utf8_general_ci;

USE readme;

CREATE TABLE users (
       id INT AUTO_INCREMENT PRIMARY KEY,
       registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       email VARCHAR(255) UNIQUE,
       login VARCHAR(128) UNIQUE,
       password CHAR(64),
       avatar VARCHAR(255)
);

CREATE TABLE content_types (
       id INT AUTO_INCREMENT PRIMARY KEY,
       type VARCHAR(32) UNIQUE,
       image_class VARCHAR(128)
);

CREATE TABLE hashtags (
       id INT AUTO_INCREMENT PRIMARY KEY,
       hashtag VARCHAR(128) UNIQUE
);

CREATE TABLE posts (
       id INT AUTO_INCREMENT PRIMARY KEY,
       publication_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       title VARCHAR(255) NOT NULL,
       content TEXT,
       author VARCHAR(128),
       img VARCHAR(2000),
       video VARCHAR(2000),
       reference VARCHAR(255),
       views INT DEFAULT 0,
       user_id INT,
       content_type_id INT,
       FOREIGN KEY (user_id) REFERENCES users(id),
       FOREIGN KEY (content_type_id) REFERENCES content_types(id)
);

CREATE INDEX title ON posts(title);
CREATE INDEX views ON posts(views);

CREATE TABLE posts_hashtags (
       post_id INT NOT NULL,
       hashtag_id INT NOT NULL,
       FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
       FOREIGN KEY (hashtag_id) REFERENCES hashtags(id) ON DELETE CASCADE,
       PRIMARY KEY (post_id, hashtag_id)
);

CREATE TABLE comments (
       id INT AUTO_INCREMENT PRIMARY KEY,
       publication_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       content TEXT NOT NULL,
       author_id INT,
       post_id INT,
       FOREIGN KEY (author_id) REFERENCES users(id),
       FOREIGN KEY (post_id) REFERENCES posts(id)
);

CREATE TABLE likes (
       id INT AUTO_INCREMENT PRIMARY KEY,
       user_id INT,
       post_id INT,
       FOREIGN KEY (user_id) REFERENCES users(id),
       FOREIGN KEY (post_id) REFERENCES posts(id)
);

CREATE UNIQUE INDEX user_post ON likes(user_id, post_id);

CREATE TABLE subscriptions (
       id INT AUTO_INCREMENT PRIMARY KEY,
       author_id INT,
       subscriber_id INT,
       FOREIGN KEY (author_id) REFERENCES users(id),
       FOREIGN KEY (subscriber_id) REFERENCES users(id)
);

CREATE UNIQUE INDEX author_subscriber ON subscriptions(author_id, subscriber_id);

CREATE TABLE messages (
       id INT AUTO_INCREMENT PRIMARY KEY,
       publication_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       content TEXT NOT NULL,
       sender_id INT,
       recipient_id INT,
       FOREIGN KEY (sender_id) REFERENCES users(id),
       FOREIGN KEY (recipient_id) REFERENCES users(id)
);


