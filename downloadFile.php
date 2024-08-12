<?php
// session start
session_start();
?>
<html>
    <head>
        <link rel="stylesheet" href="overallCascadeStyleSheet.css">
    </head>
    <body>
        <?php

        function downloadFile($filePath) {
            $fileName = basename($filePath);
            $fileLength = filesize($filePath);

            header("Content-Type: application/ octet-stream");
            header("Content-Disposition: attachment; filename = \"$fileName\" ");
            header("Content-Length: $fileLength");
            header("Content-Description: File Tranfer");
            header("Expires: 0");
            header("Cache-Control: must-revalidate");
            header("Pragma: private");
            echo "inside calling. <br>";
            ob_clean();
            flush();
            readfile($filePath);
            flush();
        }

        $files = $_POST["checked"];
        $fileCount = count($files);
        $x=0;
        
        while ($x < $fileCount ) {
            $fileSource = "Uploads/".$files[$x];
            downloadFile($fileSource);
            $x++;
        }
        
        ?>
    </body>
</html>
