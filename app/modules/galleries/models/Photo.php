<?php

class Photo extends Eloquent {
	protected $guarded = array();

	protected $table = 'photos';

	public static $order_by = 'photos.id DESC';

	public function thumb() {
		#return link::to(Config::get('site.galleries_thumb_dir')) . "/" . $this->name;
		return URL::to(Config::get('site.galleries_thumb_public_dir') . "/" . $this->name);
	}

	public function full() {
		return $this->path();
	}

	public function path() {
		#return link::to(Config::get('site.galleries_photo_dir')) . "/" . $this->name;
		return URL::to(Config::get('site.galleries_photo_public_dir') . "/" . $this->name);
	}

    public function fullpath() {
        #return link::to(Config::get('site.galleries_photo_dir')) . "/" . $this->name;
        return str_replace('//', '/', public_path(Config::get('site.galleries_photo_public_dir') . "/" . $this->name));
    }

    public function extract() {
        return $this;
    }

	public function cachepath($w, $h, $method = 'crop') {
		return URL::to(Config::get('site.galleries_cache_public_dir') . "/" . $this->id . "_" . $w . "x" . $h . ($method == 'resize' ? 'r' : '') . ".png");
	}

	public function fullcachepath($w, $h, $method = 'crop') {
		return str_replace('//', '/', public_path(Config::get('site.galleries_cache_public_dir') . "/" . $this->id . "_" . $w . "x" . $h . ($method == 'resize' ? 'r' : '') . ".png"));
	}

}