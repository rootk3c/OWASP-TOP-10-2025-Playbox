<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/utils.php';

$msg = "";
$msg_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $subject = sanitize($_POST['subject']);
    $message = sanitize($_POST['message']);

    if (empty($name) || empty($email) || empty($message)) {
        $msg = "Please fill in all required fields.";
        $msg_type = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Invalid email format.";
        $msg_type = "error";
    } else {
        $log_entry = "[" . date('Y-m-d H:i:s') . "] Support Request from $email: $subject\n";
        // file_put_contents('logs/support.log', $log_entry, FILE_APPEND); // Commented out for Docker safety
        
        $msg = "Thank you, $name. Dispatch has been notified. Ticket #". rand(1000, 9999);
        $msg_type = "success";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact Support - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <nav>
        <a href="index.php">Home</a> | 
        <a href="track.php">Track Package</a> | 
        <a href="contact.php" style="text-decoration: underline;">Support</a> | 
        <a href="login.php">Login</a>
    </nav>

    <div class="container">
        <h2>📞 Driver Support / Dispatch</h2>
        <p>Use this form to report vehicle issues, schedule changes, or payroll discrepancies.</p>
        
        <?php if($msg): ?>
            <div class="alert <?php echo $msg_type == 'success' ? 'success-box' : ''; ?>">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="contact.php">
            <label>Driver Name</label>
            <input type="text" name="name" placeholder="John Doe" required>

            <label>Email / Employee ID</label>
            <input type="text" name="email" placeholder="john.d@globallogistics.local" required>

            <label>Subject</label>
            <select name="subject" style="width: 95%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="Vehicle Maintenance">Vehicle Maintenance Request</option>
                <option value="Route Issue">Route / GPS Issue</option>
                <option value="Payroll">Payroll Inquiry</option>
                <option value="Other">Other</option>
            </select>

            <label>Message</label>
            <textarea name="message" rows="5" style="width: 93%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px;" required></textarea>

            <button type="submit">Submit Ticket</button>
        </form>

        <div class="small">
            <p><strong>Emergency Dispatch Line:</strong> 1-800-555-0199 (24/7)</p>
            <p><strong>HQ Address:</strong> 404 Logistics Way, Transport City</p>
        </div>
    </div>
</body>
</html>
