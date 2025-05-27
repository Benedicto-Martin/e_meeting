<?php
require '../config/db.php';
require '../config/google-config.php';
$id = $_GET['id'];

// Ambil event_id dari database
$result = $conn2->query("SELECT event_id FROM meetings WHERE id=$id");
$meeting = $result->fetch_assoc();
$eventId = $meeting['event_id'];

// Hapus dari database
$conn2->query("DELETE FROM meetings WHERE id=$id");

// Hapus dari Google Calendar
$client->setAccessToken($_SESSION['access_token']);
$calendarService = new Google_Service_Calendar($client);
$calendarService->events->delete('primary', $eventId);
echo "Jadwal meeting berhasil dihapus dari aplikasi dan Google Calendar.";
?>