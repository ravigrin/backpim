## Модуль ozon

### Назначение
1. Импорт товаров ozon и создание связи с товарами pim
2. Сохранение истории изменений товара
3. Обновление товара в ozon
4. Обновление цен товара в ozon

### Структура

sh: посмотреть полную структуру
```text
tree module/ozon/src
```
Общая концепция архитекруты
```
Application - слой для внешних вызовов по концепции cqrs 
    
    Command - команды на запить (всегда возращают void)
        AttributeImport - каждая команда в своей папке
            Command.php - описывает данные для команды (строго простые типы)
            Handler.php - реализация (подключение сервисов и/или интерфейсов репозиториев) из Domain
    
    Query - запрос на чтение данных (в идеале возвращает dto)
        GetByCatalog - каждая query в своей папке
            Fetcher.php - реализация, подключение интерфейсов репозиториев за которыми sql запрос без doctrine
            Query.php - описывает данные для запроса (строго простые типы)

Domain - слой бизнес логики
    Entity - Модели таблиц в базе данных Doctine (в идеали их отвязать и сделать инициализацию через паттерн Hydrator) 
      В них реализована событийная модель через AggregateRoot 
    Event - Сами события. Описываются как Command или Query
    Listener - Реализация событий. Они запускаются в worker (symfony messenger)
    Repository - Интерфейсы репозиториев (сама реализация в Infrastructure)
    Service - Сервисы которые позволяют избавиться от дублирования кода в Command / Listener
    ValueObject - Описывает типы в Entity. Реализовано через custom type в Doctrine ORM 
    
Infrastructure
    Internal - Тут реазизация интерфейсов для межмодульного взаиможействия
    Doctirne - Реализация интрефейсов для работы через Docrine ORM или DBAL
    

```
