-- Create the database
CREATE DATABASE IF NOT EXISTS if0_37987503_mind_say;

-- Use the database
USE if0_37987503_mind_say;

-- Create the posts table
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each post
    user_name VARCHAR(255) NOT NULL,  -- Name of the user who made the post
    content TEXT NOT NULL,            -- Content of the post
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Timestamp of when the post was created
);

-- Create the replies table
CREATE TABLE replies (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each reply
    post_id INT NOT NULL,              -- ID of the post this reply belongs to
    user_name VARCHAR(255) NOT NULL,   -- Name of the user who replied
    reply TEXT NOT NULL,               -- Content of the reply
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Timestamp of when the reply was created
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE -- Link to the posts table
);
