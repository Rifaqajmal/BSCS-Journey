-- Task: Indexes

-- Create Index on email
CREATE INDEX idx_email
ON Student(email);

-- Explanation:
-- Index improves search performance when querying by email