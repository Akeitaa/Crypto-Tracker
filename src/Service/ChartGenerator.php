<?php

namespace App\Service;

use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartGenerator
{
    /**
     * @var ChartBuilderInterface
     */
    protected ChartBuilderInterface $chartBuilder;

    public function __construct(ChartBuilderInterface $chartBuilder)
    {
        $this->chartBuilder = $chartBuilder;
    }

    public function getChart(array $dailyValuations): Chart
    {
        $labels = $this->getDateForChart($dailyValuations);
        $data = $this->getValueForChart($dailyValuations);

        //Set up the graph
        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Vos Gains',
                    'backgroundColor' => '#efefef',
                    'borderColor' => '#1fc36c',
                    'data' => $data,
                ],
            ],
        ]);

        $chart->setOptions([]);

        return $chart;
    }

    private function getDateForChart(array $demands): array
    {
        $labels = [0];

        foreach ($demands as $demand)
        {
            $labels[] = $demand->getCreatedAt()->format('Y-m-d');
        }

        return $labels;
    }

    private function getValueForChart(array $demands): array
    {
        $data = [0];

        foreach ($demands as $demand)
        {
            $data[] = $demand->getAmount();
        }

        return $data;
    }
}