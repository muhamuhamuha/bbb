<?php
require __DIR__.'/utils.php';

function upload_csv_to_db(string $db_name = 'bbb.db',
                          ?string $db_path = null): bool {

    if ( !isset($db_path) )
        $db_path = 'hello';

}

?>