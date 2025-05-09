<?php

namespace App\Services;

use PDO;
use Exception;

class KrsService
{
    private $db;
    private $maxSKS = 24;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getMaxSKS()
    {
        return $this->maxSKS;
    }

    public function getMataKuliahList()
    {
        $stmt = $this->db->query("SELECT mk.id as mk_id, mk.nama_mk, mk.kode_mk, mk.sks, d.id as dosen_id, d.nama_dosen
                    FROM mata_kuliah mk
                    JOIN dosen d ON mk.id_dosen = d.id");
        return $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);
    }

    public function getKrsMahasiswa($idMahasiswa)
    {
        $stmt = $this->db->prepare("SELECT mata_kuliah_id FROM krs WHERE mahasiswa_id = ?");
        $stmt->execute([$idMahasiswa]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'mata_kuliah_id');
    }

    public function simpanKRS($idMahasiswa, $mataKuliah)
    {
        $selectedMK = array_keys($mataKuliah);
        $placeholders = implode(',', array_fill(0, count($selectedMK), '?'));

        $stmt = $this->db->prepare("SELECT id, sks FROM mata_kuliah WHERE id IN ($placeholders)");
        $stmt->execute($selectedMK);
        $mkData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalSKS = array_sum(array_column($mkData, 'sks'));

        if ($totalSKS > $this->maxSKS) {
            throw new Exception("Total SKS yang diambil melebihi batas maksimal ({$this->maxSKS} SKS). Saat ini: $totalSKS SKS.");
        }

        // Hapus KRS lama
        $stmt = $this->db->prepare("DELETE FROM krs WHERE mahasiswa_id = ?");
        $stmt->execute([$idMahasiswa]);

        // Tambahkan KRS baru
        if (!empty($mataKuliah)) {
            $stmt = $this->db->prepare("INSERT INTO krs (mahasiswa_id, mata_kuliah_id, dosen_id) VALUES (?, ?, ?)");
            foreach ($mataKuliah as $mk_id => $dosen_id) {
                $stmt->execute([$idMahasiswa, $mk_id, $dosen_id]);
            }
        }
    }
}
