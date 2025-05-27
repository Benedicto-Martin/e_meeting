<a href="add_meeting.php">Tambah Jadwal Meeting</a>
<?php
require '../config/db.php';
$result = $conn2->query("SELECT * FROM meetings ORDER BY date_time ASC");
echo "<table border='1'>";
echo "<tr><th>Judul</th><th>Deskripsi</th><th>Waktu</th><th>Lokasi</th><th>Aksi</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['title'] . "</td>";
    echo "<td>" . $row['description'] . "</td>";
    echo "<td>" . $row['date_time'] . "</td>";
    echo "<td>" . $row['location'] . "</td>";
    echo "<td>
    <a href='edit_meeting.php?id=" . $row['id'] . "'>Edit</a> | 
    <a href='delete_meeting.php?id=" . $row['id'] . "' class='btn btn-danger' onclick='return confirm(\"Apakah Anda yakin?\")'>Hapus</a> | 
    <a href='../google/sync_google_calendar.php?id=" . $row['id'] . "'>Sync</a> 
</td>";
    echo "</tr>";
}
echo "</table>";
?>