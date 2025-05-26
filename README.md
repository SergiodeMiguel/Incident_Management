# Incident Management System

A web-based incident management system that allows users to create, edit, assign, and track incidents. Includes role-based access using Keycloak (admin/user) and secure login via OpenID Connect.

## 🛠️ Technologies Used

- **Frontend:** HTML, CSS, JavaScript  
- **Backend:** PHP (REST API)  
- **Authentication:** Keycloak + Jumbojett OpenIDConnectClient  
- **Database:** MySQL  
- **Environment:** XAMPP (Apache, PHP, MySQL)

## 🚀 Getting Started

### Prerequisites

- PHP ≥ 7.4  
- MySQL  
- Composer  
- XAMPP (or any local server stack)  
- Keycloak server running on `http://localhost:8080`

### Installation Steps

1. Clone the repository:
   ```bash
   git clone https://github.com/tuusuario/incident-management-system.git
Import the database:

Open phpMyAdmin or another MySQL client.

Import the file Incident_Management.sql.

Set up Keycloak:

Create a realm (e.g., Incident-System) and a client (php-app).

Enable OpenID Connect (public client, standard flow).

Add test users and assign them roles: admin, user.

Install PHP dependencies with Composer:

bash
Copiar
Editar
composer install
Start Apache and MySQL via XAMPP or your preferred stack.

Open the application:

arduino
Copiar
Editar
http://localhost/PROYECT/CLIENT/HTML/Login.html
🔒 Login & Roles
Authentication is managed via Keycloak. After logging in, user info and role are saved in localStorage.
The interface dynamically changes depending on the user's role (admin vs user).

📁 Project Structure
graphql
Copiar
Editar
PROJECT/
│
├── CLIENT/
│   ├── HTML/          # Frontend pages (Login, Home, etc.)
│   ├── JS/            # JavaScript logic (Home.js, Create.js...)
│   └── Assets/        # CSS and static files
│
├── SERVER/
│   └── API/
│       ├── Incidents/     # REST API for incident management
│       └── Login/         # Keycloak login handler
│
├── vendor/                # Composer dependencies
├── Incident_Management.sql
└── README.md
✨ Features
Secure login using Keycloak and OpenID Connect

Role-based access: Admin vs Regular User

Create, edit, delete, assign, and track incidents

Display of incident stats (open/closed)

Dynamic welcome and interface adjustment by role

💡 Future Improvements
🔄 Google Calendar Integration: Auto-create, update, or delete calendar events based on incident deadlines.

🔁 User Sync with Keycloak: Dynamically load Keycloak users for dropdowns instead of using local MySQL.

📧 Email Notifications: Notify users when an incident is assigned, edited, or closed.

🔍 Advanced Filters: Filter incidents by category, date, department, or assigned user.

📄 License
This project is for educational or demo purposes. Feel free to fork, modify, and reuse as needed.
