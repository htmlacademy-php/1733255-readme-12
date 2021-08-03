INSERT INTO content_types (type, title, image_class)
VALUES ('text', 'Текст', 'post-text'),
       ('quote', 'Цитата', 'post-quote'),
       ('photo', 'Фото', 'post-photo'),
       ('video', 'Видео', 'post-video'),
       ('link', 'Ссылка', 'post-link');

INSERT INTO users (email, user_name, password, avatar)
VALUES ('jaba@gmail.com', 'Виталий', '202cb962ac59075b964b07152d234b70', ''),
       ('humanor@yandex.ru', 'Сергей', 'b497dd1a701a33026f7211533620780d', 'userpic-mark.jpg'),
       ('grog@yandex.ru', 'Георгий', '5cde9aad32f032f6f0d00389b6af361b', 'userpic.jpg'),
       ('mono@yandex.ru', 'Моника', '816b112c6105b3ebd537828a39af4818', 'userpic-larisa-small.jpg');

INSERT INTO posts (title, content, img, reference, views, user_id, content_type_id)
VALUES ('Цитата', 'Мы в жизни любим только раз, а после ищем лишь похожих', '', '', 5, 2, 2),
       ('Игра престолов',
        'Товарищи! Начало повседневной работы по формированию позиции требуют определения и уточнения модели развития. С другой стороны рамки и место обучения кадров способствует подготовки и реализации соответствующий условий активизации. Значимость этих проблем настолько очевидна, что рамки и место обучения кадров в значительной степени обуславливает создание существенных финансовых и административных условий. Таким образом начало повседневной работы по формированию позиции способствует подготовки и реализации систем массового участия. Задача организации, в особенности же реализация намеченных плановых заданий представляет собой интересный эксперимент проверки системы обучения кадров, соответствует насущным потребностям. Таким образом постоянный количественный рост и сфера нашей активности позволяет оценить значение соответствующий условий активизации.',
        '', '', 50, 3, 1),
       ('Наконец, обработал фотки!', '', 'rock-medium.jpg', '', 1, 1, 3),
       ('Моя мечта', '', 'coast-medium.jpg', '', 17, 4, 3),
       ('Лучшие курсы', '', '', 'https://htmlacademy.ru/', 521, 2, 5);

INSERT INTO comments (content, author_id, post_id)
VALUES ('Неплохо получилось', 1, 3),
       ('Что за бред? При чём тут игра престолов', 2, 2);

/*
 Получаем список постов с сортировкой по популярности вместе с именами авторов и типом контента
 */
SELECT p.*, u.user_name, ct.type
  FROM posts p
  JOIN users u ON p.user_id = u.id
  JOIN content_types ct ON p.content_type_id = ct.id
 ORDER BY views DESC;

/*
 Получаем список постов для пользователя c id 2
 */
SELECT *
  FROM posts
 WHERE user_id = 2;

/*
 Получаем список комментариев для поста c id 2 c логином пользователя
 */
SELECT c.content, u.user_name
  FROM comments c JOIN users u ON c.author_id = u.id
 WHERE post_id = 2;

/*
 Добавляем лайк к посту с id 2;
 */
INSERT INTO likes (user_id, post_id)
VALUES (1, 2);

/*
 Добавляем подписку пользователя с id 1 на пользователя с id 3;
 */
INSERT INTO subscriptions (author_id, subscriber_id)
VALUES (3, 1);
