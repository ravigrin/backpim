<?php

declare(strict_types=1);

namespace Influence\EndPoint\Api\V1;

use Influence\Application\Command\TableSave\Command as TableSaveCommand;
use Influence\Application\Query\ValueList\Query as ValueListQuery;
use Shared\Domain\Command\CommandBusInterface;
use Shared\Domain\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ValueController extends AbstractController
{
    public function __construct(
        private QueryBusInterface  $queryBus,
        private CommandBusInterface $commandBus,
        private ValidatorInterface $validator
    ) {
    }

    #[Route('/values', name: 'values_list', methods: 'post')]
    #[IsGranted('ROLE_INFLUENCE_VALUES_SHOW', message: 'no rights to watch values')]
    public function list(Request $request): JsonResponse
    {
        return $this->json($this->queryBus->dispatch(
            new ValueListQuery(
                $request->request->getInt("tableId")
            )
        ));
    }

    #[Route('/values/edit', name: 'values_edit', methods: 'post')]
    #[IsGranted('ROLE_INFLUENCE_VALUES_EDIT', message: 'no rights to change values')]
    public function edit(Request $request): JsonResponse
    {
        $body = $request->request->all();

        $this->commandBus->dispatch(
            new TableSaveCommand(
                tableId: $body["tableId"],
                rowId: $body["rowId"],
                values: $body["values"],
            )
        );

        return $this->json(['message' => 'success']);
    }
}
