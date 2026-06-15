-- Task: GROUP BY and HAVING

-- Count students in each department
SELECT department, COUNT(*) AS total
FROM Student
GROUP BY department;

-- Departments with more than 2 students
SELECT department, COUNT(*) AS total
FROM Student
GROUP BY department
HAVING COUNT(*) > 2;