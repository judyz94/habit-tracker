# Habit Tracker

This project is a habit tracker and system builder inspired by the ideas of James Clear’s Atomic Habits.
Instead of focusing only on goals, it helps you design identity-based systems — daily actions that make consistency natural and sustainable.

## How It Works
The app allows users to:

📋 **Create, edit, and delete Goals and Habits:**

Organize your objectives and the daily actions that help you achieve them, both weekly and monthly.

📅 **Use an interactive Habit Tracker:**

Log your progress day by day, add notes and reminders, and visually track your consistency over the week.

💭 **View affirmations and motivational quotes:**

Stay inspired with positive phrases that support your mindset and reinforce your process.

🎯 **Review your weekly and monthly goals at a glance:**

Quickly recall what you’re working toward and stay focused on what truly matters.

🏆 **Associate rewards with your habits:**

Celebrate your progress and strengthen the habit loop with meaningful incentives.

> “You do not rise to the level of your goals. You fall to the level of your systems.” — James Clear

---

## Requirements

- **PHP** ^8.2
- **Composer** ^2.7
- **Laravel** ^12.x
- **MySQL** ^8.0
- **Node.js** ^22.12+
- **Npm** ^10.x
- **Vue.js** ^3.5.13

---

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/judyz94/habit-tracker.git
   cd habit-tracker

2. **Install Laravel Dependencies**
   ```bash
   composer install

3. **Configure the .env file**

   Copy the sample file and edit it with the specific DB credentials:
   ```bash
   cp .env.example .env

4. **Generate the application key**
   ```bash
   php artisan key:generate
   
5. **Create DB in MySQL**

   The default name in the .env.example is "habit_tracker"


6. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed

7. **Set the correct Node.js version**
   
    Before compiling the assets, make sure you are using the correct Node.js version
   ```bash
   nvm install 20
   nvm use 20

8. **Install Node.js Dependencies**
   ```bash
   npm install
   
9. **Compile the assets**
   ```bash
   npm run dev

10. **Start the local web server**
    ```bash
    php artisan serve

11. **Access the application**

    After the server is running, open your browser and go to the URL shown in the terminal (usually http://127.0.0.1:8000)

    Click on the **Login** option, or go directly to http://127.0.0.1:8000/login and log in using the following test user credentials:

    > ```php
    > 'email' => 'test@example.com'
    > 'password' => '1234'
    > ```

12. **Run the tests**

    To execute the test suite, you can use either of the following commands:

    Run tests using Laravel's built-in test runner:
    ```bash
    php artisan test
    ```
    
    Or, to generate a code coverage report in HTML:
    ```bash
    vendor/bin/phpunit --coverage-html coverage
    
---

## Authentication & API Overview

This project provides a secure RESTful API built with Laravel Sanctum.  
Users can register, log in, and manage their personal goals, habits, and affirmations through authenticated routes.



### Authentication Flow

The authentication system uses **Laravel Sanctum** tokens.  
Users must first register or log in to obtain an **API token**, which must be included in subsequent requests via the `Authorization` header.

#### Headers Example
```http
Authorization: Bearer your_api_token_here
Accept: application/json
```

## Protected API Endpoints

All routes below require authentication (`auth:sanctum` middleware).

---

### Authentication API

**Base route:** `/api`

| Method | Endpoint         | Description                     | Auth Required |
|--------|------------------|----------------------------------|----------|
| POST   | `/api/register`  | Register a new user              | No      |
| POST   | `/api/login`     | Log in and receive an API token  | No      |
| POST   | `/api/logout`    | Log out and revoke the token     | Yes     |
| GET    | `/api/user`      | Retrieve the authenticated user  | Yes     |

---

### Goals API

**Base route:** `/api/goals`

| Method | Endpoint             | Description                  |
|--------|----------------------|------------------------------|
| GET    | `/api/goals`         | List all user goals          |
| GET    | `/api/goals/{id}`    | Get a specific goal          |
| POST   | `/api/goals`         | Create a new goal            |
| PUT    | `/api/goals/{id}`    | Update an existing goal      |
| DELETE | `/api/goals/{id}`    | Delete a goal                |
| GET    | `/api/goals/weekly`  | Retrieve weekly goals        |
| GET    | `/api/goals/monthly` | Retrieve monthly goals       |

---

### Habits API

**Base route:** `/api/habits`

| Method | Endpoint              | Description                  |
|--------|-----------------------|------------------------------|
| GET    | `/api/habits`         | List all user habits         |
| GET    | `/api/habits/{id}`    | Get a specific habit         |
| POST   | `/api/habits`         | Create a new habit           |
| PUT    | `/api/habits/{id}`    | Update an existing habit     |
| DELETE | `/api/habits/{id}`    | Delete a habit               |
| GET    | `/api/habits/active`  | Retrieve all active habits   |

---

### Affirmations API

**Base route:** `/api/affirmations`

| Method | Endpoint                   | Description              |
|--------|----------------------------|--------------------------|
| GET    | `/api/affirmations`        | List all affirmations    |
| POST   | `/api/affirmations`        | Create a new affirmation |
| PUT    | `/api/affirmations/{id}`   | Update an affirmation    |
| DELETE | `/api/affirmations/{id}`   | Delete an affirmation    |

---

### Habit Logs API

**Base route:** `/api/habit-logs`

This resource tracks daily progress for each habit  
(e.g., marking a habit as completed for a specific day).

| Method | Endpoint                  | Description             |
|--------|---------------------------|-------------------------|
| GET    | `/api/habit-logs`         | List habit logs         |
| POST   | `/api/habit-logs`         | Record a daily habit log|
| PUT    | `/api/habit-logs/{id}`    | Update a habit log      |
| DELETE | `/api/habit-logs/{id}`    | Delete a habit log      |
