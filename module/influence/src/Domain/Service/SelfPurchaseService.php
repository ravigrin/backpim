<?php

namespace Influence\Domain\Service;

use Influence\Domain\Repository\MicrosoftIntegrationInterface;
use Influence\Domain\Repository\SelfPurchaseRepositoryInterface;

readonly class SelfPurchaseService
{
    public function __construct(
        private MicrosoftIntegrationInterface $microsoftIntegration,
        private SelfPurchaseRepositoryInterface $selfPurchaseRepository
    ) {
    }

    public function updateDocument(): void
    {
        $document = $this->microsoftIntegration->getDataFormat(
            drive: 'b!Yn5sNM4VFE2zb2OlDHW-9h0vLJpLLV5MoJ14rtxeR8DFoGhA7jPaTZeaE4r3dMm_',
            item: '01MCAZM4CUFEH32ARLWJDLDFAOKG2DV7KS',
            worksheet: 'ВЫКУПЫ',
        );

        $this->selfPurchaseRepository->save($document);
    }

}
