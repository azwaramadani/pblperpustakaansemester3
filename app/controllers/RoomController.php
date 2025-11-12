<?php
require_once __DIR__ . '/../models/room.php';

class RoomController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        $roomModel = new Room($this->db);
        $rooms = $roomModel->getAllRooms();

        include __DIR__ . '/../views/user/ruangan.php';
    }
}
?>
