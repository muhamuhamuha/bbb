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

function insert_into_db(string $ddl, string $db_path = null): void {
    if ( !isset($db_path) )
        $db_path = join_paths(get_root_path(), 'database', 'bbb_database.sqlite');
    
    $db = new SQLite3($db_path);
    $result = $db->exec($ddl);
    if (!$result) {

        echo $db->lastErrorMsg();
    } else {

        console_log('records updated sucessfully @ ' . date('y-m-d h:i:s', time()));
    }
    $db->close();
}

// this stupid class isn't working, something wrong with the path
// class DataBase {

//     public function
//     __construct(string $db_name = 'bbb.sqlite',
//                 ?string $db_path = null,
//                 bool $verbose = true) {

//         date_default_timezone_set('US/Eastern');
//         $this->verbose = $verbose;

//         if ( !isset($db_path) )
//             $this->db_path = join_paths(get_root_path(), 'database', $db_name);

//         else
//             $this->db_path = join_paths($db_path, $db_name);
        
//         $this->db = new SQLite3($db_path);

//         if ( $this->verbose )
//             console_log("Opened $this->db_path @ " . date('Y-m-d h:i:s', time()));
          
//     }

//     public function
//     __destruct() {

//         $this->db->close();
//         if ( $this->verbose )
//             console_log("Closed $this->db_path @ " . date('Y-m-d h:i:s', time()));
            
//     }
// }

// and this stupid class didn't work either for the same reason as above.
// class DataBase2 {


//     public function 
//     __construct(string $db_name = 'bbb.db', ?string $db_path = null) {

//         if ( !isset($db_path) )
//             $this->db_path = join_paths(get_root_path(), 'database', $db_name);

//         else
//             $this->db_path = $db_path . $db_name;

//         $this->conn = new PDO('sqlite:' . $db_path);
//         // have it raise errors...
//         $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     }


//     public function
//     execute_ddl(string $ddl): bool {
//         $sql = $this->conn->prepare($ddl);
//         if ( $sql->execute() ) {
//             echo "<br>Successfully executed $ddl<br>";
//         }

//         print_r($sql->errorInfo());
//         return false;
//     }

//     // public function
//     // execute_simple_select(string $select_fields,
//     //                       string $table_name,
//     //                       ?string $where_clause = null,): string|bool {

//     // }

// }


//
?>