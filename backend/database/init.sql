-- Create products table if not exists
CREATE TABLE IF NOT EXISTS products (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_url TEXT,
    stock INT NOT NULL DEFAULT 0,
    category VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create cart_items table if not exists
CREATE TABLE IF NOT EXISTS cart_items (
    id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(100) NOT NULL,
    product_id VARCHAR(36) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_product_id (product_id)
);

-- Insert sample products if table is empty
INSERT INTO products (id, name, description, price, image_url, stock, category)
SELECT * FROM (SELECT 
    UUID() as id,
    'MacBook Pro 16' as name,
    'Powerful laptop for professionals' as description,
    2499.99 as price,
    '/images/products/macbook.jpg' as image_url,
    25 as stock,
    'laptops' as category
) AS tmp
WHERE NOT EXISTS (
    SELECT id FROM products LIMIT 1
);
