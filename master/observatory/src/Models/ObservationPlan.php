<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class ObservationPlan
{
    public function __construct(private PDO $db)
    {
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO observation_plans (station_id, event_id, night_date,
             planned_start_utc, planned_end_utc, status, notes)
             VALUES (:sid, :eid, :night, :start, :end, :status, :notes)'
        );
        $stmt->execute([
            'sid' => $data['station_id'],
            'eid' => $data['event_id'],
            'night' => $data['night_date'],
            'start' => $data['planned_start_utc'],
            'end' => $data['planned_end_utc'],
            'status' => $data['status'] ?? 'planned',
            'notes' => $data['notes'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function forStationNight(int $stationId, string $nightDate): array
    {
        $stmt = $this->db->prepare(
            'SELECT op.*, co.designation, oe.predicted_time_utc, oe.star_designation
             FROM observation_plans op
             JOIN occultation_events oe ON oe.id = op.event_id
             JOIN celestial_objects co ON co.id = oe.object_id
             WHERE op.station_id = :sid AND op.night_date = :night
             ORDER BY op.planned_start_utc'
        );
        $stmt->execute(['sid' => $stationId, 'night' => $nightDate]);
        return $stmt->fetchAll();
    }

    public function allForNight(string $nightDate): array
    {
        $stmt = $this->db->prepare(
            'SELECT op.*, s.name AS station_name, co.designation
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

    public function exportCsv(string $nightDate): string
    {
        $plans = $this->allForNight($nightDate);
        $lines = ['Stacja,Planetoida,Start UTC,Koniec UTC,Status'];
        foreach ($plans as $p) {
            $lines[] = sprintf(
                '%s,%s,%s,%s,%s',
                $p['station_name'],
                $p['designation'],
                $p['planned_start_utc'],
                $p['planned_end_utc'],
                $p['status']
            );
        }
        return implode("\n", $lines);
    }
}
