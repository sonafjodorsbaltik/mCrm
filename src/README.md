# mCRM — Mini CRM для сбора заявок

Мини-CRM система для сбора и обработки заявок с сайта через универсальный виджет.

## Технологии

- **Backend:** Laravel 12, PHP 8.4
- **Database:** MySQL 8.0
- **Cache:** Redis
- **Packages:** spatie/laravel-permission, spatie/laravel-medialibrary, giggsey/libphonenumber-for-php

## Быстрый старт (Docker)

```bash
# Клонировать репозиторий
git clone <repository-url>
cd mCrm

# Запустить контейнеры
docker-compose up -d

# Установить зависимости
docker-compose exec app composer install

# Настроить окружение
cp src/.env.example src/.env
docker-compose exec app php artisan key:generate

# Миграции и seed данные
docker-compose exec app php artisan migrate --seed

# Создать символическую ссылку для storage
docker-compose exec app php artisan storage:link
```

Приложение доступно по адресу: **http://localhost**

## Тестовые данные

После выполнения `php artisan migrate --seed` создаются:

| Email | Пароль | Роль |
|-------|--------|------|
| admin@test.com | password | Admin |
| manager@test.com | password | Manager |

Также создаются тестовые клиенты и заявки.

## Встраивание виджета (iframe)

Виджет обратной связи можно встроить на любой сайт:

```html
<iframe 
    src="http://localhost/widget" 
    width="100%" 
    height="600" 
    frameborder="0"
    style="max-width: 500px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
</iframe>
```

## API

### POST /api/v1/tickets — Создание заявки

```bash
curl -X POST http://localhost/api/v1/tickets \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Иван Иванов",
    "phone": "+380501234567",
    "email": "ivan@example.com",
    "subject": "Вопрос по услуге",
    "content": "Текст сообщения",
    "files": []
  }'
```

**Ответ (201 Created):**
```json
{
  "data": {
    "id": 1,
    "subject": "Вопрос по услуге",
    "content": "Текст сообщения",
    "status": "Новая",
    "status_code": "new",
    "created_at": "2025-12-06T19:00:00.000000Z",
    "customer": {
      "name": "Иван Иванов",
      "email": "ivan@example.com",
      "phone": "+380501234567"
    },
    "files": []
  }
}
```

### GET /api/v1/tickets/statistics — Статистика (требует авторизации)

```bash
curl -X GET "http://localhost/api/v1/tickets/statistics?period=week" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer {token}"
```

**Параметры:**
- `period`: `day` | `week` | `month` (по умолчанию: `day`)

**Ответ:**
```json
{
  "data": {
    "period": "week",
    "total": 25,
    "new": 10,
    "in_progress": 8,
    "closed": 7
  }
}
```

## Структура проекта

```
app/
├── Models/           # Eloquent модели
├── Services/         # Бизнес-логика
├── Repositories/     # Доступ к данным
├── DTOs/             # Data Transfer Objects
├── Enums/            # Перечисления (TicketStatusEnum)
├── Observers/        # Eloquent Observers
├── Http/
│   ├── Controllers/  # API, Admin, Widget контроллеры
│   ├── Requests/     # Form Requests для валидации
│   └── Resources/    # API Resources
└── Rules/            # Custom Validation Rules
```

## Админ-панель

Доступна по адресу `/admin` после авторизации:

- **Dashboard** — статистика и последние заявки
- **Tickets** — список заявок с фильтрацией
- **Users** — управление менеджерами (только для Admin)

## Rate Limiting

Ограничение: не более 1 заявки в сутки с одного телефона И email.

## Лицензия

MIT
