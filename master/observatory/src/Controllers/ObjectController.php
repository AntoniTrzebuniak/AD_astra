<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\View;
use App\Models\CelestialObject;
use App\Models\Photo;
use PDO;

class ObjectController
{
    public function __construct(private PDO $db)
    {
    }

    public function index(): void
    {
        $objectModel = new CelestialObject($this->db);
        View::render('objects/index', [
            'title' => 'Obiekty obserwowane',
            'objects' => $objectModel->all(),
        ]);
    }

    public function show(int $id): void
    {
        $objectModel = new CelestialObject($this->db);
        $photoModel = new Photo($this->db);
        $object = $objectModel->find($id);
        if (!$object) {
            http_response_code(404);
            View::render('errors/404', ['title' => 'Nie znaleziono']);
            return;
        }
        View::render('objects/show', [
            'title' => $object['designation'],
            'object' => $object,
            'events' => $objectModel->getEvents($id),
            'observations' => $objectModel->getObservations($id),
            'photos' => $photoModel->forObject($id),
        ]);
    }
}
