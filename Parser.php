<?php


class Parser {
	public $dictionaryRegex;
	public $dictionaryReplace;
	public $dictionary = array();
	public $keywords = array();
	private $patternMatcher;
	private $foundKeywords;
	public function __construct() {		
		
        $this->keywords  = array('keywords','go','here');    	
    	
		
    }
   /*
	 * Sets a dictionary of keywords to use for parsing content
	 * @param $keywordArray -- an array of words e.g. array('cat','dog','mouse')	 
	 * 
	 */
    public function createRegexDictionary($keywordArray){
    	 $this->dictionaryReplace = array();
    	 $this->dictionaryRegex = array();
    	foreach ($keywordArray as $keyword) {
    		$this->dictionaryReplace[] = '<span class="keyword_highlight">$1</span>';
    		$this->dictionaryRegex[] = '/(?!(?:[^<\[]+[>\]]|[^>\]]+<\/a>))\b('.preg_quote($keyword, "/").')\b/msiU';
    	}
    	
    	
    }
    public function findKeywords($content){
    	$this->dictionary = array();    	
    	foreach($this->keywords as $word){
    		if(strpos($content, $word) !== false){
    			$this->dictionary[] = $word;
    		}
    	}
    	usort($this->dictionary, array( $this, 'longestfirst' ));
    }    
    /*
	 * Parses content
	 * @param $content -- the content to be parsed 
	 * @return The modified content -- each keyword will have a <span> tag wrapped around it 
	 */
    public function parseContent($content){
    	 ini_set('max_execution_time', 300); //300 seconds = 5 minutes
	  	$this->findKeywords(strtolower($content));		
    	$updatedContent = $content;    	
    	if(count($this->dictionary) > 0){
    		$this->createRegexDictionary($this->dictionary);    		
    		$updatedContent = preg_replace($this->dictionaryRegex, $this->dictionaryReplace, $content);    		
    	}
    	
    	return $updatedContent;
    }
	private function longestfirst($str1, $str2) {
   		 return strlen($str2) - strlen($str1);
    }
    
    
}

