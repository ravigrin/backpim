<?php

declare(strict_types=1);

namespace Mobzio\EndPoint\Api\V1;

use Shared\Domain\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Mobzio\Application\Query\Statistic\GetAll\Query as StatisticGetAllQuery;

final class StatisticController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus
    )
    {
    }

    #[Route('/statistic', name: 'statistic', methods: 'POST')]
    #[IsGranted('ROLE_MOBZIO_SHOW', message: 'no rights to watch statistic')]
    public function list(Request $request): JsonResponse
    {
        $filter = $request->request->all('filter');

        return $this->json($this->queryBus->dispatch(
            new StatisticGetAllQuery(
                linkId: $request->request->getString('linkId'),
                dateFrom: $filter['dateFrom'],
                dateTo: $filter['dateTo'],
                page: $filter['page'],
                perPage: $filter['perPage']
            )
        ));
    }
}
