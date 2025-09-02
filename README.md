# ShopVue - Modern E-commerce Website

A modern, full-stack e-commerce website built with Vue 3, TypeScript, Tailwind CSS, PHP, and MySQL.

<img width="1920" height="4162" alt="Home-Page" src="https://github.com/user-attachments/assets/81f0487a-8f6c-4536-a5e6-a9b44add8372" />

# ShopVue - Product Page

<img width="1920" height="3358" alt="All-Product-Page" src="https://github.com/user-attachments/assets/85e99988-1331-4e30-9542-834a72564041" />

# ShopVue - About Page

<img width="1920" height="2520" alt="About" src="https://github.com/user-attachments/assets/dbffd66f-dc2b-4414-9ba5-fc08407d6938" />

# ShopVue - Login Page

<img width="1920" height="1455" alt="Login-page" src="https://github.com/user-attachments/assets/182b11c4-09f4-4444-a222-e74d4e213d66" />

# ShopVue - Register Page

<img width="1920" height="1521" alt="Register-page" src="https://github.com/user-attachments/assets/461bd834-b324-4cdf-9c82-480267c36dc3" />

# ShopVue - Admin Dashboard Page

<img width="1920" height="2103" alt="Admin-Dashboard" src="https://github.com/user-attachments/assets/829fb8e1-6b84-46cd-ac0d-b9d85557ec44" />

# ShopVue - Shopping Cart Page

<img width="1920" height="1353" alt="Shopping-Cart" src="https://github.com/user-attachments/assets/2d656a5b-017d-474f-950b-5721679edc39" />

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
