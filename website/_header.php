<?php ?>
<head>
  <meta charset="utf-8">
  <title>Home Page</title>
  <?php
  header("Strict-Transport-Security: max-age=31536000 ; includeSubDomains");
  header("X-Frame-Options: deny");
  //header("X-XSS-Protection: 1; mode=block");
  header("X-Content-Type-Options: nosniff");
  //header("Content-Security-Policy: script-src 'self'");
  header("X-Permitted-Cross-Domain-Policies: none");
  header("Referrer-Policy: no-referrer");
  header("Expect-CT: max-age=86400, enforce");
  header("Cache-control: no-store");
  header("Pragma: no-cache");
?>
<?php if (file_exists(__DIR__ . '/css/bootstrap.css')): ?>
  <link rel="stylesheet" href="/css/bootstrap.css">
<?php else: ?>
  <?php die('Something went wrong! Please check your directory and try again.'); ?>
<?php endif; ?>
<?php if (file_exists(__DIR__ . '/css/custom.css')): ?>
  <link rel="stylesheet" href="/css/custom.css">
<?php else: ?>
  <?php die('Something went wrong! Please check your directory and try again.'); ?>
<?php endif; ?>
<?php if (file_exists(__DIR__ . '/js/jquery-3.4.1.min.js')): ?>
  <script src="/js/jquery-3.4.1.min.js"></script>
<?php else: ?>
  <?php die('Something went wrong! Please check your directory and try again.'); ?>
<?php endif; ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
