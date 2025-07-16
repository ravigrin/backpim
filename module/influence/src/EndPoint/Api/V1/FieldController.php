<?php

declare(strict_types=1);

namespace Influence\EndPoint\Api\V1;

use Influence\Application\Query\FieldList\Query as FieldListQuery;
use Shared\Domain\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class FieldController extends AbstractController
{
    public function __construct(
        private QueryBusInterface  $queryBus,
        private ValidatorInterface $validator
    ) {
    }

    #[Route('/fields', name: 'fields_list', methods: 'post')]
    #[IsGranted('ROLE_INFLUENCE_FIELDS_SHOW', message: 'no rights to watch fields')]
    public function list(Request $request): JsonResponse
    {
        return $this->json($this->queryBus->dispatch(
            new FieldListQuery(
                $request->request->getInt("tableId")
            )
        ));
    }

    #[Route('/fields/edit', name: 'fields_edit', methods: 'post')]
    #[IsGranted('ROLE_INFLUENCE_FIELDS_EDIT', message: 'no rights to change fields')]
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
