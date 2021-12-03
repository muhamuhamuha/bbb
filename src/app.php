<?php

    /**
     * Given an array (like a $_POST), will ensure all items in the
     * array are not empty.
     */
	function assert_no_empty_fields(array $keys): bool {
		foreach($keys as $k => $v) {
			if ( empty($v) ) {
				raise_alert("$k cannot be empty.");
				return false;
			}
		}
		return true;
	}

     

?>