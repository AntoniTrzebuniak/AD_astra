<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\View;
use App\Models\OccultationEvent;
use App\Models\ObservationPlan;
use App\Models\Sprint;
use App\Models\Station;
use PDO;

class DashboardController
{
    public function __construct(private PDO $db)
    {
    }

    public function index(): void
    {
        $nightDate = getTonightDate();
        $eventModel = new OccultationEvent($this->db);
        $planModel = new ObservationPlan($this->db);
        $stationModel = new Station($this->db);
        $sprintModel = new Sprint($this->db);
        $sprintModel->updateStatuses();

        View::render('dashboard/index', [
            'title' => 'Centrum Obserwacji',
            'nightDate' => $nightDate,
            'events' => $eventModel->upcoming(10),
            'plans' => $planModel->allForNight($nightDate),
            'stations' => $stationModel->all(),
            'sprints' => array_merge($sprintModel->all('active'), $sprintModel->all('upcoming')),
        ]);
    }
}
