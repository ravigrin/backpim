<?php

namespace Influence\Domain\Service;

use Influence\Domain\Repository\InfluenceIntegrationRepositoryInterface;
use Influence\Domain\Repository\MicrosoftIntegrationInterface;
use Psr\Log\LoggerInterface;

readonly class InfluenceIntegrationService
{
    public function __construct(
        private MicrosoftIntegrationInterface           $microsoftIntegration,
        private InfluenceIntegrationRepositoryInterface $influenceIntegrationRepository,
        private LoggerInterface $logger,
    ) {
    }

    public function updateDocument(): void
    {
        $files = [
            // "name": "2023 INFLUENCE_Media Plan_ 2023.xlsx"
            "01RNFONAOKMMPGQKWIQJDKTZHI2OV2UC5Q" => [
                [
                    'drive' => 'b!l3AC4vy-cUal3blcNbhtXoR2-817TohMlslES_N46v0Vdt-3b0RrSLLY2AHZUN3h',
                    'item' => '01RNFONAOKMMPGQKWIQJDKTZHI2OV2UC5Q',
                    'worksheet' => 'INFLUENCE stories OFFICE',
                    'range' => "A1:AV1000"
                ],
                [
                    'drive' => 'b!l3AC4vy-cUal3blcNbhtXoR2-817TohMlslES_N46v0Vdt-3b0RrSLLY2AHZUN3h',
                    'item' => '01RNFONAOKMMPGQKWIQJDKTZHI2OV2UC5Q',
                    'worksheet' => 'INFL stories REMOTE',
                    'range' => "A1:AV1000"
                ],
                [
                    'drive' => 'b!l3AC4vy-cUal3blcNbhtXoR2-817TohMlslES_N46v0Vdt-3b0RrSLLY2AHZUN3h',
                    'item' => '01RNFONAOKMMPGQKWIQJDKTZHI2OV2UC5Q',
                    'worksheet' => 'Influence БАРТЕР new',
                    'range' => "A1:W4000"
                ],
                [
                    'drive' => 'b!l3AC4vy-cUal3blcNbhtXoR2-817TohMlslES_N46v0Vdt-3b0RrSLLY2AHZUN3h',
                    'item' => '01RNFONAOKMMPGQKWIQJDKTZHI2OV2UC5Q',
                    'worksheet' => 'INFLUENCE REELS ',
                    'range' => "A1:AS1000"
                ],
                [
                    'drive' => 'b!l3AC4vy-cUal3blcNbhtXoR2-817TohMlslES_N46v0Vdt-3b0RrSLLY2AHZUN3h',
                    'item' => '01RNFONAOKMMPGQKWIQJDKTZHI2OV2UC5Q',
                    'worksheet' => 'БЛОГЕРСКИЕ ТОВАРЫ-БЮДЖЕТ',
                    'range' => "A1:AF500"
                ],
            ],
            // "name": "1_INFLUENCE_Media Plan_ 2024.xlsx"
            "01RNFONAKGUEPPSCCBRVCIZQZSADE6PJZH" => [
                [
                    'drive' => 'b!l3AC4vy-cUal3blcNbhtXoR2-817TohMlslES_N46v0Vdt-3b0RrSLLY2AHZUN3h',
                    'item' => '01RNFONAKGUEPPSCCBRVCIZQZSADE6PJZH',
                    'worksheet' => 'INFL IG stories-TG posts OFFICE',
                    'range' => "A1:AV1000"
                ],
                [
                    'drive' => 'b!l3AC4vy-cUal3blcNbhtXoR2-817TohMlslES_N46v0Vdt-3b0RrSLLY2AHZUN3h',
                    'item' => '01RNFONAKGUEPPSCCBRVCIZQZSADE6PJZH',
                    'worksheet' => 'INFL IG stories-TG posts REMOTE',
                    'range' => "A1:AV1000"
                ],
                [
                    'drive' => 'b!l3AC4vy-cUal3blcNbhtXoR2-817TohMlslES_N46v0Vdt-3b0RrSLLY2AHZUN3h',
                    'item' => '01RNFONAKGUEPPSCCBRVCIZQZSADE6PJZH',
                    'worksheet' => 'INFL REELS_SHORTS',
                    'range' => "A1:W2000"
                ],
                [
                    'drive' => 'b!l3AC4vy-cUal3blcNbhtXoR2-817TohMlslES_N46v0Vdt-3b0RrSLLY2AHZUN3h',
                    'item' => '01RNFONAKGUEPPSCCBRVCIZQZSADE6PJZH',
                    'worksheet' => 'Influence БАРТЕР new',
                    'range' => "A1:AS1000"
                ],
                [
                    'drive' => 'b!l3AC4vy-cUal3blcNbhtXoR2-817TohMlslES_N46v0Vdt-3b0RrSLLY2AHZUN3h',
                    'item' => '01RNFONAKGUEPPSCCBRVCIZQZSADE6PJZH',
                    'worksheet' => 'БЛОГЕРСКИЕ ТОВАРЫ-БЮДЖЕТ',
                    'range' => "A1:AF500"
                ],
            ],
            // "name": "WB список рк для бота.xlsx"
            "01YV7443PMDR7AG2QR3ZA3MZUQVZCWUCWR" => [
                [
                    'drive' => 'b!K7sV6N1oNEa2KZcTSXMhHoR2-817TohMlslES_N46v0Vdt-3b0RrSLLY2AHZUN3h',
                    'item' => '01YV7443PMDR7AG2QR3ZA3MZUQVZCWUCWR',
                    'worksheet' => 'Лист1',
                    'range' => "A1:Z2000"
                ],
            ],
            // "name": "Q2_INFLUENCE_Media Plan_ 2024.xlsx"
            "01RNFONAKARDJOE5NBRBC2756DG3HYXFCW" => [
                [
                    'drive' => 'b!l3AC4vy-cUal3blcNbhtXoR2-817TohMlslES_N46v0Vdt-3b0RrSLLY2AHZUN3h',
                    'item' => '01RNFONAKARDJOE5NBRBC2756DG3HYXFCW',
                    'worksheet' => 'АПР_БЛОГЕРСКИЕ ТОВАРЫ-БЮДЖЕТ',
                    'range' => "A1:AP500"
                ],
                [
                    'drive' => 'b!l3AC4vy-cUal3blcNbhtXoR2-817TohMlslES_N46v0Vdt-3b0RrSLLY2AHZUN3h',
                    'item' => '01RNFONAKARDJOE5NBRBC2756DG3HYXFCW',
                    'worksheet' => 'МАЙ_БЛОГЕРСКИЕ ТОВАРЫ - БЮДЖЕТ',
                    'range' => "A2:AA500"
                ],
                [
                    'drive' => 'b!l3AC4vy-cUal3blcNbhtXoR2-817TohMlslES_N46v0Vdt-3b0RrSLLY2AHZUN3h',
                    'item' => '01RNFONAKARDJOE5NBRBC2756DG3HYXFCW',
                    'worksheet' => 'UNIT 1_Stories, posts',
                    'range' => "A2:AX1000"
                ],
                [
                    'drive' => 'b!l3AC4vy-cUal3blcNbhtXoR2-817TohMlslES_N46v0Vdt-3b0RrSLLY2AHZUN3h',
                    'item' => '01RNFONAKARDJOE5NBRBC2756DG3HYXFCW',
                    'worksheet' => 'UNIT 1_Reels_Shorts',
                    'range' => "A1:AT500"
                ],
                [
                    'drive' => 'b!l3AC4vy-cUal3blcNbhtXoR2-817TohMlslES_N46v0Vdt-3b0RrSLLY2AHZUN3h',
                    'item' => '01RNFONAKARDJOE5NBRBC2756DG3HYXFCW',
                    'worksheet' => 'UNIT 2_Stories, posts',
                    'range' => "A1:AX1000"
                ],
                [
                    'drive' => 'b!l3AC4vy-cUal3blcNbhtXoR2-817TohMlslES_N46v0Vdt-3b0RrSLLY2AHZUN3h',
                    'item' => '01RNFONAKARDJOE5NBRBC2756DG3HYXFCW',
                    'worksheet' => 'UNIT 2_Reels_Shorts',
                    'range' => "A1:AT500"
                ],
                [
                    'drive' => 'b!l3AC4vy-cUal3blcNbhtXoR2-817TohMlslES_N46v0Vdt-3b0RrSLLY2AHZUN3h',
                    'item' => '01RNFONAKARDJOE5NBRBC2756DG3HYXFCW',
                    'worksheet' => 'Influence БАРТЕР new',
                    'range' => "A1:V2000"
                ],
            ]
        ];

        $fileIndex = [
            '01RNFONAKGUEPPSCCBRVCIZQZSADE6PJZH' => '1_INFLUENCE_Media Plan_ 2024.xlsx',
            '01RNFONAOKMMPGQKWIQJDKTZHI2OV2UC5Q' => '2023 INFLUENCE_Media Plan_ 2023.xlsx',
            '01YV7443PMDR7AG2QR3ZA3MZUQVZCWUCWR' => 'WB список рк для бота.xlsx',
            '01RNFONAKARDJOE5NBRBC2756DG3HYXFCW' => 'Q2_INFLUENCE_Media Plan_ 2024.xlsx'
        ];

        $result = [];
        foreach ($files as $fileId => $lists) {
            $fileResult = [];
            foreach ($lists as $item) {
                try {
                    $document = $this->microsoftIntegration->getDataFormat(
                        drive: $item['drive'],
                        item: $item['item'],
                        worksheet: $item['worksheet'],
                        range: $item['range'],
                    );

                    echo $item['worksheet'] . ' ' . count($document) . PHP_EOL;

                    $fileResult[] = [
                        'feedName' => $item['worksheet'],
                        'feedData' => $document
                    ];
                } catch (\Exception $exception) {
                    $this->logger->error(sprintf("Load excel files error: %s", $exception->getMessage()));
                }
            }

            $result[] = [
                "fileName" => $fileIndex[$fileId],
                "feeds" => $fileResult
            ];
        }

        $this->influenceIntegrationRepository->save($result);
    }

}
