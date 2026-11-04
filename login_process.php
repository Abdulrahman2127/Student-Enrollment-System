<?php
function generateRandomPassword($length = 12)
{
    $length = max(4, (int) $length);
    $groups = array('ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz', '0123456789', '!@#$%^&*()-_=+?');
    $characters = array();
    foreach ($groups as $group) {
        $characters[] = $group[random_int(0, strlen($group) - 1)];
    }
    $allCharacters = implode('', $groups);
    while (count($characters) < $length) {
        $characters[] = $allCharacters[random_int(0, strlen($allCharacters) - 1)];
    }
    shuffle($characters);
    return implode('', $characters);
}

$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = '';
    $password = '';
    if (isset($_POST['student_id']) && is_string($_POST['student_id'])) {
        $studentId = trim($_POST['student_id']);
    }
    if (isset($_POST['password']) && is_string($_POST['password'])) {
        $password = $_POST['password'];
    }

    if ($studentId === 'ST12345' && $password === 'Password123') {
        $success = true;
        $message = 'Login successful! Welcome, Student ST12345!';
    } else {
        http_response_code(401);
        $message = 'Invalid credentials. Please check your Student ID and Password.';
    }
} else {
    http_response_code(405);
    header('Allow: POST');
    $message = 'Please submit the login form using POST.';
}
header('Cache-Control: no-store');

$heading = 'Login unsuccessful';
$alertClass = 'alert-danger';
if ($success) {
    $heading = 'Welcome, student!';
    $alertClass = 'alert-success';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Result | COMP 315</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
</head>
<body>
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <section class="form-card">
                    <p class="text-uppercase text-primary small fw-bold">COMP 315 · Student Portal</p>
                    <h1><?php echo $heading; ?></h1>
                    <p class="alert <?php echo $alertClass; ?>" role="alert"><?php echo htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="login.html" class="btn btn-primary">Back to Login</a>
                        <a href="index.html" class="btn btn-secondary">Register Here</a>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>
