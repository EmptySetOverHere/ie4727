# Naming Conventions

## PHP
- **Classes**: Use `StudlyCaps` (e.g., `UserAccount.php`).
- **Methods**: Use `lower_snake_case` (e.g., `get_user_data`).
- **Variables**: Use `lower_snake_case` (e.g., `$username_count`).
- **Constants**: Use `UPPER_SNAKE_CASE` (e.g., `MAX_USERS`).
- **Filenames**: Use `lower_snake_case` (e.g., `user_account.php`).
    - **HTTP request handler files**: Use `api_[action].php` (e.g., `api_create_user.php`).

## HTML/CSS
- **HTML Elements**: Use `lower_snake_case` or `kebab-case` for IDs, classes and names(e.g., `user_profile`, `main-header`).
- **CSS Classes**: Use `kebab-case` (e.g., `.btn-primary`, `.nav-item`).
- **CSS IDs**: Use `kebab-case` (e.g., `#main-content`, `#sidebar`).

## SQL (MariaDB)
- **Database Tables**: Use `lower_snake_case` (e.g., `user_accounts`).
- **Table Attributes (Columns)**: Use `lower_snake_case` (e.g., `user_id`, `created_at`).
  - Prefix attributes with the table name when necessary to avoid ambiguity (e.g., `user_accounts.user_id`).