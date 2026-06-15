-- Task: UPDATE and DELETE

-- Update department
UPDATE Student
SET department = 'Software Engineering'
WHERE id = 2;

-- Delete student
DELETE FROM Student
WHERE id = 10;