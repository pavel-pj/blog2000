<?php

namespace App\Services;

use App\Models\Word;
use App\Repositories\WordRepository;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Services\TopicWordService;

class WordService
{
 
    public function __construct(
        protected WordRepository $repository,
        protected TopicWordService $topicWordService
        ){}

    public function index(string $subjectId): Array
    {
        return $this->repository->index($subjectId);
    }

    public function show(string $id): Word 
    {
        return $this->repository->show($id);
    }

    public function store(array $validated)//: EloquentBuilder
    {
  
        try {

            DB::beginTransaction();

            $wordValidated = array_filter($validated, function($value, $key){
                return $key !== 'topics';
            }, ARRAY_FILTER_USE_BOTH);
            $topics = $validated['topics'];

            $word =  Word::create($wordValidated);

            if (!$word) {
                throw new \Exception("It is not possible to create new word");
            }
             
            foreach ($topics as $topic) 
            {
                $this->topicWordService->store([
                    'topic_id' => $topic,
                    'word_id' => $word->id
                ]);
            }
             
            //$word->topics()->attach($topics);


            DB::commit();
            $wordWithTopics = $word->load('topics');

            // Убираем pivot и оставляем только нужные поля
            $wordWithTopics->topics->transform(function($topic) {
                return $topic->only('id', 'name');
            });

return $wordWithTopics;

        } catch (Exception $e) {
            DB::rollBack(); 
            throw new \Exception($e->getMessage());
        }
 
    }

    public function update(array $validated, string $id):EloquentCollection 
    {
       
        Word::updateOrInsert(
            ['id' => $id],
            $validated
        );
 
        return Word::where('id', $id)->get()  ;
    }

    public function destroy(string $id): void
    {
     

        $result = Word::destroy($id);
        if (!$result) {
            throw new \Exception("Item could not be deleted");
        }
    }
}
