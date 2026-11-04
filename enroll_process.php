<?php
function postText($name)
{
    if (isset($_POST[$name]) && is_string($_POST[$name])) {
        return $_POST[$name];
    }
    return '';
}

function escape($value)
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$errors = array();
$fullName = trim(postText('full_name'));
$studentId = trim(postText('student_id'));
$email = trim(postText('email'));
$password = postText('password');
$confirmPassword = postText('confirm_password');
$dateOfBirth = postText('date_of_birth');
$gender = postText('gender');
$program = postText('program');
$contactMethods = array();
if (isset($_POST['contact_methods'])) {
    $contactMethods = $_POST['contact_methods'];
}
$programs = array('Computer Science', 'Engineering', 'Business Administration', 'Arts & Humanities');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    $errors[] = 'Please submit the enrollment form using POST.';
} else {
    if ($fullName === '') {
        $errors[] = 'Full Name cannot be empty.';
    }
    if (!preg_match('/\AST[0-9]{5}\z/', $studentId)) {
        $errors[] = 'Student ID must be ST followed by exactly 5 digits.';
    }
    if (!preg_match('/\A[^\s@]+@[^\s@]+\.[^\s@]+\z/u', $email)) {
        $errors[] = 'Enter a valid email address.';
    }
    if (!preg_match('/\A[\s\S]{8,}\z/u', $password) ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must have at least 8 characters, uppercase, lowercase, and a number.';
    }
    if ($confirmPassword === '' || $confirmPassword !== $password) {
        $errors[] = 'Passwords must match.';
    }
    if (!in_array($program, $programs, true)) {
        $errors[] = 'Select a valid Program of Study.';
    }
    if ($dateOfBirth !== '') {
        $dateParts = explode('-', $dateOfBirth);
        if (!preg_match('/\A[0-9]{4}-[0-9]{2}-[0-9]{2}\z/', $dateOfBirth) ||
            !checkdate((int) $dateParts[1], (int) $dateParts[2], (int) $dateParts[0])) {
            $errors[] = 'Enter a valid Date of Birth.';
        }
    }
    if ($gender !== '' && !in_array($gender, array('Male', 'Female', 'Other'), true)) {
        $errors[] = 'Select a valid Gender.';
    }
    if (!is_array($contactMethods)) {
        $errors[] = 'Select valid contact methods.';
    } else {
        foreach ($contactMethods as $method) {
            if (!in_array($method, array('Email', 'SMS', 'Phone Call'), true)) {
                $errors[] = 'Select valid contact methods.';
                break;
            }
        }
    }
    if ($errors) {
        http_response_code(422);
    }
}
header('Cache-Control: no-store');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Result | COMP 315</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
</head>
<body>
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <section class="form-card">
                    <p class="text-uppercase text-primary small fw-bold">COMP 315 · Student Portal</p>
                    <?php if ($errors): ?>
                        <h1>Enrollment could not be completed</h1>
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo escape($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <a href="index.html" class="btn btn-primary">Back to Enrollment</a>
                    <?php else: ?>
                        <h1>Registration successful!</h1>
                        <p class="alert alert-success">Your enrollment form was submitted successfully. Verify your details below.</p>
                        <?php
                        if ($dateOfBirth === '') {
                            $dateOfBirth = 'Not provided';
                        }
                        if ($gender === '') {
                            $gender = 'Not selected';
                        }
                        $contactText = 'Not selected';
                        if (count($contactMethods) > 0) {
                            $contactText = implode(', ', $contactMethods);
                        }
                        $details = array(
                            'Full Name' => $fullName,
                            'Student ID' => $studentId,
                            'Email Address' => $email,
                            'Password' => '******** (accepted)',
                            'Confirm Password' => '******** (matched)',
                            'Date of Birth' => $dateOfBirth,
                            'Gender' => $gender,
                            'Program of Study' => $program,
                            'Preferred Contact Method' => $contactText
                        );
                        ?>
                        <dl class="row">
                            <?php foreach ($details as $label => $value): ?>
                                <dt class="col-sm-5 mb-2"><?php echo escape($label); ?></dt>
                                <dd class="col-sm-7 verification-value"><?php echo escape($value); ?></dd>
                            <?php endforeach; ?>
                        </dl>
                        <p class="text-secondary small">Passwords are masked. This assignment demonstration does not save registration data. Login uses the supplied demo credentials.</p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="login.html" class="btn btn-primary">Go to Login</a>
                            <a href="index.html" class="btn btn-secondary">Back to Enrollment</a>
                        </div>
                    <?php endif; ?>
                </section>
            </div>
        </div>
    </main>
</body>
</html>
