<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

final class ChartController extends AbstractController
{
    #[Route('/chart', name: 'chart')]
    public function chart(): Response
    {
        return $this->render('home/dinochart.html.twig');
    }

    #[Route('/chart_api', name: 'chart_api')]
    public function chartAPI(HttpClientInterface $httpClient, ChartBuilderInterface $chartBuilder): Response
    {
        $response = $httpClient->request('GET', 'https://api.nasa.gov/insight_weather/?api_key=BEQa1bbvdnsfUajBWcCVCCogF6oCmakM6ab2jrXW&feedtype=json&ver=1.0');
        $data = json_decode($response->getContent(), true);

        $solKeys = $data['sol_keys'];
        $minTemps = array_map(fn($sol) => $data[$sol]['AT']['mn'], $solKeys);
        $maxTemps = array_map(fn($sol) => $data[$sol]['AT']['mx'], $solKeys);
        $avgWindSpeeds = array_map(fn($sol) => $data[$sol]['HWS']['av'], $solKeys);

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $solKeys,
            'datasets' => [
                [
                    'label' => 'Température Minimale (°C)',
                    'data' => $minTemps,
                    'borderColor' => 'blue',
                    'fill' => false,
                    'tension' => 0.4
                ],
                [
                    'label' => 'Température Maximale (°C)',
                    'data' => $maxTemps,
                    'borderColor' => 'red',
                    'fill' => false,
                    'tension' => 0.4
                ],
            ]
        ]);

        $chart->setOptions([
            'maintainAspectRatio' => false,
            "plugins" => [
                "title" => [
                    "display" => true,
                    "text" => "Températures sur Mars par jour martien (Sol)"
                ]
            ],
            "scales" => [
                "x" => [
                    "title" => [
                        "display" => true,
                        "text" => "Jour martien (Sol)"
                    ]
                ],
                "y" => [
                    "title" => [
                        "display" => true,
                        "text" => "Température (°C)"
                    ]
                ]
            ]
        ]);

        $windChart = $chartBuilder->createChart(Chart::TYPE_BAR);
        $windChart->setData([
            'labels' => $solKeys,
            'datasets' => [
                [
                    'label' => 'Vitesse moyenne du vent (m/s)',
                    'data' => [...$avgWindSpeeds,...$avgWindSpeeds,...$avgWindSpeeds,...$avgWindSpeeds,...$avgWindSpeeds,...$avgWindSpeeds,...$avgWindSpeeds,...$avgWindSpeeds,...$avgWindSpeeds,...$avgWindSpeeds,],
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1
                ]
            ]
        ]);

        $windChart->setOptions([
            'maintainAspectRatio' => false,
            "plugins" => [
                "title" => [
                    "display" => true,
                    "text" => "Vitesse moyenne du vent sur Mars (m/s)"
                ]
            ],
            "scales" => [
                "x" => [
                    "title" => [
                        "display" => true,
                        "text" => "Jour martien (Sol)"
                    ]
                ],
                "y" => [
                    "title" => [
                        "display" => true,
                        "text" => "Vitesse du vent (m/s)"
                    ],
                    'beginAtZero' => true
                ]
            ]
        ]);

        return $this->render('home/chart.html.twig', [
            'chart' => $chart,
            'windChart' => $windChart,
        ]);
    }
}
