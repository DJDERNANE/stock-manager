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
git clone https://github.com/yourusername/stock-manager.git
cd stock-manager
```

### 2. Install dependencies

```bash
composer install
```

### 3. Environment configuration

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

### 4. Generate application key

```bash
php artisan key:generate
```

### 5. Run migrations

```bash
php artisan migrate
```

### 6. Install and configure Laravel Passport

```bash
# Install Passport
php artisan install:api --passport

# Create Personal Access Client
php artisan passport:client --personal
```

Copy the generated Client ID and Secret to your `.env`:

```env
PASSPORT_PERSONAL_ACCESS_CLIENT_ID=1
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=your_generated_secret
```

### 7. Create storage link for images

```bash
php artisan storage:link
```

### 8. Seed database (optional)

```bash
php artisan db:seed
```

### 9. Start the development server

```bash
php artisan serve
```

The API will be available at: `http://localhost:8000`

## 📚 API Documentation

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
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response:** `201 Created`
```json
{
  "message": "Registration and login successful",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
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
  "email": "john@example.com",
  "password": "password123"
}
```

**Response:** `200 OK`
```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
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

---

### Get Authenticated User

Get current user information.

**Endpoint:** `GET /auth/user`

**Headers:**
```
Authorization: Bearer {access_token}
```

**Response:** `200 OK`
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "email_verified_at": null,
    "created_at": "2025-01-15T10:30:00.000000Z"
  }
}
```

---

### Logout

Revoke current access token.

**Endpoint:** `POST /auth/logout`

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

---

### Forgot Password

Request password reset link.

**Endpoint:** `POST /auth/forgot-password`

**Request Body:**
```json
{
  "email": "john@example.com"
}
```

**Response:** `200 OK`
```json
{
  "message": "Password reset link sent to your email"
}
```

---

### Reset Password

Reset password with token.

**Endpoint:** `POST /auth/reset-password`

**Request Body:**
```json
{
  "token": "reset_token_from_email",
  "email": "john@example.com",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

**Response:** `200 OK`
```json
{
  "message": "Password reset successful. You can now login with your new password."
}
```

---

## 📦 Product Endpoints

### Create Product

Add a new product to inventory.

**Endpoint:** `POST /products`

**Headers:**
```
Authorization: Bearer {access_token}
Content-Type: multipart/form-data
```

**Request Body:**
```json
{
  "name": "Laptop Dell XPS 15",
  "sku": "DELL-XPS-001",
  "purchase_price": 1200.00,
  "selling_price": 1500.00,
  "quantity": 10,
  "image": "file upload"
}
```

**Response:** `201 Created`
```json
{
  "message": "Product created successfully",
  "data": {
    "id": 1,
    "name": "Laptop Dell XPS 15",
    "sku": "DELL-XPS-001",
    "purchase_price": 1200.00,
    "selling_price": 1500.00,
    "quantity": 10,
    "image_url": "http://localhost:8000/storage/products/image.jpg",
    "created_at": "2025-01-15T10:30:00.000000Z",
    "updated_at": "2025-01-15T10:30:00.000000Z"
  }
}
```

**Validation Rules:**
- `name`: required, string, max 255 characters
- `sku`: required, string, unique, max 100 characters
- `purchase_price`: required, numeric, min 0
- `selling_price`: required, numeric, greater than purchase_price
- `quantity`: required, integer, min 0
- `image`: optional, image (jpeg, png, jpg, gif), max 2MB

---

### List Products

Get paginated list of products with search and filters.

**Endpoint:** `GET /products`

**Headers:**
```
Authorization: Bearer {access_token}
```

**Query Parameters:**
- `search` (optional): Search by name or SKU
- `sort` (optional): Field to sort by (name, sku, purchase_price, selling_price, quantity, created_at)
- `order` (optional): Sort order (asc, desc) - default: asc
- `per_page` (optional): Items per page (1-100) - default: 15
- `page` (optional): Page number

**Example Request:**
```
GET /products?search=laptop&sort=name&order=asc&per_page=10&page=1
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
      "selling_price": 1500.00,
      "quantity": 10,
      "image_url": "http://localhost:8000/storage/products/image.jpg",
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

---

### Get Product

Get single product details.

**Endpoint:** `GET /products/{id}`

**Headers:**
```
Authorization: Bearer {access_token}
```

**Response:** `200 OK`
```json
{
  "data": {
    "id": 1,
    "name": "Laptop Dell XPS 15",
    "sku": "DELL-XPS-001",
    "purchase_price": 1200.00,
    "selling_price": 1500.00,
    "quantity": 10,
    "image_url": "http://localhost:8000/storage/products/image.jpg",
    "created_at": "2025-01-15T10:30:00.000000Z",
    "updated_at": "2025-01-15T10:30:00.000000Z"
  }
}
```

**Error Response:** `404 Not Found`
```json
{
  "message": "Product not found"
}
```

---

### Update Product

Update existing product.

**Endpoint:** `PUT /products/{id}` or `POST /products/{id}` (with `_method=PUT`)

**Headers:**
```
Authorization: Bearer {access_token}
Content-Type: multipart/form-data
```

**Request Body:** (all fields optional)
```json
{
  "name": "Laptop Dell XPS 15 Updated",
  "sku": "DELL-XPS-001-V2",
  "purchase_price": 1250.00,
  "selling_price": 1600.00,
  "quantity": 15,
  "image": "new file upload (optional)"
}
```

**Response:** `200 OK`
```json
{
  "message": "Product updated successfully",
  "data": {
    "id": 1,
    "name": "Laptop Dell XPS 15 Updated",
    "sku": "DELL-XPS-001-V2",
    "purchase_price": 1250.00,
    "selling_price": 1600.00,
    "quantity": 15,
    "image_url": "http://localhost:8000/storage/products/new-image.jpg",
    "created_at": "2025-01-15T10:30:00.000000Z",
    "updated_at": "2025-01-15T11:45:00.000000Z"
  }
}
```

---

### Delete Product

Soft delete a product.

**Endpoint:** `DELETE /products/{id}`

**Headers:**
```
Authorization: Bearer {access_token}
```

**Response:** `200 OK`
```json
{
  "message": "Product deleted successfully"
}
```

---

## 📊 Stock Management Endpoints

### Adjust Quantity

Increase or decrease product quantity.

**Endpoint:** `POST /products/{id}/adjust-quantity`

**Headers:**
```
Authorization: Bearer {access_token}
```

**Request Body:**
```json
{
  "type": "increase",
  "quantity": 5,
  "reason": "Restocking from supplier"
}
```

**Type Options:**
- `increase`: Add to current quantity
- `decrease`: Subtract from current quantity

**Response:** `200 OK`
```json
{
  "message": "Quantity adjusted successfully",
  "data": {
    "id": 1,
    "name": "Laptop Dell XPS 15",
    "sku": "DELL-XPS-001",
    "previous_quantity": 10,
    "new_quantity": 15,
    "adjustment": 5,
    "type": "increase"
  }
}
```

**Error Response:** `422 Unprocessable Entity`
```json
{
  "message": "Validation failed",
  "errors": {
    "quantity": [
      "Cannot decrease quantity below zero. Current stock: 10, requested decrease: 15"
    ]
  }
}
```

**Validation Rules:**
- `type`: required, must be 'increase' or 'decrease'
- `quantity`: required, integer, greater than 0
- `reason`: optional, string, max 500 characters

---

### Get Stock History

View complete stock movement history.

**Endpoint:** `GET /stock-history`

**Headers:**
```
Authorization: Bearer {access_token}
```

**Query Parameters:**
- `product_id` (optional): Filter by specific product
- `type` (optional): Filter by adjustment type (increase, decrease)
- `start_date` (optional): Filter from date (Y-m-d)
- `end_date` (optional): Filter to date (Y-m-d)
- `per_page` (optional): Items per page (1-100) - default: 15
- `page` (optional): Page number

**Example Request:**
```
GET /stock-history?product_id=1&type=increase&per_page=20
```

**Response:** `200 OK`
```json
{
  "data": [
    {
      "id": 1,
      "product_id": 1,
      "product_name": "Laptop Dell XPS 15",
      "product_sku": "DELL-XPS-001",
      "type": "increase",
      "quantity_change": 5,
      "previous_quantity": 10,
      "new_quantity": 15,
      "reason": "Restocking from supplier",
      "user_id": 1,
      "user_name": "John Doe",
      "created_at": "2025-01-15T10:30:00.000000Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 3,
    "per_page": 20,
    "to": 20,
    "total": 45
  }
}
```

---

### Get Product Stock History

View stock history for specific product.

**Endpoint:** `GET /products/{id}/stock-history`

**Headers:**
```
Authorization: Bearer {access_token}
```

**Query Parameters:**
- `type` (optional): Filter by adjustment type
- `start_date` (optional): Filter from date
- `end_date` (optional): Filter to date
- `per_page` (optional): Items per page

**Response:** `200 OK`
```json
{
  "product": {
    "id": 1,
    "name": "Laptop Dell XPS 15",
    "sku": "DELL-XPS-001",
    "current_quantity": 15
  },
  "history": {
    "data": [
      {
        "id": 1,
        "type": "increase",
        "quantity_change": 5,
        "previous_quantity": 10,
        "new_quantity": 15,
        "reason": "Restocking from supplier",
        "user_name": "John Doe",
        "created_at": "2025-01-15T10:30:00.000000Z"
      }
    ],
    "meta": {
      "current_page": 1,
      "total": 10
    }
  }
}
```

---

## 🗄️ Database Schema

### Users Table
```sql
- id (bigint, primary key)
- name (varchar)
- email (varchar, unique)
- email_verified_at (timestamp, nullable)
- password (varchar)
- remember_token (varchar, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

### Products Table
```sql
- id (bigint, primary key)
- name (varchar)
- sku (varchar, unique)
- purchase_price (decimal)
- selling_price (decimal)
- quantity (integer)
- image (varchar, nullable)
- deleted_at (timestamp, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

### Stock Histories Table
```sql
- id (bigint, primary key)
- product_id (bigint, foreign key)
- user_id (bigint, foreign key)
- type (enum: 'increase', 'decrease')
- quantity_change (integer)
- previous_quantity (integer)
- new_quantity (integer)
- reason (text, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

---

## 🧪 Testing

### Run tests

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

### Test with Postman

1. Import the Postman collection (included in `/postman` directory)
2. Set environment variables:
   - `base_url`: http://localhost:8000
   - `access_token`: (will be set automatically after login)
3. Run the collection

### Manual Testing with cURL

```bash
# Register
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'

# Create Product
curl -X POST http://localhost:8000/api/products \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "name=Laptop Dell" \
  -F "sku=DELL-001" \
  -F "purchase_price=1200" \
  -F "selling_price=1500" \
  -F "quantity=10" \
  -F "image=@/path/to/image.jpg"

# List Products
curl -X GET "http://localhost:8000/api/products?search=laptop&per_page=10" \
  -H "Authorization: Bearer YOUR_TOKEN"

# Adjust Quantity
curl -X POST http://localhost:8000/api/products/1/adjust-quantity \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "type": "increase",
    "quantity": 5,
    "reason": "Restocking"
  }'

# View Stock History
curl -X GET http://localhost:8000/api/stock-history \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## 🔒 Security

- All passwords are hashed using bcrypt
- API authentication via OAuth2 tokens
- CSRF protection enabled
- SQL injection prevention with Eloquent ORM
- XSS protection with Laravel's blade templating
- File upload validation and sanitization
- Rate limiting on API endpoints

---

## 📝 Error Handling

The API returns consistent error responses:

### Validation Error (422)
```json
{
  "message": "Validation failed",
  "errors": {
    "email": ["The email has already been taken."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### Unauthorized (401)
```json
{
  "message": "Unauthenticated."
}
```

### Forbidden (403)
```json
{
  "message": "This action is unauthorized."
}
```

### Not Found (404)
```json
{
  "message": "Resource not found"
}
```

### Server Error (500)
```json
{
  "message": "Server error occurred",
  "error": "Error details (only in development)"
}
```

---

## 🚀 Deployment

### Production Checklist

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Generate production encryption keys
- [ ] Configure production database
- [ ] Set up proper mail service (SendGrid, Mailgun, etc.)
- [ ] Configure file storage (S3, DigitalOcean Spaces)
- [ ] Enable HTTPS
- [ ] Set up queue workers for background jobs
- [ ] Configure caching (Redis recommended)
- [ ] Set up monitoring and logging
- [ ] Run `composer install --optimize-autoloader --no-dev`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📄 License

This project is open-sourced under the MIT License. See the [LICENSE](LICENSE) file for details.

---

## 👨‍💻 Author

**Your Name**
- GitHub: [@yourusername](https://github.com/yourusername)
- Email: your.email@example.com

---

## 🙏 Acknowledgments

- Laravel Framework
- Laravel Passport
- PostgreSQL
- All contributors and supporters

---

## 📞 Support

For support, email support@stockmanager.com or open an issue on GitHub.

---

## 🗺️ Roadmap

- [ ] Add product categories
- [ ] Multi-warehouse support
- [ ] Advanced reporting and analytics
- [ ] Export data to CSV/Excel
- [ ] Low stock alerts
- [ ] Barcode scanning integration
- [ ] Mobile app (React Native)
- [ ] Real-time notifications
- [ ] Multi-language support
- [ ] Dark mode

---

**Made with ❤️ using Laravel**