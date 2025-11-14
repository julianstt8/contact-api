CREATE DATABASE contacts_api;
USE contacts_api;

-- Tabla principal de contactos
CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL
);

-- Tabla de teléfonos asociados a contactos
CREATE TABLE phones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contact_id INT NOT NULL,
    telefono VARCHAR(50) NOT NULL,
    FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE CASCADE
);

INSERT INTO contacts (nombre, apellido, email)
VALUES 
('Carlos', 'Gomez', 'carlos@test.com'),
('Ana', 'Martinez', 'ana@test.com');

INSERT INTO phones (contact_id, telefono)
VALUES
(1, '3001112233'),
(1, '3105557788'),
(2, '3214448899');