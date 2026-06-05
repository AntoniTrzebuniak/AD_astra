<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class Sprint
{
    public function __construct(private PDO $db)
    {
    }

    public function updateStatuses(): void
    {
        $this->db->exec(
            'UPDATE sprints SET status = "active"
             WHERE status = "upcoming" AND start_utc <= UTC_TIMESTAMP() AND end_utc > UTC_TIMESTAMP()'
        );
        $this->db->exec(
            'UPDATE sprints SET status = "completed"
             WHERE status IN ("upcoming", "active") AND end_utc <= UTC_TIMESTAMP()'
        );
    }

    public function all(?string $status = null): array
    {
        $sql = 'SELECT sp.*, co.designation, co.name AS object_name, u.name AS creator_name,
                       (SELECT COUNT(*) FROM sprint_registrations sr WHERE sr.sprint_id = sp.id) AS participants
                FROM sprints sp
                JOIN celestial_objects co ON co.id = sp.target_object_id
                JOIN users u ON u.id = sp.creator_id';
        if ($status) {
            $sql .= ' WHERE sp.status = ' . $this->db->quote($status);
        }
        $sql .= ' ORDER BY sp.start_utc DESC';
        return $this->db->query($sql)->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT sp.*, co.designation, co.name AS object_name, u.name AS creator_name
             FROM sprints sp
             JOIN celestial_objects co ON co.id = sp.target_object_id
             JOIN users u ON u.id = sp.creator_id
             WHERE sp.id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO sprints (creator_id, target_object_id, title, description, start_utc, end_utc)
             VALUES (:cid, :oid, :title, :desc, :start, :end)'
        );
        $stmt->execute([
            'cid' => $data['creator_id'],
            'oid' => $data['target_object_id'],
            'title' => $data['title'],
            'desc' => $data['description'] ?? null,
            'start' => $data['start_utc'],
            'end' => $data['end_utc'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function register(int $sprintId, int $stationId): bool
    {
        try {
            $stmt = $this->db->prepare(
                'INSERT INTO sprint_registrations (sprint_id, station_id) VALUES (:sid, :stid)'
            );
            $stmt->execute(['sid' => $sprintId, 'stid' => $stationId]);
            return true;
        } catch (\PDOException) {
            return false;
        }
    }

    public function isRegistered(int $sprintId, int $stationId): bool
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM sprint_registrations WHERE sprint_id = :sid AND station_id = :stid'
        );
        $stmt->execute(['sid' => $sprintId, 'stid' => $stationId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function getParticipants(int $sprintId): array
    {
        $stmt = $this->db->prepare(
            'SELECT sr.*, s.name AS station_name, s.latitude, s.longitude
             FROM sprint_registrations sr
             JOIN stations s ON s.id = sr.station_id
             WHERE sr.sprint_id = :sid ORDER BY sr.registered_at'
        );
        $stmt->execute(['sid' => $sprintId]);
        return $stmt->fetchAll();
    }

    public function getSummary(int $sprintId): array
    {
        $sprint = $this->find($sprintId);
        if (!$sprint) {
            return [];
        }
        $participants = $this->getParticipants($sprintId);
        $photoModel = new Photo($this->db);
        $photos = $photoModel->forSprint($sprintId);
        return [
            'sprint' => $sprint,
            'participants' => $participants,
            'photos' => $photos,
            'photo_count' => count($photos),
            'participant_count' => count($participants),
        ];
    }
}
