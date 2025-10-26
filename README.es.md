# Rastreador de Hábitos

[Read in English](./README.md)

Este proyecto es un rastreador de hábitos y constructor de sistemas de hábitos inspirado en las ideas del libro Hábitos Atómicos de James Clear.
En lugar de centrarse solo en las metas, te ayuda a diseñar sistemas basados en la identidad: acciones diarias que hacen que la constancia sea natural y sostenible.

## Cómo Funciona
La aplicación permite a los usuarios:

📋 **Crear, editar y eliminar Metas y Hábitos:**

Organiza tus objetivos y las acciones diarias que te ayudan a lograrlos, tanto semanal como mensualmente.

📅 **Usar un Rastreador de Hábitos interactivo:**

Registra tu progreso día a día, agrega notas y recordatorios, y visualiza tu consistencia a lo largo de la semana.

💭 **Ver afirmaciones y frases motivacionales:**

Mantente inspirado con frases positivas que refuercen tu mentalidad y tu proceso.

🎯 **Revisar tus metas semanales y mensuales de un vistazo:**

Recuerda rápidamente en qué estás trabajando y mantén el enfoque en lo que realmente importa.

🏆 **Asociar recompensas a tus hábitos:**

Celebra tu progreso y fortalece el ciclo del hábito con incentivos significativos.

> “No te elevas al nivel de tus metas. Caes al nivel de tus sistemas.” — James Clear

---

## Requerimientos

- **PHP** ^8.2
- **Composer** ^2.7
- **Laravel** ^12.x
- **MySQL** ^8.0
- **Node.js** ^22.12+
- **Npm** ^10.x
- **Vue.js** ^3.5.13

---

## Instalación

1. **Clone el repositorio**
   ```bash
   git clone https://github.com/judyz94/habit-tracker.git
   cd habit-tracker

2. **Instale las dependencias de Laravel**
   ```bash
   composer install

3. **Configure el archivo .env**

   Copie el archivo de muestra y edítelo con las credenciales de la base de datos específicas:
   ```bash
   cp .env.example .env

4. **Genere la clave de la aplicación**
   ```bash
   php artisan key:generate
   
5. **Cree la base de datos con MySQL**

   El nombre predeterminado en .env.example es "habit_tracker"


6. **Ejecute las migraciones y los seeders**
   ```bash
   php artisan migrate --seed

7. **Establezca la versión correcta de Node.js**

   Antes de compilar los assets, asegúrese de estar utilizando la versión correcta de Node.js
   ```bash
   nvm install 20
   nvm use 20

8. **Instale las dependencias de Node.js**
   ```bash
   npm install
   
9. **Compile los assets**
   ```bash
   npm run dev

10. **Inicie el servidor web local**
    ```bash
    php artisan serve

11. **Acceda a la aplicación**

    Después de que el servidor esté en ejecución, abre tu navegador y ve a la URL que aparece en la terminal (normalmente http://127.0.0.1:8000)

    Haz clic en la opción Login, o ve directamente a http://127.0.0.1:8000/login e inicia sesión usando las siguientes credenciales de prueba:

    > ```php
    > 'email' => 'test@example.com'
    > 'password' => '1234'
    > ```

12. **Ejecuta los tests**

    Para ejecutar el conjunto de pruebas, puedes usar cualquiera de los siguientes comandos:

    Ejecuta las pruebas utilizando el ejecutor de pruebas integrado de Laravel:
    ```bash
    php artisan test
    ```

    O bien, para generar un informe de cobertura de código en HTML:
    ```bash
    vendor/bin/phpunit --coverage-html coverage
    
---

## Autenticación y Descripción General de la API

Este proyecto ofrece una API RESTful segura construida con **Laravel Sanctum**.
Los usuarios pueden registrarse, iniciar sesión y gestionar sus metas, hábitos y afirmaciones personales a través de rutas autenticadas.

## Flujo de Autenticación

El sistema de autenticación utiliza tokens de **Laravel Sanctum**.
Los usuarios deben primero registrarse o iniciar sesión para obtener un **token de API**, el cual debe incluirse en las solicitudes posteriores mediante el encabezado Authorization.

#### Ejemplo de Headers
```http
Authorization: Bearer your_api_token_here
Accept: application/json
```

### Authentication API

**Base route:** `/api`

| Método | Endpoint         | Descripción                          | Autenticación Requerida |
|--------|------------------|--------------------------------------|-------------------------|
| POST   | `/api/register`  | Registrar un nuevo usuario           | No                      |
| POST   | `/api/login`     | Iniciar sesión y obtener un token API | No                      |
| POST   | `/api/logout`    | Cerrar sesión y revocar el token     | Sí                      |
| GET    | `/api/user`      | Obtener el usuario autenticado       | Sí                      |

---

## Endpoints de API protegidos

Todas las rutas a continuación requieren autenticación (middleware `auth:sanctum`).


### API de Metas

**Ruta base:** `/api/goals`

| Método | Endpoint             | Descripción                       |
|--------|----------------------|-----------------------------------|
| GET    | `/api/goals`         | Listar todas las metas del usuario |
| GET    | `/api/goals/{id}`    | Obtener una meta específica        |
| POST   | `/api/goals`         | Crear una nueva meta              |
| PUT    | `/api/goals/{id}`    | Actualizar una meta existente     |
| DELETE | `/api/goals/{id}`    | Eliminar una meta                 |
| GET    | `/api/goals/weekly`  | Obtener las metas semanales       |
| GET    | `/api/goals/monthly` | Obtener las metas mensuales       |

---

### API de Hábitos

**Ruta basee:** `/api/habits`

| Método | Endpoint              | Descripción                         |
|--------|-----------------------|-------------------------------------|
| GET    | `/api/habits`         | Listar todos los hábitos del usuario |
| GET    | `/api/habits/{id}`    | Obtener un hábito específico         |
| POST   | `/api/habits`         | Crear un nuevo hábito                |
| PUT    | `/api/habits/{id}`    | Actualizar un hábito existente       |
| DELETE | `/api/habits/{id}`    | Eliminar un hábito                   |
| GET    | `/api/habits/active`  | Obtener todos los hábitos activos    |

---

### API de Afirmaciones

**Ruta base:** `/api/affirmations`

| Método | Endpoint                   | Descripción                    |
|--------|----------------------------|--------------------------------|
| GET    | `/api/affirmations`        | Listar todas las afirmaciones  |
| POST   | `/api/affirmations`        | Crear una nueva afirmación     |
| PUT    | `/api/affirmations/{id}`   | Actualizar una afirmación      |
| DELETE | `/api/affirmations/{id}`   | Eliminar una afirmación        |

---

### API de Logs de Hábitos

**Ruta base:** `/api/habit-logs`

Este recurso rastrea el progreso diario de cada hábito (por ejemplo, marcar un hábito como completado para un día específico).

| Método | Endpoint                  | Descripción                          |
|--------|---------------------------|--------------------------------------|
| GET    | `/api/habit-logs`         | Listar los registros de hábitos      |
| POST   | `/api/habit-logs`         | Registrar el seguimiento diario de un hábito |
| PUT    | `/api/habit-logs/{id}`    | Actualizar un registro de hábito     |
| DELETE | `/api/habit-logs/{id}`    | Eliminar un registro de hábito       |
