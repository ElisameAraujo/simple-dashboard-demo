# 🔔 NotificationHelper

A classe **NotificationHelper** fornece funções utilitárias para manipulação de notificações do usuário no Laravel.  
Ela simplifica operações comuns como listar notificações não lidas, contar, marcar como lidas/não lidas, remover e consultar histórico.

---

## 📂 Funções disponíveis

### `unreadNotificationsByType(string $type, ?string $subfolder = null, ?int $limit = 5)`

Lista notificações **não lidas** de um tipo específico.

-   `$type`: Nome da classe de notificação.
-   `$subfolder`: Subpasta dentro de `App\Notifications`. Opcional.
-   `$limit`: Limite de registros retornados. Opcional, padrão `5`.

```php
NotificationHelper::unreadNotificationsByType('NewMessageNotification', 'User', 10);
```

---

### `unreadNotificationsByTypeCount(string $type, ?string $subfolder = null): int`

Retorna o **total de notificações não lidas** de um tipo específico.

```php
NotificationHelper::unreadNotificationsByTypeCount('NewMessageNotification', 'User');
// 3
```

---

### `allUnreadNotifications(?int $limit = 10)`

Lista **todas as notificações não lidas** do usuário.

-   `$limit`: Limite de registros retornados. Opcional, padrão `10`.

```php
NotificationHelper::allUnreadNotifications(20);
```

---

### `allUnreadNotificationsCount(): int`

Retorna o **total geral de notificações não lidas** do usuário.

```php
NotificationHelper::allUnreadNotificationsCount();
// 12
```

---

### `markAllAsRead(): void`

Marca **todas as notificações não lidas** como lidas.

```php
NotificationHelper::markAllAsRead();
```

---

### `markAllAsReadByType(string $type, ?string $subfolder = null): void`

Marca **todas as notificações não lidas de um tipo específico** como lidas.

```php
NotificationHelper::markAllAsReadByType('NewMessageNotification', 'User');
```

---

### `latestNotifications(?int $limit = 10)`

Lista as **últimas notificações** (lidas ou não).

-   `$limit`: Limite de registros retornados. Opcional, padrão `10`.

```php
NotificationHelper::latestNotifications(15);
```

---

### `markAsRead(string $notificationId): bool`

Marca uma **notificação específica** como lida.

-   `$notificationId`: ID da notificação.

```php
NotificationHelper::markAsRead('12345-uuid');
// true
```

---

### `markAsUnread(string $notificationId): bool`

Marca uma **notificação específica** como não lida.

-   `$notificationId`: ID da notificação.

```php
NotificationHelper::markAsUnread('12345-uuid');
// true
```

---

### `deleteNotification(string $notificationId): bool`

Remove uma **notificação específica**.

-   `$notificationId`: ID da notificação.

```php
NotificationHelper::deleteNotification('12345-uuid');
// true
```

---

## ✅ Observações importantes

-   Todas as funções verificam se o usuário está autenticado (`Auth::check()`).
-   Se o usuário não estiver autenticado, funções que retornam listas devolvem uma `Collection` vazia e funções que retornam contadores devolvem `0`.
-   IDs de notificações são geralmente UUIDs gerados pelo Laravel.
-   Métodos de marcação (`markAsRead`, `markAsUnread`, `markAllAsRead`, `markAllAsReadByType`) atualizam o campo `read_at`.
-   `deleteNotification` remove permanentemente a notificação do banco.
