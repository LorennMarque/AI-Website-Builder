# Ai-Chatbot-Website-Builder

## Setup

1. **Database Credentials:**
   - Create a `.env` file in the `server` directory with the following format:
     ```
     DB_HOST=localhost
     DB_NAME=webuilder
     DB_USER=root
     DB_PASSWORD=your_password
     ```
   - Replace `your_password` with your actual database password.

2. **Start the PHP Server:**
   - Navigate to the project root directory in your terminal.
   - Run the command: `php -S localhost:8000`

   
3. **Database Setup:**
   - Create a database named `webuilder`.
   - Run the following SQL commands to create the necessary tables:

   ```sql
   CREATE TABLE users (
       id INT AUTO_INCREMENT PRIMARY KEY,
       email VARCHAR(100) NOT NULL UNIQUE,
       password VARCHAR(255) NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       active BOOLEAN DEFAULT TRUE
   );

    CREATE TABLE website_information (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        business_name VARCHAR(100) NOT NULL,
        project_description TEXT,
        services_offered TEXT,
        phone VARCHAR(20),
        email VARCHAR(100),
        address VARCHAR(255),
        country VARCHAR(100),
        instagram_url VARCHAR(255),
        google_maps_url VARCHAR(255),
        facebook_url VARCHAR(255),
        postal_code VARCHAR(20),
        logo_path VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        active BOOLEAN DEFAULT TRUE,
        FOREIGN KEY (user_id) REFERENCES users(id)
    );

    CREATE TABLE sections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    content TEXT,
    img_route VARCHAR(255),
    website_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (website_id) REFERENCES website_information(id)
);

   ```

