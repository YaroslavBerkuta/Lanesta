<?php

abstract class Smartcrawl_Schema_Loop {
	/**
	 * @param $id
	 * @param $post
	 *
	 * @return Smartcrawl_Schema_Loop
	 */
	public static function create( $id, $post ) {
		switch ( $id ) {
			case Smartcrawl_Schema_Loop_Woocommerce_Reviews::ID:
				return new Smartcrawl_Schema_Loop_Woocommerce_Reviews( $post );

			case Smartcrawl_Schema_Loop_Comments::ID:
				return new Smartcrawl_Schema_Loop_Comments( $post );

			default:
				return null;
		}
	}

	public abstract function get_property_value( $property );
}
