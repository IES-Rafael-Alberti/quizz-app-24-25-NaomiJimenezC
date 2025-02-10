-- Crear la tabla Usuario
CREATE TABLE IF NOT EXISTS "Usuario" (
                                         user_id SERIAL PRIMARY KEY,
                                         username TEXT NOT NULL UNIQUE,
                                         is_administrator BOOLEAN NOT NULL ,
                                         password TEXT NOT NULL
);

-- Crear la tabla Cuestionario
CREATE TABLE IF NOT EXISTS "Cuestionario" (
                                              quiz_id SERIAL PRIMARY KEY,
                                              title TEXT NOT NULL,
                                              description TEXT
);

-- Crear la tabla Pregunta
CREATE TABLE IF NOT EXISTS "Pregunta" (
                                          question_id SERIAL PRIMARY KEY,
                                          quiz_id SERIAL NOT NULL,
                                          question_text TEXT NOT NULL,
                                          option_a TEXT NOT NULL,
                                          option_b TEXT NOT NULL,
                                          option_c TEXT NOT NULL,
                                          option_d TEXT NOT NULL,
                                          correct_option CHAR(1) NOT NULL,
                                          FOREIGN KEY (quiz_id) REFERENCES "Cuestionario" (quiz_id) ON DELETE CASCADE
);

-- Crear la tabla Respuestas
CREATE TABLE IF NOT EXISTS "Respuestas" (
                                            response_id SERIAL PRIMARY KEY,
                                            user_id SERIAL NOT NULL,
                                            quiz_id SERIAL NOT NULL,
                                            question_id SERIAL NOT NULL,
                                            selected_option CHAR(1) NOT NULL,
                                            is_correct BOOLEAN NOT NULL,
                                            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                            FOREIGN KEY (user_id) REFERENCES "Usuario" (user_id) ON DELETE CASCADE,
                                            FOREIGN KEY (quiz_id) REFERENCES "Cuestionario" (quiz_id) ON DELETE CASCADE,
                                            FOREIGN KEY (question_id) REFERENCES "Pregunta" (question_id) ON DELETE CASCADE
);
