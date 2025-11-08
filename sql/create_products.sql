CREATE TABLE IF NOT EXISTS products (
  id SERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  price DECIMAL(10,2),
  image VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO products (name, description, price, image) VALUES
('Áo thun nữ', 'Áo thun cotton thoáng mát', 120000, 'shirt1.jpg'),
('Quần jeans', 'Quần jeans co giãn', 350000, 'jeans1.jpg'),
('Áo khoác nữ', 'Áo khoác dày ấm', 450000, 'coat1.jpg'),
('Áo sơ mi nam', 'Sơ mi tay dài công sở', 220000, 'shirt2.jpg'),
('Váy midi', 'Váy midi dáng xòe', 320000, 'dress1.jpg'),
('Áo len', 'Áo len cổ tròn', 280000, 'sweater1.jpg'),
('Giày sneaker', 'Sneaker phối màu', 680000, 'sneaker1.jpg'),
('Túi tote', 'Túi vải tote đơn giản', 150000, 'tote1.jpg'),
('Mũ lưỡi trai', 'Mũ vải thoáng mát', 90000, 'cap1.jpg'),
('Thắt lưng da', 'Thắt lưng da bản vừa', 170000, 'belt1.jpg'),
('Áo khoác jean', 'Jean jacket cá tính', 520000, 'jacket1.jpg'),
('Áo khoác gió', 'Chống nước nhẹ', 410000, 'wind1.jpg'),
('Quần short', 'Short kaki basic', 180000, 'short1.jpg'),
('Đầm hoa', 'Đầm hoa nhí', 390000, 'dress2.jpg'),
('Giày búp bê', 'Đế êm, dễ đi', 260000, 'flat1.jpg'),
('Giày cao gót', 'Cao 5cm', 430000, 'heel1.jpg'),
('Túi đeo chéo', 'Đeo chéo mini', 240000, 'cross1.jpg'),
('Áo polo', 'Polo cá sấu', 250000, 'polo1.jpg'),
('Áo hoodie', 'Hoodie oversize', 490000, 'hoodie1.jpg'),
('Quần tây', 'Quần tây ống đứng', 360000, 'trouser1.jpg');