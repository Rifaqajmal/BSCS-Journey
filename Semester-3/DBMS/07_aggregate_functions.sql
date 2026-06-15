-- Task: Aggregate Functions

-- Total students
SELECT COUNT(*) AS total_students FROM Student;

-- Average age
SELECT AVG(age) AS average_age FROM Student;

-- Maximum age
SELECT MAX(age) AS max_age FROM Student;

-- Minimum age
SELECT MIN(age) AS min_age FROM Student;