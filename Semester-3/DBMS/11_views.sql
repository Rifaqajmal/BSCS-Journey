-- Task: Views

-- Create View
CREATE VIEW CS_Students AS
SELECT * FROM Student WHERE department = 'CS';

-- Use View
SELECT * FROM CS_Students;