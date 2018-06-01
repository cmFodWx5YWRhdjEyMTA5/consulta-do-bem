<?php

if (!defined('FW')) {
    die('Forbidden');
}

class FW_Extension_Articles extends FW_Extension {

    /**
     * @internal
     */
    public function _init() {
        $this->register_article_post_type();
    }

    /**
     * @Render Articles Listing
     * @return type
     */
    public function render_article_listing() {
        return $this->render_view('listing');
    }

    /**
     * @Render Articles Add View
     * @return type
     */
    public function render_add_articles() {
        return $this->render_view('add');
    }

    /**
     * @Render Articles Edit View
     * @return type
     */
    public function render_edit_articles() {
        return $this->render_view('edit');
    }
	
	/**
     * @Render Articles Edit View
     * @return type
     */
    public function render_display_dashboard_articles() {
        return $this->render_view('articles');
    }

    /**
     * @access Private
     * @Register Post Type
     */
    private function register_article_post_type() {
		if( class_exists('DocDirect_Articles') ) {
        	new DocDirect_Articles();
		}
    }

}
