-- Task: WHERE Conditions

-- Name starts with 'A'
SELECT * FROM Student
WHERE name LIKE 'A%';

-- CS students with age > 21
SELECT * FROM Student
WHERE department = 'CS' AND age > 21;