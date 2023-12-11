<?php
require('fpdf/fpdf.php');
require('fpdi/src/autoload.php');

function updateProgressBar($current, $total)
{
    $progress = ($current / $total) * 100;
    $barLength = 50;
    $numBars = round($progress / (100 / $barLength));
    $numSpaces = $barLength - $numBars;

    echo "\r[";
    echo str_repeat("=", $numBars);
    echo str_repeat(" ", $numSpaces);
    echo "] " . round($progress, 2) . "%";
}

function mergePDFs($pdf1, $pdf2, $outputFilename)
{
    $pdf = new \setasign\Fpdi\Fpdi();

    $pagecount1 = $pdf->setSourceFile($pdf1);
    $pagecount2 = $pdf->setSourceFile($pdf2);
    $totalPages = max($pagecount1, $pagecount2);

    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i <= $pagecount1) {
            $pdf->setSourceFile($pdf1);
            $tplIdx = $pdf->importPage($i);
            $pdf->AddPage();
            $pdf->useTemplate($tplIdx, 0, 0, $pdf->getTemplateSize($tplIdx)['width'], $pdf->getTemplateSize($tplIdx)['height']);
        }

        if ($i <= $pagecount2) {
            $pdf->setSourceFile($pdf2);
            $tplIdx = $pdf->importPage($i);
            $pdf->AddPage();
            $pdf->useTemplate($tplIdx, 0, 0, $pdf->getTemplateSize($tplIdx)['width'], $pdf->getTemplateSize($tplIdx)['height']);
        }

        // Atualiza a barra de loading
        updateProgressBar($i, $totalPages);
    }

    echo "\nPDFs foram mesclados com sucesso!\n";

    // Salva o PDF resultante
    $pdf->Output($outputFilename, 'F');
}

// Substitua 'arquivo1.pdf', 'arquivo2.pdf' e 'output.pdf' com os nomes dos seus arquivos
mergePDFs('arquivo1.pdf', 'arquivo2.pdf', 'output2.pdf');
