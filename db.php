<?php

$dbHost = 'localhost';
$dbUser = 'root';
$dbPassword = '';
$dbName = 'asclepius_db';

$conn = new mysqli($dbHost, $dbUser, $dbPassword);

if ($conn->connect_error) {
  die('Database connection failed: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');
$conn->query("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db($dbName);

$schemaStatements = [
  "CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(191) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY unique_users_email (email)
  )",
  "CREATE TABLE IF NOT EXISTS doctors (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    firstName VARCHAR(100) NOT NULL,
    lastName VARCHAR(100) NOT NULL,
    middleName VARCHAR(100),
    specialty VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL,
    shift VARCHAR(50),
    licenseNumber VARCHAR(100) UNIQUE NOT NULL,
    employeeId VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(191) UNIQUE,
    address TEXT,
    dob DATE,
    gender VARCHAR(20),
    education TEXT,
    notes TEXT,
    status VARCHAR(50) DEFAULT 'On Duty',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
  )",
  "CREATE TABLE IF NOT EXISTS patients (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    firstName VARCHAR(100) NOT NULL,
    lastName VARCHAR(100) NOT NULL,
    middleName VARCHAR(100),
    dateOfBirth DATE,
    gender VARCHAR(20),
    bloodType VARCHAR(10),
    phone VARCHAR(20),
    email VARCHAR(191),
    address TEXT,
    emergencyContact VARCHAR(150),
    emergencyPhone VARCHAR(20),
    medicalHistory TEXT,
    allergies TEXT,
    insurance_provider VARCHAR(150),
    insurance_number VARCHAR(100),
    status VARCHAR(50) DEFAULT 'Active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
  )",
  "CREATE TABLE IF NOT EXISTS appointments (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    patientId INT UNSIGNED NOT NULL,
    doctorId INT UNSIGNED NOT NULL,
    appointmentDate DATE NOT NULL,
    appointmentTime TIME NOT NULL,
    reason TEXT,
    status VARCHAR(50) DEFAULT 'Scheduled',
    notes TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX appointment_patient_idx (patientId),
    INDEX appointment_doctor_idx (doctorId),
    CONSTRAINT appointment_patient_fk FOREIGN KEY (patientId) REFERENCES patients(id) ON DELETE CASCADE,
    CONSTRAINT appointment_doctor_fk FOREIGN KEY (doctorId) REFERENCES doctors(id) ON DELETE CASCADE
  )",
  "CREATE TABLE IF NOT EXISTS medical_records (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    patientId INT UNSIGNED NOT NULL,
    recordType VARCHAR(100),
    recordDate DATE NOT NULL,
    description TEXT,
    findings TEXT,
    recommendations TEXT,
    physician VARCHAR(150),
    department VARCHAR(100),
    chiefComplaint TEXT,
    diagnosis TEXT,
    clinicalNotes TEXT,
    prescription TEXT,
    bloodPressure VARCHAR(50),
    heartRate VARCHAR(50),
    temperature VARCHAR(50),
    weight VARCHAR(50),
    allergies VARCHAR(255),
    attachments VARCHAR(500),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX medical_record_patient_idx (patientId),
    CONSTRAINT medical_record_patient_fk FOREIGN KEY (patientId) REFERENCES patients(id) ON DELETE CASCADE
  )",
  "CREATE TABLE IF NOT EXISTS laboratory_results (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    patientId INT UNSIGNED NOT NULL,
    testType VARCHAR(100),
    testDate DATE NOT NULL,
    results TEXT,
    referenceRange VARCHAR(100),
    abnormalFlag VARCHAR(10),
    orderedBy INT UNSIGNED,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX laboratory_patient_idx (patientId),
    INDEX laboratory_doctor_idx (orderedBy),
    CONSTRAINT laboratory_patient_fk FOREIGN KEY (patientId) REFERENCES patients(id) ON DELETE CASCADE,
    CONSTRAINT laboratory_doctor_fk FOREIGN KEY (orderedBy) REFERENCES doctors(id) ON DELETE SET NULL
  )",
  "CREATE TABLE IF NOT EXISTS prescriptions (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    patientId INT UNSIGNED NOT NULL,
    doctorId INT UNSIGNED NOT NULL,
    medicationName VARCHAR(150) NOT NULL,
    dosage VARCHAR(100),
    frequency VARCHAR(100),
    duration VARCHAR(100),
    prescriptionDate DATE NOT NULL,
    expiryDate DATE,
    notes TEXT,
    status VARCHAR(50) DEFAULT 'Active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX prescription_patient_idx (patientId),
    INDEX prescription_doctor_idx (doctorId),
    CONSTRAINT prescription_patient_fk FOREIGN KEY (patientId) REFERENCES patients(id) ON DELETE CASCADE,
    CONSTRAINT prescription_doctor_fk FOREIGN KEY (doctorId) REFERENCES doctors(id) ON DELETE CASCADE
  )",
  "CREATE TABLE IF NOT EXISTS billing (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    patientId INT UNSIGNED NOT NULL,
    appointmentId INT UNSIGNED,
    description VARCHAR(255),
    amount DECIMAL(10, 2),
    status VARCHAR(50) DEFAULT 'Pending',
    billingDate DATE,
    paymentDate DATE,
    paymentMethod VARCHAR(50),
    notes TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX billing_patient_idx (patientId),
    INDEX billing_appointment_idx (appointmentId),
    CONSTRAINT billing_patient_fk FOREIGN KEY (patientId) REFERENCES patients(id) ON DELETE CASCADE,
    CONSTRAINT billing_appointment_fk FOREIGN KEY (appointmentId) REFERENCES appointments(id) ON DELETE SET NULL
  )",
  "CREATE TABLE IF NOT EXISTS patient_contacts (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    patientId INT UNSIGNED NOT NULL,
    contactName VARCHAR(150) NOT NULL,
    relationship VARCHAR(100),
    phoneNumber VARCHAR(20) NOT NULL,
    email VARCHAR(191),
    address TEXT,
    isPrimary TINYINT(1) DEFAULT 0,
    notes TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX patient_contacts_patient_idx (patientId),
    CONSTRAINT patient_contacts_patient_fk FOREIGN KEY (patientId) REFERENCES patients(id) ON DELETE CASCADE
  )"
];

foreach ($schemaStatements as $statement) {
  if (!$conn->query($statement)) {
    die('Database setup failed: ' . $conn->error);
  }
}

// Ensure medical_records has physician/department and other record metadata columns for detail viewing.
$upgradeColumns = [
  "physician VARCHAR(150)",
  "department VARCHAR(100)",
  "chiefComplaint TEXT",
  "diagnosis TEXT",
  "clinicalNotes TEXT",
  "prescription TEXT",
  "bloodPressure VARCHAR(50)",
  "heartRate VARCHAR(50)",
  "temperature VARCHAR(50)",
  "weight VARCHAR(50)",
  "allergies VARCHAR(255)"
];
foreach ($upgradeColumns as $columnDefinition) {
  $conn->query("ALTER TABLE medical_records ADD COLUMN IF NOT EXISTS $columnDefinition");
}
