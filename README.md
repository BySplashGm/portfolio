# 🚀 Maxime's Portfolio

![CI](https://github.com/BySplashGm/portfolio/actions/workflows/ci.yml/badge.svg)

Welcome to my personal **portfolio**, built with **Symfony 7** and designed to present my background, skills, and projects in a modern and dynamic way.

## 🌐 Live Demo

- [https://portfolio.maximeval.in](https://portfolio.maximeval.in)

## 🧰 Tech Stack

- **Backend**: Symfony 7.4 (PHP >= 8.2), EasyAdmin 4
- **Frontend**: Twig, HTML5, CSS, Hotwire Turbo & Stimulus (via AssetMapper — no Node/npm)
- **Database**: MariaDB
- **Tools**: Composer, php-cs-fixer

## 📁 Features

- 🧑‍💼 Complete resume and education background
- 💼 Detailed project section with dynamic management
- ✨ Clean and modern design
- 🔍 Interactive and responsive UI

## 🛠️ Installation

To run this project locally:

```bash
git clone https://github.com/BySplashGm/portfolio.git
cd portfolio
composer install
php bin/console importmap:install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load   # test admin: admin@test.com / adminpass
symfony server:start -d
```

### 🔐 Example `.env.local`

```env
APP_SECRET=your_secret_here
DATABASE_URL="mysql://user:password@127.0.0.1:3306/portfolio?serverVersion=10.11.6-MariaDB&charset=utf8mb4"
```

### 🧪 Running tests

```bash
php bin/phpunit
```

## 🤝 Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## 📄 License

This project is licensed under the MIT License. See the `LICENSE` file for details.
