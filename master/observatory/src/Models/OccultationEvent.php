<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class OccultationEvent
{
    public function __construct(private PDO $db)
    {
    }

    public function upcoming(int $limit = 20): array
    {
        $stmt = $this->db->prepare(
            'SELECT oe.*, co.designation, co.name AS object_name
             FROM occultation_events oe
             JOIN celestial_objects co ON co.id = oe.object_id
             WHERE oe.status = "upcoming" AND oe.predicted_time_utc >= UTC_TIMESTAMP()
             ORDER BY oe.predicted_time_utc ASC LIMIT :lim'
        );
        $stmt->bindValue('lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT oe.*, co.designation, co.name AS object_name
             FROM occultation_events oe
             JOIN celestial_objects co ON co.id = oe.object_id
             WHERE oe.id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO occultation_events (object_id, star_designation, star_magnitude,
             predicted_time_utc, duration_ms, path_uncertainty_km, magnitude_drop)
             VALUES (:oid, :star, :smag, :ptime, :dur, :path, :mdrop)'
        );
        $stmt->execute([
            'oid' => $data['object_id'],
            'star' => $data['star_designation'],
            'smag' => $data['star_magnitude'] ?? null,
            'ptime' => $data['predicted_time_utc'],
            'dur' => $data['duration_ms'] ?? 5000,
            'path' => $data['path_uncertainty_km'] ?? null,
            'mdrop' => $data['magnitude_drop'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function all(): array
    {
        return $this->db->query(
            'SELECT oe.*, co.designation FROM occultation_events oe
             JOIN celestial_objects co ON co.id = oe.object_id
             ORDER BY oe.predicted_time_utc DESC'
        )->fetchAll();
    }

    public function forTonight(string $nightDate): array
    {
        $stmt = $this->db->prepare(
            'SELECT oe.*, co.designation, co.name AS object_name
             FROM occultation_events oe
             JOIN celestial_objects co ON co.id = oe.object_id
             WHERE oe.status = "upcoming"
               AND DATE(oe.predicted_time_utc) BETWEEN :night AND DATE_ADD(:night2, INTERVAL 1 DAY)
             ORDER BY oe.predicted_time_utc'
        );
        $stmt->execute(['night' => $nightDate, 'night2' => $nightDate]);
        return $stmt->fetchAll();
    }
}
