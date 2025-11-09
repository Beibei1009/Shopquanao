CREATE TABLE IF NOT EXISTS products (id SERIAL PRIMARY KEY,name VARCHAR(255) NOT NULL,description TEXT,price NUMERIC(12,2) NOT NULL DEFAULT 0,image VARCHAR(255),created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE IF NOT EXISTS contacts (id SERIAL PRIMARY KEY,name VARCHAR(100) NOT NULL,email VARCHAR(120) NOT NULL,message TEXT NOT NULL,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
INSERT INTO products (name, description, price, image) VALUES
('Đầm cổ bèo xám','Đầm nữ thanh lịch cổ bèo.',520000,'dress1.jpg'),
('Đầm nâu công sở','Đầm công sở tông nâu.',560000,'dress2.jpg'),
('Áo gile xám nữ','Áo gile phối sơ mi.',390000,'vest1.jpg'),
('Chân váy be','Chân váy be dài qua gối.',320000,'skirt_be.jpg'),
('Chân váy xòe đen','Chân váy A-line đen.',340000,'skirt_black_a.jpg'),
('Chân váy bút chì đen','Váy bút chì công sở.',330000,'skirt_black_pencil.jpg'),
('Áo khoác đỏ bo gấu','Áo khoác đỏ ấm áp.',620000,'coat_red1.jpg'),
('Áo khoác đỏ phối đen','Áo khoác ngắn cổ bẻ.',650000,'coat_red2.jpg'),
('Set vàng ELISE','Đầm + áo khoác vàng.',980000,'set_yellow.jpg'),
('Tee trắng in lưng','Áo thun unisex in sau.',220000,'tee_white_back.jpg'),
('Tee xanh EXPLORER nam','Áo thun nam xanh chữ EXPLORER.',240000,'tee_blue_explorer.jpg'),
('Khoác nỉ zipper be nam','Áo khoác nỉ zip tông be.',450000,'hoodie_be_man.jpg'),
('Khoác nỉ zipper xanh nữ','Áo khoác nỉ zip xanh navy.',440000,'hoodie_navy_woman.jpg'),
('Áo khoác gió xanh nam','Khoác gió chống nắng.',520000,'windbreaker_blue.jpg'),
('Tee xanh lá nam','Áo thun xanh lá graphic.',230000,'tee_green.jpg'),
('Tee cam nữ','Áo thun nữ orange graphic.',230000,'tee_orange_woman.jpg'),
('Quần short thể thao','Short nỉ thể thao.',260000,'short_sport.jpg'),
('Sơ mi đen nam','Sơ mi basic đen.',300000,'shirt_black_man.jpg'),
('Váy xếp ly đen','Chân váy xếp ly đen.',350000,'skirt_pleat_black.jpg'),
('Đầm xòe đen','Đầm xòe chữ A.',590000,'dress_black_a.jpg');