<?php

namespace Drinks\Storefront\Storage;

use Drinks\Storefront\Factory\ElasticsearchFactory;
use Drinks\Storefront\Factory\TwigFactory;
use Drinks\Storefront\Repository\WebsiteRepository;

class NavigationMenu
{
    const STARTING_LEVEL = 2;

    /**
     * @var TwigFactory
     */
    private $twigFactory;

    /**
     * @var ElasticsearchFactory
     */
    private $elasticsearchFactory;

    /**
     * @var WebsiteRepository
     */
    private $websiteRepository;

    public function __construct(
        TwigFactory $twigFactory,
        ElasticsearchFactory $elasticsearchFactory,
        WebsiteRepository $websiteRepository
    ) {
        $this->twigFactory = $twigFactory;
        $this->elasticsearchFactory = $elasticsearchFactory;
        $this->websiteRepository = $websiteRepository;
    }

    public function regenerateForWebsite($website, $locale)
    {
        $categoriesIndex = $this->websiteRepository->lookupCategoryIndex($website, $locale);
        $firstLevel = $this->getCategories(
            $categoriesIndex,
            [
                'terms' => [
                    'level' => [self::STARTING_LEVEL]
                ]
            ]
        );
        $tree = [];
        foreach ($firstLevel as $firstLevelItem) {
            $tree[ $firstLevelItem['entity_id'] ] = $firstLevelItem;
            $secondLevel = $this->getCategories(
                $categoriesIndex,
                [
                    'terms' => [
                        'parent_id' => [$firstLevelItem['entity_id']]
                    ]
                ]
            );
            foreach ($secondLevel as $secondLevelItem) {
                $tree[ $firstLevelItem['entity_id'] ]['children'][ $secondLevelItem['entity_id'] ] = $secondLevelItem;
                $thirdLevel = $this->getCategories(
                    $categoriesIndex,
                    [
                        'terms' => [
                            'parent_id' => [$secondLevelItem['entity_id']]
                        ]
                    ]
                );
                foreach ($thirdLevel as $thirdLevelItem) {
                    $tree[ $firstLevelItem['entity_id'] ]
                        ['children']
                        [ $secondLevelItem['entity_id'] ]
                        ['children']
                        [ $thirdLevelItem['entity_id'] ] = $thirdLevelItem;
                }
            }
        }
//        print_r($tree);exit;

        $twig = $this->twigFactory->create();
        $content = $twig->render(
            'page/nav_source.twig',
            [
                'tree' => $tree,
            ]);
        file_put_contents('templates/default/page/nav.twig', $content);
    }

    private function getCategories($index, array $query = [])
    {
        $params = [
            'index' => $index,
            'type' => 'category',
            'sort' => ['level:asc', 'position:asc'],
            'body'  => [
                'from' => 0, 'size' => 10,
                '_source' => ['entity_id', 'parent_id', 'level', 'name', 'url_key', 'url_path'],
//                'query' => [
//                    'terms' => [
//                        'level' => [2]
//                    ]
//                ]
            ]
        ];
        if (!empty($query)) {
            $params['body']['query'] = $query;
        }
        $hits = $this->elasticsearchFactory->create()->search($params)['hits']['hits'];
        return array_column($hits, '_source');
    }
}
