# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

This is a **reusable Symfony bundle library** (`carloschininin/app`) that provides shared infrastructure, UI components, and abstractions for CRUD-oriented Symfony applications with role-based security.

- Type: Composer library (`carloschininin/app`)
- PHP 8.4+, Symfony 7.0|8.0, Doctrine ORM 3.0
- PSR-4 namespace: `CarlosChininin\App\`

## Commands

```bash
# Install dependencies
composer install

# Run tests
./vendor/bin/phpunit

# Check code style (without fixing)
./vendor/bin/php-cs-fixer fix --dry-run --diff

# Fix code style
./vendor/bin/php-cs-fixer fix
```

## Architecture

The library follows a two-layer clean architecture:

### Domain Layer (`src/Domain/Model/`)
Abstract base models with no framework dependencies:
- `AuthUser` — implements Symfony `UserInterface`
- `AuthRole` — permission container
- `AuthMenu` — navigation menu item

### Infrastructure Layer (`src/Infrastructure/`)
Symfony-specific implementations:

| Directory | Purpose |
|-----------|---------|
| `Controller/` | `WebController`, `WebAuthController`, `ApiController` (Bearer token auth) |
| `Manager/` | `CRUDManager` (CRUD + pagination + export), `ExportManager`, `ListPaginatedManager` |
| `Repository/` | `BaseRepository` for Doctrine ORM |
| `Security/` | RBAC with `Permission` enum, `Security` service, `MenuBuilder` |
| `Filter/` | `AbstractDtoFilter`, `AbstractFormFilter` with text search & pagination traits |
| `Form/` | Custom types: `DateFlatpickrType`, `NumberDecimalType`, `CollectionFormType` |
| `Twig/Extension/` | Template functions: date, text, pagination, math, translation, state |
| `Cache/` | Tag-aware `BaseCache` with preset durations (10h, 1h, 10d) |
| `Doctrine/Type/` | `AbstractEnumType` for PHP 8.1+ backed enums |

### Bundle Entry Points (`src/Infrastructure/Symfony/Bundle/`)
- `AppBundle.php` — registers two compiler passes:
  - `RegisterEnumTypePass` — auto-registers tagged backed enums as Doctrine types
  - `RegisterCustomTypePass` — auto-registers custom Doctrine types
- `DependencyInjection/AppExtension.php` — loads `services.php`
- `Resources/config/services.php` — autowiring, tagged services (`app.doctrine_enum_type`, `app.menu_service`, `app.paginator`)

## Security / RBAC

Permissions are defined as a PHP enum (`Permission`) with values: `MASTER`, `NEW`, `LIST`, `SHOW`, `EDIT`, `PRINT`, `REPORT`, `EXPORT`, `IMPORT`, `ENABLE`, `DISABLE`, `DELETE` — each with a `*_ALL` variant for owner-agnostic access.

`Security` service checks permissions against menu items and ownership. `MenuBuilder` renders hierarchical navigation based on user permissions.

## Frontend Assets (`src/Infrastructure/Symfony/Bundle/Resources/public/`)

Vanilla JavaScript (no bundler, no framework):
- `js/shared/` — reusable components: `crud_list.js`, `crud_list_filter.js`, `collection.js`, `datepicker.js`, `flatpickr.js`, `select2.js`, `export_data.js`, `notify.js`
- `js/api/` — HTTP layer: `api_manager.js`, `route_manager.js`, `download_service.js`

Two Twig themes (`views/theme1/`, `views/theme2/`) with identical structure: form, pagination, breadcrumb, action, table templates, and menu partials.

## Code Style

Enforced by PHP-CS-Fixer with the `@Symfony` ruleset plus:
- `declare_strict_types: true` on all files
- Required file header: `This file is part of the PIDIA.\n(c) Carlos Chininin <cio@pidia.pe>`
- `strict_comparison` and `strict_param` enabled
- No yoda style, no `not_operator_with_successor_space`
