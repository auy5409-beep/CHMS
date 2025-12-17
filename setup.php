<?php
/**
 * CampusHealth Database Setup
 * Run this file once to create the database and tables
 * Access it at: http://localhost/iM/setup.php
 */

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "campushealth";

// Connect to MySQL without selecting a database
$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "✅ Database '$dbname' created or already exists.<br>";
} else {
    echo "❌ Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db($dbname);

// Create students table
$sql = "CREATE TABLE IF NOT EXISTS students (
    id VARCHAR(50) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    sex VARCHAR(20),
    age INT,
    height INT,
    contact VARCHAR(20),
    course VARCHAR(100),
    yearLevel VARCHAR(50),
    temperature DECIMAL(5,2),
    heartRate INT,
    oxygen INT,
    status VARCHAR(20),
    lastSeen VARCHAR(100),
    photo LONGTEXT,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "✅ Table 'students' created successfully.<br>";
} else {
    echo "❌ Error creating students table: " . $conn->error . "<br>";
}

// Create health_logs table for tracking student health history
$sql = "CREATE TABLE IF NOT EXISTS health_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    studentId VARCHAR(50) NOT NULL,
    temperature DECIMAL(5,2),
    heartRate INT,
    oxygen INT,
    status VARCHAR(20),
    notes TEXT,
    loggedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (studentId) REFERENCES students(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "✅ Table 'health_logs' created successfully.<br>";
} else {
    echo "❌ Error creating health_logs table: " . $conn->error . "<br>";
}

// Create alerts table
$sql = "CREATE TABLE IF NOT EXISTS alerts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    studentId VARCHAR(50) NOT NULL,
    alertType VARCHAR(50),
    message TEXT,
    severity VARCHAR(20),
    isRead BOOLEAN DEFAULT FALSE,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (studentId) REFERENCES students(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "✅ Table 'alerts' created successfully.<br>";
} else {
    echo "❌ Error creating alerts table: " . $conn->error . "<br>";
}

// Insert sample data
$sampleStudents = [
    ['id' => 'S001', 'name' => 'Ana Cruz', 'sex' => 'Female', 'age' => 20, 'height' => 160, 'contact' => '09171234567', 'course' => 'BSIT', 'yearLevel' => '2nd Year', 'temperature' => 36.7, 'heartRate' => 72, 'oxygen' => 98, 'status' => 'OK'],
    ['id' => 'S002', 'name' => 'Mark Dela Vega', 'sex' => 'Male', 'age' => 19, 'height' => 170, 'contact' => '09179876543', 'course' => 'BSCRIM', 'yearLevel' => '1st Year', 'temperature' => 38.3, 'heartRate' => 95, 'oxygen' => 94, 'status' => 'WATCH'],
    ['id' => 'S003', 'name' => 'Liza Santos', 'sex' => 'Female', 'age' => 21, 'height' => 158, 'contact' => '09173456789', 'course' => 'BSCED', 'yearLevel' => '3rd Year', 'temperature' => 39.1, 'heartRate' => 110, 'oxygen' => 90, 'status' => 'CRITICAL']
];

$stmt = $conn->prepare("REPLACE INTO students (id, name, sex, age, height, contact, course, yearLevel, temperature, heartRate, oxygen, status, lastSeen) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

foreach ($sampleStudents as $student) {
    $stmt->bind_param("sssiiissiiis", 
        $student['id'], $student['name'], $student['sex'], $student['age'], 
        $student['height'], $student['contact'], $student['course'], $student['yearLevel'],
        $student['temperature'], $student['heartRate'], $student['oxygen'], $student['status']
    );
    
    if ($stmt->execute()) {
        echo "✅ Sample student '{$student['name']}' inserted.<br>";
    } else {
        echo "❌ Error inserting student: " . $stmt->error . "<br>";
    }
}

$conn->close();

echo "<br><hr>";
echo "<h2 style='color: green;'>✅ Database Setup Complete!</h2>";
echo "<p>Your CampusHealth database is ready to use.</p>";
echo "<p><a href='index.html' style='color: blue;'>Go to Dashboard</a></p>";
?>
