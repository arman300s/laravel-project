# Library Management System

## 📚 Оглавление
- [📝 Описание](#описание)
- [✨ Функциональность](#функциональность)
- [🛠️ Установка](#установка)
    - [Без Docker](#без-docker)
    - [С Docker](#с-docker)
- [🚀 Использование](#использование)

---

## 📝 Описание
**Library Management System** — это Full-stack веб-приложение на Laravel для управления библиотекой. Оно предоставляет систему регистрации пользователей, гибкий интерфейс на Blade-компонентах и быструю фронтенд-сборку с помощью Vite. Авторизация реализована через Laravel Breeze.

---

## ✨ Функциональность
- 📖 Управление книгами, пользователями и транзакциями
- 🧩 Blade-компоненты для модульной разработки интерфейса
- 🔐 Аутентификация через Laravel Breeze
- ⚡ Быстрая сборка фронтенда с помощью Vite
- 🐳 Развёртывание через Docker (Laravel + MySQL + Nginx)

---

## 🛠️ Установка

### Без Docker
1. Клонируйте репозиторий:
    ```bash
    git clone https://github.com/elitekbtu/team-project.git
    cd team-project
    ```
2. Скопируйте файл `.env`:
    ```bash
    cp .env.example .env
    ```
3. Установите зависимости:
    ```bash
    composer install
    npm install
    npm run build
    ```
4. Сгенерируйте ключ приложения:
    ```bash
    php artisan key:generate
    ```
5. Выполните миграции:
    ```bash
    php artisan migrate
    ```
6. Запустите локальный сервер:
    ```bash
    php artisan serve
    ```

После этого приложение будет доступно по адресу: [http://localhost:8000](http://localhost:8000)

---

### С Docker
1. Скопируйте файл `.env`:
    ```bash
    cp .env.example .env
    ```
2. Запустите контейнеры:
    ```bash
    docker-compose up -d --build
    ```
3. Выполните следующие команды внутри контейнера:
    ```bash
    docker exec -it php bash
    composer install
    npm install
    npm run build
    php artisan key:generate
    php artisan migrate
    exit
    ```

После этого приложение будет доступно по адресу: [http://localhost](http://localhost)

---

## 🚀 Использование
После запуска проекта вы сможете:
- Зарегистрироваться или войти
- Добавлять книги и управлять ими
- Просматривать историю выдачи и возврата книг
