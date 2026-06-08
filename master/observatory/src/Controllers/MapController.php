<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\View;
use App\Models\Station;
use PDO;

class MapController
{
    public function __construct(private PDO $db)
    {
    }

    public function index(): void
    {
        View::render('map/index', ['title' => 'Mapa stacji']);
    }

    public function apiData(): void
    {
        $nightDate = getTonightDate();
        $stationModel = new Station($this->db);
        $data = $stationModel->getMapData($nightDate);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
