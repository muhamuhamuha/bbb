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
     * Checks if user has submitted a form and if another condition has been met.
     * Just a shorthand for handling pages with multiple forms.
     * . */
    function checkRequest(bool $and_other_condition = true, string $method = 'POST'): bool {
	    return $_SERVER['REQUEST_METHOD'] === $method && $and_other_condition;
    }
    /**
    * joins all the given paths to the given $base_path.
    * 
    * TODO try to accept varargs with a default $base_path set to get_root_path()...
    */
    function join_paths(string $base_path, string ...$paths): string|bool {
        foreach ( $paths as $p ) 
            $base_path .= DIRECTORY_SEPARATOR . $p;

        return realpath($base_path);
    }

    /**
    * logs output to the browser's console.
    */
    function console_log(mixed $msg): void {
        $js_code = 'console.log(' . json_encode($msg) . ');';
        echo '<script>' . $js_code . '</script>';
    }

    /**
    * raises javascript alert on page refresh or form submit.
    */
    function raise_alert(string $msg): void {
        echo '<script>alert("' . $msg . '");</script>';
    }
?>