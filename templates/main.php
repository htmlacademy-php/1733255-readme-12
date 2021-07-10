<?php require_once 'helpers.php'?>

<div class="container">
    <h1 class="page__title page__title--popular">Популярное</h1>
</div>
<div class="popular container">
    <div class="popular__filters-wrapper">
        <div class="popular__sorting sorting">
            <b class="popular__sorting-caption sorting__caption">Сортировка:</b>
            <ul class="popular__sorting-list sorting__list">
                <li class="sorting__item sorting__item--popular">
                    <a class="sorting__link sorting__link--active" href="#">
                        <span>Популярность</span>
                        <svg class="sorting__icon" width="10" height="12">
                            <use xlink:href="#icon-sort"></use>
                        </svg>
                    </a>
                </li>
                <li class="sorting__item">
                    <a class="sorting__link" href="#">
                        <span>Лайки</span>
                        <svg class="sorting__icon" width="10" height="12">
                            <use xlink:href="#icon-sort"></use>
                        </svg>
                    </a>
                </li>
                <li class="sorting__item">
                    <a class="sorting__link" href="#">
                        <span>Дата</span>
                        <svg class="sorting__icon" width="10" height="12">
                            <use xlink:href="#icon-sort"></use>
                        </svg>
                    </a>
                </li>
            </ul>
        </div>
        <div class="popular__filters filters">
            <b class="popular__filters-caption filters__caption">Тип контента:</b>
            <ul class="popular__filters-list filters__list">
                <li class="popular__filters-item popular__filters-item--all filters__item filters__item--all">
                    <a class="filters__button filters__button--ellipse filters__button--all filters__button--active"
                       href="#">
                        <span>Все</span>
                    </a>
                </li>
                <li class="popular__filters-item filters__item">
                    <a class="filters__button filters__button--photo button" href="#">
                        <span class="visually-hidden">Фото</span>
                        <svg class="filters__icon" width="22" height="18">
                            <use xlink:href="#icon-filter-photo"></use>
                        </svg>
                    </a>
                </li>
                <li class="popular__filters-item filters__item">
                    <a class="filters__button filters__button--video button" href="#">
                        <span class="visually-hidden">Видео</span>
                        <svg class="filters__icon" width="24" height="16">
                            <use xlink:href="#icon-filter-video"></use>
                        </svg>
                    </a>
                </li>
                <li class="popular__filters-item filters__item">
                    <a class="filters__button filters__button--text button" href="#">
                        <span class="visually-hidden">Текст</span>
                        <svg class="filters__icon" width="20" height="21">
                            <use xlink:href="#icon-filter-text"></use>
                        </svg>
                    </a>
                </li>
                <li class="popular__filters-item filters__item">
                    <a class="filters__button filters__button--quote button" href="#">
                        <span class="visually-hidden">Цитата</span>
                        <svg class="filters__icon" width="21" height="20">
                            <use xlink:href="#icon-filter-quote"></use>
                        </svg>
                    </a>
                </li>
                <li class="popular__filters-item filters__item">
                    <a class="filters__button filters__button--link button" href="#">
                        <span class="visually-hidden">Ссылка</span>
                        <svg class="filters__icon" width="21" height="18">
                            <use xlink:href="#icon-filter-link"></use>
                        </svg>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="popular__posts">
        <?php foreach ($postCards as $key => $value): ?>
            <article class="popular__post post <?= htmlspecialchars($value['type']); ?>">
                <header class="post__header">
                    <h2><?= htmlspecialchars($value['title']); ?></h2>
                </header>
                <div class="post__main">
                    <?php if ($value['type'] == 'post-quote'): ?>
                        <blockquote>
                            <p>
                                <?= htmlspecialchars($value['content']); ?>
                            </p>
                            <cite>Неизвестный Автор</cite>
                        </blockquote>
                    <?php elseif ($value['type'] == 'post-link'): ?>
                        <div class="post-link__wrapper">
                            <a class="post-link__external" href="http://" title="Перейти по ссылке">
                                <div class="post-link__info-wrapper">
                                    <div class="post-link__icon-wrapper">
                                        <img src="https://www.google.com/s2/favicons?domain=vitadental.ru"
                                             alt="Иконка">
                                    </div>
                                    <div class="post-link__info">
                                        <h3><!--здесь заголовок--></h3>
                                    </div>
                                </div>
                                <span><?= htmlspecialchars($value['content']); ?></span>
                            </a>
                        </div>
                    <?php elseif ($value['type'] == 'post-photo'): ?>
                        <div class="post-photo__image-wrapper">
                            <img src="img/<?= htmlspecialchars($value['content']); ?>" alt="Фото от пользователя" width="360"
                                 height="240">
                        </div>
                    <?php elseif ($value['type'] == 'post-video'): ?>
                        <div class="post-video__block">
                            <div class="post-video__preview">
                                <?= embed_youtube_cover(/* вставьте ссылку на видео */); ?>
                                <img src="img/coast-medium.jpg" alt="Превью к видео" width="360" height="188">
                            </div>
                            <a href="post-details.html" class="post-video__play-big button">
                                <svg class="post-video__play-big-icon" width="14" height="14">
                                    <use xlink:href="#icon-video-play-big"></use>
                                </svg>
                                <span class="visually-hidden">Запустить проигрыватель</span>
                            </a>
                        </div>
                    <?php elseif ($value['type'] == 'post-text'): ?>
                        <?php
                        function shorten_text($text, $num_letters) {
                            $num = mb_strlen($text);
                            if ($num > $num_letters) {
                                $words = explode(' ', $text);
                                $new_words = [];
                                $text_length = 0;
                                foreach ($words as $word) {
                                    if ($text_length < $num_letters) {
                                        if (count($new_words) == 0) {
                                            $text_length += mb_strlen($word);
                                        } else {
                                            $text_length += mb_strlen($word) + 1;
                                        }
                                        $new_words[] = $word;
                                    } else {
                                        break;
                                    }
                                }
                                return '<p>' . implode(' ', $new_words) . '...' . '</p>' . '<a class="post-text__more-link" href="#">Читать далее</a>';
                            } else {
                                return '<p>' . $text . '</p>';
                            }
                        }
                        echo shorten_text(htmlspecialchars($value['content']), 300);
                        ?>
                    <?php endif; ?>
                </div>
                <footer class="post__footer">
                    <div class="post__author">
                        <a class="post__author-link" href="#" title="Автор">
                            <div class="post__avatar-wrapper">
                                <img class="post__author-avatar" src="img/<?= htmlspecialchars($value['avatar']); ?>"
                                     alt="Аватар пользователя">
                            </div>
                            <div class="post__info">
                                <b class="post__author-name"><?= htmlspecialchars($value['userName']); ?></b>
                                <?php
                                    date_default_timezone_set('Europe/Moscow');
                                    $originalDate = generate_random_date($key);
                                    $instanceDate = date_create($originalDate);
                                    $titleDate = date_format($instanceDate, 'd.m.Y H:i');
                                    $countDate = date_diff(date_create(), $instanceDate);
                                    if ($countDate->i) {
                                        $properNoun = get_noun_plural_form($countDate->i, 'минуту', 'минуты', 'минут');
                                        $relativeDate = date_interval_format($countDate, '%i ' . $properNoun . ' назад');
                                    } elseif ($countDate->h) {
                                        $properNoun = get_noun_plural_form($countDate->h, 'час', 'часа', 'часов');
                                        $relativeDate = date_interval_format($countDate, '%h ' . $properNoun . ' назад');
                                    } elseif ($countDate->d && $countDate->d < 7) {
                                        $properNoun = get_noun_plural_form($countDate->d, 'день', 'дня', 'дней');
                                        $relativeDate = date_interval_format($countDate, '%d ' . $properNoun . ' назад');
                                    } elseif ($countDate->d && $countDate->d >= 7) {
                                        $weeksCount = ceil($countDate->d / 7);
                                        $properNoun = get_noun_plural_form($weeksCount, 'неделю', 'недели', 'недель');
                                        $relativeDate = $weeksCount . ' ' . $properNoun . ' назад';
                                    } elseif ($countDate->m) {
                                        $properNoun = get_noun_plural_form($countDate->m, 'месяц', 'месяца', 'месяцев');
                                        $relativeDate = date_interval_format($countDate, '%m ' . $properNoun . ' назад');
                                    }
                                ?>
                                <time class="post__time" datetime="<?= $originalDate ?>" title="<?= $titleDate ?>">
                                    <?= $relativeDate ?>
                                </time>
                            </div>
                        </a>
                    </div>
                    <div class="post__indicators">
                        <div class="post__buttons">
                            <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                                <svg class="post__indicator-icon" width="20" height="17">
                                    <use xlink:href="#icon-heart"></use>
                                </svg>
                                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20"
                                     height="17">
                                    <use xlink:href="#icon-heart-active"></use>
                                </svg>
                                <span>0</span>
                                <span class="visually-hidden">количество лайков</span>
                            </a>
                            <a class="post__indicator post__indicator--comments button" href="#"
                               title="Комментарии">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-comment"></use>
                                </svg>
                                <span>0</span>
                                <span class="visually-hidden">количество комментариев</span>
                            </a>
                        </div>
                    </div>
                </footer>
            </article>
        <?php endforeach; ?>
    </div>
</div>
