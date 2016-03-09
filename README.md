# Laravel Friendable

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

## Install

Via Composer

``` bash
$ composer require draperstudio/laravel-friendable
```

And then include the service provider within `app/config/app.php`.

``` php
'providers' => [
    DraperStudio\Friendable\ServiceProvider::class
];
```

At last you need to publish and run the migration.

```
php artisan vendor:publish --provider="DraperStudio\Friendable\ServiceProvider" && php artisan migrate
```

## Usage

## Setup a Model
``` php
<?php

/*
 * This file is part of Laravel Friendable.
 *
 * (c) DraperStudio <hello@draperstudio.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use DraperStudio\Friendable\Contracts\Friendable;
use DraperStudio\Friendable\Traits\Friendable as FriendableTrait;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements Friendable
{
    use FriendableTrait;
}
```

## Examples

#### Send a Friend-Request to a Model
``` php
$user->befriend($userToBeFriendsWith);
```

#### Unfriend a Model
``` php
$user->unfriend($userToBeFriendsWith);
```

#### Deny a Friend-Request from a Model
``` php
$user->denyFriendRequest($userToBeFriendsWith);
```

#### Accept a Friend-Request from a Model
``` php
$user->acceptFriendRequest($userToBeFriendsWith);
```

#### Block a Model
``` php
$user->blockFriendRequest($userToBeFriendsWith);
```

#### Unblock a Model
``` php
$user->unblockFriendRequest($userToBeFriendsWith);
```

#### Check if the Model has blocked another Model
``` php
$user->hasBlocked($userToBeFriendsWith);
```

#### Check if one Model is blocked by another Model
``` php
$user->isBlockedBy($userToBeFriendsWith);
```

#### Check if a Friendship exists between two models
``` php
$user->isFriendsWith($userToBeFriendsWith);
```

#### Get a single friendship
``` php
$user->getFriendship($userToBeFriendsWith);
```

#### Get a list of all Friendships
``` php
$user->getAllFriendships();
```

#### Get a list of pending Friendships
``` php
$user->getPendingFriendships();
```

#### Get a list of accepted Friendships
``` php
$user->getAcceptedFriendships();
```

#### Get a list of denied Friendships
``` php
$user->getDeniedFriendships();
```

#### Get a list of blocked Friendships
``` php
$user->getBlockedFriendships();
```

#### Get a list of pending Friend-Requests
``` php
$user->getFriendRequests();
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email hello@draperstudio.tech instead of using the issue tracker.

## Credits

- [DraperStudio][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/DraperStudio/laravel-friendable.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/DraperStudio/Laravel-Friendable/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/DraperStudio/laravel-friendable.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/DraperStudio/laravel-friendable.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/DraperStudio/laravel-friendable.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/DraperStudio/laravel-friendable
[link-travis]: https://travis-ci.org/DraperStudio/Laravel-Friendable
[link-scrutinizer]: https://scrutinizer-ci.com/g/DraperStudio/laravel-friendable/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/DraperStudio/laravel-friendable
[link-downloads]: https://packagist.org/packages/DraperStudio/laravel-friendable
[link-author]: https://github.com/DraperStudio
[link-contributors]: ../../contributors
