<?php
session_start();
require '../config/db.php';
require '../config/google-config.php';

// Pastikan user sudah login ke Google
if (!isset($_SESSION['access_token'])) {
    die("Error: Anda belum login ke Google.");
}

$client->setAccessToken($_SESSION['access_token']);
$calendarService = new Google_Service_Calendar($client);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title       = $_POST['title'];
    $description = $_POST['description'];
    $date_time   = $_POST['date_time'];
    $location    = $_POST['location'];

    // Simpan ke database terlebih dahulu
    $stmt = $conn2->prepare("INSERT INTO meetings (title, description, date_time, location) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $description, $date_time, $location);

    if ($stmt->execute()) {
        $meeting_id = $stmt->insert_id; // Ambil ID terakhir yang dimasukkan

        // Buat event Google Calendar
        $event = new Google_Service_Calendar_Event([
            'summary'     => $title,
            'description' => $description,
            'start'       => [
                'dateTime' => date('c', strtotime($date_time)),
                'timeZone' => 'Asia/Jakarta',
            ],
            'end'         => [
                'dateTime' => date('c', strtotime($date_time . ' +1 hour')),
                'timeZone' => 'Asia/Jakarta',
            ],
            'location'    => $location
        ]);

        // Kirim ke Google Calendar
        $createdEvent = $calendarService->events->insert('primary', $event);
        $event_id = $createdEvent->getId(); // Ambil event_id dari Google

        // Simpan event_id ke database
        $update = $conn2->prepare("UPDATE meetings SET event_id = ? WHERE id = ?");
        $update->bind_param("si", $event_id, $meeting_id);
        $update->execute();

        echo "Jadwal Meeting berhasil disimpan dan disinkronkan ke Google Calendar!";
    } else {
        echo "Gagal menyimpan jadwal.";
    }
}
?>
