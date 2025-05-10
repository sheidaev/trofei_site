CREATE TABLE products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255),
    description TEXT,
    price NUMERIC(10,2),
    image VARCHAR(255)
);

INSERT INTO products (name, description, price, image) VALUES
('Трофей 1', 'Опис трофею 1', 340.00, 'trophy1.jpg'),
('Трофей 2', 'Опис трофею 2', 350.00, 'trophy2.jpg'),
('Трофей 3', 'Опис трофею 3', 360.00, 'trophy3.jpg'),
('Трофей 4', 'Опис трофею 4', 370.00, 'trophy4.jpg'),
('Трофей 5', 'Опис трофею 5', 380.00, 'trophy5.jpg'),
('Трофей 6', 'Опис трофею 6', 390.00, 'trophy6.jpg'),
('Трофей 7', 'Опис трофею 7', 400.00, 'trophy7.jpg'),
('Трофей 8', 'Опис трофею 8', 410.00, 'trophy8.jpg'),
('Трофей 9', 'Опис трофею 9', 420.00, 'trophy9.jpg'),
('Трофей 10', 'Опис трофею 10', 430.00, 'trophy10.jpg'),
('Трофей 11', 'Опис трофею 11', 440.00, 'trophy11.jpg'),
('Трофей 12', 'Опис трофею 12', 450.00, 'trophy12.jpg');
