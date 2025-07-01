# admin-user

This package allows you to perform CRUD operations for managing users in the admin panel.

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

| Method | Endpoint        | Description         |
|--------|----------------|---------------------|
| GET    | `/users`       | List all users      |
| POST   | `/users`       | Create a new user   |
| GET    | `/users/{id}`  | Get user details    |
| PUT    | `/users/{id}`  | Update a user       |
| DELETE | `/users/{id}`  | Delete a user       |

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
composer require admin/user
```

## License

MIT