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

	/**
	 * multiplies all the quantities in the array of records
	 * after summing them, by 2.
	 */
	function calcShipping(array $books, string $quantityCol = 'Quantity'): int {
		// filter out quantites and cast
		$quantities = array_map(function($x) use ($quantityCol) { return intval($x[$quantityCol]); },
														$books);

		return array_sum($quantities) * 2;
	}

  /**
	 * Given an array of records with a price field and quantity field,
	 * this function will output the subtotal after casting the data to
	 * the correct type.
	 */ 
	function calcSubtotal(array $books,
												string $priceCol = 'Price',
												string $quantityCol = 'Quantity'): float {
		// filter out prices and cast
		$prices = array_map(function($x) use ($priceCol) { return floatval($x[$priceCol]); },
												$books);
		// filter out quantites and cast
		$quantities = array_map(function($x) use ($quantityCol) { return intval($x[$quantityCol]); },
														$books);

		for ($i = 0; $i < count($prices); $i++) {
			$prices[$i] = $quantities[$i] * $prices[$i];
		}
		return array_sum($prices);
	}

?>