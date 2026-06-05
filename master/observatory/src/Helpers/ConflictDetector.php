<?php

declare(strict_types=1);

namespace App\Helpers;

use PDO;

class ConflictDetector
{
    public function __construct(private PDO $db)
    {
    }

    public function findConflicts(
        int $eventId,
        int $stationId,
        string $startUtc,
        string $endUtc,
        ?int $excludePlanId = null
    ): array {
        $sql = 'SELECT op.*, s.name AS station_name, co.designation
                FROM observation_plans op
                JOIN stations s ON s.id = op.station_id
                JOIN occultation_events oe ON oe.id = op.event_id
                JOIN celestial_objects co ON co.id = oe.object_id
                WHERE op.event_id = :event_id
                  AND op.station_id != :station_id
                  AND op.status NOT IN ("cancelled", "completed")
                  AND op.planned_start_utc < :end_utc
                  AND op.planned_end_utc > :start_utc';

        $params = [
            'event_id' => $eventId,
            'station_id' => $stationId,
            'start_utc' => $startUtc,
            'end_utc' => $endUtc,
        ];

        if ($excludePlanId !== null) {
            $sql .= ' AND op.id != :exclude_id';
            $params['exclude_id'] = $excludePlanId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function suggestFreeEvents(string $nightDate, int $stationId): array
    {
        $sql = 'SELECT oe.*, co.designation, co.name AS object_name
                FROM occultation_events oe
                JOIN celestial_objects co ON co.id = oe.object_id
                WHERE oe.status = "upcoming"
                  AND DATE(oe.predicted_time_utc) BETWEEN :night_date AND DATE_ADD(:night_date2, INTERVAL 1 DAY)
                  AND oe.id NOT IN (
                      SELECT event_id FROM observation_plans
                      WHERE night_date = :night_date3
                        AND status NOT IN ("cancelled", "completed")
                  )
                ORDER BY oe.predicted_time_utc ASC
                LIMIT 10';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'night_date' => $nightDate,
            'night_date2' => $nightDate,
            'night_date3' => $nightDate,
        ]);
        return $stmt->fetchAll();
    }
}
