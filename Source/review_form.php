<?php
require_once('lib/Review.php');
session_start();

if ((string)$_SESSION['rand'] !== $_POST['captcha']) {
    die('Неправильно введена капча!');
}

if (isset($_POST['username']) && isset($_POST['subject']) && isset($_POST['review'])) {
    $review = new Review();
    $review->createReview((string)$_POST['username'],
        (string)$_POST['review'],
        (int)$_POST['subject'],
        $_FILES['image'],
        (int)$_POST['likeCounter']);
}
header('Location: ' . $_SERVER['HTTP_REFERER']);
