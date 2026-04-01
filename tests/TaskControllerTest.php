<?php

namespace Tests\Controllers;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Controllers\TaskController;
use App\Models\TaskModel;

class TaskControllerTest extends TestCase
{
    private $mockModel;
    private $mockTemplate;
    private TaskController $controller;

    protected function setUp(): void
    {
        $_GET     = [];
        $_SESSION = [];

        $this->mockModel    = $this->createMock(TaskModel::class);
        $this->mockTemplate = $this->createMock(\Twig\Environment::class);

        // Le modèle est injecté via le constructeur (nécessite la modif ci-dessous)
        $this->controller = new TaskController(
            $this->mockTemplate,
            $this->mockModel
        );
    }

    public function testOffresPageRendersTemplateWithExpectedData(): void
    {
        // Arrange
        $_GET = ['p' => '1', 'q' => ''];

        $this->mockModel->method('getTotalCount')->willReturn(2);
        $this->mockModel->method('getPaginatedOffres')->willReturn([
            ['id_offre' => 1, 'titre' => 'Stage Développeur PHP'],
            ['id_offre' => 2, 'titre' => 'Stage Data Analyst'],
        ]);
        $this->mockModel->method('getCompetencesByOffreId')->willReturn([
            ['nom_competence' => 'PHP'],
        ]);
        $this->mockModel->method('getAllCompetences')->willReturn([]);

        $this->mockTemplate
            ->expects($this->once())
            ->method('render')
            ->with(
                'offres.twig.html',
                $this->anything()
            )
            ->willReturn('');

        // Act
        $this->controller->offresPage();
    }
}