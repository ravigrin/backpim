<?php

declare(strict_types=1);

namespace Influence\EndPoint\Api\V1;

use Influence\Application\Query\TableList\Query as TableListQuery;
use Shared\Domain\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class TableController extends AbstractController
{
    public function __construct(
        private QueryBusInterface  $queryBus,
        private ValidatorInterface $validator
    ) {
    }

    #[Route('/tables', name: 'tables_list', methods: 'post')]
    #[IsGranted('ROLE_INFLUENCE_TABLES_SHOW', message: 'no rights to watch tables')]
    public function list(): JsonResponse
    {
        return $this->json($this->queryBus->dispatch(
            new TableListQuery()
        ));
    }

    #[Route('/tables/edit', name: 'tables_edit', methods: 'post')]
    #[IsGranted('ROLE_INFLUENCE_TABLES_EDIT', message: 'no rights to change tables')]
    public function edit(Request $request): JsonResponse
    {
//        $body = $request->request->all();
//
//
//        $errors = $this->validator->validate($command);
//        if (is_array($errors)) {
//            return $this->json(['error' => $errors], 418);
//        }

        return $this->json(['message' => 'success']);
    }
}
