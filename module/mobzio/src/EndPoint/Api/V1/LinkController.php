<?php
declare(strict_types=1);

namespace Mobzio\EndPoint\Api\V1;

use Mobzio\Infrastructure\Exception\SourceNotFound;
use Shared\Domain\Command\CommandBusInterface;
use Shared\Domain\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Mobzio\Application\Query\Link\GetAll\Query as LinkGetAllQuery;
use Mobzio\Application\Query\Link\GetAllExcel\Query as LinkGetAllExcelQuery;
use Mobzio\Application\Query\Statistic\GetOneExcel\Query as StatGetOneExcelQuery;
use Mobzio\Application\Query\Link\GetOne\Query as LinkGetOneQuery;
use Mobzio\Application\Query\Link\GetLastOne\Query as LinkGetLastOneQuery;
use Mobzio\Application\Command\MakeLink\Command as MakeLinkCommand;

final class LinkController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface   $queryBus,
        private readonly CommandBusInterface $commandBus
    )
    {
    }

    #[Route('/links', name: 'mobzio_links', methods: 'post')]
    #[IsGranted('ROLE_MOBZIO_SHOW', message: 'no rights to watch mobzio link list')]
    public function list(Request $request): JsonResponse
    {
        return $this->json($this->queryBus->dispatch(
            new LinkGetAllQuery(
                page: $request->request->getInt('page'),
                perPage: $request->request->getInt('perPage')
            )
        ));
    }

    /**
     * @throws SourceNotFound
     */
    #[Route('/excel', name: 'mobzio_link_excel', methods: 'GET')]
//    #[IsGranted('ROLE_MOBZIO_SHOW', message: 'no rights to get mobzio link')]
    public function excel(Request $request): BinaryFileResponse|null|false
    {
        $source = $request->query->get('source');
        $fileName = $source."_".date('d-m-Y-H-i').".xlsx";
        return $this->file(match ($source) {
            "links" => $this->queryBus->dispatch(
                new LinkGetAllExcelQuery()
            ),
            "stat" => $this->queryBus->dispatch(
                new StatGetOneExcelQuery(
                    linkId: $request->query->get('linkId') ?? null,
                    dateFrom: $request->query->get('dateFrom'),
                    dateTo: $request->query->get('dateTo')
                )
            ),
            default => throw new SourceNotFound("Source '$source' not found - LinkController::excel"),
        }, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    #[Route('/link', name: 'mobzio_link', methods: 'post')]
    #[IsGranted('ROLE_MOBZIO_SHOW', message: 'no rights to watch mobzio link')]
    public function show(Request $request): JsonResponse
    {
        return $this->json($this->queryBus->dispatch(
            new LinkGetOneQuery(
                linkId: $request->request->getString('linkId'),
                priceDate: $request->request->getString('dateFrom')
            )
        ));
    }

    #[Route('/create', name: 'mobzio_create', methods: 'post')]
    #[IsGranted('ROLE_MOBZIO_EDIT', message: 'no rights to create mobzio link')]
    public function create(Request $request): JsonResponse
    {
        $productId = $request->request->getString('productId');
        $phrase = $request->request->getString('phrase');
        $blogger = $request->request->getString('blogger');
        $hash = md5($productId.$phrase.$blogger.date('Yd-m-Y-H-i-s'));

        $this->json($this->commandBus->dispatch(
            new MakeLinkCommand(
                productId: $productId,
                phrase: $phrase,
                blogger: $blogger,
                hash: $hash
            )
        ));

        return $this->json($this->queryBus->dispatch(
            new LinkGetLastOneQuery(
                hash: $hash
            )
        ));
    }
}
