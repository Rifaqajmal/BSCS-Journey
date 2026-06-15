-- Task: Create Student Table

CREATE TABLE Student (
    id INT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    age INT CHECK (age >= 17),
    department VARCHAR(50),
    email VARCHAR(100) UNIQUE
);