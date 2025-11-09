<?php
require_once '../config/database.php';

echo "<h2>✅ Kết nối thành công tới PostgreSQL!</h2>";

$stmt = $pdo->query("SELECT current_database();");
echo "<p><b>Database hiện tại:</b> " . $stmt->fetchColumn() . "</p>";

echo "<h3>Các bảng trong database:</h3>";
$tables = $pdo->query("
    SELECT table_name FROM information_schema.tables 
    WHERE table_schema='public';
")->fetchAll(PDO::FETCH_COLUMN);

echo "<ul>";
foreach ($tables as $t) {
    echo "<li>$t</li>";
}
echo "</ul>";