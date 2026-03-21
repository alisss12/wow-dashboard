// database.js

const mysql = require('mysql2/promise');
const dbConfig = require('./config');

async function initializeDatabase() {
  try {
    const connection = await mysql.createConnection(dbConfig);
    console.log('Database connection successfully established');
    return connection;
  } catch (error) {
    console.error('Database connection failed:', error);
    throw error;
  }
}

module.exports = initializeDatabase;