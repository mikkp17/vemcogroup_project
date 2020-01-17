<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterSheet;

class ReportExport implements FromArray, ShouldAutoSize, WithEvents
{
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $spreadsheet = $event->sheet->getDelegate();
                $spreadsheet->getStyle('A1:N1')->getFont()->setSize(14);

                //Condition 1 used for red color when percentage is lower than 100
                $conditional1 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
                $conditional1->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS);
                $conditional1->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_LESSTHAN);
                $conditional1->addCondition('1');
                $conditional1->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $conditional1->getStyle()->getFill()->getStartColor()->setRGB('F28A8C');
                $conditional1->getStyle()->getFill()->getEndColor()->setRGB('F28A8C');

                //Condition 2 used for green color when percentage is higher than 100
                $conditional2 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
                $conditional2->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS);
                $conditional2->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_GREATERTHANOREQUAL);
                $conditional2->addCondition('1');
                $conditional2->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $conditional2->getStyle()->getFill()->getStartColor()->setRGB('AFF2A7');
                $conditional2->getStyle()->getFill()->getEndColor()->setRGB('AFF2A7');

                $conditionalStyles = $spreadsheet->getStyle('B24')->getConditionalStyles();
                $conditionalStyles[] = $conditional1;
                $conditionalStyles[] = $conditional2;
                $spreadsheet->getStyle('B24:K24')->setConditionalStyles($conditionalStyles);
                $spreadsheet->getStyle('N2:N21')->setConditionalStyles($conditionalStyles);

                //Used to format numbers
                $spreadsheet->getStyle('B22:K23')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
                $spreadsheet->getStyle('L2:M21')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
                $spreadsheet->getStyle('B24:K24')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE);
                $spreadsheet->getStyle('N2:N21')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE);
            }
        ];
    }

    protected $report;

    public function __construct(array $report)
    {
        $this->report = $report;
    }

    public function array(): array
    {
        return $this->report;
    }
}
