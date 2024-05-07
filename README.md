# Habit Tracker

# Description

This project presents a web application for habit tracking using **Laravel 11** as a PHP framework and **Tailwind CSS** for user interface design. The application allows users to:

- **Create and manage habits**: Users can define their own habits, set goals, and track their daily progress.
- **Get motivation and support**: The application includes features to share goals with friends and receive community support.

## Technologies used

**Backend:**
- **Laravel 11**
- **MySQL**

**Frontend:**
- **Tailwind CSS**
- **JavaScript**

## Features

- **Habit tracking**: Users can create new habits, set goals, and track their daily progress.
- **Reminders**: Set up custom reminders to keep users motivated.
- **Intuitive user interface**: Attractive and easy-to-use design with Tailwind CSS.

## Requirements to run the application

- **Web server**: Apache or Nginx.
- **PHP 8.1 or higher**.
- **Relational database**: MySQL.
- **Composer**: Dependency management tool for PHP.
- **NPM**: Package manager for JavaScript.

## Instructions to run the application

1. **Clone the repository**:
   ```bash
   git clone https://github.com/judyz94/habit-tracker.git
2. **Install dependencies**:
   ```bash
   composer install
   npm install
3. **Configure the database**:
Update the .env file with the database connection details.
4. **Run migrations and seeds**:
    ```bash
    php artisan migrate
    php artisan db:seed
5. Start the development server:
    ```bash
    php artisan serve
6. Open the application in the browser:
Access http://localhost:8000 in a web browser.

**Use this web application to set goals, track your progress, and achieve your personal goals.**
