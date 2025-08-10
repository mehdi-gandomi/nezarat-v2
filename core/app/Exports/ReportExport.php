<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
class ReportExport implements FromArray,WithHeadings,WithStartRow,WithStrictNullComparison,WithEvents
{
	  /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getDelegate()->setRightToLeft(true);
            },
        ];
    }
  protected $headings;
  protected $items;

    public function __construct(array $headings,array $items)
    {
        $this->headings = $headings;
		$this->items = $items;
    }
 public function headings(): array
    {
        return $this->headings;
    }
    public function array(): array
    {
        return [$this->items];
    }
	/**
 * @return int
 */
public function startRow(): int
{
    return 2;
}
}
