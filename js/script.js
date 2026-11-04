var enrollmentForm = document.getElementById('enrollment-form');
var fieldNames = ['full_name', 'student_id', 'email', 'password', 'confirm_password', 'program'];

function showError(fieldName, message) {
    var field = document.getElementById(fieldName);
    document.getElementById(fieldName + '-error').textContent = message;

    if (message !== '') {
        field.classList.add('is-invalid');
        field.setAttribute('aria-invalid', 'true');
    } else {
        field.classList.remove('is-invalid');
        field.setAttribute('aria-invalid', 'false');
    }
}

function clearErrors() {
    for (var i = 0; i < fieldNames.length; i++) {
        showError(fieldNames[i], '');
    }
}

function validateEnrollment(event) {
    clearErrors();
    var valid = true;
    var fullName = document.getElementById('full_name').value.trim();
    var studentId = document.getElementById('student_id').value.trim();
    var email = document.getElementById('email').value.trim();
    var password = document.getElementById('password').value;
    var confirmation = document.getElementById('confirm_password').value;
    var program = document.getElementById('program').value;

    if (fullName === '') {
        showError('full_name', 'Full Name cannot be empty.');
        valid = false;
    }
    if (!/^ST[0-9]{5}$/.test(studentId)) {
        showError('student_id', 'Use ST followed by exactly 5 digits (e.g., ST12345).');
        valid = false;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showError('email', 'Enter a valid email address.');
        valid = false;
    }
    if (!/^[\s\S]{8,}$/u.test(password) || !/[A-Z]/.test(password) ||
        !/[a-z]/.test(password) || !/[0-9]/.test(password)) {
        showError('password', 'Use at least 8 characters with uppercase, lowercase, and a number.');
        valid = false;
    }
    if (confirmation === '' || confirmation !== password) {
        showError('confirm_password', 'Passwords must match.');
        valid = false;
    }
    if (program === '') {
        showError('program', 'Select a Program of Study.');
        valid = false;
    }

    if (!valid) {
        event.preventDefault();
        enrollmentForm.querySelector('.is-invalid').focus();
    } else if (!enrollmentForm.checkValidity()) {
        event.preventDefault();
        enrollmentForm.reportValidity();
    }
}

if (enrollmentForm) {
    enrollmentForm.noValidate = true;
    enrollmentForm.addEventListener('submit', validateEnrollment);
    enrollmentForm.addEventListener('reset', clearErrors);
}
