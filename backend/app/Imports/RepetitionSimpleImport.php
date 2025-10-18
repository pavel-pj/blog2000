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
use App\Models\RepetitionWord;
use Illuminate\Support\Facades\DB;
 

class RepetitionSimpleImport implements ToModel,   WithValidation, SkipsOnFailure,WithHeadingRow
{
    use Importable, SkipsFailures;

    private $rowCount = 0;

    public function __construct(private string $subject_id)
    {
        
    }
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        // Пропускаем пустые строки (где нет данных)
        if (empty($row['new_id'])) {
            return null;
        }

        $this->rowCount++;
         // Use transaction to ensure both records are created
        return  DB::transaction(function () use ($row) {
            $repetition = Repetition::create([
                'task' => $row['task'],                      // Первая колонка - слово (обязательно)
                'answer' => $row['answer'] ,                  // Вторая колонка - перевод (обязательно)
                'subject_id' => $this->subject_id
            ]);
            
            RepetitionWord::create([
                'word_id' => $row['new_id'],
                'repetition_id' => $repetition->id
            ]);

            if (($row['repeated_id'])){
                RepetitionWord::create([
                    'word_id' => $row['repeated_id'],
                    'repetition_id' => $repetition->id
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
