<?php

namespace Mobzio\Infrastructure\Mobzio;

use GuzzleHttp\Psr7\Request;
use Mobzio\Domain\Repository\Dto\AddLinkDto;
use Mobzio\Domain\Repository\Dto\AddLinkResponseDto;
use Mobzio\Domain\Repository\Dto\FolderResponseDto;
use Mobzio\Domain\Repository\Dto\GetStatsDto;
use Mobzio\Domain\Repository\Dto\MyLinkResponseDto;
use Mobzio\Domain\Repository\Dto\StatsDto;
use Mobzio\Domain\Repository\Dto\StatsShortResponseDto;
use Mobzio\Domain\Repository\MobzioRepositoryInterface;
use Mobzio\Infrastructure\Exception\EmptyMessageResponse;
use Mobzio\Infrastructure\Exception\LinkTypeNotSupported;
use Mobzio\Infrastructure\Exception\ResponseFail;
use Mobzio\Infrastructure\Exception\TokenNotFound;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

/**
 * Репозиторий для работы с сервисом коротких ссылок Mobzio
 */
class MobzioRepository implements MobzioRepositoryInterface
{
    private const string URL = 'https://mobz.io/api/public';

    private string $token;

    /**
     * @throws TokenNotFound
     */
    public function __construct(
        private readonly ClientInterface $client,
    )
    {
        if (!$_ENV['MOBZIO_TOKEN']) {
            throw new TokenNotFound('Not found mobzio api token in .env file: 
            module/mobzio/src/Infrastructure/Mobzio/MobzioRepository.php');
        }

        $this->token = $_ENV['MOBZIO_TOKEN'];
    }

    /**
     * Get запрос к Mobzio
     * @param string $endpoint
     * @return array
     * @throws EmptyMessageResponse
     * @throws ClientExceptionInterface
     */
    private function request(string $endpoint): array
    {
        $response = $this->client->sendRequest(new Request(
            method: 'GET',
            uri: $endpoint,
            headers: ['Authorization' => $this->token]
        ));

        $resp = json_decode($response->getBody()->getContents(), true);

        if (!$contents = ($resp['message'] ?? $resp['result'])) {
            throw new EmptyMessageResponse('mobzio/src/Infrastructure/Mobzio/MobzioRepository.php::getMyLinks()');
        }

        return $contents;
    }

    /**
     * @inheritDoc
     * @throws EmptyMessageResponse|ClientExceptionInterface
     */
    public function getMyLinks(?bool $stats = false): array
    {
        $endpoint = self::URL . '/mylinks';
        if ($stats) {
            $endpoint .= '?stats=1';
        }

        $contents = $this->request($endpoint);

        return array_map(fn(array $content) => new MyLinkResponseDto(
            linkId: $content['link_id'],
            link: $content['link'],
            shortcode: $content['shortcode'],
            originalLink: $content['original_link'],
            stats: $stats
                ? new StatsShortResponseDto(
                    today: (int)$content['stats']['today'],
                    yesterday: (int)$content['stats']['yesterday'],
                    all: (int)$content['stats']['all'])
                : null,
            description: (!empty($content['description'])) ? $content['description'] : null
        ), $contents);
    }

    /**
     * @inheritDoc
     * @throws EmptyMessageResponse|ClientExceptionInterface
     */
    public function getOneLink(int $linkId, ?bool $stats = false): MyLinkResponseDto
    {
        $endpoint = self::URL . '/onelink?' . $linkId;
        if ($stats) {
            $endpoint .= '&stats=1';
        }

        $content = $this->request($endpoint);

        return new MyLinkResponseDto(
            linkId: $content['link_id'],
            link: $content['link'],
            shortcode: $content['shortcode'],
            originalLink: $content['original_link'],
            stats: $stats && isset($content['stats'])
                ? new StatsShortResponseDto(
                    today: (int)$content['stats']['today'],
                    yesterday: (int)$content['stats']['yesterday'],
                    all: (int)$content['stats']['all'])
                : null,
            description: (!empty($content['description'])) ? $content['description'] : null,
            folder: isset($content['folder'])
                ? new FolderResponseDto(
                    linkType: $content['folder']['link_type'],
                    folderId: $content['folder']['folder_id'],
                    folderName: $content['folder']['folder_name'],
                    folderDescription: $content['folder']['folder_description'])
                : null
        );
    }

    /**
     * @inheritDoc
     * TODO: add stats date filter
     *          if ($statsDto->dateFrom) {
     *              $endpoint .= '&dateFrom=1705909758'; //. $statsDto->dateFrom;
     *          }
     *          if ($statsDto->dateTo) {
     *              $endpoint .= '&dateTo=1706773758'; //. $statsDto->dateTo;
     *          }
     */
    public function getStats(GetStatsDto $statsDto): array
    {
        $endpoint = self::URL . '/stats?link_id=' . $statsDto->linkId;

        $contents = [];
        $this->getStatsContent($endpoint, $contents);

        return array_map(fn(array $content) => new StatsDto(
            addTime: $content['addTime'],
            userAgent: $content['user_agent'],
            isMobile: $content['isMobile']
        ), $contents);
    }


    /**
     * Проходит по всем страницам и собирает статистику в $contents
     * @param string $endpoint
     * @param $contents
     * @return void
     * @throws EmptyMessageResponse|ClientExceptionInterface
     */
    private function getStatsContent(string $endpoint, &$contents): void
    {
        $response = $this->request($endpoint);
        $contents = array_merge($contents, $response['stats']);
        if ($response['next_page']) {
            $endpoint .= '&page=' . $response['next_page'];
            $this->getStatsContent($endpoint, $contents);
        }
    }

    /**
     * @inheritDoc
     * @throws ClientExceptionInterface
     * @throws EmptyMessageResponse
     * @throws LinkTypeNotSupported
     * @throws ResponseFail
     */
    public function addLink(AddLinkDto $addLinkDto): AddLinkResponseDto
    {
        $endpoint = self::URL . '/addlink';

        $web = match ($addLinkDto->type) {
            'custom' => 'web',
            'ozon' => 'ozon',
            'wildberries' => 'wildberries',
            default => throw new LinkTypeNotSupported('module/mobzio/src/Infrastructure/Mobzio/MobzioRepository.php::addLink()')
        };

        $response = $this->client->request('POST',
            $endpoint,
            [
                'headers' => ['Authorization' => $this->token],
                'form_params' => [
                    $web => $addLinkDto->web,
                    'shortcode' => $addLinkDto->shortcode,
                    'type' => $addLinkDto->type,
                    'agree' => $addLinkDto->agree
                ]
            ]
        );

        if ($resp = json_decode($response->getBody()->getContents(), true)) {
            return new AddLinkResponseDto(
                status: $resp['status'],
                message: $resp['message'],
                linkId: isset($resp['info']) ? $resp['info']['link_id'] : null
            );
        }

        throw new ResponseFail('Could not get a response from Mobzio 
        - module/mobzio/src/Infrastructure/Mobzio/MobzioRepository.php::addLink()');
    }
}
