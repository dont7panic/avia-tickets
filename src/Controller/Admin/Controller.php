<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class Controller extends AbstractController
{
  #[Route('/admin', name: 'admin')]
  public function index(ChartBuilderInterface $chartBuilder): Response {
    // Допустим, мы получили список забронированных товаров за месяц
    // Метод для поиска можно определить в EntityRepository
    // $orders = $entityRepository->findAllByMonth()

    // $labels - список названий всех элементов
    // foreach ($orders as $order) { $labels[] = $order->getName()}
    $labels = ['Product 1', 'Product 2', 'Product 3', 'Product 4', 'Product 5', 'Product 6'];

    // $data - продолжительность всех броней в формате Unix time, умноженных на 1000, т.к.
    // JS, в отличии от PHP, считает время в милисекундах. Каждый элемент массива $data - массив,
    // первый элемент которого, дата начала аренды, второй - дата окончания
    // foreach ($orders as $order) {...}
    // { $data[] = [strtotime($order->getStartDate()) * 1000, strtotime($order->getStartDate()) * 1000] }

    $data = [
      [1627754400000, 1628532000000],
      [1628100000000, 1628964000000],
      [1628704800000, 1629223200000],
      [1628532000000, 1629568800000],
      [1628877600000, 1629828000000],
      [1629655200000, 1630260000000]
    ];

    // $startDate - дата начала графика. Можно подставить из первого заказа
    // $startDate = strtotime($orders[0]->getStartDate()) * 1000
    // или с сегодняшнего дня
    // $startDate = strtotime('today') * 1000
    $startDate = $data[0][0];

    // $endDate - дата конца графика. Можно, например, указать продолжительность в месяц (как у меня)
    $endDate = strtotime('+1 month', $startDate / 1000) * 1000;

    // Устанавливаем тип графика TYPE_BAR_HORIZONTAL для отображения в виде горизонтальных столбцов
    $chart = $chartBuilder->createChart(Chart::TYPE_BAR_HORIZONTAL);
    $chart->setData([
      'labels' => $labels,
      'datasets' => [
        [
          'data' => $data,
          // Устанавливаем цвета заливки
          'backgroundColor' => [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)'
          ],
          // Устанавливаем цвета рамки
          'borderColor' => [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)'
          ],
          // Устанавливаем толщину рамки
          'borderWidth' => 1
        ],
      ]
    ]);

    $chart->setOptions([
      // Убираем ненужные подписи
      'legend' => [
        'display' => false
      ],
      'scales' => [
        // Настройки для оси Х
        'xAxes' => [
          [
            // Подписями для оси служат дата или время
            'type' => 'time',
            // Разбиваем ось по дням
            'time' => [
              'unit' => 'day'
            ],
            // Начальная и конечная точки оси
            'ticks' => [
              'min' => $startDate,
              'max' => $endDate
            ]
          ]
        ]
      ]
    ]);

    return $this->render('admin/index.html.twig', [
      'headerLinks' => [
        'flights' => 'admin_flight_index',
        'airports' => 'admin_airport_index',
        'planes' => 'admin_plane_index',
        'users' => 'admin_user_index'
      ],
      // Отдаём график шаблону (см. в templates/admin/index.html.twig)
      'chart' => $chart
    ]);
  }
}