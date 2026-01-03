<?php

$db = new PDO('mysql:host=127.0.0.1;port=3306;dbname=ruang_aksara_db', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get Kipli
$kipli = $db->query("SELECT id, points FROM users WHERE email = 'kipli@example.com'")->fetch(PDO::FETCH_ASSOC);
if (!$kipli) {
    echo "Kipli not found\n";
    exit(1);
}

echo "Kipli current points: " . $kipli['points'] . "\n\n";

// Delete the 150 points
echo "Deleting 150 points from Kipli...\n";
$stmt = $db->prepare("UPDATE users SET points = 0 WHERE id = ?");
$stmt->execute([$kipli['id']]);

// Delete points_logs entry
echo "Deleting points_logs entry...\n";
$stmt = $db->prepare("DELETE FROM points_logs WHERE user_id = ?");
$stmt->execute([$kipli['id']]);

// Verify
$kipli = $db->query("SELECT id, points FROM users WHERE email = 'kipli@example.com'")->fetch(PDO::FETCH_ASSOC);
$logs = $db->prepare("SELECT COUNT(*) as cnt FROM points_logs WHERE user_id = ?");
$logs->execute([$kipli['id']]);
$logCount = $logs->fetch(PDO::FETCH_ASSOC)['cnt'];

echo "\n✅ Kipli's points sekarang: " . $kipli['points'] . "\n";
echo "✅ Points logs entries: " . $logCount . "\n";
?>
