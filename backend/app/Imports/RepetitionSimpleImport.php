<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\Repetition;  
use App\Models\Task;
use App\Models\TaskWord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
 

class RepetitionSimpleImport implements ToModel,   WithValidation, SkipsOnFailure,WithHeadingRow
{
    use Importable, SkipsFailures;

    private $rowCount = 0;
    private string  $repetitionId;

    public function __construct(private string $subject_id)
    {
        $this->repetitionId = '';
        
    }
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {

        // Create repetition ONLY if it doesn't exist yet
            if ($this->repetitionId === '') {
                $repetition = Repetition::create([
                    'subject_id' => $this->subject_id,
                ]);
                $this->repetitionId = $repetition->id;
            }



        // Пропускаем пустые строки (где нет данных)
        if (empty($row['new_id'])) {
            return null;
        }
  
        $this->rowCount++;
         // Use transaction to ensure both records are created
        return  DB::transaction(function () use ($row) {
            $task = Task::create([
                'task' => $row['task'],                      // Первая колонка - слово (обязательно)
                'answer' => $row['answer'] ,                  // Вторая колонка - перевод (обязательно)
                'repetition_id' => $this->repetitionId,
                'position' =>  $this->rowCount
            
            ]);
 
            
            TaskWord::create([
                'word_id' => $row['new_id'],
                'task_id' => $task->id
            ]);

            if (($row['repeated_id'])){
                TaskWord::create([
                    'word_id' => $row['repeated_id'],
                    'task_id' => $task->id
                ]); 
            }

        });


    }

    public function rules(): array
    {
        return [
            'new_id' => 'required|string|exists:words,id',       // ID word NEW
            'repeated_id' => 'nullable|string|exists:words,id',       // ID word REPEATED
            'task' => 'required|string|max:500',              // task
            'answer' => 'required|string|max:500',              // anser
            
        ];
    }

     public function getRowCount(): int
    {
        return $this->rowCount;
    }
}
