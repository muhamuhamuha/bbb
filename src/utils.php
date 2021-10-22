<?php

/**
 * Recursively gets the project's root path.
 * 
 */
function get_root_path(string $path = __DIR__,
                       string $project_name = 'bbb'): string|bool {
    $path = realpath($path);
    if (basename($path) === $project_name)
        return $path;

    elseif (dirname($path) === $path)
        return false;
            
    return get_root_path( dirname($path) );
}


/**
 * joins all the given paths to the given $base_path.
 * 
 */
function join_paths(string $base_path, string ...$paths): string {
    foreach ( $paths as $p ) 
        $base_path .= DIRECTORY_SEPARATOR . $p;

    return $base_path;
}

?>