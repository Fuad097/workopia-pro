<?php

use Framework\Session;

?>


<?php $successmessage = Session::getFlashmessage('success_message') ?>
<?php if ($successmessage !== null): ?>
 <?php if (is_array($successmessage)) {
        foreach ($successmessage as $msg) {
            echo '<div class="message bg-green-100 px-1 my-3">' . htmlspecialchars($msg) . '</div>';
        }
    } else {
        echo '<div class="message bg-green-100 px-1 my-3">' . htmlspecialchars($successmessage) . '</div>';
    }?>
<?php endif; ?>


<?php $errormessage = Session::getFlashmessage('error_message') ?>
<?php if ($errormessage !== null): ?>
  <?php   if (is_array($errormessage)) {
        foreach ($errormessage as $msg) {
            echo '<div class="message bg-red-100 px-1 my-3">' . htmlspecialchars($msg) . '</div>';
        }
    } else {
        echo '<div class="message bg-red-100 px-1 my-3">' . htmlspecialchars($errormessage) . '</div>';
    }?>
<?php endif; ?>





