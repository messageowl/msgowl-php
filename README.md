# MessageOwl PHP SDK

PHP SDK for the [MessageOwl](https://msgowl.com) SMS API.

## Installation

```bash
composer require messageowl/msgowl-php
```

## Laravel Setup

Add your API key to `.env`:

```env
MESSAGEOWL_API_KEY=your-api-key
```

The service provider is auto-discovered. Optionally publish the config:

```bash
php artisan vendor:publish --tag=messageowl-config
```

## Plain PHP Setup

```php
use MessageOwl\MessageOwl;

$client = new MessageOwl('your-api-key');
```

## Authentication

By default the SDK uses the `Authorization: AccessKey {key}` header. If headers are unavailable, switch to query param auth:

```php
$client = new MessageOwl('your-api-key', useQueryAuth: true);
```

---

## Usage

### Send a Message

```php
// Single recipient
$response = $client->message()
    ->to('9609848571')
    ->from('MyApp')
    ->body('Hello from MessageOwl!')
    ->send();

echo $response->id;      // int
echo $response->message; // "Message has been sent successfully."

// Multiple recipients
$client->message()
    ->to(['9609848571', '9609876543'])
    ->from('MyApp')
    ->body('Hello!')
    ->send();
```

### List Messages

```php
// Latest 100 messages
$messages = $client->messages()->all();

foreach ($messages as $message) {
    echo $message->id;
    echo $message->smsHeader;
    echo $message->status;
    echo $message->createdAt;
    echo $message->body; // null if message.body.read scope is absent
}
```

### Fetch a Message

```php
$message = $client->messages()->find(8848);

echo $message->accountId;

foreach ($message->recipients as $recipient) {
    echo $recipient->phoneNumber;
    echo $recipient->deliveryStatus->name; // DeliveryStatus enum
    echo $recipient->smsStatus->name;      // SmsStatus enum
    echo $recipient->deliveredOn;
}
```

### OTP

```php
// Send OTP (code auto-generated if omitted)
$otp = $client->otp()->send('9609999999');
$otp = $client->otp()->send('9609999999', codeLength: 8);
$otp = $client->otp()->send('9609999999', code: '235311');

echo $otp->id;
echo $otp->phoneNumber;
echo $otp->timestamp;
echo $otp->messageId;

// Resend OTP
$otp = $client->otp()->resend('9609999999', id: $otp->id);

// Verify OTP
$result = $client->otp()->verify('9609999999', '235311');

echo $result->status ? 'Verified' : 'Invalid';
```

### Groups

```php
// List
$groups = $client->groups()->all();

// Fetch (includes contacts)
$group = $client->groups()->find(22);
foreach ($group->contacts as $contact) {
    echo $contact->name;
    echo $contact->number;
}

// Create
$group = $client->groups()->create('My Group');

// Update
$group = $client->groups()->update(22, 'New Name');

// Delete
$client->groups()->delete(22); // returns true
```

### Contacts

```php
// List (paginated, 100 per page)
$list = $client->contacts()->all(page: 1);

echo $list->currentPage;
echo $list->nextPage;
echo $list->previousPage;

foreach ($list->contacts as $contact) {
    echo $contact->name;
    echo $contact->number;
}

// Create (optionally assign to groups by name)
$contact = $client->contacts()->create('John', '9609999999', ['My Group']);

foreach ($contact->groups as $group) {
    echo $group->id;
    echo $group->name;
}

// Update
$contact = $client->contacts()->update(1232, 'John Updated', '9609999999', ['My Group']);

// Delete
$client->contacts()->delete(1232); // returns true
```

### Balance

```php
$balance = $client->balance();

echo $balance->balance; // "130.6347" (string)
```

### Sender IDs

```php
$senderIds = $client->senderIds();

foreach ($senderIds as $sender) {
    echo $sender->name;
    echo $sender->status;    // "approved" | "pending verification"
    echo $sender->purpose;   // nullable
    echo $sender->remarks;   // nullable
    echo $sender->handledAt; // nullable
}
```

---

## Exception Handling

```php
use MessageOwl\Exceptions\AuthenticationException;
use MessageOwl\Exceptions\MethodNotAllowedException;
use MessageOwl\Exceptions\MessageOwlException;
use MessageOwl\Exceptions\NotFoundException;
use MessageOwl\Exceptions\RateLimitException;
use MessageOwl\Exceptions\RequestTimeoutException;
use MessageOwl\Exceptions\ServerException;
use MessageOwl\Exceptions\ValidationException;

try {
    $client->message()->to('9609848571')->from('App')->body('Hi')->send();
} catch (AuthenticationException $e) {
    // 401 — invalid API key
} catch (NotFoundException $e) {
    // 404 — resource not found
} catch (MethodNotAllowedException $e) {
    // 405
} catch (RequestTimeoutException $e) {
    // 408 or connection timeout
} catch (ValidationException $e) {
    // 422 — check $e->bulkLimit for bulk limit violations
    if ($e->bulkLimit !== null) {
        echo "Bulk limit: {$e->bulkLimit}";
    }
} catch (RateLimitException $e) {
    // 429 — retry after $e->retryAfter seconds
    echo "Retry after: {$e->retryAfter}s";
    echo "Limit: {$e->rateLimitLimit}";
    echo "Remaining: {$e->rateLimitRemaining}";
    echo "Reset at: {$e->rateLimitReset}";
} catch (ServerException $e) {
    // 5xx
} catch (MessageOwlException $e) {
    // catch-all for any other SDK error
}
```

---

## Rate Limiting

The API allows **50 POST requests per 60 seconds** per account. When exceeded, a `RateLimitException` is thrown with the `retryAfter` property set to the number of seconds to wait.

---

## Laravel Facade

```php
use MessageOwl\Laravel\Facades\MessageOwl;

MessageOwl::message()->to('9609848571')->from('MyApp')->body('Hello!')->send();
MessageOwl::balance();
MessageOwl::senderIds();
```

---

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md).

## License

MIT — see [LICENSE](LICENSE).
