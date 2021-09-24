<?php
require_once './helpers.php';

$titleKey = 'heading';
$linkKey = 'url';
$textKey = 'content';
$authorKey = 'author';
$tagsKey = 'tags';


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
                        $currentType = array_filter($postContentTypes, function($row) use ($currentContentTypeId) {
                            return $row['id'] === $currentContentTypeId;
                        }); // Находим нужный тип контента
                        $currentType = array_values($currentType)[0]; // Убираем вложенность
                        $hiddenFormTitle = match ($currentType['type']) {
                        'text'  => 'текста',
                        'quote' => 'цитаты',
                        'link'  => 'ссылки',
                        'photo' => 'фото',
                        'video' => 'видео',
                        default => 'контента',
                        };
                        $currentEnctype = $currentType['type'] === 'photo' ? 'enctype="multipart/form-data"' : '';
                    ?>
                    <section class="adding-post__<?= $currentType['type'] ?> tabs__content tabs__content--active">
                        <h2 class="visually-hidden">Форма добавления <?= $hiddenFormTitle ?></h2>
                        <form class="adding-post__form form" action="add.php" method="post" <?= $currentEnctype ?>>
                            <input type="hidden" name="contentId" value="<?= $currentType['id'] ?>"> <!-- ID контента для POST -->
                            <input type="hidden" name="contentType" value="<?= $currentType['type'] ?>"> <!-- Тип контента для POST -->
                            <div class="form__text-inputs-wrapper">
                                <div class="form__text-inputs">
                                    <!-- Заголовок -->
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="heading">Заголовок <span class="form__input-required">*</span></label>
                                        <div class="form__input-section <?= setErrorClass($titleKey, $errors) ?>">
                                            <input class="adding-post__input form__input" id="heading" type="text" name="heading" placeholder="Введите заголовок" value="<?= htmlspecialchars($_POST[$titleKey] ?? '')?>">
                                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc"><?= setErrorText($titleKey, $errors) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $typesWithLink = ['photo', 'video', 'link'];
                                    if (in_array($currentType['type'], $typesWithLink, true)) :

                                    $linkLable = match ($currentType['type']) {
                                        'photo' => 'Ссылка из интернета ',
                                        'video' => 'Ссылка youtube <span class="form__input-required">*</span>',
                                        'link' => 'Ссылка <span class="form__input-required">*</span>',
                                    }
                                    ?>
                                    <!-- Ссылка -->
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="url"><?= $linkLable ?></label>
                                        <div class="form__input-section <?= setErrorClass('url', $errors) ?>">
                                            <input class="adding-post__input form__input" id="url" type="text" name="url" placeholder="Введите ссылку" value="<?= htmlspecialchars($_POST[$linkKey] ?? '')?>">
                                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc"><?= setErrorText('url', $errors) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    endif;
                                    $typesWithText = ['quote', 'text'];
                                    if (in_array($currentType['type'], $typesWithText, true)) :

                                    $contentLable = $currentType['type'] === 'quote' ? 'Текст цитаты' : 'Текст поста';
                                    $contentPlaceholder = $currentType['type'] === 'quote' ? $contentLable : 'Введите текст публикации';
                                    ?>
                                    <!-- Текст -->
                                    <div class="adding-post__textarea-wrapper form__textarea-wrapper">
                                        <label class="adding-post__label form__label" for="content"><?= $contentLable ?> <span class="form__input-required">*</span></label>
                                        <div class="form__input-section <?= setErrorClass($textKey, $errors) ?>">
                                            <textarea class="adding-post__textarea form__textarea form__input" id="content" placeholder="<?= $contentPlaceholder ?>" name="content"><?= htmlspecialchars($_POST[$textKey] ?? '')?></textarea>
                                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc"><?= setErrorText($textKey, $errors) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    endif;
                                    if ($currentType['type'] === 'quote') :
                                    ?>
                                    <!-- Автор цитаты -->
                                    <div class="adding-post__textarea-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="author">Автор <span class="form__input-required">*</span></label>
                                        <div class="form__input-section <?= setErrorClass($authorKey, $errors) ?>">
                                            <input class="adding-post__input form__input" id="author" type="text" name="author" value="<?= htmlspecialchars($_POST[$authorKey] ?? '')?>">
                                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc"><?= setErrorText($authorKey, $errors) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <!-- Теги -->
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="tags">Теги</label>
                                        <div class="form__input-section <?= setErrorClass($tagsKey, $errors) ?>">
                                            <input class="adding-post__input form__input" id="tags" type="text" name="tags" placeholder="Введите теги через пробел" value="<?= htmlspecialchars($_POST[$tagsKey] ?? '')?>">
                                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc"><?= setErrorText($tagsKey, $errors) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Список ошибок -->
                                <?php if (count($errors)) : ?>
                                <div class="form__invalid-block">
                                    <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                                    <ul class="form__invalid-list">
                                    <?php
                                    foreach ($errors as $error => $text) :
                                        $errorType = match($error) {
                                        'heading' => 'Заголовок',
                                        'url' => 'Ссылка',
                                        'content' => 'Текст',
                                        'author' => 'Автор',
                                        'tags' => 'Теги',
                                        }
                                    ?>
                                        <li class="form__invalid-item"><?= $errorType . '. ' . $text ?></li>
                                    <?php endforeach; ?>
                                    </ul>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php if ($currentType['type'] === 'photo') : ?>
                            <!-- Загрузка фото -->
                            <div class="adding-post__input-file-container form__input-container form__input-container--file">
                                <div class="adding-post__input-file-wrapper form__input-file-wrapper">
                                    <div class="adding-post__file-zone adding-post__file-zone--photo form__file-zone dropzone">
                                        <input class="adding-post__input-file form__input-file" id="photo" type="file" name="photo" title=" ">
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
                            <?php endif; ?>

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
