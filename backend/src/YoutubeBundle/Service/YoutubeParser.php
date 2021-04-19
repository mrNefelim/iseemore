<?php

namespace App\YoutubeBundle\Service;

use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class YoutubeParser
{
    private $url;
    private $key;

    public function __construct(string $url, string $key)
    {
        $this->url = $url;
        $this->key = $key;
    }

    /**
     * @param string $regionCoordinate
     * @param int $radius
     * @return array
     * @throws YoutubeParserException
     */
    public function parse(string $regionCoordinate, int $radius): array
    {
        $links = [];
        $nextPageToken = '';
        while (true) {
            try {
                $pageData = $this->getPageData(
                    $this->generateLink($regionCoordinate, $radius, $nextPageToken)
                );
            } catch (GuzzleException $exception) {
                throw new YoutubeParserException($exception->getMessage());
            }

            $videoSourceIds = array_map(function ($video) {
                $date = Carbon::createFromFormat('Y-m-d\TH:i:s\Z', $video['snippet']['publishedAt']);
                if ($date->diffInDays(Carbon::now()) == 0 && preg_match('/[а-яА-Я]/ui', $video['snippet']['title'])) {
                    return $video['id']['videoId'];
                } else {
                    return null;
                }
            }, $pageData['items']
            );

            $videoIds = array_diff($videoSourceIds, ['']);

            try {
                $stats = $this->getPageData(
                    $this->generateStatisticLink($videoIds)
                );
            } catch (GuzzleException $exception) {
                throw new YoutubeParserException($exception->getMessage());
            }

            foreach ($stats['items'] as $video) {
                if ($video['statistics']['viewCount'] < 10) {
                    $links[] = $video['id'];
                }
            }

            if (count($videoSourceIds) != count($videoIds)) {
                break;
            }

            if (isset($pageData['nextPageToken'])) {
                $nextPageToken = $pageData['nextPageToken'];
                sleep(1);
            } else {
                break;
            }
        }

        return $links;
    }

    /**
     * @param string $url
     * @return mixed
     * @throws GuzzleException
     */
    private function getPageData(string $url)
    {
        return json_decode((new GuzzleClient())->get($url)->getBody()->getContents(), true);
    }

    /**
     * @param string $regionCoordinate
     * @param int $radius
     * @param string $nextPageToken
     * @return string
     */
    private function generateLink(string $regionCoordinate, int $radius, $nextPageToken = ''): string
    {
        $apiUrlPattern = '%ssearch?part=snippet&location=%s&locationRadius=%skm&maxResults=100&order=date&type=video&key=%s';

        if (!empty($nextPageToken)) {
            $apiUrlPattern .= '&pageToken=' . $nextPageToken;
        }

        return sprintf($apiUrlPattern, $this->url, urlencode($regionCoordinate), $radius, $this->key);
    }

    /**
     * @param array $ids
     * @return string
     */
    private function generateStatisticLink(array $ids): string
    {
        $apiUrlPattern = '%svideos?part=statistics&id=%s&key=%s';
        return sprintf($apiUrlPattern, $this->url, implode(',', $ids), $this->key);
    }
}