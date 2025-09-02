# ShopVue - Modern E-commerce Website

A modern, full-stack e-commerce website built with Vue 3, TypeScript, Tailwind CSS, PHP, and MySQL.

## Features

### Frontend Features
- 🎨 Modern, responsive design with Tailwind CSS and Heroicons
- 🛒 Real-time shopping cart with optimistic updates
- 🔐 Secure user authentication (login/register)
- 📱 Mobile-first responsive design with Headless UI
- 🔍 Advanced product search and filtering
- 📄 Dynamic pagination for product listings
- 💳 Streamlined checkout process
- 📊 Personalized user dashboard
- ⚡ Comprehensive admin dashboard

### Backend Features
- 🗄️ MySQL database for robust data storage
- 🔒 Secure PHP API endpoints with proper validation
- 📦 Efficient data management and caching
- 🔐 JWT-based authentication system

## Tech Stack

- **Frontend**: Vue 3, TypeScript, Tailwind CSS, Vite
- **Backend**: PHP, MySQL
- **Authentication**: Custom PHP implementation with JWT
- **State Management**: Pinia
- **Routing**: Vue Router
- **API Client**: Axios
- **UI Components**: Headless UI

## Prerequisites

- XAMPP (for Apache and MySQL)
- Node.js (v16 or higher)
- npm or yarn

## Installation

1. Clone the repository:
   ```bash
   git clone [repository-url]
   cd ShopVue
   ```

2. Install frontend dependencies:
   ```bash
   npm install
   ```

3. Set up XAMPP:
   - Start Apache and MySQL services
   - Place the project in your XAMPP htdocs directory

4. Initialize the database:
   - Visit: http://localhost/ShopVue/backend/check_db.php
   - This will create the users table and default admin user

5. Initialize sample products:
   - Visit: http://localhost/ShopVue/backend/init_products.php

6. Start the development server:
   ```bash
   npm run dev
   ```

## Default Login Credentials

- **Admin User**: 
  - Email: admin@example.com
  - Password: admin123

## Project Structure

```
ShopVue/
├── public/
│   └── images/products/     # Product images
├── src/
│   ├── components/          # Reusable Vue components
│   ├── stores/              # Pinia state management
│   ├── views/               # Page components
│   ├── router/              # Vue Router configuration
│   └── types/               # TypeScript type definitions
├── backend/
│   ├── api/                 # PHP API endpoints
│   ├── config/              # Database configuration
│   ├── check_db.php         # Database setup script
│   └── init_products.php    # Sample data initialization
└── README.md
```

## API Endpoints

- `GET /backend/api/products/list.php` - Get all products
- `GET /backend/api/products/get.php?id={id}` - Get single product
- `POST /backend/api/auth/register.php` - User registration
- `POST /backend/api/auth/login.php` - User login
- `GET /backend/api/cart/list.php` - Get cart items
- `POST /backend/api/cart/add.php` - Add to cart
- `POST /backend/api/orders/create.php` - Create order

## Features Overview

### Home Page
- Hero section with call-to-action
- Featured products display
- Category showcases
- Newsletter subscription

### Products Page
- Complete product listing with pagination
- Search functionality
- Category filtering
- Price sorting (low to high, high to low)
- Name sorting (A-Z, Z-A)

### Authentication
- User registration with validation
- Secure login system
- Session persistence
- Protected routes

### Admin Dashboard
- Product management (add, edit, delete)
- Order management
- User management
- Sales statistics
- Revenue tracking

### Shopping Cart
- Add/remove items
- Quantity management
- Real-time total calculation
- Persistent cart across sessions

## Development

To run in development mode:

```bash
npm run dev
```

To build for production:

```bash
npm run build
```

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is licensed under the MIT License.