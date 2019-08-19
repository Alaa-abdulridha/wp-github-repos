<?php

class Github_API {
    
    const GITHUB = "https://api.github.com";

    function __construct() {
      
    }
    public function get_repos($user) {
        $path = "/users/$user/repos";
        $response = $this->github_request($path);
        $repos = array();
        return $response;
    }

    private function github_request($path) {
        @$url = self::GITHUB . $path;
    
				$context = stream_context_create(
    array(
        "http" => array(
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
						)
		)
	);

		@$result = json_decode(file_get_contents($url, false, $context));
		if (!empty($result))
			return @$result;
		else
			return false;
		
    }
} 
?>
