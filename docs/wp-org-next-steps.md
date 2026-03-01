# Шаги для WP.org и инструкция по companion-плагину

Этот файл — короткая памятка, что осталось сделать для публикации темы и плагина.

## Что уже сделано

- Тема и плагин разделены.
- Логика `Second Blog` вынесена в `plugins/kilka-second-blog/kilka-second-blog.php`.
- Для плагина задан отдельный `Text Domain`: `kilka-second-blog`.
- Сборка выполняется раздельно через `./scripts/build-packages.sh`.
- Атрибуция оригинального автора уже добавлена в `style.css` и `readme.txt`.
- Подготовлен шаблон readme плагина: `plugins/kilka-second-blog/readme.txt`.

## Что осталось для WordPress.org Theme Directory

1. Выбрать финальное уникальное имя/slug темы.
2. Обновить метаданные в `style.css` (имя, версия, при необходимости `Text Domain`).
3. Проверить `Contributors` в `readme.txt` (должен быть WordPress.org username).
4. Проверить блок лицензий всех сторонних ресурсов (Bootstrap, SlickNav, шрифты и т.д.).
5. Прогнать тему через Theme Check и исправить обязательные замечания.
6. Собирать ZIP темы отдельно: `dist/kilka.zip` (без плагина внутри).
7. Отправить тему в каталог WP.org и пройти ревью.

## Плагин: как опубликовать в WordPress.org Plugin Directory

1. Подготовить метаданные плагина:
   - проверить header в `plugins/kilka-second-blog/kilka-second-blog.php`;
   - поддерживать `plugins/kilka-second-blog/readme.txt` (это основной исходник readme для плагина).
   - проверить `Contributors` в plugin readme (должен совпадать с реальным WordPress.org username).
2. Подать заявку на slug плагина:
   - `https://wordpress.org/plugins/developers/add/`
3. После одобрения использовать выданный SVN-репозиторий:
   - код в `trunk/`;
   - релиз в `tags/x.y.z/`;
   - баннер/иконки в `assets/` (по желанию, но рекомендуется).
4. Указать `Stable tag` в plugin `readme.txt`.
5. Закоммитить в SVN и проверить, что страница плагина появилась в каталоге.

## Где фиксировать readme плагина

1. В Git-репозитории:
   - `plugins/kilka-second-blog/readme.txt`
2. В WP.org SVN после одобрения:
   - `trunk/readme.txt`
   - `tags/x.y.z/readme.txt` (для каждого релиза)

## Как тема должна ссылаться на плагин

1. Тема может рекомендовать плагин.
2. Тема не должна зашивать/автоустанавливать плагин нарушающим правила способом.
3. CPT/таксономии должны оставаться в плагине.

## Сборка ZIP (тема + плагин)

```bash
./scripts/build-packages.sh
```

Результат:

- `dist/kilka.zip`
- `dist/kilka-second-blog.zip`

## Рекомендуемый порядок релиза

1. Поднять версии темы и плагина.
2. Собрать ZIP.
3. Проверить на чистом WordPress:
   - сначала активировать плагин;
   - потом активировать тему.
4. Проверить ключевые сценарии:
   - архив `second-blog`;
   - одиночный пост `second-blog`;
   - выборки по tag/category/date/author;
   - поиск с `post_type=world_note`;
   - `Customizer -> Second Blog Intro`.
5. Публиковать.

## Полезные ссылки

- Требования к темам: `https://make.wordpress.org/themes/handbook/review/required/`
- Header `style.css`: `https://developer.wordpress.org/themes/classic-themes/basics/main-stylesheet-style-css/`
- Handbook по плагинам: `https://developer.wordpress.org/plugins/`
- Подача плагина: `https://wordpress.org/plugins/developers/add/`
- Лицензия WordPress: `https://wordpress.org/about/license/`
- Trademark policy: `https://wordpressfoundation.org/trademark-policy/`
