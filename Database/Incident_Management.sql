/*                                                      MYSQL DATABASE STRUCTURE 
This script creates a database for incident management, including tables for users, categories, departments, incidents, and updates.
*/

-- Drop the database if it exists 
DROP DATABASE IF EXISTS incident_managementdb;

-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS incident_managementdb;

-- Use the created database
USE incident_managementdb;

-- Drop the tables if they exist to avoid conflicts
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS departments;
DROP TABLE IF EXISTS incidents;
DROP TABLE IF EXISTS updates;

-- Create the roles table to store user roles
-- This table will define the roles of users in the system (e.g., Admin, User)
CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each role
  name CHAR(50)                      -- Role name (Admin, User)
);

-- Create the users table to store user information
-- This table will store the users who can report incidents
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,  -- Unique ID for each user  
  name VARCHAR(100),                  -- User's name
  password VARCHAR(255),              -- password storage
  email VARCHAR(100) UNIQUE,          -- User's email
  role_id INT,                        -- ID of the user's role

  FOREIGN KEY (role_id) REFERENCES roles(id) -- Foreign key to link to the roles table
);

-- Create the categorias table to store incident categories
-- This table will categorize the incidents reported by users
CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each category
  name VARCHAR(100)                  -- Category name (e.g., Hardware, Software, Network)
);

-- Create the departamentos table to store department information
-- This table will store the departments responsible for handling incidents
CREATE TABLE departments (
  id INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each department
  name VARCHAR(100)                  -- Department name (e.g., IT, Support, Development)
);

-- Create the incidencias table to store incident information
-- This table will store the incidents reported by users
CREATE TABLE incidents (
  id INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each incident
  title VARCHAR(255),                -- Incident title
  description TEXT,                  -- Incident description
  user_id INT,                       -- ID of the user who reported the incident
  category_id INT,                   -- ID of the category assigned to the incident
  department_id INT,                 -- ID of the department assigned to the incident
  assigned_user_id INT,              -- ID of the user currently assigned to handle it
  status VARCHAR(50),                -- Incident status (e.g., Open, Closed)
  creation_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, -- Date when the incident was created

  FOREIGN KEY (user_id) REFERENCES users(id),             -- Foreign key to link to the users table
  FOREIGN KEY (category_id) REFERENCES categories(id),    -- Foreign key to link to the categories table
  FOREIGN KEY (department_id) REFERENCES departments(id), -- Foreign key to link to the departments table
  FOREIGN KEY (assigned_user_id) REFERENCES users(id)     -- Foreign key to link to the users table
);

-- Create the actualizaciones table to store updates on incidents
-- This table will store comments and status changes for incidents
CREATE TABLE updates (
  id INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each update
  incident_id INT,                   -- ID of the incident being updated
  user_id INT,                       -- ID of the user making the update
  assigned_user_id INT,              -- ID of the new user assigned (if changed)
  comment TEXT,                      -- Comment or update on the incident
  new_status VARCHAR(50),            -- New status of the incident (e.g., In Progress, Resolved)
  date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, -- Date when the update was made

  FOREIGN KEY (incident_id) REFERENCES incidents(id), -- Foreign key to link to the incidents table
  FOREIGN KEY (user_id) REFERENCES users(id),         -- Foreign key to link to the users table
  FOREIGN KEY (assigned_user_id) REFERENCES users(id) -- Foreign key to link to the users table
);

-- Insert data into the roles table
INSERT INTO roles (name) VALUES 
  ('Admin'), 
  ('User');

-- Insert data into the users table
-- This data will be used for testing and demonstration purposes
INSERT INTO users (name, email, password, role_id) VALUES 
  ('Sergio', 'sergio.de-miguel-g@yorksj.ac.uk', '1234', 1),
  ('Useradmin', 'useradmin@gmail.com', '1234', 2);

-- Insert data into the categories table
-- This data will be used to categorize incidents
INSERT INTO categories (name) VALUES ('Hardware'), ('Software'), ('Network');

-- Insert data into the departments table
-- This data will be used to categorize incidents by department
INSERT INTO departments (name) VALUES ('IT'), ('Support'), ('Development');
