<?php
session_start();

require '../config/google-config.php';
require '../config/db.php';

if (!isset($_SESSION['access_token'])) {
    die("Error: Anda belum login ke Google.");
}

$client->setAccessToken($_SESSION['access_token']);
$calendarService = new Google_Service_Calendar($client);

// Ambil data dari form
$id = $_POST['id'];
$title = $_POST['title'];
$description = $_POST['description'];
$date_time = $_POST['date_time'];

// Validasi awal
if (empty($id) || empty($title) || empty($date_time)) {
    die("Error: Data tidak lengkap.");
}

// Update database
$conn2->query("UPDATE meetings SET title='$title', description='$description', date_time='$date_time' WHERE id=$id");

// Ambil event_id dari database
$result = $conn2->query("SELECT event_id FROM meetings WHERE id=$id");
$meeting = $result->fetch_assoc();
$eventId = $meeting['event_id'];

if (empty($eventId)) {
    die("Error: Meeting ini belum pernah disinkronkan ke Google Calendar.");
}

// Buat waktu mulai dan selesai dalam format ISO 8601
$startTime = date('c', strtotime($date_time));
$endTime = date('c', strtotime($date_time . ' +1 hour'));

// Ambil dan perbarui event di Google Calendar
$event = $calendarService->events->get('primary', $eventId);
$event->setSummary($title);
$event->setDescription($description);
$event->setStart(new Google_Service_Calendar_EventDateTime([
    'dateTime' => $startTime,
    'timeZone' => 'Asia/Jakarta'
]));
$event->setEnd(new Google_Service_Calendar_EventDateTime([
    'dateTime' => $endTime,
    'timeZone' => 'Asia/Jakarta'
]));

$updatedEvent = $calendarService->events->update('primary', $eventId, $event);
echo "Event berhasil diperbarui: <a href='" . $updatedEvent->htmlLink . "' target='_blank'>Lihat di Google Calendar</a>";

?>
