<?php

namespace App\Imports;

use App\Models\Word;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;                  

class WordsImport implements ToModel,   WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    private $rowCount = 0;

    public function __construct(private string $subject_id)
    {
        
    }

    public function model(array $row)
    {
        // Пропускаем пустые строки (где нет слова)
        if (empty($row[0])) {
            return null;
        }

        $this->rowCount++;

        return new Word([
            'name' => $row[0],                      // Первая колонка - слово (обязательно)
            'translation' => $row[1] ?? null,       // Вторая колонка - перевод (может быть пустым)
            'subject_id' => $this->subject_id
        ]);
    }

    public function rules(): array
    {
        return [
            '0' => 'required|string|max:255',       // Только первая колонка обязательна
            // Вторая колонка не валидируется - может быть пустой
        ];
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}
