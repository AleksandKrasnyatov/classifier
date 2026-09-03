### Проверить:

1. Запустить приложение:
    ```bash
    php -S localhost:8080 -t public
    ```

2. Отправить тестовый запрос через терминал:
    ```bash
    curl -X POST -H "Content-Type: application/json" -d '{
      "channel": "email",
      "customer_tier": "premium",
      "country": "MX",
      "subject": "Payment was charged twice",
      "tags": ["payment", "duplicate_charge"],
      "customer_age_days": 420
    }' http://localhost:8080
    ```
3. Ожидаемый ответ:
```json

```
