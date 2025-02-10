# Calculate Price API

## Описание
Symfony REST API для расчета цены продукта с учетом налогов и скидок, а также для осуществления покупки через платежные процессоры (PayPal или Stripe).

## Требования
- PHP 8.3+
- Composer
- Docker
- PostgreSQL 16
- Redis (опционально - выключен по умолчанию)

## Установка
1. Клонируйте репозиторий и перейдите в директорию:
   ```bash
   git clone https://github.com/codefinecode/calculate-price.git
   cd calculate-price
   ```
2. Соберите и запустите контейнеры:  
    `make init`  
   Это выполнит:  
   * Сборку образов.
   * Запуск контейнеров.
   * Применение миграций.
   * Загрузку фикстур.

3. Запустите тесты:  
    `make test`

## API Документация
по умолчанию приложение длоступно на порту 8337: http://localhost:8337/
### `/calculate-price`
- **Метод**: `POST`

#### Тело запроса:

```json
{
    "product": 1,
    "taxNumber": "DE123456789",
    "couponCode": "D15"
}
```
#### Пример ответа:
```json
{
    "product": "Iphone",
    "base_price": 85,
    "discount": 15,
    "tax": 16.15,
    "final_price": 101.15
}
```
### `/purchase`
- **Метод**: `POST`

### Для PayPal
#### Тело запроса:

```json
{
  "product": 1,
  "taxNumber": "IT12345678900",
  "couponCode": "D15",
  "paymentProcessor": "paypal"
}
```
#### Пример ответа:
```json
{
  "status": "success",
  "message": "Payment of 103.7 processed via PayPal"
}
```
### Для Stripe
#### Тело запроса:

```json
{
  "product": 1,
  "taxNumber": "IT12345678900",
  "couponCode": "D15",
  "paymentProcessor": "stripe"
}
```
#### Пример ответа:
```json
{
  "status": "success",
  "message": "Payment of 103.7 processed via Stripe"
}
```
## Расширенная контейнеризация
Проект включает следующие контейнеры:
* **PHP** (`sio_test`) : Основной контейнер с Symfony и Doctrine.
* **PostgreSQL** (`database`) : База данных для хранения продуктов и купонов.
* **Adminer** (`adminer`) : Интерфейс для администрирования базы данных.
* **Redis** (`redis`) : Опциональный сервис для кеширования результатов расчета цен. (в коде не реализован и закомментирован в `docker-compose.yml`, хотя было бы целесообразно использовать)

Запустите все контейнеры:  
`make init`  

Запустите тесты:  
`make test`

### Команды Makefile
| Команда             | Описание                                                             |
|---------------------|----------------------------------------------------------------------|
| `make init`         | Инициализация и запуск проекта (сборка, запуск, миграции, фикстуры). |
| `make up`           | Запуск контейнеров.                                                  |
| `make down`         | Остановка и удаление контейнеров.                                    |
| `make test`         | Запуск PHPUnit-тестов с подготовкой тестового окружения.             |
| `make console`      | Подключение к консоли приложения.                                    |
| `make drop-db`      | Удаление базы данных.                                                |
| `make drop-test-db` | Удаление базы данных для тестов.                                     |
| `make db`           | Создание основной и тестовой баз данных.                             |


## Возможности расширения
1. Добавление новых платежных процессоров :
   * Создайте адаптер для нового процессора, реализующий интерфейс `PaymentProcessorInterface`.
2. Интеграция Redis :
   * Раскомментируйте Redis в `docker-compose.yml`.
   * Настройте кеширование в сервисах через `services.yaml`.
3. Добавление новых типов скидок :
   * Создайте новую стратегию, реализующую интерфейс `DiscountStrategyInterface`.

## Автор
https://t.me/codemastercode
## Лицензия
MIT License
