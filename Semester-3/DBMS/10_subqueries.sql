-- Task: Subqueries

-- Students older than average age
SELECT *
FROM Student
WHERE age > (SELECT AVG(age) FROM Student);