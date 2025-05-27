<?php
require '../config/db.php';
$id = $_GET['id'];
$result = $conn2->query("SELECT * FROM meetings WHERE id = $id");
$meeting = $result->fetch_assoc();
?>
<form action="update_meeting.php" method="POST">
    <input type="hidden" name="id" value="<?=$meeting['id']; ?>">
    <label>Judul Meeting:</label>
    <input type="text" name="title" value="<?=$meeting['title']; ?>">
    <label>Deskripsi:</label>
    <textarea name="description"><?=$meeting['description']; ?></textarea>
    <label>Tanggal & Waktu:</label>
    <input type="datetime-local" name="date_time"value="<?= date('Y-m-d\TH:i', strtotime($meeting['date_time'])); ?>">
    <button type="submit">Simpan Perubahan</button>
</form>