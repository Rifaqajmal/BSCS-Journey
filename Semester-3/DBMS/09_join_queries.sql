-- Task: JOIN Queries

-- Create Course Table
CREATE TABLE Course (
    course_id INT PRIMARY KEY,
    course_name VARCHAR(50)
);

-- Create Enrollment Table
CREATE TABLE Enrollment (
    student_id INT,
    course_id INT,
    FOREIGN KEY (student_id) REFERENCES Student(id),
    FOREIGN KEY (course_id) REFERENCES Course(course_id)
);

-- Insert Courses
INSERT INTO Course VALUES
(1, 'Database'),
(2, 'AI'),
(3, 'Networks');

-- Insert Enrollment Data
INSERT INTO Enrollment VALUES
(1,1),
(2,2),
(3,1),
(4,3),
(5,2);

-- INNER JOIN
SELECT Student.name, Course.course_name
FROM Student
INNER JOIN Enrollment ON Student.id = Enrollment.student_id
INNER JOIN Course ON Course.course_id = Enrollment.course_id;