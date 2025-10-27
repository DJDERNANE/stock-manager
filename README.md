# 📦 Stock Manager API

A comprehensive stock management system built with Laravel and Laravel Passport OAuth2 authentication. This APP provides complete stock management functionality including product CRUD operations, quantity adjustments, and detailed stock history tracking.

## 🎯 Features

### Authentication (OAuth2)
- ✅ Secure login with Personal Access Tokens
- ✅ Token-based authentication using Laravel Passport
- ✅ Logout with token revocation

### Product Management
- ✅ Create products with images
- ✅ Update product details
- ✅ Delete products (soft delete)
- ✅ List products with pagination
- ✅ Search by name or SKU
- ✅ Unique SKU validation

### Stock Management
- ✅ Increase product quantity
- ✅ Decrease product quantity
- ✅ Prevent negative stock
- ✅ Automatic stock history logging

### Stock History
- ✅ Complete audit trail of all stock movements
- ✅ Track who made changes
- ✅ Timestamp for each transaction
- ✅ Reason/note for adjustments

## 📋 Requirements

- PHP >= 8.2
- Composer
- PostgreSQL >= 15 
- Laravel >= 12.x

## 🚀 Installation

### 1. Clone the repository

```bash
git clone https://github.com/DJDERNANE/stock-manager.git
cd stock-manager
```

### 2. Environment configuration

```bash
cp .env.example .env
```

Edit `.env` file with your database credentials:

```env
APP_NAME="Stock Manager"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=stock_manager
DB_USERNAME=postgres
DB_PASSWORD=your_password

# Mail Configuration (for password reset)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@stockmanager.com
MAIL_FROM_NAME="${APP_NAME}"

# Passport Configuration
PASSPORT_PERSONAL_ACCESS_CLIENT_ID=
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=
```
### 3. build and run docker compose 
docker compose up -d --build

### 3. run migrations 
docker exec stock-manager php artisan migrate  migrate

### 4. key generation 
docker exec stock-manager php artisan key:generate

### 5. Créer le lien de stockage
docker exec stock-manager php artisan storage:link


### Copy the generated Client ID and Secret to your `.env`:

```env
PASSPORT_PERSONAL_ACCESS_CLIENT_ID=1
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=your_generated_secret
```


## 📚 API Documentation (we have only get products end point , the others are blade templates in the laravel app)

### Base URL
```
http://localhost:8000/api
```

### Authentication

All protected endpoints require the `Authorization` header:
```
Authorization: Bearer {your_access_token}
```

---

## 🔐 Authentication Endpoints

### Register

Create a new user account.

**Endpoint:** `POST /auth/register`

**Request Body:**
```json
{
  "name": "Djilali Dernane",
  "email": "djilali@gmail.com",
  "password": "12345678",
  "password_confirmation": "12345678"
}
```

**Response:** `201 Created`
```json
{
  "message": "Registration and login successful",
  "user": {
    "id": 1,
    "name": "Djilali Dernane",
    "email": "djilali@gmail.com"
  },
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "Bearer"
}
```

---

### Login

Authenticate and receive access token.

**Endpoint:** `POST /auth/login`

**Request Body:**
```json
{
   "email": "djilali@gmail.com",
  "password": "12345678",
}
```

**Response:** `200 OK`
```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "Djilali Dernane",
    "email": "djilali@gmail.com"
  },
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "Bearer"
}
```

**Error Response:** `401 Unauthorized`
```json
{
  "message": "Invalid credentials"
}
```



### Logout

Revoke current access token.

**Endpoint:** `POST /logout`

**Headers:**
```
Authorization: Bearer {access_token}
```

**Response:** `200 OK`
```json
{
  "message": "Logout successful"
}
```


## 📦 Product Endpoint


### List Products

Get paginated list of products with search and filters.

**Endpoint:** `GET /products`

**Headers:**
```
Authorization: Bearer {access_token}
```



**Example Request:**
```
GET /products
```

**Response:** `200 OK`
```json
{
  "data": [
    {
      "id": 1,
      "name": "Laptop Dell XPS 15",
      "sku": "DELL-XPS-001",
      "purchase_price": 1200.00,
      "quantity": 10,
      "image_url": "products/image.jpg",
      "created_at": "2025-01-15T10:30:00.000000Z",
      "updated_at": "2025-01-15T10:30:00.000000Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "per_page": 10,
    "to": 10,
    "total": 50
  }
}
```


test webhook