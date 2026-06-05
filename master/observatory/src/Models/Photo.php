<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class Photo
{
    public function __construct(private PDO $db)
    {
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO photos (observation_id, sprint_id, station_id, file_path, caption)
             VALUES (:oid, :sid, :stid, :path, :caption)'
        );
        $stmt->execute([
            'oid' => $data['observation_id'] ?? null,
            'sid' => $data['sprint_id'] ?? null,
            'stid' => $data['station_id'] ?? null,
            'path' => $data['file_path'],
            'caption' => $data['caption'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function forObservation(int $observationId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM photos WHERE observation_id = :oid');
        $stmt->execute(['oid' => $observationId]);
        return $stmt->fetchAll();
    }

    public function forStation(int $stationId): array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, o.observed_at_utc, co.designation
             FROM photos p
             LEFT JOIN observations o ON o.id = p.observation_id
             LEFT JOIN celestial_objects co ON co.id = o.object_id
             WHERE p.station_id = :sid OR o.station_id = :sid2
             ORDER BY p.uploaded_at DESC'
        );
        $stmt->execute(['sid' => $stationId, 'sid2' => $stationId]);
        return $stmt->fetchAll();
    }

    public function forObject(int $objectId): array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, o.observed_at_utc, s.name AS station_name
             FROM photos p
             JOIN observations o ON o.id = p.observation_id
             JOIN stations s ON s.id = o.station_id
             WHERE o.object_id = :oid ORDER BY p.uploaded_at DESC'
        );
        $stmt->execute(['oid' => $objectId]);
        return $stmt->fetchAll();
    }

    public function forSprint(int $sprintId): array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, s.name AS station_name FROM photos p
             LEFT JOIN stations s ON s.id = p.station_id
             WHERE p.sprint_id = :sid ORDER BY p.uploaded_at DESC'
        );
        $stmt->execute(['sid' => $sprintId]);
        return $stmt->fetchAll();
    }
}
