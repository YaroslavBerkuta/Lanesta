<?php

namespace WebpConverter\Settings\Option;

/**
 * .
 */
class OptionsAggregator {

	/**
	 * Objects of supported options.
	 *
	 * @var OptionInterface[]
	 */
	private $options = [];

	public function __construct() {
		$this->set_option( new LoaderTypeOption() );
		$this->set_option( new SupportedExtensionsOption() );
		$this->set_option( new SupportedDirectoriesOption() );
		$this->set_option( new ConversionMethodOption() );
		$this->set_option( new OutputFormatsOption() );
		$this->set_option( new ImagesQualityOption() );
		$this->set_option( new ExtraFeaturesOption() );
	}

	/**
	 * @return OptionInterface[]
	 */
	public function get_options(): array {
		$options = apply_filters( 'webpc_settings_options', $this->options );

		usort(
			$options,
			function ( OptionInterface $option_a, OptionInterface $option_b ) {
				return $option_a->get_priority() <=> $option_b->get_priority();
			}
		);
		return $options;
	}

	/**
	 * @param OptionInterface $new_option .
	 *
	 * @return void
	 */
	private function set_option( OptionInterface $new_option ) {
		foreach ( $this->options as $option_index => $option ) {
			if ( $option->get_name() === $new_option->get_name() ) {
				$this->options[ $option_index ] = $new_option;
				return;
			}
		}

		$this->options[] = $new_option;
	}
}
