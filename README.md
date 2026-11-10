# BD Phone Validator for Laravel

Validate, normalize and detect the operator of Bangladeshi mobile numbers.

```php
$request->validate([
    'phone' => ['required', new ValidBdPhone],
]);

BdPhone::operator('01712345678');        // Grameenphone
BdPhone::normalize('+880 1712-345678');  // 01712345678
BdPhone::international('01712345678');   // +8801712345678
```

## Installation

```bash
composer require sanvalabs/bd-phone-validator
```

The service provider and facade are registered automatically (Laravel package auto-discovery).

## Usage

### Validation rule

```php
use Sanvalabs\BdPhone\Rules\ValidBdPhone;

$request->validate([
    'phone' => ['required', new ValidBdPhone],
]);
```

Allow only some operators:

```php
'phone' => ['required', new ValidBdPhone(['Grameenphone', 'Robi'])],
```

Error messages:

| Case | English | Bangla (`APP_LOCALE=bn`) |
|---|---|---|
| Invalid number | The phone must be a valid Bangladeshi mobile number. | phone একটি সঠিক বাংলাদেশি মোবাইল নম্বর হতে হবে। |
| Operator not allowed | The phone must be a Grameenphone, Robi number. | phone অবশ্যই Grameenphone, Robi নম্বর হতে হবে। |

### Facade

```php
use Sanvalabs\BdPhone\Facades\BdPhone;

BdPhone::isValid('01712345678');           // true
BdPhone::isValid('12345');                 // false

BdPhone::normalize('+880 1712-345678');    // 01712345678
BdPhone::normalize('12345');               // null

BdPhone::operator('01812345678');          // Robi
BdPhone::international('017-1234-5678');   // +8801712345678
```

Accepted formats: `01712345678`, `+8801712345678`, `8801712345678`, and any of these with spaces or dashes.

### Supported operators

| Prefix | Operator |
|---|---|
| 013, 017 | Grameenphone |
| 014, 019 | Banglalink |
| 015 | Teletalk |
| 016 | Airtel |
| 018 | Robi |

## Configuration

Publish the config file to change or add operator prefixes:

```bash
php artisan vendor:publish --tag=bd-phone-config
```

`config/bd-phone.php`:

```php
return [
    'operators' => [
        '013' => 'Grameenphone',
        '014' => 'Banglalink',
        // ...
    ],
];
```

## Translations

The package ships English (`en`) and Bangla (`bn`) messages. To customize them:

```bash
php artisan vendor:publish --tag=bd-phone-lang
```

Then edit the files in `lang/vendor/bd-phone/`.

## Testing

```bash
composer test
```

## License

MIT
