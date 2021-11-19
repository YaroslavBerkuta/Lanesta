<?php

namespace WebpConverter\Conversion\Format;

/**
 * Supports AVIF as output format for images.
 */
class AvifFormat extends FormatAbstract {

	const FORMAT_EXTENSION = 'avif';

	/**
	 * {@inheritdoc}
	 */
	public function get_extension(): string {
		return self::FORMAT_EXTENSION;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_mime_type(): string {
		return 'image/avif';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_label(): string {
		/* translators: %s format name */
		return sprintf( __( '%s (planned soon)', 'webp-converter-for-media' ), 'AVIF' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_available( string $conversion_method ): bool {
		return false;
	}
}
