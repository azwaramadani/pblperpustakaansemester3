<?php
class Room {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Ambil semua data ruangan
    public function getAllRooms() {
        $query = "SELECT * FROM room ORDER BY room_id ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ambil ruangan berdasarkan kategori (optional)
    public function getRoomsByType($type) {
        $query = "SELECT * FROM room WHERE deskripsi LIKE :type";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':type', "%$type%");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
