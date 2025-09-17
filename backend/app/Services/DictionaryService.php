<?php

namespace App\Services;

use App\Models\Article;
use App\Repositories\CatalogRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class DictionaryService
{
  
    public function __construct(protected CatalogRepository $catalogRepository){}

    public function getDictionaryByParams(array $validated, string $typeDictionary): Array
    {

        if ($typeDictionary === 'catalog') {
            return [
                'dictionaryCatalog' => $this->catalogRepository->getCatalogDictionary(),

            ];
        }

        throw new NotFoundHttpException('There is no data for this request');
    }
}
