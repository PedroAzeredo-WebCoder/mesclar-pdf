<?php
require('fpdf/fpdf.php');
require('fpdi/src/autoload.php');

function mergePDFs($pdf1, $pdf2, $outputFilename)
{
    $pdf = new \setasign\Fpdi\Fpdi();

    // Obtenha o número de páginas
    $pagecount1 = $pdf->setSourceFile($pdf1);
    $pagecount2 = $pdf->setSourceFile($pdf2);

    // Percorra cada página
    for ($i = 1; $i <= max($pagecount1, $pagecount2); $i++) {
        // Se ainda houver uma página no pdf1, adicione-a
        if ($i <= $pagecount1) {
            $pdf->setSourceFile($pdf1);
            $tplIdx = $pdf->importPage($i);
            $pdf->AddPage();
            $pdf->useTemplate($tplIdx, 0, 0, $pdf->getTemplateSize($tplIdx)['width'], $pdf->getTemplateSize($tplIdx)['height']);
            echo "Adicionada página $i do $pdf1\n";
        }

        // Se ainda houver uma página no pdf2, adicione-a
        if ($i <= $pagecount2) {
            $pdf->setSourceFile($pdf2);
            $tplIdx = $pdf->importPage($i);
            $pdf->AddPage();
            $pdf->useTemplate($tplIdx, 0, 0, $pdf->getTemplateSize($tplIdx)['width'], $pdf->getTemplateSize($tplIdx)['height']);
            echo "Adicionada página $i do $pdf2\n";
        }
    }

    // Salva o PDF resultante
    $pdf->Output($outputFilename, 'D');
    $pdf->Output($outputFilename, 'I');
    $pdf->Output($outputFilename, 'F');
}

// Substitua 'arquivo1.pdf', 'arquivo2.pdf' e 'output.pdf' com os nomes dos seus arquivos
mergePDFs('arquivo1.pdf', 'arquivo2.pdf', 'output.pdf');

echo 'PDFs foram mesclados com sucesso!';
