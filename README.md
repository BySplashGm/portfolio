# 🚀 Maxime's Portfolio

Welcome to my personal **portfolio**, built with **Symfony 7** and designed to present my background, skills, and projects in a modern and dynamic way.

## 🌐 Live Demo

- [https://maximeval.in](https://maximeval.in)

## 🧰 Tech Stack

- **Backend**: Symfony 7 (PHP)
- **Frontend**: Twig, HTML5, CSS
- **Database**: MySQL
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
npm install
npm run dev
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
symfony server:start
```

### 🔐 Example .env configuration (without credentials)

```env
APP_ENV=dev
DB_ADDR_IP=
DB_PORT=3306
DB_NAME=
DB_USER=
DB_USER_PWD=
DATABASE_URL="mysql://${DB_USER}:${DB_USER_PWD}@${DB_ADDR_IP}:${DB_PORT}/${DB_NAME}?serverVersion=8.0.32&charset=utf8mb4"
```

## 🤝 Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## 📄 License

This project is licensed under the MIT License. See the `LICENSE` file for details.
