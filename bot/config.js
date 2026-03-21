const dotenv = require('dotenv');
dotenv.config(); // Load environment variables from .env file

// Database configuration
const dbConfig = {
  host: process.env.DB_HOST,
  user: process.env.DB_USER,
  password: process.env.DB_PASSWORD,
  database: process.env.DB_NAME,
  port: Number(process.env.DB_PORT || 3306), // Retrieve port from environment variable or use 3306 as default
  waitForConnections: true,
  connectionLimit: Number(process.env.DB_CONN_LIMIT || 10),
  queueLimit: 0
};

module.exports = dbConfig;
