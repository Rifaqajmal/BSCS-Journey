-- Task: Basic SELECT Queries

-- All students
SELECT * FROM Student;

-- Students from CS department
SELECT * FROM Student WHERE department = 'CS';

-- Students older than 20
SELECT * FROM Student WHERE age > 20;