<?php
# ===============================================
# MODEL: ROOM (FINAL, SESUAI STRUKTUR TABEL KAMU)
# ===============================================

class Room extends Model
{
    protected $table = 'room';   # ← ini yang benar

    # Ambil semua ruangan
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY nama_ruangan ASC";
        return $this->query($sql)->fetchAll();
    }

    # Ambil ruangan populer (opsional kalau punya rating)
    public function getPopular()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY room_id ASC LIMIT 3";
        return $this->query($sql)->fetchAll();
    }

    # Ambil berdasarkan ID
    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE room_id = ?";
        return $this->query($sql, [$id])->fetch();
    }

    # Tambah ruangan
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table}
                (gambar_ruangan, nama_ruangan, kapasitas_min, kapasitas_max, deskripsi, status)
                VALUES (?, ?, ?, ?, ?, 'Tersedia')";

        return $this->query($sql, [
            $data['gambar_ruangan'],
            $data['nama_ruangan'],
            $data['kapasitas_min'],
            $data['kapasitas_max'],
            $data['deskripsi']
        ]);
    }

    # Update ruangan
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table}
                SET gambar_ruangan=?, nama_ruangan=?, kapasitas_min=?, kapasitas_max=?, deskripsi=?
                WHERE room_id=?";

        return $this->query($sql, [
            $data['gambar_ruangan'],
            $data['nama_ruangan'],
            $data['kapasitas_min'],
            $data['kapasitas_max'],
            $data['deskripsi'],
            $id
        ]);
    }

    # Update status ruangan
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE {$this->table}
                SET status = ?
                WHERE room_id = ?";

        return $this->query($sql, [$status, $id]);
    }
}
