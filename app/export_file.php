<?php


function export_file($rows, $cols, $tables, $tableNames, $data)
{
    $tableStyle = [
        'borderColor' => "000000",
        'borderSize' => 2,
        'layout' => 'autofit',
        // 'cellSpacing' => 15
        'bidiVisual' => false,
        'alignment' => 'left'
    ];
    $cellStyle = [
        'valign' => 'center'
    ];
    $cellTextStyle = [
        'bold' => true,
        'allCaps' => true
    ];
    $header = array('size' => 48, 'bold' => true, 'center' => true, 'vAlign' => 'both');
    $sectionStyle = [
        'marginLeft' => 25,
        'marginRight' => 25
    ];

    $tables = explode(", ", $tables);
    $tables = array_reverse($tables);
    $tableNames = array_reverse($tableNames);

    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $section = $phpWord->addSection($sectionStyle);
    $section->addText(htmlspecialchars('Bibliometr'), $header);
    $tableR = $section->addTable($tableStyle);

    $pubName = "publication_" . $data[0]['title'];

    $tableR->addRow(900);
    foreach ($tableNames as $table) {
        foreach ($tables as $name) {
            if (isset($table[$name])) {
                $tableR->addCell(null, $cellStyle)->addText(htmlspecialchars($table[$name]), $cellTextStyle);
            }
        }
    }

    foreach ($data as $pub) {
        $tableR->addRow();
        foreach ($tables as $tableName) {
            $val = $pub[$tableName];
            if ($tableName === "authors") {
                $val = join(", ", json_decode($val));
            } elseif ($tableName === "shares") {
                $shares = [];
                $sh = json_decode($val);
                if ($sh) {

                    foreach ($sh as $au => $val) {
                        array_push($shares, $au . ": " . $val);
                    }
                    $val = join(", ", $shares);
                } else {
                    $val = "";
                }
            }

            $tableR->addCell()->addText(htmlspecialchars($val));
        }
    }

    $file = $pubName . '.docx';
    // $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    // $objWriter->save($file);
    // return $file;

    header("Content-Description: File Transfer");
    header('Content-Disposition: attachment; filename="' . $file . '"');
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Expires: 0');
    $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    $xmlWriter->save("php://output");
    // header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessing‌​ml.document"); // you should look for the real header that you need if it's not Word 2007!!!
    // header( 'Content-Disposition: attachment; filename='.$file );

    // $h2d_file_uri = tempnam( "", "htd" );
    // $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, "Word2007");
    // $objWriter->save("php://output");

    // header("Location: index.php");
}
