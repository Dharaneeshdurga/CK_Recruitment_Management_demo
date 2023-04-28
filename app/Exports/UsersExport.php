<?php
  
namespace App\Exports;
  
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class UsersExport implements FromCollection, WithHeadings, WithEvents
{
    protected $data;
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function collection()
    {
        return collect($this->data);
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings() :array
    {
        return [
            'RFH',
            '',
            '',
            // 'Requested By',
            // 'Mobile number',
            // 'Email',
            // 'Position reports to',
            // 'Approved by',
            // 'Ticket Number',
            // 'Position Title',
            // 'Location',
            // 'Location preferred',
            // 'Business',
            // 'Band',
            // 'Division',
            // 'Function',
            // 'No. of Positions',
            // 'JD / Roles & Responsibilities',
            // 'Qualification',
            // 'Essential Skill sets',
            // 'Good to have Skill sets',
            // 'Experience',
            // 'Maximum CTC(Per Month)',
            // 'Any other specific consideration',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
   
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(40);
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(50);
                $event->sheet->getDelegate()->getRowDimension('2')->setRowHeight(50);
                $event->sheet->getStyle('B2')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('B7')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('B9')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('B13')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('B10')->getAlignment()->setWrapText(true);

                $event->sheet->getDelegate()->getStyle('A1:A14')
                                ->getFont()
                                ->setBold(true);
                $event->sheet->getDelegate()->getStyle('A2:C2')
                                ->getFont()
                                ->setBold(true);
                $event->sheet->getDelegate()->getStyle('A3:C3')
                                ->getFont()
                                ->setBold(true);
                $event->sheet->getDelegate()->getStyle('A4:C4')
                                ->getFont()
                                ->setBold(true);
                $event->sheet->getDelegate()->getStyle('A5:C5')
                                ->getFont()
                                ->setBold(true);
                $event->sheet->getDelegate()->getStyle('A6:C6')
                                ->getFont()
                                ->setBold(true);
                $event->sheet->getStyle('A2:C2')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => '20B2AA'],]);
                $event->sheet->getStyle('A1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'B0C4DE'],]);
                $event->sheet->getStyle('A1:C13')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ]
                ]);
                // $event->sheet->mergeCells('B7:C7');
                // $event->sheet->mergeCells('B8:C8');
                // $event->sheet->mergeCells('B9:C9');
                // $event->sheet->mergeCells('B10:C10');
                // $event->sheet->mergeCells('B11:C11');
                // $event->sheet->mergeCells('B12:C12');
                // $event->sheet->mergeCells('B13:C13');
                // $event->sheet->getStyle('B12')->getAlignment()->setAlignment('left');
                $event->sheet->getDelegate()->getStyle('B12')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('A7')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A9')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A10')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A13')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('C2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                
            },
        ];
    }
}