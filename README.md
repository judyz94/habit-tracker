# Habit Tracker

This project is a habit tracker and system builder inspired by the ideas of James Clear’s Atomic Habits.
Instead of focusing only on goals, it helps you design identity-based systems — daily actions that make consistency natural and sustainable.

The app allows users to:

- Create, edit, and track habits with flexible schedules.

- Reflect on small wins and build momentum.

- Turn goals into repeatable systems for long-term growth.

“You do not rise to the level of your goals. You fall to the level of your systems.” — James Clear

## Requirements

- **PHP** ^8.2
- **Composer** ^2.7
- **Laravel** ^12.x
- **MySQL** ^8.0
- **Node.js** ^22.12+
- **npm** ^10.x
- **Vue.js** ^3.5.13

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/judymzh94/habit-tracker.git
   cd habit-tracker

2. **Install Laravel Dependencies**
   ```bash
   composer install

3. **Install Node.js Dependencies**
   ```bash
   npm install

4. **Configure the .env file**

   Copy the sample file and edit it with the specific DB credentials:
   ```bash
   cp .env.example .env

5. **Create DB in MySQL**

   The default name in the .env.example is "habitracker"


6. **Generate the application key**
   ```bash
   php artisan key:generate

7. **Run migrations**
   ```bash
   php artisan migrate

8. **Start the local web server**
   ```bash
   php artisan serve

8. **Compile the assets**
   ```bash
   npm run dev

