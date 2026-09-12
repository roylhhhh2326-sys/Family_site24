<?php
// صفحة اختبار بسيطة - تتأكد بيها إن PHP و MySQL شغالين صح قبل ما تروح لموقع العيلة الكامل
header('Content-Type: text/html; charset=utf-8');

$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'family_site';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>صفحة اختبار</title>
<style>
  body { font-family: Tahoma, sans-serif; background:#f6f4f0; padding: 30px; }
  .box { background:#fff; border:1px solid #e6e0d8; border-radius:10px; padding:20px; max-width:600px; margin-bottom:16px; }
  .ok { color: #2e7d32; font-weight:bold; }
  .fail { color: #c0392b; font-weight:bold; }
</style>
</head>
<body>

<div class="box">
  <h2>1) هل PHP شغال؟</h2>
  <p class="ok">✔ أيوا، PHP شغال. النسخة: <?= phpversion() ?></p>
</div>

<div class="box">
  <h2>2) هل الاتصال بـ MySQL شغال؟</h2>
  <?php
  try {
      $pdo = new PDO("mysql:host=$dbHost;charset=utf8mb4", $dbUser, $dbPass, [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      ]);
      echo '<p class="ok">✔ الاتصال بـ MySQL نجح.</p>';

      // هل قاعدة البيانات family_site موجودة؟
      $stmt = $pdo->query("SHOW DATABASES LIKE '$dbName'");
      if ($stmt->fetch()) {
          echo '<p class="ok">✔ قاعدة البيانات "'.$dbName.'" موجودة.</p>';

          $pdo->exec("USE `$dbName`");
          $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
          if ($stmt->fetch()) {
              echo '<p class="ok">✔ جدول "users" موجود.</p>';
              $count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
              echo '<p class="ok">عدد الحسابات المسجلة دلوقتي: '.$count.'</p>';
          } else {
              echo '<p class="fail">✘ جدول "users" مش موجود. لازم تستورد ملف database.sql من phpMyAdmin.</p>';
          }
      } else {
          echo '<p class="fail">✘ قاعدة البيانات "'.$dbName.'" مش موجودة. روح phpMyAdmin واستورد ملف database.sql.</p>';
      }
  } catch (PDOException $e) {
      echo '<p class="fail">✘ الاتصال بـ MySQL فشل.</p>';
      echo '<p style="color:#888;font-size:13px">تفاصيل الخطأ: '.htmlspecialchars($e->getMessage()).'</p>';
      echo '<p>اتأكد إن MySQL شغال (أخضر) في XAMPP Control Panel.</p>';
  }
  ?>
</div>

<div class="box">
  <h2>النتيجة</h2>
  <p>لو كل النقط اللي فوق طلعت بعلامة ✔ خضرا، يبقى كل حاجة تمام وتقدر تروح تفتح موقع العيلة على طول.</p>
</div>

</body>
</html>
