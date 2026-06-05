<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class Observation
{
    public function __construct(private PDO $db)
    {
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO observations (station_id, object_id, plan_id, observed_at_utc, result, duration_ms, notes)
             VALUES (:sid, :oid, :pid, :obs_at, :result, :dur, :notes)'
        );
        $stmt->execute([
            'sid' => $data['station_id'],
            'oid' => $data['object_id'],
            'pid' => $data['plan_id'] ?? null,
            'obs_at' => $data['observed_at_utc'],
            'result' => $data['result'],
            'dur' => $data['duration_ms'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT o.*, co.designation, s.name AS station_name
             FROM observations o
             JOIN celestial_objects co ON co.id = o.object_id
             JOIN stations s ON s.id = o.station_id
             WHERE o.id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
