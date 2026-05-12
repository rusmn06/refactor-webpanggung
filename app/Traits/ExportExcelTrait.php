<?php

namespace App\Traits;

use App\Models\RumahTangga;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

trait ExportExcelTrait
{
    protected function generateExcel(RumahTangga $rt): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Tenaga Kerja');

        // ── STYLE HELPER ──
        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1a7a5e']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical'   => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ];
        $subHeaderStyle = [
            'font'      => ['bold' => true],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'c3ece3']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ];
        $dataStyle = [
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['vertical'   => Alignment::VERTICAL_CENTER],
        ];

        // ── HEADER JUDUL ──
        $sheet->mergeCells('A1:N1');
        $sheet->setCellValue('A1', 'DATA TENAGA KERJA DESA');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '145e48']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // ── INFO LOKASI ──
        $sheet->mergeCells('A2:B2'); $sheet->setCellValue('A2', 'Provinsi');
        $sheet->mergeCells('C2:F2'); $sheet->setCellValue('C2', $rt->provinsi);
        $sheet->mergeCells('G2:H2'); $sheet->setCellValue('G2', 'Kabupaten');
        $sheet->mergeCells('I2:N2'); $sheet->setCellValue('I2', $rt->kabupaten);

        $sheet->mergeCells('A3:B3'); $sheet->setCellValue('A3', 'Kecamatan');
        $sheet->mergeCells('C3:F3'); $sheet->setCellValue('C3', $rt->kecamatan);
        $sheet->mergeCells('G3:H3'); $sheet->setCellValue('G3', 'Desa');
        $sheet->mergeCells('I3:N3'); $sheet->setCellValue('I3', $rt->desa);

        $sheet->mergeCells('A4:B4'); $sheet->setCellValue('A4', 'RT / RW');
        $sheet->mergeCells('C4:F4'); $sheet->setCellValue('C4',
            str_pad($rt->rt,3,'0',STR_PAD_LEFT) . ' / ' . str_pad($rt->rw,3,'0',STR_PAD_LEFT));
        $sheet->mergeCells('G4:H4'); $sheet->setCellValue('G4', 'Tgl. Pembuatan');
        $sheet->mergeCells('I4:N4'); $sheet->setCellValue('I4',
            $rt->tgl_pembuatan->isoFormat('D MMMM YYYY'));

        $sheet->mergeCells('A5:B5'); $sheet->setCellValue('A5', 'Nama Pendata');
        $sheet->mergeCells('C5:F5'); $sheet->setCellValue('C5', $rt->nama_pendata);
        $sheet->mergeCells('G5:H5'); $sheet->setCellValue('G5', 'Nama Responden');
        $sheet->mergeCells('I5:N5'); $sheet->setCellValue('I5', $rt->nama_responden);

        // Style info lokasi
        $sheet->getStyle('A2:N5')->applyFromArray([
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['vertical'   => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getStyle('A2:B5')->getFont()->setBold(true);
        $sheet->getStyle('G2:H5')->getFont()->setBold(true);

        // ── HEADER TABEL ANGGOTA ──
        $row      = 7;
        $headers  = [
            'No', 'Nama Lengkap', 'NIK', 'Kelamin', 'Hub. KRT',
            'NUK', 'Hub. KK', 'Sts. Kawin', 'Pendidikan',
            'Sts. Kerja', 'Jenis Kerja', 'Sub Jenis', 'Pendapatan/bln', 'Ket'
        ];
        $cols = range('A', 'N');
        foreach ($headers as $idx => $h) {
            $sheet->setCellValue($cols[$idx] . $row, $h);
        }
        $sheet->getStyle("A{$row}:N{$row}")->applyFromArray($headerStyle);
        $sheet->getRowDimension($row)->setRowHeight(20);

        // ── DATA ANGGOTA ──
        $row++;
        foreach ($rt->anggotaKeluarga as $a) {
            $sheet->setCellValue('A'.$row, $a->nuk);
            $sheet->setCellValue('B'.$row, $a->nama);
            $sheet->setCellValue('C'.$row, $a->nik);
            $sheet->setCellValue('D'.$row, $a->kelamin_text);
            $sheet->setCellValue('E'.$row, $a->hdkrt_text);
            $sheet->setCellValue('F'.$row, $a->nuk);
            $sheet->setCellValue('G'.$row, $a->hdkk_text);
            $sheet->setCellValue('H'.$row, $a->status_perkawinan_text);
            $sheet->setCellValue('I'.$row, $a->pendidikan_terakhir_text);
            $sheet->setCellValue('J'.$row, $a->status_pekerjaan_text);
            $sheet->setCellValue('K'.$row, $a->jenis_pekerjaan_text);
            $sheet->setCellValue('L'.$row, $a->sub_jenis_pekerjaan_text);
            $sheet->setCellValue('M'.$row, $a->pendapatan_per_bulan_text);
            $sheet->setCellValue('N'.$row, '');
            $sheet->getStyle("A{$row}:N{$row}")->applyFromArray($dataStyle);
            $row++;
        }

        // ── REKAPITULASI ──
        $row++;
        $sheet->mergeCells("A{$row}:N{$row}");
        $sheet->setCellValue("A{$row}", 'REKAPITULASI');
        $sheet->getStyle("A{$row}:N{$row}")->applyFromArray($subHeaderStyle);
        $row++;

        foreach ([
            ['Jumlah Anggota RT',         $rt->jart . ' orang'],
            ['Bekerja',                   $rt->jart_ab . ' orang'],
            ['Tidak/Belum Bekerja',       $rt->jart_tb . ' orang'],
            ['Masih Sekolah',             $rt->jart_ms . ' orang'],
            ['Status Validasi',           $rt->status_validasi_text],
        ] as [$label, $value]) {
            $sheet->mergeCells("A{$row}:D{$row}");
            $sheet->setCellValue("A{$row}", $label);
            $sheet->mergeCells("E{$row}:N{$row}");
            $sheet->setCellValue("E{$row}", $value);
            $sheet->getStyle("A{$row}:N{$row}")->applyFromArray($dataStyle);
            $sheet->getStyle("A{$row}")->getFont()->setBold(true);
            $row++;
        }

        // ── LEBAR KOLOM ──
        $widths = [5, 25, 20, 12, 18, 5, 18, 14, 22, 18, 18, 20, 22, 10];
        foreach ($widths as $idx => $w) {
            $sheet->getColumnDimension($cols[$idx])->setWidth($w);
        }

        // ── OUTPUT FILE ──
        $writer   = new Xlsx($spreadsheet);
        $filename = 'TenagaKerja_' . str_replace(' ', '_', $rt->nama_responden)
                    . '_RT' . str_pad($rt->rt,3,'0',STR_PAD_LEFT)
                    . '_' . now()->format('Ymd') . '.xlsx';

        return response()->streamDownload(
            function () use ($writer) { $writer->save('php://output'); },
            $filename,
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        );
    }
}