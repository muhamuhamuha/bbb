<?php
namespace db;
use SQLite3;
require_once __DIR__.'/utils.php';

// this is why it would be nice to have decorators...
function select_from_db(string $sql, string $db_path = null): array {
    if ( !isset($db_path) )
        $db_path = join_paths(get_root_path(), 'database', 'bbb_database.sqlite');
    
    $db = new SQLite3($db_path);

    // i hate php
    $result = Array();
    $ret = $db->query($sql);
    $i = 0;
    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        $result[$i] = $row;
        $i++;  // God I hate php...
    }
    $db->close();
    return $result;
}

function crud_db(string $ddl, string $db_path = null, bool $enableWarn = false) {
    if ( !isset($db_path) )
        $db_path = join_paths(get_root_path(), 'database', 'bbb_database.sqlite');
    
    $db = new SQLite3($db_path);

    if (!$enableWarn)
        error_reporting(E_ERROR | E_PARSE);

    $result = $db->exec($ddl);
    if (!$result) {
        return $db->lastErrorMsg();
    } else {
        console_log('records updated sucessfully @ ' . date('y-m-d h:i:s', time()));
    }
    $db->close();
}
