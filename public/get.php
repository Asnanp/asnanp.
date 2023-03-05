<?php if (isset($message)) : ?>
    <div class="alert alert-success">
        <?= $message ?>
    </div>
<?php endif ?>

<form action="index.php" method="post">
    <header>
        <h1>Send Us a Message</h1>
    </header>

    <div>
        <label for="name">Name:</label>
        <input type="text" value="<?= $inputs['name'] ?? '' ?>" name="name" id="name" placeholder="Full name">
        <small><?= $errors['name'] ?? '' ?></small>
    </div>

    <div>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?= $inputs['email'] ?? '' ?>" placeholder="Email address">
        <small><?= $errors['email'] ?? '' ?></small>
    </div>

    <div>
        <label for="subject">Subject:</label>
        <input type="subject" name="subject" id="subject" value="<?= $inputs['subject'] ?? '' ?>" placeholder="Enter a subject">
        <small><?= $errors['subject'] ?? '' ?></small>
    </div>

    <div>
        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="5"><?= $inputs['message'] ?? '' ?></textarea>
        <small><?= $errors['message'] ?? '' ?></small>
    </div>

    <label for="nickname" aria-hidden="true" class="user-cannot-see"> Nickname
        <input type="text" name="nickname" id="nickname" class="user-cannot-see" tabindex="-1" autocomplete="off">
    </label>

    <button type="submit">Send Message</button>
</form>
<?php

session_start();

$errors = [];
$inputs = [];

$request_method = strtoupper($_SERVER['REQUEST_METHOD']);

if ($request_method === 'GET') {

    // show the message
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
    } elseif (isset($_SESSION['inputs']) && isset($_SESSION['errors'])) {
        $errors = $_SESSION['errors'];
        unset($_SESSION['errors']);
        $inputs = $_SESSION['inputs'];
        unset($_SESSION['inputs']);
    }
    // show the form
    require_once __DIR__ . '/inc/get.php';
} elseif ($request_method === 'POST') {
    // check the honeypot and validate the field
    require_once __DIR__ . '/inc/post.php';

    if (!$errors) {
        // send an email
        require_once __DIR__ . '/inc/mail.php';
        // set the message
        $_SESSION['message'] =  'Thanks for contacting us! We will be in touch with you shortly.';
    } else {
        $_SESSION['errors'] =   $errors;
        $_SESSION['inputs'] =   $inputs;
    }

    header('Location: index.php', true, 303);
    exit;
}