<?php

namespace App\Services;

/** XLSX Open XML asli (bukan CSV), tanpa ekstensi tambahan. */
class SimpleXlsx
{
    public static function buat(array $rows): string
    {
        $files = [];
            $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
            $sheet = $xml . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">';
            $sheet .= '<sheetViews><sheetView workbookViewId="0"/></sheetViews><sheetFormatPr defaultRowHeight="17"/>';
            $sheet .= '<sheetData>';

            foreach ($rows as $rowIndex => $row) {
                $rowNum = $rowIndex + 1;
                $sheet .= '<row r="' . $rowNum . '">';
                foreach (array_values($row) as $colIndex => $value) {
                    $cell = self::kolom($colIndex) . $rowNum;
                    $style = $rowIndex === 0 ? ' s="1"' : '';
                    if (is_int($value) || is_float($value)) {
                        $number = is_finite((float) $value) ? (string) $value : '0';
                        $sheet .= '<c r="' . $cell . '"' . $style . '><v>' . $number . '</v></c>';
                    } else {
                        // Inline string mencegah formula injection dari nama akun / input user.
                        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', (string) $value);
                        $escaped = htmlspecialchars($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
                        $sheet .= '<c r="' . $cell . '" t="inlineStr"' . $style . '><is><t xml:space="preserve">' .
                            $escaped . '</t></is></c>';
                    }
                }
                $sheet .= '</row>';
            }
            $sheet .= '</sheetData></worksheet>';

            self::add($files, '[Content_Types].xml', $xml .
                '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">' .
                '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>' .
                '<Default Extension="xml" ContentType="application/xml"/>' .
                '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>' .
                '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>' .
                '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>' .
                '</Types>');
            self::add($files, '_rels/.rels', $xml .
                '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' .
                '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>' .
                '</Relationships>');
            self::add($files, 'xl/workbook.xml', $xml .
                '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" ' .
                'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">' .
                '<sheets><sheet name="Laporan BreadSaver" sheetId="1" r:id="rId1"/></sheets></workbook>');
            self::add($files, 'xl/_rels/workbook.xml.rels', $xml .
                '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' .
                '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>' .
                '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>' .
                '</Relationships>');
            self::add($files, 'xl/styles.xml', $xml .
                '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">' .
                '<fonts count="2"><font><sz val="11"/><name val="Calibri"/></font>' .
                '<font><b/><color rgb="FFFFFFFF"/><sz val="11"/><name val="Calibri"/></font></fonts>' .
                '<fills count="3"><fill><patternFill patternType="none"/></fill>' .
                '<fill><patternFill patternType="gray125"/></fill>' .
                '<fill><patternFill patternType="solid"><fgColor rgb="FF23365F"/><bgColor indexed="64"/></patternFill></fill></fills>' .
                '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>' .
                '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>' .
                '<cellXfs count="2"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>' .
                '<xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1"/></cellXfs>' .
                '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>' .
                '</styleSheet>');
            self::add($files, 'xl/worksheets/sheet1.xml', $sheet);

        // Penyusun ZIP (STORED) internal: tidak perlu ekstensi zip Composer/XAMPP.
        return self::zip($files);
    }

    private static function add(array &$files, string $name, string $content): void
    {
        $files[$name] = $content;
    }

    private static function zip(array $files): string
    {
        $output = '';
        $central = '';
        $count = 0;
        foreach ($files as $name => $data) {
            $length = strlen($data);
            $crc = crc32($data);
            $offset = strlen($output);
            $nameLength = strlen($name);
            // ZIP local header (store, tidak dikompresi)
            $output .= pack('VvvvvvVVVvv',
                0x04034b50, 20, 0, 0, 0, 0, $crc, $length, $length, $nameLength, 0
            ) . $name . $data;
            // ZIP central-directory header
            $central .= pack('VvvvvvvVVVvvvvvVV',
                0x02014b50, 20, 20, 0, 0, 0, 0, $crc, $length, $length,
                $nameLength, 0, 0, 0, 0, 0, $offset
            ) . $name;
            ++$count;
        }
        $directoryOffset = strlen($output);
        $output .= $central;
        // End of central directory
        $output .= pack('VvvvvVVv',
            0x06054b50, 0, 0, $count, $count, strlen($central), $directoryOffset, 0
        );
        return $output;
    }

    private static function kolom(int $index): string
    {
        $name = '';
        do {
            $name = chr(65 + ($index % 26)) . $name;
            $index = intdiv($index, 26) - 1;
        } while ($index >= 0);
        return $name;
    }
}
