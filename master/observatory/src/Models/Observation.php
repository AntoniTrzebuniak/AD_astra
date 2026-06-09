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
        $stmt->bindValue('sid', $data['station_id'], PDO::PARAM_INT);
        $stmt->bindValue('oid', $data['object_id'], PDO::PARAM_INT);
        $stmt->bindValue('pid', $data['plan_id'] ?? null, $data['plan_id'] ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindValue('obs_at', $data['observed_at_utc']);
        $stmt->bindValue('result', $data['result']);
        $stmt->bindValue('dur', $data['duration_ms'] ?? null, isset($data['duration_ms']) ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindValue('notes', $data['notes'] ?? null);
        $stmt->execute();
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
