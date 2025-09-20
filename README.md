# Laravel FCM

A Laravel package for integrating Firebase Cloud Messaging (FCM) with your Laravel application. **Still in early development.**

## Features

- Send push notifications via FCM using Laravel Notifications
- Manage user/device FCM tokens
- Flexible message builder (title, body, data, badge, sound, platform config)
- Built-in authentication and service client for FCM API
- Eloquent support for storing tokens (polymorphic relation)
- Extensible for custom notification types

## Installation

```bash
composer require mustafaamaklad/laravel-fcm
```

Publish the config (if available):

```bash
php artisan vendor:publish --provider="MustafaAMaklad\Fcm\Providers\FcmServiceProvider"
```

Run the migration to create the FCM tokens table:

```bash
php artisan migrate
```

## Configuration

Add your Firebase credentials to your `.env`:

```
FIREBASE_PROJECT_ID=your_project_id
FIREBASE_CLIENT_EMAIL=your_client_email
FIREBASE_PRIVATE_KEY="your_private_key"
FIREBASE_TOKEN_URI="https://oauth2.googleapis.com/token"
FCM_SCOPE="https://www.googleapis.com/auth/firebase.messaging"
```

## Usage

### Storing Device Tokens

Your Notifiable models (e.g., User) should use the `FcmNotifiable` trait:

```php
use MustafaAMaklad\Fcm\Traits\FcmNotifiable;

class User extends Model
{
    use FcmNotifiable;
}
```

### Sending Notifications

Create a notification that implements `FcmNotification`:

```php
use MustafaAMaklad\Fcm\Contracts\FcmNotification;
use MustafaAMaklad\Fcm\Services\FcmMessage;

class InvoicePaidNotification extends Notification implements FcmNotification
{
    public function toFcm($notifiable)
    {
        return (new FcmMessage())
            ->token($notifiable->routeNotificationForFcm())
            ->title('Invoice Paid')
            ->body('Your invoice has been paid!')
            ->data(['invoice_id' => 123]);
    }
}
```

Send the notification:

```php
$user->notify(new InvoicePaidNotification());
```

### Facade Usage

You may also use the `Fcm` facade directly for custom sending.

## Migration

A migration is provided to store FCM tokens related to your notifiable models.

```php
$table->morphs('tokenable');
$table->string('token')->unique();
$table->boolean('is_active')->default(true);
```

## Testing

This package uses [Orchestra Testbench](https://github.com/orchestral/testbench) for Laravel package testing.

## Roadmap

- More message customization options
- Improved error handling
- Documentation and examples

## License

This package is currently unlicensed. Please contact [MustafaAMaklad](https://github.com/MustafaAMaklad) before use in production.

---

**Note:** This package is under active development. Feedback and contributions are welcome!
