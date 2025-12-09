# Архитектура проекта mCRM

## Выбор архитектурного паттерна

**Repository + Service + DTO** — разделение ответственности:

| Слой | Ответственность |
|------|-----------------|
| **Controller** | Только HTTP (принять запрос → вернуть ответ) |
| **Service** | Бизнес-логика, транзакции, оркестрация |
| **Repository** | Доступ к данным (Eloquent queries) |
| **DTO** | Типобезопасная передача данных между слоями |

**Почему не Active Record напрямую:**
- Легче тестировать (можно подменить репозиторий)
- Единая точка изменения запросов
- Контроллеры остаются тонкими

---

## Выбор библиотек

### spatie/laravel-permission
- **Зачем:** Роли `admin` и `manager`
- **Почему:** Стандарт де-факто для Laravel, простая интеграция, middleware из коробки

### spatie/laravel-medialibrary
- **Зачем:** Файлы к заявкам
- **Почему:** Полиморфные связи, автоматическая очистка при удалении модели, конвертации изображений

### giggsey/libphonenumber-for-php
- **Зачем:** Валидация телефонов E.164
- **Почему:** Порт Google libphonenumber, проверяет реальность номера (не только формат)

---

## Ключевые решения

### Enum для статусов
```php
enum TicketStatusEnum: string {
    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case CLOSED = 'closed';
}
```
**Почему:** Типобезопасность, автодополнение в IDE, MySQL ENUM в БД.

### Rate Limit (1 заявка/сутки)
- Проверка по `phone` **И** `email` (оба должны совпасть)
- Реализовано в `RateLimitService`, не middleware
- HTTP 429 при превышении

### Observer для replied_at
- `TicketObserver` автоматически устанавливает `replied_at` при смене статуса
- Логика инкапсулирована в `TicketStatusEnum::requiresReplyDate()`

### SoftDeletes для Ticket
- Заявки не удаляются физически
- Сохраняется история для аналитики

---

## Структура каталогов

```
app/
├── DTOs/           # CreateTicketDto, TicketStatisticsDto
├── Enums/          # TicketStatusEnum
├── Models/         # User, Customer, Ticket
├── Observers/      # TicketObserver (replied_at)
├── Policies/       # TicketPolicy, UserPolicy
├── Repositories/
│   ├── Contracts/  # Interfaces
│   └── Eloquent/   # Implementations
├── Rules/          # E164PhoneNumber (custom validation)
└── Services/       # Бизнес-логика
```

---

## Принципы

- **SOLID** — особенно SRP (каждый класс = одна задача)
- **PSR-12** — стиль кода
- **DRY** — переиспользование через сервисы
- **KISS** — минимум магии, явные зависимости
