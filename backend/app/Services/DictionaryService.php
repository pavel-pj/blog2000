<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Topic;
use App\Repositories\CatalogRepository;
use App\Repositories\TopicRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DictionaryService
{
  
    public function __construct(
        protected CatalogRepository $catalogRepository,
        protected TopicRepository $topicRepository
        ){}

    public function getDictionaryByParams(array $validated, string $typeDictionary): Array
    {
        
        if ($typeDictionary === 'catalog') {
            return [
                'dictionaryCatalog' => $this->catalogRepository->getCatalogDictionary(),

            ];
        }

          if ($typeDictionary === 'user') {
            return [
                'data' => $this->topicRepository->getTopicsDctionary(),

            ];
        }

        throw new NotFoundHttpException('There is no data for this request');
    }
}
