<?php
/**
 * SwissCode aka PHP MacGyver
 *
 * Swiss Army Knife but for php coding
 * Some (basic) utilities functions 
 *
 *	Copyright (c) 2013 - 92 Bond Street, Yassine Azzout
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 *	The above copyright notice and this permission notice shall be included in
 *	all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package SwissCode
 * @version 1.0
 * @copyright 2013 - 92 Bond Street, Yassine Azzout
 * @author Yassine Azzout
 * @link http://www.92bondstreet.com SwissCode
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
 
 // Random user agent for curl
 require_once('useragent.php');
// PHP Simple HTML DOM Parser
// Download on http://sourceforge.net/projects/simplehtmldom/
 require_once('simple_html_dom.php');
 
/**
 * THE PERFECT PHP CLEAN URL GENERATOR
 * http://cubiq.org/the-perfect-php-clean-url-generator
 *
 * @param $str, $replace, $delimiter
 *
 * @return string
 */

function MacG_toAscii($str, $replace=array(), $delimiter='-') {
	if( !empty($replace) ) {
		$str = str_replace((array)$replace, ' ', $str);
	}

	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
	$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
	$clean = strtolower(trim($clean, '-'));
	
	return $clean;
}

/**
 * trims text to a space then adds ellipses if desired
 * @param string $input text to trim
 * @param int $length in characters to trim to
 * @param bool $ellipses if ellipses (...) are to be added
 * @param bool $strip_html if html tags are to be stripped
 * @return string 
 */
function MacG_trim_text($input, $length, $ellipses = true, $strip_html = true) {
    //strip tags, if desired
    if ($strip_html) {
        $input = strip_tags($input);
    }
  
    //no need to trim, already shorter than trim length
    if (strlen($input) <= $length) {
        return $input;
    }
  
    //find last space within length
    $last_space = strrpos(substr($input, 0, $length), ' ');
    $trimmed_text = substr($input, 0, $last_space);
  
    //add ellipses (...)
    if ($ellipses) {
        $trimmed_text .= '...';
    }
  
    return $trimmed_text;
}

/**
 * Connect to an URL with CURL
 *
 * @param $url		to connect
 * @param $proxy	The HTTP proxy to tunnel requests through.
 * @param $userpwd	A username and password formatted as "[username]:[password]" to use for the connection to the proxy.
 *
 * @return content
 */
 
function MacG_connect_to($url, $proxy=NULL, $userpwd=NULL){
			
	$ch = curl_init();

	// webnavigator simulator
	$header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
	$header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
	$header[] = "Cache-Control: max-age=0";
	$header[] = "Connection: keep-alive";
	$header[] = "Keep-Alive: 300";
	$header[] = "Accept-Charset: utf-8";
	$header[] = "Accept-Language: en"; // en language
	$header[] = "Pragma: "; // simulate brower
	$useragent = random_user_agent();
	
	
	//Set options for curl session
	$options = array(CURLOPT_USERAGENT => $useragent,	
			 CURLOPT_TIMEOUT => 60,
			 /*CURLOPT_HEADER => TRUE,*/
			 CURLOPT_HTTPHEADER => $header,
			 CURLOPT_RETURNTRANSFER => TRUE,
			 CURLOPT_COOKIEFILE => 'cookie.txt',
			 CURLOPT_COOKIEJAR => 'cookies.txt',
			 CURLOPT_SSL_VERIFYPEER => FALSE,
			 CURLOPT_FOLLOWLOCATION => TRUE);

	if(isset($proxy)){
		$options[CURLOPT_HTTPPROXYTUNNEL] = 1;
		$options[CURLOPT_PROXY] =  $proxy;
		$options[CURLOPT_PROXYTYPE] =  CURLPROXY_HTTP;
		if(isset($userpwd))			
			$options[CURLOPT_PROXYUSERPWD] =  $userpwd;
    }
			 
	$options[CURLOPT_URL] = $url;
	$options[CURLOPT_FOLLOWLOCATION] = TRUE;
	curl_setopt_array($ch, $options);
	$content = curl_exec($ch);

	// Error or not ?
	if(curl_errno($ch))  
	{ 
	   echo 'Erreur Curl : ' . curl_error($ch);  
	} 
	
	//Close curl session
	curl_close($ch);
	
	return $content;
}
 
 
?>