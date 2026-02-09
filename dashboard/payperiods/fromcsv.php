<?php
include $_SERVER['DOCUMENT_ROOT'] . "/auth/session.php";
include $_SERVER['DOCUMENT_ROOT']. "/app/functions.php";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        if (!isset($_FILES['file']['error']) || is_array($_FILES['file']['error'])) {
            throw new RuntimeException("Invalid file");
        }

        switch ($_FILES['file']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }

        if ($_FILES['file']['size'] > 1000000) {
            throw new RuntimeException('Exceeded filesize limit.');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search(
            $finfo->file($_FILES['file']['tmp_name']),
            array(
                'csv' => 'text/csv',
                'txt' => 'text/plain',
                'xls' => 'application/vnd.ms-excel',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

            ),
            true
        )) {
            throw new RuntimeException('Invalid file format.');
        }
    } catch (RuntimeException $e) {

        echo $e->getMessage();
    }

    echo $_FILES['file']['tmp_name']."<br>";


    $filecontents = file_get_contents($_FILES['file']['tmp_name']);

    submit_to_sql($filecontents, $_POST['ppid']);
    
}



if ($_SERVER['REQUEST_METHOD'] == 'GET'):
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/head.php"; ?>


    </head>

    <body class="w-full">
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/header.php"; ?>
        <main>
            <section class="place-content-center">
                <div class="flex flex-col justify-center items-center p-3 m-4 border-solid rounded-4xl  border-4 border-black shadow-2xl">
                    <h2 class="text-center text-2xl mb-5">Edit Pay Period</h2>
                    <div class="flex flex-col justify-center items-center w-full">
                        <form class="w-full max-w-md" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="ppid" readonly value="<?= $_GET['id'] ?>">
                            <div class="grid grid-cols-3 grid-rows-2 gap-4">
                                <div class="p-2 sm:p-auto col-span-3 bg-slate-200 rounded border border-black border-solid">
                                    <label for="file">File</label>
                                    <input type="file" class="max-w-full border border-black" name="file" accept=".csv, .txt, .xls, text/csv">
                                </div>
                            </div>
                            <div class="grid grid-cols-3 grid-rows-2 gap-4">
                                <div class="p-2 sm:p-auto col-span-3 bg-slate-200 rounded border border-black border-solid">
                                    <button type="submit" class="w-full h-full">Load</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </main>


        <script>

        </script>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/components/footer.php"; ?>
    </body>

    </html>
<?php endif; ?>