# PAU Workflow Automation System

A premium, high-fidelity Digital Workflow Automation platform designed for institutional approvals, clearances, and process tracking.

## 🚀 Overview
**PAU Workflow** is a full-stack PHP MVC application designed to replace manual institutional processes with a secure, automated, and auditable digital environment. It features a sophisticated role-based access control (RBAC) system, an AI-assisted routing engine, and a premium dashboard for real-time tracking.

## 🛠 Tech Stack
*   **Core**: PHP (Standard MVC Architecture)
*   **Database**: MySQL / MariaDB (Optimized with structured indexes)
*   **Front-End**: Vanilla CSS & Tailwind CSS (Modern, Responsive Design)
*   **Automation**: AI Routing Service (Pattern-based next-approver logic)
*   **Security**: Role-Based Access Control, Hashed Passwords, Audit Logging.

## 📂 Architecture
The project follows a clean MVC structure:
*   `/app/Core`: Core framework logic (Database, Routing, Base Controller/Model).
*   `/app/Controllers`: Business logic and request handling.
*   `/app/Models`: Database interaction and entity logic.
*   `/app/Views`: Premium UI templates using PHP and Tailwind.
*   `/public`: Document root containing `index.php` and static assets (CSS, Images, Uploads).

## 🔑 Key Features
*   **Premium Authentication**: Centered-card design with secure password confirmation and intelligent data capture.
*   **Smart Routing**: An internal **AIService** suggests subsequent approvers based on workflow type and priority.
*   **Operational Insights**: Real-time bottleneck detection (3-request threshold) and delay monitoring (48-hour SLA).
*   **Full Audit Trail**: Every action (Created, Approved, Rejected, Escalated) is timestamped and logged with comments.
*   **Official Document Specs**: Integrated PDF generation/viewing for institutional document requirements.


## 🛡 Role Mapping
*   **Student**: Initiates clearances and tracks personal requests.
*   **Staff / HOD**: Initiates procurement and departmental requests.
*   **Finance/Registry/Library**: Specialized approvers for financial, archival, and resource-based clearances.
*   **Admin(to be implemented)**: Total system oversight (Bootstrap account required for initial management).

---
**PAU Workflow** - *Automate your institutional process with intelligence and transparency.*