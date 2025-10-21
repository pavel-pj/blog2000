<?php

namespace App\Exports;

use App\Models\Word;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Enums\WordStatus;
use App\Models\SubjectOptions;

class RepetitionImportantExport implements  FromCollection, WithHeadings, WithMapping, WithStyles,WithEvents
{
     //user options 
    private int $totalRows = 30;
    private int $newWords = 5;
    private int $importantWords = 5;
    private string $repetnType = 'NEW';
    private string $topic = "Wild animals";
    private int $sentenseLength = 18; 

    //Program
    private string $allCombinedText;
    private string $promt;

    public function __construct(private string $subject_id)
    {
        $options =  SubjectOptions::findOrFail($subject_id);
        $this->totalRows = $options->total_rows;
        $this->newWords = $options->new_words;
        $this->importantWords = $options->important_words;
        $this->repetnType = $options->repetition_type;
        $this->sentenseLength = $options->row_length;
        
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

         
        $newWords =  Word::where('subject_id', $this->subject_id)
            ->where('status', WordStatus::NEW)
            ->select('id', 'name', 'translation', 'status','created_at')->orderBy('created_at','ASC')->limit($this->newWords)
            ->get();
        
        $importantWords =  Word::where('subject_id', $this->subject_id)
            ->where('status', WordStatus::IMPORTANT)
            ->select('id', 'name', 'translation', 'status','created_at')->orderBy('created_at','ASC')->limit($this->importantWords)
            ->get();    
            
 
        $repeatedWords = Word::where('subject_id', $this->subject_id)
            ->where('status', WordStatus::REPEATED)
            ->orderBy('repeated_at','ASC')
            ->select('id', 'name', 'translation', 'status','repeated_at')
            ->get(); 
            
        // Combine both collections
        $combined = collect();
        // Get the maximum count between both statuses
        //$maxCount = max($newWords->count(), $repeatedWords->count());
        
        for ($i = 0; $i < $this->totalRows; $i++) {

            $newWordIndex = $i % $this->newWords; // Циклический индекс (0,1,2,3,4,0,1,2...)
            $mportantWordIndex = $i % $this->importantWords; // Циклический индекс (0,1,2,3,4,0,1,2...)
            //$repeatedWords = $repeatedWords->get($i);   // REPEATED слова идут по порядку

            $combined->push([
                'NEW' => $newWords->get($newWordIndex),
                'IMPORTANT' => $importantWords->get($mportantWordIndex),
                'REPEATED' => $repeatedWords->get($i)
            ]);
        }
 

        // Сохраняем объединенный текст в свойство класса
        $this->allCombinedText = $this->generateCombinedText($combined);
        $this->promt = $this->createPromt();

        return $combined;
            
    }

    private function generateCombinedText($combined): string
    {
        $allText = '';
        foreach ($combined as $index => $row) {
            $newWord = $row['NEW'];
            $importantWord = $row['IMPORTANT'];
            $repeatedWord = $row['REPEATED'];
            
            $newCombinedText = '';
            if ($newWord) {
                $newCombinedText .= "'{$newWord->name}'";
                if ($newWord->translation) {
                    $newCombinedText .= " with meaning '{$newWord->translation}'";
                }
            }

            $importantCombinedText = '';
            if ($newWord) {
                $importantCombinedText .= "'{$importantWord->name}'";
                if ($importantWord->translation) {
                    $importantCombinedText .= " with meaning '{$importantWord->translation}'";
                }
            }
 
            $repeatedCombinedText = '';
            if ($repeatedWord) {
                $repeatedCombinedText .= "'{$repeatedWord->name}'";
                if ($repeatedWord->translation) {
                    $repeatedCombinedText .= " with meaning '{$repeatedWord->translation}'";
                }
            }

            $rowNumber = $index + 1;
            $currentRowText = "{$rowNumber}. In the {$rowNumber} sentence you must use words: {$newCombinedText}";
            
            if ($repeatedCombinedText) {
                $currentRowText .= " , {$repeatedCombinedText}";
            }

            if ($repeatedCombinedText) {
                $currentRowText .= " , {$repeatedCombinedText}";
            }
            
            $allText .= $currentRowText . " ; ";
        }
        
        return $allText;
    }

    public function createPromt()
    {
        $task =  "Напиши ".$this->totalRows . " предложений на английском языке на тему ". $this->topic ; 
        $task .= " Фразы должны быть либо вопросительными в прошлом и настоящем, либо повествовательными, но в прошлом. ";
        $task .= $this->allCombinedText ;
        $task .= " Список предложений должен быть пронумерованным и отсортированным по выданному тебе номеру. ";
        $task .= " Как видишь, для каждого предложения нужно использовать две фразы. ";
        $task .= " Первая фраза, к примеру, make a mistake может использоваться в разных временах или раздельно друг от друга ,если это позволяет язык. ";
        $task .= " к примеру she makes a greate mistake..или she made a great mistake. ";
        $task .= " Все глаголы конечно же указаны в инфинитиве или настоящем времени. Если ты задаёшь задание - предложение в прошедшем времени делай паврильную грамматику : ";
        $task .= " к примеру she makes a greate mistake..будет she made a great mistake. ";
        $task .= " НЕ НУЖНО вместо she made a great mistake писать she would make a great mistake. ";
        $task .= " Чередуй  времена между фразами ровно через одно предложение! ";
        $task .= " Обязательно! Каждое предложение не должно быть длинее ". $this->sentenseLength . " слов! ";
        $task .= "somebody, smbd и smth заменить на местоимения или существительные исходя из контекста. Чтобы в примерах ВООБЩЕ НЕ БЫЛО somebody, smth, someone,smth!";
        
        return $task;
 
    }

    public function headings(): array
    {

        

        return [
            'new_id',
            'new_name',
            'new_translation', 
            'new_status',
            'new_date',
            'repeated_id',
            'repeated_name',
            'repeated_translation',
            'repeated_status',
            'repeated_date',
            'task',
            'answer'

           // 'Combined Words',
        ];
    }

    public function map($row ): array
    {
        $newWord = $row['NEW'];
        $repeatedWord = $row['REPEATED'];
       
        
  

        return [
            $newWord ? $newWord->id : '',
            $newWord ? $newWord->name : '',
            $newWord ? $newWord->translation : '',
           
            $newWord ? $newWord->status : '',
            $newWord ? $newWord->created_at : '',
            $repeatedWord ? $repeatedWord->id : '',
            $repeatedWord ? $repeatedWord->name : '',
            $repeatedWord ? $repeatedWord->translation : '',
          
            $repeatedWord ? $repeatedWord->status : '',
            $repeatedWord ? $repeatedWord->repeated_at : '',
            //$this->allCombinedText ,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E6E6FA']
                ]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Добавляем объединенный текст после всех данных
                $startRow = $this->totalRows + 3; // Через 2 строки после данных
                
                // Добавляем заголовок для текста
                $sheet->setCellValue('A' . $startRow, 'Combined Sentences:');
                $sheet->getStyle('A' . $startRow)->getFont()->setBold(true);
                
                // Добавляем весь текст в следующую строку
                $sheet->setCellValue('A' . ($startRow + 1), $this->promt);
                
                // Настраиваем перенос текста и выравнивание
               // $sheet->getStyle('A' . ($startRow + 1))->getAlignment()->setWrapText(true);
                
                // Объединяем ячейки для текста (от A до K)
                //$sheet->mergeCells('A' . ($startRow + 1) . ':K' . ($startRow + 50));
            },
        ];
    }
}
