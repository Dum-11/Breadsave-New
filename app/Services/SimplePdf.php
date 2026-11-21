<?php

namespace App\Services;

/** PDF A4 sederhana tanpa dependency; font Courier (WinAnsi) untuk tabel laporan. */
class SimplePdf
{
    public static function buat(string $judul, array $baris): string
    {
        $lines = array_merge([$judul, 'Dibuat: ' . date('d/m/Y H:i'), str_repeat('-', 89)], $baris);
        $wrapped = [];
        foreach ($lines as $line) {
            foreach (explode("\n", wordwrap(self::safeText($line), 89, "\n", true)) as $part) {
                $wrapped[] = $part;
            }
        }
        $pages = array_chunk($wrapped, 57);
        if (!$pages) {
            $pages = [[]];
        }

        $objects = [null, null, null];
        $references = [];
        foreach ($pages as $pageNumber => $pageLines) {
            $contents = "BT\n/F1 9 Tf\n12 TL\n42 800 Td\n";
            foreach ($pageLines as $i => $line) {
                $contents .= ($i ? 'T* ' : '') . '(' . self::escape($line) . ") Tj\n";
            }
            $contents .= "ET\n";
            $pageId = count($objects) + 1;
            $contentId = $pageId + 1;
            $objects[] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] " .
                "/Resources << /Font << /F1 3 0 R >> >> /Contents {$contentId} 0 R >>";
            $objects[] = "<< /Length " . strlen($contents) . " >>\nstream\n{$contents}endstream";
            $references[] = $pageId . ' 0 R';
        }
        $objects[0] = '<< /Type /Catalog /Pages 2 0 R >>';
        $objects[1] = '<< /Type /Pages /Kids [' . implode(' ', $references) . '] /Count ' . count($pages) . ' >>';
        $objects[2] = '<< /Type /Font /Subtype /Type1 /BaseFont /Courier /Encoding /WinAnsiEncoding >>';

        $pdf = "%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";
        $offsets = [0];
        foreach ($objects as $i => $obj) {
            $id = $i + 1;
            $offsets[$id] = strlen($pdf);
            $pdf .= $id . " 0 obj\n" . $obj . "\nendobj\n";
        }
        $start = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n0000000000 65535 f \n";
        for ($id = 1; $id <= count($objects); ++$id) {
            $pdf .= sprintf('%010d 00000 n ', $offsets[$id]) . "\n";
        }
        $pdf .= 'trailer' . "\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\n" .
            "startxref\n" . $start . "\n%%EOF\n";
        return $pdf;
    }

    private static function safeText(string $text): string
    {
        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $text);
            if ($converted !== false) {
                return $converted;
            }
        }
        return preg_replace('/[^\x20-\x7E]/', '?', $text);
    }

    private static function escape(string $text): string
    {
        return str_replace(["\\", '(', ')'], ["\\\\", '\\(', '\\)'], $text);
    }
}
