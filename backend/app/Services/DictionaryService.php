<?php

namespace App\Services;

use App\Models\Article;
use App\Repositories\CatalogRepository;
use Illuminate\Support\Facades\DB;

class DictionaryService
{
    protected CatalogRepository $catalogRepository;

    public function __construct()
    {
        $this->catalogRepository = new CatalogRepository();
    }

    public function getCandidateDictionaryByParams(array $validated, string $typeDictionary)//: array
    {


        if ($typeDictionary === 'catalog') {
            return [
                'dictionaryCatalog' => $this->catalogRepository->getCatalogDictionary(),

            ];
        }

        throw new NotFoundHttpException('There is no data for this request');
    }
}
