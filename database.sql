-- =========================
-- CREATE DATABASE
-- =========================
CREATE DATABASE IF NOT EXISTS workflow_system;
USE workflow_system;

-- =========================
-- USER TABLE
-- =========================
CREATE TABLE User (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role ENUM('Student', 'Staff', 'HOD', 'Library', 'CFO', 'Finance Officer', 'Registry', 'Logistics', 'Admin') NOT NULL,
    department VARCHAR(100),
    email VARCHAR(150) UNIQUE NOT NULL
);

-- =========================
-- WORKFLOW TABLE
-- =========================
CREATE TABLE Workflow (
    workflow_id INT AUTO_INCREMENT PRIMARY KEY,
    name ENUM('Fee Waiver', 'Procurement', 'Clearance') NOT NULL,
    status ENUM('Active', 'Draft') DEFAULT 'Draft'
);

-- =========================
-- REQUEST TABLE
-- =========================
CREATE TABLE Request (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    workflow_type INT,
    submitted_by INT,
    submission_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Pending', 'Approved', 'Rejected', 'Escalated') DEFAULT 'Pending',
    current_approver INT,
    priority_level ENUM('Low', 'Medium', 'High') DEFAULT 'Medium',

    FOREIGN KEY (workflow_type) REFERENCES Workflow(workflow_id),
    FOREIGN KEY (submitted_by) REFERENCES User(user_id),
    FOREIGN KEY (current_approver) REFERENCES User(user_id)
);

-- =========================
-- APPROVAL STEP TABLE
-- =========================
CREATE TABLE ApprovalStep (
    step_id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT,
    approver_role ENUM('Student', 'Staff', 'HOD', 'Library', 'CFO', 'Finance Officer', 'Registry', 'Logistics', 'Admin'),
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    decision VARCHAR(50),
    comment TEXT,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (request_id) REFERENCES Request(request_id) ON DELETE CASCADE
);

-- =========================
-- AUDIT LOG TABLE
-- =========================
CREATE TABLE AuditLog (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT,
    action ENUM('Created', 'Approved', 'Rejected', 'Escalated'),
    performed_by INT,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    comment TEXT,

    FOREIGN KEY (request_id) REFERENCES Request(request_id) ON DELETE CASCADE,
    FOREIGN KEY (performed_by) REFERENCES User(user_id)
);

-- =========================
-- ESCALATION RULE TABLE
-- =========================
CREATE TABLE EscalationRule (
    rule_id INT AUTO_INCREMENT PRIMARY KEY,
    workflow_type INT,
    time_limit_hours INT NOT NULL,
    escalation_role ENUM('Student', 'Staff', 'HOD', 'Library', 'CFO', 'Finance Officer', 'Registry', 'Logistics', 'Admin'),

    FOREIGN KEY (workflow_type) REFERENCES Workflow(workflow_id)
);

-- =========================
-- DASHBOARD METRICS TABLE
-- =========================
CREATE TABLE DashboardMetrics (
    metric_id INT AUTO_INCREMENT PRIMARY KEY,
    avg_approval_time FLOAT,
    number_of_requests INT,
    pending_requests INT,
    escalations_count INT,
    generated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- INDEXES (PERFORMANCE BOOST)
-- =========================
CREATE INDEX idx_request_status ON Request(status);
CREATE INDEX idx_audit_request ON AuditLog(request_id);

-- =========================
-- SAMPLE DATA
-- =========================
INSERT INTO User (name, role, department, email) VALUES
('Samuel Agyei', 'CFO', 'Finance', 'samuel@pau.edu'),
('Ama Mensah', 'Finance Officer', 'Finance', 'ama@pau.edu'),
('Kojo Asante', 'Registry', 'Registry', 'kojo@pau.edu'),
('Yaw Boateng', 'Logistics', 'Logistics', 'yaw@pau.edu'),
('Dr. Jane Doe', 'HOD', 'Computer Science', 'jane.doe@pau.edu'),
('Mr. Librarian', 'Library', 'Main Library', 'library@pau.edu'),
('Alice Student', 'Student', 'Computer Science', 'alice.student@pau.edu');

INSERT INTO Workflow (name, status) VALUES
('Fee Waiver', 'Active'),
('Procurement', 'Active'),
('Clearance', 'Active');

-- =========================
-- OPTIONAL: SAMPLE REQUEST
-- =========================
INSERT INTO Request (workflow_type, submitted_by, current_approver, priority_level)
VALUES (1, 2, 1, 'High');
