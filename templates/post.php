<?php require_once './helpers.php'?>

<main class="page__main page__main--publication">
    <div class="container">
        <h1 class="page__title page__title--publication"><?= htmlspecialchars($postDetails['title']) ?></h1>
        <section class="post-details">
            <h2 class="visually-hidden">Публикация</h2>
            <div class="post-details__wrapper post-photo">
                <div class="post-details__main-block post post--details">
                    <?php if ($postDetails['type'] == 'quote') : ?>
                        <div class="post-details__image-wrapper post-quote">
                            <div class="post__main">
                                <blockquote>
                                    <p>
                                        <?= htmlspecialchars($postDetails['content']); ?>
                                    </p>
                                    <cite><?= htmlspecialchars($postDetails['author']); ?></cite>
                                </blockquote>
                            </div>
                        </div>
                    <?php elseif ($postDetails['type'] == 'link') : ?>
                        <div class="post__main">
                            <div class="post-link__wrapper">
                                <a class="post-link__external" href="http://<?= htmlspecialchars($postDetails['reference']);?>" title="Перейти по ссылке">
                                    <div class="post-link__info-wrapper">
                                        <div class="post-link__icon-wrapper">
                                            <img src="https://www.google.com/s2/favicons?domain=<?= htmlspecialchars($postDetails['img']) ?>" alt="Иконка">
                                        </div>
                                        <div class="post-link__info">
                                            <h3><?= htmlspecialchars($postDetails['title']); ?></h3>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php elseif ($postDetails['type'] == 'photo') : ?>
                        <div class="post-details__image-wrapper post-photo__image-wrapper">
                            <img src="img/<?= htmlspecialchars($postDetails['img']); ?>" alt="Фото от пользователя" width="760" height="507">
                        </div>
                    <?php elseif ($postDetails['type'] == 'video') : ?>
                        <div class="post-details__image-wrapper post-photo__image-wrapper">
                            <?=embed_youtube_video($postDetails['video']); ?>
                        </div>
                    <?php elseif ($postDetails['type'] == 'text') : ?>
                        <div class="post-details__image-wrapper post-text">
                            <div class="post__main">
                                <p>
                                    <?= htmlspecialchars($postDetails['content']); ?>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="post__indicators">
                        <div class="post__buttons">
                            <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                                <svg class="post__indicator-icon" width="20" height="17">
                                    <use xlink:href="#icon-heart"></use>
                                </svg>
                                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                    <use xlink:href="#icon-heart-active"></use>
                                </svg>
                                <span><?= htmlspecialchars($postDetails['likes']) ?></span>
                                <span class="visually-hidden">количество лайков</span>
                            </a>
                            <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-comment"></use>
                                </svg>
                                <span><?= htmlspecialchars($postDetails['comments_total']) ?></span>
                                <span class="visually-hidden">количество комментариев</span>
                            </a>
                            <a class="post__indicator post__indicator--repost button" href="#" title="Репост">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-repost"></use>
                                </svg>
                                <span>5</span>
                                <span class="visually-hidden">количество репостов</span>
                            </a>
                        </div>
                        <span class="post__view"><?= htmlspecialchars($postDetails['views']) ?> просмотров</span>
                    </div>
                    <ul class="post__tags">
                    <?php foreach ($postHashtags as $postHashtag) : ?>
                        <li><a href="#">#<?= htmlspecialchars($postHashtag['hashtag']) ?></a></li>
                    <?php endforeach; ?>
                    </ul>
                    <div class="comments">
                        <form class="comments__form form" action="#" method="post">
                            <div class="comments__my-avatar">
                                <img class="comments__picture" src="img/userpic-medium.jpg" alt="Аватар пользователя">
                            </div>
                            <div class="form__input-section form__input-section--error">
                                <textarea class="comments__textarea form__textarea form__input" placeholder="Ваш комментарий"></textarea>
                                <label class="visually-hidden">Ваш комментарий</label>
                                <button class="form__error-button button" type="button">!</button>
                                <div class="form__error-text">
                                    <h3 class="form__error-title">Ошибка валидации</h3>
                                    <p class="form__error-desc">Это поле обязательно к заполнению</p>
                                </div>
                            </div>
                            <button class="comments__submit button button--green" type="submit">Отправить</button>
                        </form>
                        <div class="comments__list-wrapper">
                            <ul class="comments__list">
                            <?php foreach ($postComments as $postComment) : ?>
                                <li class="comments__item user">
                                    <div class="comments__avatar">
                                        <a class="user__avatar-link" href="#">
                                            <?php if ($postComment['avatar']) : ?>
                                            <img class="comments__picture" src="img/<?= htmlspecialchars($postComment['avatar']) ?>" alt="Аватар пользователя">
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                    <div class="comments__info">
                                        <div class="comments__name-wrapper">
                                            <a class="comments__user-name" href="#">
                                                <span><?= htmlspecialchars($postComment['author']) ?></span>
                                            </a>
                                            <?php
                                            $publicationTime = new DateTimeImmutable($postComment['date']);
                                            $publicationDateTime = date_format(date_create($postComment['date']), 'Y-m-d');
                                            $relativePublicationTime = getRelativeDate($publicationTime);
                                            ?>
                                            <time class="comments__time" datetime="<?= $publicationDateTime ?>"><?= $relativePublicationTime ?></time>
                                        </div>
                                        <p class="comments__text">
                                            <?= htmlspecialchars($postComment['content']) ?>
                                        </p>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                            </ul>
                            <?php if ($postComments) : ?>
                            <a class="comments__more-link" href="#">
                                <span>Показать все комментарии</span>
                                <sup class="comments__amount">45</sup>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="post-details__user user">
                    <div class="post-details__user-info user__info">
                        <div class="post-details__avatar user__avatar">
                            <a class="post-details__avatar-link user__avatar-link" href="#">
                                <?php if ($postDetails['avatar']) : ?>
                                <img class="post-details__picture user__picture" src="img/<?= htmlspecialchars($postDetails['avatar']) ?>" alt="Аватар пользователя">
                                <?php endif; ?>
                            </a>
                        </div>
                        <div class="post-details__name-wrapper user__name-wrapper">
                            <a class="post-details__name user__name" href="#">
                                <span><?= htmlspecialchars($postAuthorDetails['user_name']) ?></span>
                            </a>
                            <?php
                            $registrationTime = new DateTimeImmutable($postAuthorDetails['date']);
                            $registrationDateTime = date_format(date_create($postAuthorDetails['date']), 'Y-m-d');
                            $relativeRegistrationTime = getRelativeDate($registrationTime, true);
                            ?>
                            <time class="post-details__time user__time" datetime="<?= $registrationDateTime ?>"><?= $relativeRegistrationTime ?> на сайте</time>
                        </div>
                    </div>
                    <div class="post-details__rating user__rating">
                        <p class="post-details__rating-item user__rating-item user__rating-item--subscribers">
                            <span class="post-details__rating-amount user__rating-amount"><?= htmlspecialchars($postAuthorDetails['subscribers_total']) ?></span>
                            <span class="post-details__rating-text user__rating-text">подписчиков</span>
                        </p>
                        <p class="post-details__rating-item user__rating-item user__rating-item--publications">
                            <span class="post-details__rating-amount user__rating-amount"><?= htmlspecialchars($postAuthorDetails['posts_total']) ?></span>
                            <span class="post-details__rating-text user__rating-text">публикаций</span>
                        </p>
                    </div>
                    <div class="post-details__user-buttons user__buttons">
                        <button class="user__button user__button--subscription button button--main" type="button">Подписаться</button>
                        <a class="user__button user__button--writing button button--green" href="#">Сообщение</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
