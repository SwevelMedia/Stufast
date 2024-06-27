<?php

/**
 * Returns CodeIgniter's version.
 */
function send_email($to, $subject, $message)
{
    $email = \Config\Services::email();
    $email->setTo($to);
    $email->setSubject($subject);
    $email->setMessage($message);
    $email->send();
}
