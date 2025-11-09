
-- PostgreSQL schema for FashionShop (simple products table)
CREATE TABLE IF NOT EXISTS products (
  id SERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) UNIQUE NOT NULL,
  description TEXT,
  price NUMERIC(10,2) NOT NULL DEFAULT 0,
  image VARCHAR(255),
  created_at TIMESTAMP DEFAULT now()
);
