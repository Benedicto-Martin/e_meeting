<form action="process_meeting.php" method="POST">
    <label>Judul Meeting:</label>
    <input type="text" name="title" required><br>
    <label>Deskripsi:</label>
    <textarea name="description"></textarea><br>
    <label>Waktu Meeting:</label>
    <input type="datetime-local" name="date_time"required><br>
    <label>Lokasi:</label>
    <input type="text" name="location"><br>
    <button type="submit">Simpan Jadwal</button>
</form>