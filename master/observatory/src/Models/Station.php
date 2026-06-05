<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class Station
{
    public function __construct(private PDO $db)
    {
    }

    public function all(): array
    {
        return $this->db->query(
            'SELECT s.*, u.name AS owner_name FROM stations s
             JOIN users u ON u.id = s.user_id
             WHERE s.is_active = 1 ORDER BY s.name'
        )->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT s.*, u.name AS owner_name, u.email AS owner_email
             FROM stations s JOIN users u ON u.id = s.user_id
             WHERE s.id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findByUserId(int $userId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM stations WHERE user_id = :uid LIMIT 1');
        $stmt->execute(['uid' => $userId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(int $userId, array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO stations (user_id, name, latitude, longitude, altitude_m, equipment, description)
             VALUES (:uid, :name, :lat, :lng, :alt, :equip, :desc)'
        );
        $stmt->execute([
            'uid' => $userId,
            'name' => $data['name'],
            'lat' => $data['latitude'],
            'lng' => $data['longitude'],
            'alt' => $data['altitude_m'] ?? 0,
            'equip' => $data['equipment'] ?? null,
            'desc' => $data['description'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            'UPDATE stations SET name=:name, latitude=:lat, longitude=:lng,
             altitude_m=:alt, equipment=:equip, description=:desc WHERE id=:id'
        );
        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'lat' => $data['latitude'],
            'lng' => $data['longitude'],
            'alt' => $data['altitude_m'] ?? 0,
            'equip' => $data['equipment'] ?? null,
            'desc' => $data['description'] ?? null,
        ]);
    }

    public function getObservations(int $stationId): array
    {
        $stmt = $this->db->prepare(
            'SELECT o.*, co.designation, co.name AS object_name
             FROM observations o
             JOIN celestial_objects co ON co.id = o.object_id
             WHERE o.station_id = :sid ORDER BY o.observed_at_utc DESC'
        );
        $stmt->execute(['sid' => $stationId]);
        return $stmt->fetchAll();
    }

    public function getPlansForNight(string $nightDate): array
    {
        $stmt = $this->db->prepare(
            'SELECT op.*, s.name AS station_name, s.latitude, s.longitude,
                    co.designation, oe.predicted_time_utc
             FROM observation_plans op
             JOIN stations s ON s.id = op.station_id
             JOIN occultation_events oe ON oe.id = op.event_id
             JOIN celestial_objects co ON co.id = oe.object_id
             WHERE op.night_date = :night AND op.status NOT IN ("cancelled")
             ORDER BY op.planned_start_utc'
        );
        $stmt->execute(['night' => $nightDate]);
        return $stmt->fetchAll();
    }

    public function getMapData(string $nightDate): array
    {
        $stations = $this->all();
        $plans = $this->getPlansForNight($nightDate);
        $plansByStation = [];
        foreach ($plans as $plan) {
            $plansByStation[$plan['station_id']][] = $plan;
        }
        foreach ($stations as &$station) {
            $station['plans'] = $plansByStation[$station['id']] ?? [];
        }
        return $stations;
    }
}
