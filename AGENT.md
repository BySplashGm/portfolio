# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Install dependencies
composer install

# Start dev server
symfony server:start -d

# Database setup
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load   # creates test admin: admin@test.com / adminpass

# Generate a new migration after entity changes
php bin/console doctrine:migrations:diff

# Run tests
php bin/phpunit

# Run a single test file
php bin/phpunit tests/path/to/TestFile.php

# Lint / code style (php-cs-fixer)
vendor/bin/php-cs-fixer fix

# Clear cache
php bin/console cache:clear
```

## Architecture

**Symfony 7.4** application (PHP ≥ 8.2) backed by **MariaDB**. Docker Compose (`compose.yaml`) provides both a MariaDB and a Postgres service; MariaDB is the configured target.

### Entities and relationships

- `User` — admin accounts; authenticated by email, protected by `ROLE_ADMIN`
- `SkillType` — category for skills (e.g. HardSkill, SoftSkill)
- `Skill` — belongs to one `SkillType`; ManyToMany with both `Project` and `Experience`
- `Project` — has ManyToMany `skills`; `description` field is Markdown (rendered via `league/commonmark` in `ProjectController::show`)
- `Experience` — has ManyToMany `skills` (Skill is the owning side)
- `Message` — contact form submissions stored in DB

### Controllers

| Namespace | Path | Notes |
|---|---|---|
| `App\Controller\MainController` | `/`, `/about`, `/contact` | About page fetches all skills/experiences |
| `App\Controller\ProjectController` | `/projects`, `/projects/{id}` | Detail page converts Markdown description to HTML |
| `App\Controller\ExperienceController` | `/experiences` | Lists experiences |
| `App\Controller\SkillController` | `/skills/{id}` | Skill detail |
| `App\Controller\SecurityController` | `/login`, `/logout` | Form login |
| `App\Controller\Admin\*` | `/admin` | EasyAdmin 4 dashboard; requires `ROLE_ADMIN` |

### Admin panel

EasyAdmin 4 (`easycorp/easyadmin-bundle`) at `/admin`. CRUD controllers live in `src/Controller/Admin/`. The `DashboardController` is annotated with `#[AdminDashboard]` and `#[IsGranted('ROLE_ADMIN')]`. Access is also enforced via `security.yaml` (`access_control: ^/admin`).

### Frontend

Uses **Symfony AssetMapper** (no Webpack/Encore). JavaScript entry point is `assets/app.js`. Hotwire **Stimulus** and **Turbo** are included via `importmap.php`. No npm/node build step is required.

### Migrations

Migrations live in `migrations/`. Always generate with `doctrine:migrations:diff` and review before running.
