<?php

class Smartcrawl_Check_Imgalts_Keywords extends Smartcrawl_Check_Abstract {

	private $_state;

	private $_image_count = 0;

	private $_images_with_focus_count = 0;

	public function get_status_msg() {
		$image_count = $this->_image_count ? $this->_image_count : 0;

		if ( $this->_state ) {
			$message = esc_html__( "A good balance of images contain the focus keyword(s) in their alt attribute text", 'wds' );
		} else if ( 0 === $image_count ) {
			$message = esc_html__( "You haven't added any images", 'wds' );
		} else {
			$percentage = $this->get_percentage();
			if ( $percentage > 75 ) {
				$message = esc_html__( 'Too many of your image alt texts contain the focus keyword(s)', 'wds' );
			} elseif ( $percentage === 0 ) {
				$message = esc_html__( 'None of your image alt texts contain the focus keyword(s)', 'wds' );
			} else {
				$message = esc_html__( 'Too few of your image alt texts contain the focus keyword(s)', 'wds' );
			}
		}

		return $message;
	}

	public function apply() {
		$subjects = Smartcrawl_Html::find( 'img', $this->get_markup() );
		$this->_image_count = count( $subjects );
		if ( empty( $subjects ) ) {
			return false;
		}

		foreach ( $subjects as $subject ) {
			$alt = $subject->getAttribute( 'alt' );

			$this->_images_with_focus_count += (int) $this->has_focus( $alt );
		}

		$this->_state = $this->is_check_successful();

		return ! ! $this->_state;
	}

	private function is_check_successful() {
		if ( $this->_image_count < 5 ) {
			return (bool) $this->_images_with_focus_count;
		} else {
			$percentage = $this->get_percentage();
			return $percentage >= 30 && $percentage <= 75;
		}
	}

	private function get_percentage() {
		$image_count = $this->_image_count;
		if ( ! $image_count ) {
			return 0;
		}

		$images_with_focus = $this->_images_with_focus_count;
		return $images_with_focus / $image_count * 100;
	}

	public function get_recommendation() {
		$image_count = $this->_image_count ? $this->_image_count : 0;
		$images_with_focus_count = $this->_images_with_focus_count ? $this->_images_with_focus_count : 0;

		if ( $this->_state ) {
			$message = esc_html__( "Alternative attribute text for images help search engines correctly index images and aid visually impaired readers. The text is also used in place of the image if it's unable to load. You should add alternative text for all images in your content.", 'wds' );
		} else if ( 0 === $image_count ) {
			$message = esc_html__( "Images are a great addition to any piece of content and it’s highly recommended to have imagery on your pages. Consider adding a few images that relate to your body content to enhance the reading experience of your article. Where possible, it’s also a great opportunity to include your focus keyword(s) to further associate the article with the topic you’re writing about.", 'wds' );
		} else {
			$percentage = $this->get_percentage();
			if ( $percentage > 75 ) {
				$message = sprintf(
					esc_html__( '%d/%d images on this page have alt text with your keyword(s) which is too much. Whilst it’s great that you have image alternative text with your focus keyword(s), you can also get penalized for having too many keywords on a page. Try to include your keyword(s) in image alt texts only when it makes sense.', 'wds' ),
					$images_with_focus_count,
					$image_count
				);
			} elseif ( $percentage === 0 ) {
				$message = esc_html__( 'None of the images on this page have alt text containing your focus keyword. It’s recommended practice to have your topic keywords in a few of your images to further associate the article with the topic you’re writing about. Add your keyword to one or more of your images, but be careful not to overdo it.', 'wds' );
			} else {
				$message = sprintf(
					esc_html__( '%d/%d images on this page have alt text with your chosen keyword(s). Alternative attribute text for images helps search engines correctly index images and aid visually impaired readers. It’s recommended practice to have your topic keywords in a good number of your images to further associate the article with the topic you’re writing about. Add your keyword(s) to a few more of your images, but be careful not to overdo it.', 'wds' ),
					$images_with_focus_count,
					$image_count
				);
			}
		}

		return $message;
	}

	public function get_more_info() {
		return esc_html__( "Image alternative text attributes help search engines correctly index images, aid visually impaired readers, and the text is used in place of the image if it's unable to load. You should add alternative text for all images in your content.", 'wds' );
	}
}
