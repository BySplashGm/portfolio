# 🚀 Maxime's Portfolio

Welcome to my personal **portfolio**, built with **Symfony 7** and designed to present my background, skills, and projects in a modern and dynamic way.

## 🌐 Live Demo

- [https://portfolio.maximeval.in](https://portfolio.maximeval.in)

## 🧰 Tech Stack

- **Backend**: Symfony 7.4 (PHP >= 8.2)
- **Frontend**: Twig, HTML5, CSS
- **Database**: MariaDB
- **Tools**: Composer

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
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
symfony server:start -d
```

### 🔐 Example dev .env.local configuration (without credentials) (you can also just use docker compose)

```env
APP_ENV=dev
APP_DEBUG=1
DB_ADDR_IP=127.0.0.1
DB_PORT=3306
DB_NAME=DATABASE_NAME
DB_USER=DATABASE_USER
DB_USER_PWD=DATABASE_USER_PASSWORD
DATABASE_URL="mysql://${DB_USER}:${DB_USER_PWD}@${DB_ADDR_IP}:${DB_PORT}/${DB_NAME}?serverVersion=8.0.32&charset=utf8mb4"
```

## 🤝 Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## 📄 License

This project is licensed under the MIT License. See the `LICENSE` file for details.
