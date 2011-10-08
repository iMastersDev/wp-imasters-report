<?php
class YouTube {

    private $videos;

    function __construct($url) {
        $xml = simplexml_load_file($url);
        $this->videos = $xml->entry;
    }

    public function getTitle() {
        return $this->videos->title;
    }

    public function getContent() {
        return $this->videos->content;
    }

    public function getLink() {
        return $this->videos->link["href"];
    }

    public function getID() {
        $url_id = $this->videos->id;
        $id = explode('/', $url_id);
        return $id[6];
    }

}