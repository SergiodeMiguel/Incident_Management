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
DROP TABLE IF EXISTS users;

-- Create the users table to store user information
-- This table will store the users who can report incidents
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,  -- Unique ID for each user  
  name VARCHAR(100),                  -- User's name
  email VARCHAR(100)                  -- User's email
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
  status VARCHAR(50),                -- Incident status (e.g., Open, Closed)
  creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Date when the incident was created
);

-- Create the actualizaciones table to store updates on incidents
-- This table will store comments and status changes for incidents
CREATE TABLE updates (
  id INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each update
  incident_id INT,                   -- ID of the incident being updated
  user_id INT,                       -- ID of the user making the update
  comment TEXT,                      -- Comment or update on the incident
  new_status VARCHAR(50),            -- New status of the incident (e.g., In Progress, Resolved)
  date TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Date when the update was made
);

-- Insert sample data into the users table
-- This data will be used for testing and demonstration purposes
INSERT INTO users (name, email) VALUES 
  ('Ana Pérez', 'ana@example.com'),
  ('Carlos Ruiz', 'carlos@example.com');

-- Insert sample data into the categories table
-- This data will be used to categorize incidents
INSERT INTO categories (name) VALUES ('Hardware'), ('Software'), ('Red');

-- Insert sample data into the departments table
-- This data will be used to categorize incidents by department
INSERT INTO departments (name) VALUES ('IT'), ('Support'), ('Development');
