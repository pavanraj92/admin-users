# Admin User Manager

This Laravel module provides a simple CRUD (Create, Read, Update, Delete) interface for managing basic website users. It enables administrators to easily control and update core users used throughout the application.

## Features

- Create new users
- View a list of existing users
- Update user details
- Delete users

## Usage

1. **Create**: Add a new user with name, email, and password.
2. **Read**: View all users in a paginated list.
3. **Update**: Edit user information.
4. **Delete**: Remove users that are no longer needed.

## Example Endpoints

| Method | Endpoint              | Description         |
|--------|-----------------------|---------------------|
| GET    | `/users/{type}`       | List all users      |
| POST   | `/users/{type}`       | Create a new user   |
| GET    | `/users/{type}/{id}`  | Get user details    |
| PUT    | `/users/{type}/{id}`  | Update a user       |
| DELETE | `/users/{type}/{id}`  | Delete a user       |

## Requirements

- PHP 8.2+
- Laravel Framework

## Update `composer.json` file

Add the following to your `composer.json` to use the package from a local path:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/pavanraj92/admin-users.git"
    }
]
```

## Installation

```bash
composer require admin/user:@dev
```

## Usage

1. Publish the configuration and migration files:
    ```bash
    php artisan user:publish --force

    composer dump-autoload
    
    php artisan migrate
    ```
2. Access the User manager from your admin dashboard.

## Example

```php
// Creating a new user
$user = new User();
$user->first_name = 'John';
$user->last_name = 'Doe';
$user->email = 'john.doe@example.com';
$user->mobile = '9876543210';
$user->status = 1;
$user->save();
```

## Customization

You can customize views, routes, and permissions by editing the configuration file.

## License

This package is open-sourced software licensed under the Dotsquares.write code in the readme.md file regarding to the admin/user manager