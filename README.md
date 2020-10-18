<h2>Комментарий к заданию:</h2>

<b>Затраченное время: 8 часов.</b>
Думаю, близко к правде. Сильно точно я не следил, примерно 4 сессии по 2 часа.

Также решил выложить задание, на это ушло какое-то время.
И пришлось немного погуглить:
1) Оказывается Laravel не умеет запустить свои же миграции из коробки, только с хаком
Schema::defaultStringLength(191);
2) Весьма удивлен, что в билдере список операторов не включает IN, только черзе whereIn

<h2>Как пользоваться:</h2>

1. Получение курса валют на любой день.
currency/{day}/{month}/{year}
2. Получение курса для списка сравниваемых валют.
currency/form - тут нужно ввести в форму нужное
3. Возможность сохранить подборку
При отправке формы на currency/list сразу пытаюсь сохранить подборку
4. Получение сохраненной подборки
currency/list/{id} - Есть уже пару штук в система
5. Возмжоность добавлять примечание к подборке
По пути в форме currency/form есть поле для примечания

<h2>Проблема:</h2>
1. Минимизация запросов к внешнему сервису. - 1 запрос на день. Любые выборки получаются на основе сохраненных данных.
2. Оптимизация/ускорение ответов для пользователя. - Тут бы кеш прикрутить. Но впринципе данные получаются быстро,
да и тестовое все-таки, показал только основной вектор разработки.

БД mysql
Сервис https://www.cbr.ru
Приложение: https://goriaev.ru

<h2>Что в коде хорошего:</h2>
Есть небольшой велосипед GetSetTrait.php, но на самом деле он для того, чтобы разделить данные от операций над данными (SRP).
Компонент Currency с бизнес-логикой наиболее абстрактен и не содержит зависимостей фреймворка (DIP).
Выделены интерфейсы и зависимости инвертированы:
Поток управления CurrencyController (самый "грязный" компонент, создает конкретные классы, также как и тесты),
тут же и репозитории, а слой данных отделен.

<h2>Что в коде плохого:</h2>
Точно надо выпиливать RangeRecord и CurrencyRecord и полностью переходить на репозитории. Сейчас франкенштейн получился, сохранение идет через модель.
Программа не учитывает часовой пояс.
Приложение не учитывает повторяющиеся тикеры при выборке (неправильные учитывает).
Тест только 1 - проверяет, что по запросу к https://www.cbr.ru приходит xml