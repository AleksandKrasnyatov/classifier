#### Измененные/новые файлы пометил phpdoc /** new */

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
{
  "statusCode": 200,
  "data": {
    "queue": "payments-l2",
    "priority": "high",
    "response_target_minutes": 15,
    "rule_id": "premium_payment_incident",
    "rules_version": "2026-07-23T00:00:00Z"
  }
}
```
