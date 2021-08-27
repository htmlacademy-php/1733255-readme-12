<?php require_once './helpers.php'?>

<?php
    function checkErrorClass($errors, $field) {
        return $errors[$type . '-heading'] ? 'form__input-section--error' : '';
    }
?>

<?php
    function createFormTitle($type , $errors): string
    {
        var_dump($errors);
        $errorKey = $type . '-heading';
        $errorClass = $errors[$type . '-heading'] ? 'form__input-section--error' : '';
        return '
        <div class="adding-post__input-wrapper form__input-wrapper">
            <label class="adding-post__label form__label" for="' . $type . '-heading">Заголовок <span class="form__input-required">*</span></label>
            <div class="form__input-section ' . $errorClass . '">
                <input class="adding-post__input form__input" id="' . $type . '-heading" type="text" name="' . $type . '-heading" placeholder="Введите заголовок">
                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Заголовок сообщения</h3>
                    <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                </div>
            </div>
        </div>
        ';
    }

    function createFormTags($type): string
    {
        return '
        <div class="adding-post__input-wrapper form__input-wrapper">
            <label class="adding-post__label form__label" for="' . $type . '-tags">Теги</label>
            <div class="form__input-section">
                <input class="adding-post__input form__input" id="' . $type . '-tags" type="text" name="' . $type . '-tags" placeholder="Введите теги">
                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Заголовок сообщения</h3>
                    <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                </div>
            </div>
        </div>
        ';
    }
?>

<main class="page__main page__main--adding-post">
    <div class="page__main-section">
        <div class="container">
            <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
        </div>
        <div class="adding-post container">
            <div class="adding-post__tabs-wrapper tabs">
                <div class="adding-post__tabs filters">
                    <ul class="adding-post__tabs-list filters__list tabs__list">
                    <?php foreach ($postContentTypes as $postContentType) : ?>
                        <li class="adding-post__tabs-item filters__item">
                            <a class="adding-post__tabs-link filters__button filters__button--<?= htmlspecialchars($postContentType['type']) ?>
                            <?= $currentContentTypeId === $postContentType['id']  ? 'filters__button--active tabs__item--active' : '' ?> tabs__item button"
                            href="<?= modifyParamsPageUrl('contentId', $postContentType['id']); ?>">
                                <svg class="filters__icon" width="22" height="18">
                                    <use xlink:href="#icon-filter-<?= htmlspecialchars($postContentType['type']) ?>"></use>
                                </svg>
                                <span><?= htmlspecialchars($postContentType['title']) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                </div>
                <div class="adding-post__tab-content">
                    <?php
                        function checkCurrentType($currentFormType, $allTypes, $currentTypeId): string
                        {
                            $currentContent = array_values(array_filter($allTypes, function($row) use ($currentTypeId) {return $row['id'] === $currentTypeId;}));
                            $activeContentClass = 'tabs__content--active';
                            return $currentContent[0]['type'] ===  $currentFormType ? $activeContentClass : '';
                        }
                    ?>
                    <section class="adding-post__photo tabs__content <?= checkCurrentType('photo', $postContentTypes, $currentContentTypeId) ?>">
                        <h2 class="visually-hidden">Форма добавления фото</h2>
                        <form class="adding-post__form form" action="add.php" method="post" enctype="multipart/form-data">
                            <div class="form__text-inputs-wrapper">
                                <div class="form__text-inputs">
                                    <?= createFormTitle('photo', $errors)?>
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="photo-url">Ссылка из интернета</label>
                                        <div class="form__input-section">
                                            <input class="adding-post__input form__input" id="photo-url" type="text" name="photo-link" placeholder="Введите ссылку">
                                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <?= createFormTags('photo')?>
                                </div>
                                <div class="form__invalid-block">
                                    <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                                    <ul class="form__invalid-list">
                                        <li class="form__invalid-item">Заголовок. Это поле должно быть заполнено.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="adding-post__input-file-container form__input-container form__input-container--file">
                                <div class="adding-post__input-file-wrapper form__input-file-wrapper">
                                    <div class="adding-post__file-zone adding-post__file-zone--photo form__file-zone dropzone">
                                        <input class="adding-post__input-file form__input-file" id="userpic-file-photo" type="file" name="userpic-file-photo" title=" ">
                                        <div class="form__file-zone-text">
                                            <span>Перетащите фото сюда</span>
                                        </div>
                                    </div>
                                    <button class="adding-post__input-file-button form__input-file-button form__input-file-button--photo button" type="button">
                                        <span>Выбрать фото</span>
                                        <svg class="adding-post__attach-icon form__attach-icon" width="10" height="20">
                                            <use xlink:href="#icon-attach"></use>
                                        </svg>
                                    </button>
                                </div>
                                <div class="adding-post__file adding-post__file--photo form__file dropzone-previews">

                                </div>
                            </div>
                            <div class="adding-post__buttons">
                                <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
                                <a class="adding-post__close" href="#">Закрыть</a>
                            </div>
                        </form>
                    </section>

                    <section class="adding-post__video tabs__content <?= checkCurrentType('video', $postContentTypes, $currentContentTypeId) ?>">
                        <h2 class="visually-hidden">Форма добавления видео</h2>
                        <form class="adding-post__form form" action="add.php" method="post" enctype="multipart/form-data">
                            <div class="form__text-inputs-wrapper">
                                <div class="form__text-inputs">
                                    <?= createFormTitle('video', $errors)?>
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="video-url">Ссылка youtube <span class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input class="adding-post__input form__input" id="video-url" type="text" name="video-link" placeholder="Введите ссылку">
                                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <?= createFormTags('video')?>
                                </div>
                                <div class="form__invalid-block">
                                    <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                                    <ul class="form__invalid-list">
                                        <li class="form__invalid-item">Заголовок. Это поле должно быть заполнено.</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="adding-post__buttons">
                                <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
                                <a class="adding-post__close" href="#">Закрыть</a>
                            </div>
                        </form>
                    </section>

                    <section class="adding-post__text tabs__content <?= checkCurrentType('text', $postContentTypes, $currentContentTypeId) ?>">
                        <h2 class="visually-hidden">Форма добавления текста</h2>
                        <form class="adding-post__form form" action="add.php" method="post">
                            <div class="form__text-inputs-wrapper">
                                <div class="form__text-inputs">
                                    <?= createFormTitle('text', $errors)?>
                                    <div class="adding-post__textarea-wrapper form__textarea-wrapper">
                                        <label class="adding-post__label form__label" for="post-text">Текст поста <span class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <textarea class="adding-post__textarea form__textarea form__input" id="post-text" placeholder="Введите текст публикации" name="text-content"></textarea>
                                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <?= createFormTags('text')?>
                                </div>
                                <div class="form__invalid-block">
                                    <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                                    <ul class="form__invalid-list">
                                        <li class="form__invalid-item">Заголовок. Это поле должно быть заполнено.</li>
                                        <li class="form__invalid-item">Цитата. Она не должна превышать 70 знаков.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="adding-post__buttons">
                                <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
                                <a class="adding-post__close" href="#">Закрыть</a>
                            </div>
                        </form>
                    </section>

                    <section class="adding-post__quote tabs__content <?= checkCurrentType('quote', $postContentTypes, $currentContentTypeId) ?>">
                        <h2 class="visually-hidden">Форма добавления цитаты</h2>
                        <form class="adding-post__form form" action="add.php" method="post">
                            <div class="form__text-inputs-wrapper">
                                <div class="form__text-inputs">
                                    <?= createFormTitle('quote', $errors)?>
                                    <div class="adding-post__input-wrapper form__textarea-wrapper">
                                        <label class="adding-post__label form__label" for="cite-text">Текст цитаты <span class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <textarea class="adding-post__textarea adding-post__textarea--quote form__textarea form__input" id="cite-text" placeholder="Текст цитаты" name="quote-content"></textarea>
                                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="adding-post__textarea-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="quote-author">Автор <span class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input class="adding-post__input form__input" id="quote-author" type="text" name="quote-author">
                                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <?= createFormTags('quote')?>
                                </div>
                                <div class="form__invalid-block">
                                    <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                                    <ul class="form__invalid-list">
                                        <li class="form__invalid-item">Заголовок. Это поле должно быть заполнено.</li>
                                        <li class="form__invalid-item">Цитата. Она не должна превышать 70 знаков.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="adding-post__buttons">
                                <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
                                <a class="adding-post__close" href="#">Закрыть</a>
                            </div>
                        </form>
                    </section>

                    <section class="adding-post__link tabs__content <?= checkCurrentType('link', $postContentTypes, $currentContentTypeId) ?>">
                        <h2 class="visually-hidden">Форма добавления ссылки</h2>
                        <form class="adding-post__form form" action="add.php" method="post">
                            <div class="form__text-inputs-wrapper">
                                <div class="form__text-inputs">
                                    <?= createFormTitle('link', $errors)?>
                                    <div class="adding-post__textarea-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="post-link">Ссылка <span class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input class="adding-post__input form__input" id="post-link" type="text" name="post-link">
                                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <?= createFormTags('link')?>
                                </div>
                                <div class="form__invalid-block">
                                    <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                                    <ul class="form__invalid-list">
                                        <li class="form__invalid-item">Заголовок. Это поле должно быть заполнено.</li>
                                        <li class="form__invalid-item">Цитата. Она не должна превышать 70 знаков.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="adding-post__buttons">
                                <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
                                <a class="adding-post__close" href="#">Закрыть</a>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</main>
