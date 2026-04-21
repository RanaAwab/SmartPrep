-- ========================================
-- DATABASE
-- ========================================
CREATE DATABASE smartprep;
USE smartprep;

-- ========================================
-- USERS
-- ========================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin','teacher','student'),
    status VARCHAR(20) DEFAULT 'approved'
);

-- ========================================
-- DEPARTMENTS
-- ========================================
CREATE TABLE departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100)
);

-- ========================================
-- COURSES
-- ========================================
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    code VARCHAR(50),
    department_id INT
);

-- ========================================
-- SUBJECTS
-- ========================================
CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    code VARCHAR(50),
    course_id INT
);

-- ========================================
-- TEACHER COURSE RELATION
-- ========================================
CREATE TABLE teacher_course (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_id INT,
    course_id INT
);

-- ========================================
-- TIMETABLE
-- ========================================
CREATE TABLE timetable (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_id INT,
    teacher_id INT,
    day VARCHAR(20),
    time TIME
);

-- ========================================
-- ANNOUNCEMENTS
-- ========================================
CREATE TABLE announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    message TEXT
);

-- ========================================
-- ASSIGNMENTS
-- ========================================
CREATE TABLE assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_id INT,
    teacher_id INT,
    title VARCHAR(255),
    description TEXT,
    deadline DATE
);

-- ========================================
-- SUBMISSIONS
-- ========================================
CREATE TABLE submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    assignment_id INT,
    student_id INT,
    content TEXT
);

-- ========================================
-- ATTENDANCE
-- ========================================
CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    subject_id INT,
    teacher_id INT,
    date DATE,
    status VARCHAR(20)
);

-- ========================================
-- RESULTS
-- ========================================
CREATE TABLE results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    subject_id INT,
    teacher_id INT,
    marks INT
);

-- ========================================
-- LECTURES (NO SAMPLE DATA)
-- ========================================
CREATE TABLE lectures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_id INT,
    teacher_id INT,
    title VARCHAR(255),
    file VARCHAR(255)
);

-- ========================================
-- QUIZ SYSTEM
-- ========================================
CREATE TABLE quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_id INT,
    teacher_id INT,
    title VARCHAR(255)
);

CREATE TABLE mcqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT,
    question TEXT,
    opt1 TEXT,
    opt2 TEXT,
    opt3 TEXT,
    opt4 TEXT,
    correct INT
);

CREATE TABLE quiz_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT,
    student_id INT,
    score INT
);

-- ========================================
-- SAMPLE DATA
-- ========================================

-- ADMIN (password: 123456)
INSERT INTO users (name,email,password,role,status) VALUES
('Admin','admin@gmail.com','$2y$10$wH7Z7nUe3R6U5KQpTQXH5Oq7zK7W8bZKz6R5U5VZ1Z5Y7Z5Y7Z5Y7','admin','approved');

-- TEACHERS
INSERT INTO users (name,email,password,role,status) VALUES
('Ali Khan','ali@gmail.com','$2y$10$wH7Z7nUe3R6U5KQpTQXH5Oq7zK7W8bZKz6R5U5VZ1Z5Y7Z5Y7Z5Y7','teacher','approved'),
('Sara Ahmed','sara@gmail.com','$2y$10$wH7Z7nUe3R6U5KQpTQXH5Oq7zK7W8bZKz6R5U5VZ1Z5Y7Z5Y7Z5Y7','teacher','approved');

-- STUDENTS
INSERT INTO users (name,email,password,role,status) VALUES
('Usman','usman@gmail.com','$2y$10$wH7Z7nUe3R6U5KQpTQXH5Oq7zK7W8bZKz6R5U5VZ1Z5Y7Z5Y7Z5Y7','student','approved'),
('Ayesha','ayesha@gmail.com','$2y$10$wH7Z7nUe3R6U5KQpTQXH5Oq7zK7W8bZKz6R5U5VZ1Z5Y7Z5Y7Z5Y7','student','approved');

-- DEPARTMENTS
INSERT INTO departments (name) VALUES
('Computer Science'),
('Business');

-- COURSES
INSERT INTO courses (name,code,department_id) VALUES
('BS Computer Science','BSCS',1),
('BBA','BBA',2);

-- SUBJECTS
INSERT INTO subjects (name,code,course_id) VALUES
('Programming','CS101',1),
('Database Systems','CS102',1),
('Marketing','BBA101',2);

-- TEACHER ASSIGNMENT
INSERT INTO teacher_course (teacher_id,course_id) VALUES
(2,1),
(3,2);

-- TIMETABLE
INSERT INTO timetable (subject_id,teacher_id,day,time) VALUES
(1,2,'Monday','09:00:00'),
(2,2,'Tuesday','11:00:00'),
(3,3,'Wednesday','10:00:00');

-- ASSIGNMENTS
INSERT INTO assignments (subject_id,teacher_id,title,description,deadline) VALUES
(1,2,'Assignment 1','Basic programming task','2026-05-01'),
(2,2,'Assignment 2','Database ERD','2026-05-05');

-- QUIZZES
INSERT INTO quizzes (subject_id,teacher_id,title) VALUES
(1,2,'Programming Quiz'),
(2,2,'Database Quiz');

-- MCQS
INSERT INTO mcqs (quiz_id,question,opt1,opt2,opt3,opt4,correct) VALUES
(1,'What is PHP?','Language','Database','OS','Browser',1),
(1,'Which symbol used for variables?','#','$','%','&',2),
(2,'Primary key is?','Unique','Duplicate','Null','None',1);

-- ANNOUNCEMENTS
INSERT INTO announcements (title,message) VALUES
('Welcome','Welcome to SmartPrep System'),
('Exam Notice','Mid exams next month');