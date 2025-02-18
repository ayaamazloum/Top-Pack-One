<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $firstName = trim($_POST['first-name']);
    $lastName = trim($_POST['last-name']);
    $email = trim($_POST['email']);
    $birthDate = trim($_POST['birth-date']);
    $birthPlace = trim($_POST['birth-place']);
    $nationality = trim($_POST['nationality']);
    $maritalStatus = trim($_POST['marital-status']);
    $gender = trim($_POST['gender']);
    $phoneNumber = trim($_POST['phone-number']);
    $fullAddress = trim($_POST['full-address']);
    $university = trim($_POST['university-institute']);
    $degree = trim($_POST['degree']);
    $speciality = trim($_POST['speciality']);
    $country = trim($_POST['country']);
    $graduationYear = trim($_POST['graduation-year']);
    $workExperience = trim($_POST['work-experience']);
    $jobTitle = trim($_POST['job-title']);
    
    // File handling
    $cvUpload = $_FILES['cv-upload'];
    $fileTmpPath = $cvUpload['tmp_name'];
    $fileName = $cvUpload['name'];
    $fileType = $cvUpload['type'];
    $fileSize = $cvUpload['size'];

    // Email preparation
    $to = "info@toppackone.com";
    $subject = "New Career Form Submission";
    $message = "
        First Name: $firstName\n
        Last Name: $lastName\n
        Email: $email\n
        Birth Date: $birthDate\n
        Birth Place: $birthPlace\n
        Nationality: $nationality\n
        Marital Status: $maritalStatus\n
        Gender: $gender\n
        Phone Number: $phoneNumber\n
        Full Address: $fullAddress\n
        University Institute: $university\n
        Degree: $degree\n
        Speciality: $speciality\n
        Country: $country\n
        Graduation Year: $graduationYear\n
        Work Experience: $workExperience\n
        Job Title: $jobTitle\n
    ";

    // Prepare the email headers
    $boundary = md5(time());
    $headers = "From: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

    // Email body
    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $body .= $message . "\r\n\r\n";

    // Attachment
    if ($fileTmpPath) {
        $fileContent = chunk_split(base64_encode(file_get_contents($fileTmpPath)));
        $body .= "--$boundary\r\n";
        $body .= "Content-Type: $fileType; name=\"$fileName\"\r\n";
        $body .= "Content-Disposition: attachment; filename=\"$fileName\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= $fileContent . "\r\n\r\n";
        $body .= "--$boundary--";
    }

    // Send email
    if (mail($to, $subject, $body, $headers)) {
        echo json_encode(['status' => 'success', 'message' => 'Your application has been submitted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'There was a problem sending your application.']);
    }
}
?>
