CREATE DATABASE readme
       DEFAULT CHARACTER SET utf8
       DEFAULT COLLATE utf8_general_ci;

USE readme;

CREATE TABLE users (
       user_id INT AUTO_INCREMENT PRIMARY KEY,
       registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       email VARCHAR(128) UNIQUE,
       login VARCHAR(128) UNIQUE,
       password CHAR(64),
       avatar VARCHAR(255)
);

CREATE INDEX u_email ON users(email);

CREATE TABLE content_types (
       content_type_id INT AUTO_INCREMENT PRIMARY KEY,
       type VARCHAR(128) UNIQUE,
       class VARCHAR(128) UNIQUE
);

CREATE TABLE hashtags (
       hashtag_id INT AUTO_INCREMENT PRIMARY KEY,
       hashtag VARCHAR(128) UNIQUE
);

CREATE TABLE posts (
       post_id INT AUTO_INCREMENT PRIMARY KEY,
       publication_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       title VARCHAR(255) NOT NULL,
       description TEXT,
       author VARCHAR(128),
       img VARCHAR(255),
       video VARCHAR(255),
       reference VARCHAR(255),
       views INT,
       user_id INT,
       content_type_id INT,
       hashtag_id INT,
       FOREIGN KEY (user_id) REFERENCES users(user_id),
       FOREIGN KEY (content_type_id) REFERENCES content_types(content_type_id),
       FOREIGN KEY (hashtag_id) REFERENCES hashtags(hashtag_id)
);

CREATE TABLE comments (
       comment_id INT AUTO_INCREMENT PRIMARY KEY,
       publication_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       content TEXT NOT NULL,
       author_id INT,
       post_id INT,
       FOREIGN KEY (author_id) REFERENCES users(user_id),
       FOREIGN KEY (post_id) REFERENCES posts(post_id)
);

CREATE TABLE likes (
       like_id INT AUTO_INCREMENT PRIMARY KEY,
       user_id INT,
       post_id INT,
       FOREIGN KEY (user_id) REFERENCES users(user_id),
       FOREIGN KEY (post_id) REFERENCES posts(post_id)
);

CREATE TABLE subscriptions (
       subscription_id INT AUTO_INCREMENT PRIMARY KEY,
       author_id INT,
       subscriber_id INT,
       FOREIGN KEY (author_id) REFERENCES users(user_id),
       FOREIGN KEY (subscriber_id) REFERENCES users(user_id)
);

CREATE TABLE messages (
       message_id INT AUTO_INCREMENT PRIMARY KEY,
       publication_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       content TEXT NOT NULL,
       sender_id INT,
       recipient_id INT,
       FOREIGN KEY (sender_id) REFERENCES users(user_id),
       FOREIGN KEY (recipient_id) REFERENCES users(user_id)
);


