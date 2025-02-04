-- Habilitar la extensión uuid-ossp
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Crear la tabla Usuario
CREATE TABLE IF NOT EXISTS "Usuario" (
    user_id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL
);

-- Crear la tabla Cuestionario
CREATE TABLE IF NOT EXISTS "Cuestionario" (
    quiz_id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    title TEXT NOT NULL,
    description TEXT
);

-- Crear la tabla Pregunta
CREATE TABLE IF NOT EXISTS "Pregunta" (
    question_id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    quiz_id UUID NOT NULL,
    question_text TEXT NOT NULL,
    option_a TEXT NOT NULL,
    option_b TEXT NOT NULL,
    option_c TEXT NOT NULL,
    option_d TEXT NOT NULL,
    correct_option CHAR(1) NOT NULL,
    FOREIGN KEY (quiz_id) REFERENCES "Cuestionario" (quiz_id) ON DELETE CASCADE
);
