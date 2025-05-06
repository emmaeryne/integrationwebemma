<?php

namespace App\Service;

use Symfony\Component\Process\Process;
use Symfony\Component\HttpKernel\KernelInterface;

class PdfGenerator
{
    public function __construct(
        private KernelInterface $kernel
    ) {}

    public function generatePdf(string $html): string
    {
        $tempDir = sys_get_temp_dir();
        $htmlFile = tempnam($tempDir, 'html_');
        $pdfFile = tempnam($tempDir, 'pdf_');
        
        try {
            file_put_contents($htmlFile, $html);
            
            $command = [
                'wkhtmltopdf',
                '--enable-local-file-access',
                '--encoding', 'UTF-8',
                '--margin-top', '10mm',
                '--margin-right', '10mm',
                '--margin-bottom', '10mm',
                '--margin-left', '10mm',
                '--page-size', 'A4',
                '--javascript-delay', '1000',
                $htmlFile,
                $pdfFile
            ];
            
            $process = new Process($command);
            $process->setTimeout(60);
            $process->run();
            
            if (!$process->isSuccessful()) {
                throw new \RuntimeException($process->getErrorOutput());
            }
            
            return file_get_contents($pdfFile);
            
        } finally {
            if (file_exists($htmlFile)) {
                unlink($htmlFile);
            }
            if (file_exists($pdfFile)) {
                unlink($pdfFile);
            }
        }
    }
}