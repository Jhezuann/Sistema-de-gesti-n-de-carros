-- Create the usuarios table
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    pregunta1 VARCHAR(255) NOT NULL,
    respuesta1 VARCHAR(255) NOT NULL,
    pregunta2 VARCHAR(255) NOT NULL,
    respuesta2 VARCHAR(255) NOT NULL,
    role ENUM('admin', 'persona') NOT NULL DEFAULT 'persona'
);

-- Insert the admin user
INSERT INTO usuarios (username, password, pregunta1, respuesta1, pregunta2, respuesta2, role)
VALUES (
    'admin',
    SHA2('admin123', 256),
    'Color favorito?',
    SHA2('azul', 256),
    'Heroe de la infancia?',
    SHA2('super man', 256),
    'admin'
);

-- Create the partes_carro table
CREATE TABLE partes_carro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- Create the problemas table
CREATE TABLE problemas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    parte_id INT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parte_id) REFERENCES partes_carro(id)
);

-- Create the soluciones table
CREATE TABLE soluciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    problema_id INT,
    titulo VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    FOREIGN KEY (problema_id) REFERENCES problemas(id)
);

-- Insert 10 pieces into partes_carro
INSERT INTO partes_carro (nombre) VALUES
('Motor'),
('Transmisión'),
('Frenos'),
('Suspensión'),
('Escape'),
('Radiador'),
('Batería'),
('Alternador'),
('Filtro de aire'),
('Filtro de aceite');

-- Insert 10 problems into problemas
INSERT INTO problemas (titulo, descripcion, parte_id) VALUES
('Fallo en el motor', 'El motor no arranca', 1),
('Problema de transmisión', 'Dificultad para cambiar de marcha', 2),
('Frenos ruidosos', 'Los frenos hacen un ruido chirriante', 3),
('Suspensión dura', 'La suspensión está muy rígida', 4),
('Escape con fugas', 'Fugas en el sistema de escape', 5),
('Sobrecalentamiento del radiador', 'El radiador se sobrecalienta', 6),
('Batería descargada', 'La batería se descarga rápidamente', 7),
('Fallo del alternador', 'El alternador no carga la batería', 8),
('Filtro de aire sucio', 'El filtro de aire está obstruido', 9),
('Cambio de aceite necesario', 'El filtro de aceite necesita ser cambiado', 10);

-- Insert 10 solutions into soluciones
INSERT INTO soluciones (problema_id, titulo, descripcion) VALUES
(1, 'Revisar batería', 'Asegúrese de que la batería esté cargada y conectada correctamente'),
(2, 'Cambiar el aceite de transmisión', 'Reemplace el aceite de transmisión y revise el sistema de cambios'),
(3, 'Reemplazar pastillas de freno', 'Sustituya las pastillas de freno para eliminar el ruido'),
(4, 'Revisar amortiguadores', 'Revise y reemplace los amortiguadores si es necesario'),
(5, 'Reparar el sistema de escape', 'Repare o reemplace las partes con fugas en el escape'),
(6, 'Limpiar el radiador', 'Limpie el radiador y revise el sistema de enfriamiento'),
(7, 'Reemplazar la batería', 'Reemplace la batería por una nueva'),
(8, 'Reparar o reemplazar el alternador', 'Repare o reemplace el alternador defectuoso'),
(9, 'Limpiar o reemplazar el filtro de aire', 'Limpie o reemplace el filtro de aire obstruido'),
(10, 'Cambiar el filtro de aceite', 'Reemplace el filtro de aceite y cambie el aceite del motor');
