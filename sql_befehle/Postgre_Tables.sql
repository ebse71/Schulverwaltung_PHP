-- Roles Tablosu
CREATE TABLE roles (
    role_id SERIAL PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL
);

-- Permissions Tablosu
CREATE TABLE permissions (
    permission_id SERIAL PRIMARY KEY,
    permission_name VARCHAR(100) NOT NULL
);

-- Users Tablosu
CREATE TABLE users (
    user_id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    surname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) CHECK (role IN ('admin', 'teacher', 'student','guest')) NOT NULL,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    userkey VARCHAR(255)
);

-- Teachers Tablosu
CREATE TABLE teachers (
    teacher_id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    branch VARCHAR(255) NOT NULL,
    additional_branch VARCHAR(255),
    birth_date DATE,
    phone_number_1 VARCHAR(15),
    phone_number_2 VARCHAR(15),
    email VARCHAR(255) NOT NULL,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_teachers_user_id FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Classes Tablosu
CREATE TABLE classes (
    class_id SERIAL PRIMARY KEY,
    class_name VARCHAR(50) NOT NULL,
    teacher_id INT,
    CONSTRAINT fk_classes_teacher_id FOREIGN KEY (teacher_id) REFERENCES teachers(teacher_id) ON DELETE SET NULL
);

-- Parents Tablosu
CREATE TABLE parents (
    parent_id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    phone_number VARCHAR(15),
    email VARCHAR(100) NOT NULL,
    CONSTRAINT fk_parents_user_id FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Students Tablosu
CREATE TABLE students (
    student_id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    class_id INT NOT NULL,
    parent_id INT,
    relationship_to_student VARCHAR(50),
    birth_date DATE,
    phone_number VARCHAR(15),
    email VARCHAR(100),
    CONSTRAINT fk_students_user_id FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    CONSTRAINT fk_students_parent_id FOREIGN KEY (parent_id) REFERENCES parents(parent_id) ON DELETE SET NULL,
    CONSTRAINT fk_students_class_id FOREIGN KEY (class_id) REFERENCES classes(class_id) ON DELETE CASCADE
);

-- Password Resets Tablosu
CREATE TABLE password_resets (
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at TIMESTAMPTZ NOT NULL,
    PRIMARY KEY (token),
    CONSTRAINT fk_password_resets_email FOREIGN KEY (email) REFERENCES users(email) ON DELETE CASCADE
);

-- Grades Tablosu
CREATE TABLE grades (
    grade_id SERIAL PRIMARY KEY,
    student_id INT NOT NULL,
    class_id INT NOT NULL,
    subject VARCHAR(100) NOT NULL,
    grade FLOAT NOT NULL,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_grades_student_id FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    CONSTRAINT fk_grades_class_id FOREIGN KEY (class_id) REFERENCES classes(class_id) ON DELETE CASCADE
);

-- Assignments Tablosu
CREATE TABLE assignments (
    assignment_id SERIAL PRIMARY KEY,
    class_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    due_date DATE,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_assignments_class_id FOREIGN KEY (class_id) REFERENCES classes(class_id) ON DELETE CASCADE
);

-- Assignment Submissions Tablosu
CREATE TABLE assignment_submissions (
    submission_id SERIAL PRIMARY KEY,
    assignment_id INT NOT NULL,
    student_id INT NOT NULL,
    submission_date TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    grade FLOAT,
    feedback TEXT,
    CONSTRAINT fk_assignment_submissions_assignment_id FOREIGN KEY (assignment_id) REFERENCES assignments(assignment_id) ON DELETE CASCADE,
    CONSTRAINT fk_assignment_submissions_student_id FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

-- Messages Tablosu
CREATE TABLE messages (
    message_id SERIAL PRIMARY KEY,
    sender_id INT NOT NULL,
    recipient_id INT NOT NULL,
    subject VARCHAR(255),
    message_body TEXT NOT NULL,
    sent_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    is_read BOOLEAN DEFAULT FALSE,
    CONSTRAINT fk_messages_sender_id FOREIGN KEY (sender_id) REFERENCES users(user_id) ON DELETE CASCADE,
    CONSTRAINT fk_messages_recipient_id FOREIGN KEY (recipient_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Announcements Tablosu
CREATE TABLE announcements (
    announcement_id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_announcements_created_by FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Role Permissions Tablosu
CREATE TABLE role_permissions (
    role_id INT NOT NULL,
    permission_id INT NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    CONSTRAINT fk_role_permissions_role_id FOREIGN KEY (role_id) REFERENCES roles(role_id) ON DELETE CASCADE,
    CONSTRAINT fk_role_permissions_permission_id FOREIGN KEY (permission_id) REFERENCES permissions(permission_id) ON DELETE CASCADE
);

-- User Permissions Tablosu
CREATE TABLE user_permissions (
    user_id INT NOT NULL,
    permission_id INT NOT NULL,
    PRIMARY KEY (user_id, permission_id),
    CONSTRAINT fk_user_permissions_user_id FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    CONSTRAINT fk_user_permissions_permission_id FOREIGN KEY (permission_id) REFERENCES permissions(permission_id) ON DELETE CASCADE
);

-- Subjects Tablosu
CREATE TABLE subjects (
    subject_id SERIAL PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL
);

-- Class Subject Teacher Tablosu
CREATE TABLE class_subject_teacher (
    class_subject_teacher_id SERIAL PRIMARY KEY,
    class_id INT NOT NULL,
    subject_id INT NOT NULL,
    teacher_id INT NOT NULL,
    CONSTRAINT fk_cst_class_id FOREIGN KEY (class_id) REFERENCES classes(class_id) ON DELETE CASCADE,
    CONSTRAINT fk_cst_subject_id FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE,
    CONSTRAINT fk_cst_teacher_id FOREIGN KEY (teacher_id) REFERENCES teachers(teacher_id) ON DELETE CASCADE
);

-- Login Attempts Tablosu
CREATE TABLE login_attempts (
    attempt_id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    attempt_time TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    success BOOLEAN DEFAULT FALSE,
    CONSTRAINT fk_login_attempts_user_id FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- User Activity Logs Tablosu
CREATE TABLE user_activity_logs (
    log_id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    activity_type VARCHAR(100) NOT NULL,
    activity_details TEXT,
    activity_time TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_user_activity_logs_user_id FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Schedules Tablosu
CREATE TABLE schedules (
    schedule_id SERIAL PRIMARY KEY,
    class_id INT NOT NULL,
    subject_id INT NOT NULL,
    teacher_id INT NOT NULL,
    day_of_week VARCHAR(10) CHECK (day_of_week IN ('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag')) NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    CONSTRAINT fk_schedules_class_id FOREIGN KEY (class_id) REFERENCES classes(class_id) ON DELETE CASCADE,
    CONSTRAINT fk_schedules_subject_id FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE,
    CONSTRAINT fk_schedules_teacher_id FOREIGN KEY (teacher_id) REFERENCES teachers(teacher_id) ON DELETE CASCADE
);
CREATE TABLE oauth_users (
    oauth_user_id SERIAL PRIMARY KEY,    -- Otomatik artan birincil anahtar
    user_id INT NOT NULL,               -- 'users' tablosuna referans
    provider VARCHAR(50) NOT NULL,      -- OAuth sağlayıcı adı (örn: 'linkedin', 'github')
    provider_id VARCHAR(255) NOT NULL,  -- Sağlayıcı tarafından verilen kullanıcı kimliği
    access_token TEXT,                  -- Access token (uzun olabileceği için TEXT tipi kullanıyoruz)
    refresh_token TEXT,                 -- Refresh token (uzun olabileceği için TEXT tipi kullanıyoruz)
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_oauth_users_user_id FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);
