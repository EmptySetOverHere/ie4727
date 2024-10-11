# Naming Conventions

## PHP
- **Classes**: Use `StudlyCaps` (e.g., `UserAccount.php`).
- **Methods**: Use `camelCase` (e.g., `getUserData`).
- **Variables**: Use `camelCase` (e.g., `$userName`).
- **Constants**: Use `UPPER_SNAKE_CASE` (e.g., `MAX_USERS`).
- **Filenames**: Use `lower_snake_case` (e.g., `user_account.php`) for consistency and enhanced readability.

## HTML/CSS
- **HTML Elements**: Use `lower_snake_case` or `kebab-case` for IDs and classes (e.g., `user_profile`, `main-header`).
- **CSS Classes**: Use `kebab-case` (e.g., `.btn-primary`, `.nav-item`) for better readability.
- **CSS IDs**: Use `kebab-case` (e.g., `#main-content`, `#sidebar`) for consistency.

## SQL (MariaDB)
- **Database Tables**: Use `lower_snake_case` (e.g., `user_accounts`).
- **Table Attributes (Columns)**: Use `lower_snake_case` (e.g., `user_id`, `created_at`) for clarity and consistency.
  - Prefix attributes with the table name when necessary to avoid ambiguity (e.g., `user_accounts.user_id`).