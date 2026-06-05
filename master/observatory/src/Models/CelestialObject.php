<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class CelestialObject
{
    public function __construct(private PDO $db)
    {
    }

    public function all(): array
    {
        return $this->db->query(
            'SELECT co.*,
                (SELECT COUNT(*) FROM occultation_events oe WHERE oe.object_id = co.id AND oe.status = "upcoming") AS upcoming_events
             FROM celestial_objects co ORDER BY co.designation'
        )->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM celestial_objects WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO celestial_objects (designation, name, type, magnitude, diameter_km, notes)
             VALUES (:desig, :name, :type, :mag, :diam, :notes)'
        );
        $stmt->execute([
            'desig' => $data['designation'],
            'name' => $data['name'] ?? null,
            'type' => $data['type'] ?? 'asteroid',
            'mag' => $data['magnitude'] ?? null,
            'diam' => $data['diameter_km'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            'UPDATE celestial_objects SET designation=:desig, name=:name, type=:type,
             magnitude=:mag, diameter_km=:diam, notes=:notes WHERE id=:id'
        );
        $stmt->execute([
            'id' => $id,
            'desig' => $data['designation'],
            'name' => $data['name'] ?? null,
            'type' => $data['type'] ?? 'asteroid',
            'mag' => $data['magnitude'] ?? null,
            'diam' => $data['diameter_km'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM celestial_objects WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function getEvents(int $objectId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM occultation_events WHERE object_id = :oid ORDER BY predicted_time_utc DESC'
        );
        $stmt->execute(['oid' => $objectId]);
        return $stmt->fetchAll();
    }

    public function getObservations(int $objectId): array
    {
        $stmt = $this->db->prepare(
            'SELECT o.*, s.name AS station_name
             FROM observations o
             JOIN stations s ON s.id = o.station_id
             WHERE o.object_id = :oid ORDER BY o.observed_at_utc DESC'
        );
        $stmt->execute(['oid' => $objectId]);
        return $stmt->fetchAll();
    }
}
