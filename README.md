# Sakina – Mental Health Clinic Backend

Sakina is a digital platform designed to help people easily find qualified and verified mental health professionals.  
Users can explore therapist profiles, check their specialties, and book appointments smoothly.  
This repository contains the **Backend (API)** built with **Laravel**, providing a secure and scalable system for the platform.

---
## 📦 Installation

### 1. Clone the repository
```bash
git clone [https://github.com/ALBaraa2/sakina.git](https://github.com/ALBaraa2/Sakina.git)
```

### 2. Install dependencies
```bash
composer install
```

### 3. Copy the `.env` file
```bash
cp .env.example .env
```

### 4. Add environment variables
Fill in the required values inside your `.env` file:

```
APP_NAME=Ntherapy
APP_ENV=local
APP_KEY=your_app_key
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=your_host
DB_PORT=5432
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=your_mail_host
MAIL_PORT=587
MAIL_USERNAME=your_mail_user
MAIL_PASSWORD=your_mail_pass
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_from_address
MAIL_FROM_NAME="Ntherapy Clinic"
```

### 5. Generate the application key
```bash
php artisan key:generate
```

### 6. Run migrations
```bash
php artisan migrate
```

### 7. Start the development server
```bash
php artisan serve
```

---

## 🧩 Backend Features
- Full appointment booking system between users and therapists  
- Therapist profiles management  
- Specialties and categories management  
- Authentication and account verification  
- Email notifications  
- RESTful API endpoints  
- Secured with Laravel Sanctum  

---

## 👨‍💻 Developer
This project was developed completely by:

- **ALBaraa ALSaiqali**  
  GitHub: [@ALBaraa2](https://github.com/ALBaraa2)

---

## 📄 License
This project is licensed under the **MIT License**.

