# Homehive254 - Premium E-Commerce Platform

A secure, full-featured e-commerce platform built with Laravel 12 designed for buying and selling premium household items (similar to Jumia).

## 🚀 Features

### Core E-Commerce
- ✅ Product catalog with categories
- ✅ Advanced search & filtering
- ✅ Shopping cart management
- ✅ Order creation & tracking
- ✅ Payment integration (Stripe/PayPal)
- ✅ Order status workflow

### User Management
- ✅ User authentication (JWT + Sanctum)
- ✅ Role-based access control (Admin, Seller, Customer)
- ✅ User profiles with addresses
- ✅ Wishlist functionality
- ✅ Order history

### Seller Features
- ✅ Product management
- ✅ Inventory tracking
- ✅ Sales analytics
- ✅ Order fulfillment

### Admin Features
- ✅ Dashboard with statistics
- ✅ User management
- ✅ Order management
- ✅ Revenue tracking
- ✅ System administration

### Security
- ✅ Password hashing (bcrypt)
- ✅ CSRF protection
- ✅ Rate limiting
- ✅ SQL injection prevention
- ✅ XSS protection
- ✅ CORS configuration
- ✅ Security headers
- ✅ Request logging

### Reviews & Ratings
- ✅ Product reviews from verified buyers
- ✅ 5-star rating system
- ✅ Review moderation

## 📋 Tech Stack

- **Backend**: Laravel 12
- **Database**: MySQL
- **Authentication**: Laravel Sanctum (JWT)
- **Caching**: Redis
- **Testing**: PHPUnit
- **Language**: PHP 8.2+

## 🛠️ Installation

### Prerequisites
- PHP 8.2+
- MySQL 8.0+
- Composer
- Redis (optional, for caching)

### Setup

1. **Clone the repository**
```bash
git clone https://github.com/Jaleny97/Homehive254.git
cd Homehive254
```

2. **Install dependencies**
```bash
composer install
```

3. **Setup environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database**
```bash
# Edit .env file and set your database credentials
DB_HOST=127.0.0.1
DB_DATABASE=homehive254
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run migrations and seed**
```bash
php artisan migrate
php artisan db:seed
```

6. **Start the development server**
```bash
php artisan serve
```

The API will be available at `http://localhost:8000/api`

## 📚 API Documentation

### Authentication

#### Register
```http
POST /api/auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "1234567890"
}
```

#### Login
```http
POST /api/auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

#### Logout
```http
POST /api/auth/logout
Authorization: Bearer {token}
```

### Products

#### Get all products
```http
GET /api/products?page=1&per_page=15&search=sofa&category_id=1&min_price=100&max_price=1000&sort_by=price&sort_order=asc
```

#### Get featured products
```http
GET /api/products/featured
```

#### Get product details
```http
GET /api/products/{id}
```

#### Create product (Seller only)
```http
POST /api/products
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Premium Sofa",
  "description": "High-quality leather sofa",
  "price": 999.99,
  "discount_price": 799.99,
  "category_id": 1,
  "quantity": 50,
  "image_url": "https://example.com/sofa.jpg"
}
```

### Orders

#### Get user's orders
```http
GET /api/orders
Authorization: Bearer {token}
```

#### Create order
```http
POST /api/orders
Authorization: Bearer {token}
Content-Type: application/json

{
  "items": [
    {
      "product_id": 1,
      "quantity": 2
    }
  ],
  "shipping_address": {
    "street": "123 Main St",
    "city": "New York",
    "country": "USA",
    "postal_code": "10001"
  },
  "payment_method": "card"
}
```

#### Get order details
```http
GET /api/orders/{id}
Authorization: Bearer {token}
```

### Reviews

#### Get product reviews
```http
GET /api/products/{id}/reviews
```

#### Create review
```http
POST /api/products/{id}/reviews
Authorization: Bearer {token}
Content-Type: application/json

{
  "rating": 5,
  "title": "Excellent product!",
  "comment": "This product exceeded my expectations..."
}
```

### Admin

#### Get dashboard statistics
```http
GET /api/admin/dashboard
Authorization: Bearer {token}
```

#### Get all users
```http
GET /api/admin/users
Authorization: Bearer {token}
```

#### Get all orders
```http
GET /api/admin/orders
Authorization: Bearer {token}
```

## 🔐 Security Features

### Password Security
- Bcrypt hashing with configurable cost factor
- Password confirmation on registration
- Secure password reset flow

### API Security
- **JWT Tokens**: Secure token-based authentication
- **Rate Limiting**: Prevents brute force attacks
- **CSRF Protection**: Token validation for form submissions
- **SQL Injection Prevention**: Parameterized queries and Eloquent ORM
- **XSS Protection**: Automatic escaping of user input

### HTTP Security Headers
- `X-Frame-Options`: Prevents clickjacking
- `X-Content-Type-Options`: Prevents MIME type sniffing
- `X-XSS-Protection`: Enables browser XSS filter
- `Content-Security-Policy`: Restricts resource loading
- `Referrer-Policy`: Controls referrer information

### CORS Configuration
- Configurable allowed origins
- Support for credentials
- Customizable allowed headers and methods

## 🧪 Testing

### Run all tests
```bash
php artisan test
```

### Run specific test
```bash
php artisan test tests/Feature/AuthTest.php
```

### Run with coverage
```bash
php artisan test --coverage
```

## 📊 Default Seed Data

When you run `php artisan db:seed`, the database is populated with:
- 1 Admin user: `admin@homehive254.com` / `password123`
- 5 Seller accounts
- 20 Customer accounts
- 8 Product categories
- 50 Products
- Product reviews and ratings

## 🌳 Project Structure

```
app/
├── Http/
│   ├── Controllers/     # API controllers
│   ├── Middleware/      # Security & custom middleware
│   ├── Requests/        # Form request validation
│   └── Policies/        # Authorization policies
├── Models/              # Eloquent models
├── Services/            # Business logic services
└── Providers/           # Service providers

routes/
└── api.php              # API routes

database/
├── migrations/          # Database migrations
├── factories/           # Model factories
└── seeders/             # Database seeders

tests/
├── Feature/             # Feature tests
└── Unit/                # Unit tests

config/
├── security.php         # Security configuration
├── cache.php            # Cache configuration
├── cors.php             # CORS configuration
└── queue.php            # Queue configuration
```

## 🔄 Workflow

1. **User Registration/Login**: Obtain JWT token
2. **Browse Products**: Search, filter, view details
3. **Create Order**: Add items to cart, provide shipping info
4. **Payment**: Process payment via Stripe/PayPal
5. **Order Tracking**: View order status updates
6. **Review Products**: Leave ratings and reviews
7. **Wishlist**: Save favorite items for later

## 🚀 Deployment

### Production Checklist
- [ ] Set `APP_DEBUG=false`
- [ ] Set `APP_ENV=production`
- [ ] Configure SSL certificate
- [ ] Set strong `APP_KEY`
- [ ] Configure database backups
- [ ] Setup Redis for caching
- [ ] Configure email service
- [ ] Enable HTTPS
- [ ] Setup monitoring and logging
- [ ] Configure payment gateway credentials

## 📝 Environment Variables

Key environment variables:
```bash
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=homehive254
DB_USERNAME=root
DB_PASSWORD=

REDIS_HOST=127.0.0.1
REDIS_PORT=6379

STRIPE_KEY=your_stripe_key
STRIPE_SECRET=your_stripe_secret

FORCE_HTTPS=true
CORS_ALLOWED_ORIGINS=https://yourdomain.com
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Write tests
5. Submit a pull request

## 📄 License

MIT License - see LICENSE file for details

## 💬 Support

For issues and questions, please open an issue on GitHub.

## 👨‍💻 Author

Francis Jaleny - [@Jaleny97](https://github.com/Jaleny97)

---

**Homehive254** - Premium Household Items E-Commerce Platform
