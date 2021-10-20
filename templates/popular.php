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
                    <?php  ?>
                    <a class="filters__button filters__button--ellipse filters__button--all <?= !$currentContentTypeId ? 'filters__button--active' : ''?>"
                       href="<?= modifyParamsPageUrl('contentId', null) ?>">
                        <span>Все</span>
                    </a>
                </li>
                <?php foreach ($contentTypes as $contentType): ?>
                    <li class="popular__filters-item filters__item">
                        <?php $activeClass = $currentContentTypeId === $contentType['id'] ? 'filters__button--active' : ''  ?>
                        <a class="filters__button filters__button--<?= htmlspecialchars($contentType['type']) . ' ' . $activeClass ?> button"
                           href="<?= modifyParamsPageUrl('contentId', $contentType['id']); ?>">
                            <span class="visually-hidden"><?= htmlspecialchars($contentType['title']); ?></span>
                            <svg class="filters__icon" width="22" height="18">
                                <use xlink:href="#icon-filter-<?= htmlspecialchars($contentType['type']); ?>"></use>
                            </svg>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="popular__posts">
        <?php foreach ($postCards as $postIndex => $postCard): ?>
            <article class="popular__post post <?= htmlspecialchars($postCard['image_class']); ?>">
                <header class="post__header">
                    <h2><a href="post.php<?= '?postId=' . $postCard['id'] ?>"><?= htmlspecialchars($postCard['title']); ?></a></h2>
                </header>
                <div class="post__main">
                    <?php if ($postCard['type'] == 'quote'): ?>
                        <blockquote>
                            <p>
                                <?= htmlspecialchars($postCard['content']); ?>
                            </p>
                            <cite>Неизвестный Автор</cite>
                        </blockquote>
                    <?php elseif ($postCard['type'] == 'link'): ?>
                        <div class="post-link__wrapper">
                            <a class="post-link__external" href="<?= getProtocolLink($postCard['reference']) ?>" title="Перейти по ссылке">
                                <div class="post-link__info-wrapper">
                                    <div class="post-link__icon-wrapper">
                                        <img src="https://www.google.com/s2/favicons?domain=vitadental.ru"
                                             alt="Иконка">
                                    </div>
                                    <div class="post-link__info">
                                        <h3><!--здесь заголовок--></h3>
                                    </div>
                                </div>
                                <span><?= htmlspecialchars($postCard['content']); ?></span>
                            </a>
                        </div>
                    <?php elseif ($postCard['type'] == 'photo'): ?>
                        <div class="post-photo__image-wrapper">
                            <img src="img/<?= htmlspecialchars($postCard['img']); ?>" alt="Фото от пользователя" width="360"
                                 height="240">
                        </div>
                    <?php elseif ($postCard['type'] == 'video'): ?>
                        <div class="post-video__block">
                            <div class="post-video__preview">
                                <?= embed_youtube_cover($postCard['video']); ?>
                            </div>
                            <a href="post.php<?= '?postId=' . $postCard['id'] ?>" class="post-video__play-big button">
                                <svg class="post-video__play-big-icon" width="14" height="14">
                                    <use xlink:href="#icon-video-play-big"></use>
                                </svg>
                                <span class="visually-hidden">Запустить проигрыватель</span>
                            </a>
                        </div>
                    <?php elseif ($postCard['type'] == 'text'):
                        echo shortenText(htmlspecialchars($postCard['content']), 300);
                    endif; ?>
                </div>
                <footer class="post__footer">
                    <div class="post__author">
                        <a class="post__author-link" href="#" title="Автор">
                            <div class="post__avatar-wrapper">
                                <?php if ($postCard['avatar']) : ?>
                                <img class="post__author-avatar" src="img/<?= htmlspecialchars($postCard['avatar']); ?>"
                                     alt="Аватар пользователя">
                                <?php endif; ?>
                            </div>
                            <div class="post__info">
                                <b class="post__author-name"><?= htmlspecialchars($postCard['user_name']); ?></b>
                                <?php
                                    date_default_timezone_set('Europe/Moscow');
                                    $originalDate = generate_random_date($postIndex);
                                    $titleDate = date_format(date_create($originalDate), 'd.m.Y H:i');
                                    $originalDateTime = new DateTimeImmutable($originalDate);
                                    $relativeDate = getRelativeDate($originalDateTime);
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
