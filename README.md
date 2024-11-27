# Динамическая обработка очередей

## Описание работы

Запуск обработчика происходит путем выполнения

``php artisan queue:dynamic``

При запуске выполняется команда `App\Console\Commands\DynamicQueue`. 
В этой команде мы подключаемся к RabbitMQ, смотрим сколько есть сообщений в очереди `test1`, `test2`, `test3`. В зависимости от того, сколько сообщений в очереди, количество уже запущенных команд и значения константы `MAX_WORKERS_PER_QUEUE`, мы запускаем команды воркеры для конкретной очереди с параметром `--once`. 

В итоге, мы можем запускать воркеры под каждое отдельное сообщение в очереди, контролируя количество константой `MAX_WORKERS_PER_QUEUE`.
Если очереди пусты, то будет запущен только один процесс — `queue:dynamic`, процессы-воркеры будут запущены только по необходимости.

## Планы на будущее

То, что я реализовал — пока набросок. 
Планирую добавить больше конфигурируемости. Например:
1. Сделать конфигурируемыми очереди, которые "слушаются" при вызове `queue:dynamic`. На текущий момент захардкожены очереди `test1`, `test2`, `test3`.
2. Для каждой очереди сделать конфигурируемыми то, с какими параметрами им необходимо запускаться. Параметры `--once` и `--queue={queue}` обязательны, поскольку в них весь смысл этой затеи. Конфигурируемыми, например, могут быть `--timeout={timeout}`. Для других стоит подробнее изучить документацию.
3. Также для каждой очереди нужно сделать конфигурируемыми `MAX_WORKERS_PER_QUEUE`, вместо использования константы в классе команды.
4. Настроить очереди failled_jobs для провалившихся команд. Также, можно настроить очереди для сервиса аналитики.
5. Рефакторинг

## Предполагаемое использование

1. Создать отдельный контейнер, где будет развернут RabbitMQ. 
2. На этот RabbitMQ будут поступать запросы на заполнение очередей сообщениями по протоколу AMQP.
3. Выделить код команды `App\Console\Commands\DynamicQueue` в отдельный пакет.
4. Подгружать на необходимый проект эту команду через composer.
5. Создать класс-воркер через `php artisan make:job {JobName}`. 
6. Наполнить класс-воркер бизнес-логикой.
7. В конфиге `queue.rabbitmq.queue` связать очередь с классом-воркером. (Как и в целом заполнить конфиг)
8. Запускать `queue:dynamic` под необходимые очереди