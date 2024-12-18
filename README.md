# Laravel Test Task Setup Instructions

This document provides step-by-step instructions to set up and run the Laravel [Test Task](task.txt) application.

---

## **1. Clone the Repository**

Clone the repository into your desired directory:

```
git clone git@github.com:Sergios22530/laravel_test_task.git .
```

---

## **2. Install Dependencies**

### **2.1 PHP Dependencies**
Install the PHP dependencies using Composer:

```
composer install
```

### **2.2 JavaScript Dependencies**
Install the required JavaScript dependencies using npm:

```
npm install
```

Compile the frontend assets:

```
npm run dev
```

---

## **3. Configure Environment File**

Create the `.env` file by renaming the example file:

```
cp .env.example .env
```

Open the `.env` file and configure the following settings:

### **Database Configuration**
Set your database credentials:

```
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

### **API User Credentials**
Add the API user credentials:

```
API_USER=api@example.com
API_PASSWORD="!@#$%^&*()132456789"
```

### **Mail Configuration**
Set up your mail configuration for password resets:

```
MAIL_MAILER=smtp
MAIL_HOST=your_mail_host
MAIL_PORT=your_mail_port
MAIL_USERNAME=your_mail_username
MAIL_PASSWORD=your_mail_password
MAIL_ENCRYPTION=your_mail_encryption
MAIL_FROM_ADDRESS=your_email@example.com
MAIL_FROM_NAME="Your Application Name"
```

---

## **4. Clear Cache**

Flush all application cache:

```
php artisan cache:flush-all
```

---

## **5. Run Migrations and Seeders**

### **5.1 Run Database Migrations**
Set up the database structure:

```
php artisan migrate
```

### **5.2 Seed the Database**
Populate the database with initial data:

```
php artisan db:seed
```

---

## **6. Generate Application Key**

Generate a unique application key:

```
php artisan key:generate
```

---

## **7. Set Folder Permissions**

Ensure the application has the correct permissions for the `storage` folder:

```
sudo chown -R www-data storage/
```

---

## **8. Admin Credentials**

You can use the following credentials to log in to the admin panel:

- **Email**: admin@example.com
- **Password**: admin123

---

## **9. Run the Application**

Start the local development server:

```
php artisan serve
```

Visit the application in your browser at:

```text
http://localhost:8000
```


## **10. Run Tests**

To run the test suite, use the following command:

```
php artisan test
```




