<?php
// (c) Copyright 2002-2016 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

/**
 *
 */
class PdfGenerator
{
	private $mode;
	private $location;

    /**
     *
     */
	function __construct()
	{
		global $prefs;
		$this->mode = 'none';
		$this->error = false;

		if ( $prefs['print_pdf_from_url'] == 'webkit' ) {
			$path = $prefs['print_pdf_webkit_path'];
			if (!empty($path) && is_executable($path)) {
				$this->mode = 'webkit';
				$this->location = $path;
			} else {
				if (!empty($path)) {
					$this->error = tr('PDF webkit path "%0" not found.', $path);
				} else {
					$this->error = tr('The PDF webkit path has not been set.');
				}
			}
		} else if ($prefs['print_pdf_from_url'] == 'weasyprint') {
			$path = $prefs['print_pdf_weasyprint_path'];
			if (!empty($path) && is_executable($path)) {
				$this->mode = 'weasyprint';
				$this->location = $path;
			} else {
				if (!empty($path)) {
					$this->error = tr('PDF WeasyPrint path "%0" not found.', $path);
				} else {
					$this->error = tr('The PDF WeasyPrint path has not been set.');
				}
			}
		} elseif ( $prefs['print_pdf_from_url'] == 'webservice' ) {
			$path = $prefs['path'];
			if ( ! empty($path) ) {
				$this->mode = 'webservice';
				$this->location = $path;
			} else {
				if (!empty($path)) {
					$this->error = tr('PDF webservice URL "%0" not found.', $path);
				} else {
					$this->error = tr('The PDF webservice URL has not been set.');
				}
			}
		} elseif ( $prefs['print_pdf_from_url'] == 'mpdf' ) {
			$path = $prefs['print_pdf_mpdf_path'];
			if (substr($path, -1) !== '/') {
				$path .= '/';
			}
			if ( ! empty($path) && is_readable($path) && file_exists($path . 'mpdf.php')) {
				self::setupMPDFCacheLocation();

				//setting up dir for custom fonts and mpdf default fonts
				define('_MPDF_TTFONTPATH',TIKI_PATH.'/lib/pdf/fontdata/fontttf/');
		        define('_MPDF_SYSTEM_TTFONTS', $path. '/ttfonts/');


				if (!class_exists('mPDF')){
					include_once($path . 'mpdf.php');
				}
				if (! is_writable(_MPDF_TEMP_PATH) ||! is_writable(_MPDF_TTFONTDATAPATH)) {
					$this->error = tr('mPDF "%0" and "%1" directories must be writable', 'tmp',
						'ttfontdata');
				} else {
					$this->mode = 'mpdf';
					$this->location = $path;
				}
			} else {
				if (!empty($path)) {
					$this->error = tr('mPDF not found in path "%0"', $path);
				} else {
					$this->error = tr('The mPDF path has not been set.');
				}
			}
		}
		if ($this->error) {
			$this->error = tr('PDF generation failed.') . ' ' . $this->error . ' '
				. tr('This is set by the administrator (search for "pdf" in the settings control panels to locate the setting).');
		}
	}

    /**
     * @param $file
     * @param array $params
     * @return mixed
     */
    function getPdf( $file, array $params, $pdata='' )
	{
		global $prefs, $base_url, $tikiroot;

		if ( $prefs['auth_token_access'] == 'y' ) {
			$perms = Perms::get();

			require_once 'lib/auth/tokens.php';
			$tokenlib = AuthTokens::build($prefs);
			$params['TOKEN'] = $tokenlib->createToken(
				$tikiroot . $file, $params, $perms->getGroups(),
				array('timeout' => 120)
			);
		}
		if (is_array($params['printpages'])) {
			$params['printpages'] = implode('&', $params['printpages']);
		}
		$url = $base_url . $file . '?' . http_build_query($params, '', '&');
        $session_params = session_get_cookie_params();
	return $this->{$this->mode}( $url,$pdata);	}

    /**
     * @param $url
     * @return null
     */
    private function none( $url )
	{
		return null;
	}

    /**
     * @param $url
     * @return mixed
     */
    private function webkit( $url )
	{
		// Make sure shell_exec is available
		if (!function_exists('shell_exec')) {
			die(tra('Required function shell_exec is not enabled.'));
		}

		// escapeshellarg will replace all % characters with spaces on Windows
		// So, decode the URL before sending it to the commandline
		$urlDecoded = urldecode($url);
		$arg = escapeshellarg($urlDecoded);

		// Write a temporary file, instead of using stdout
		// There seemed to be encoding issues when using stdout (on Windows 7 64 bit).

		// Use temp/public. It is cleaned up during a cache clean, in case some files are left
		$filename = 'temp/public/out'.mt_rand().'.pdf';

		// Run shell_exec command to generate out file
		// NOTE: this requires write permissions
		$quotedFilename = '"'.$filename.'"';
		$quotedCommand = '"'.$this->location.'"';
		
		`$quotedCommand -q $arg $quotedFilename`;

		// Read the out file
		$pdf = file_get_contents($filename);

		// Delete the outfile
		unlink($filename);

		return $pdf;
	}

	/**
     * @param $url
     * @return mixed
     */
    private function weasyprint( $url )
	{
		// Make sure shell_exec is available
		if (!function_exists('shell_exec')) {
			die(tra('Required function shell_exec is not enabled.'));
		}

		// escapeshellarg will replace all % characters with spaces on Windows
		// So, decode the URL before sending it to the commandline
		$urlDecoded = urldecode($url);
		$arg = escapeshellarg($urlDecoded);

		// Write a temporary file, instead of using stdout
		// There seemed to be encoding issues when using stdout (on Windows 7 64 bit).

		// Use temp/public. It is cleaned up during a cache clean, in case some files are left
		$filename = 'temp/public/out'.mt_rand().'.pdf';

		// Run shell_exec command to generate out file
		// NOTE: this requires write permissions
		$quotedFilename = '"'.$filename.'"';
		$quotedCommand = '"'.$this->location.'"';

		// redirect STDERR to null with 2>/dev/null becasue it outputs plenty of irrelevant warnings (hopefully nothing critical)
		`$quotedCommand $arg $quotedFilename 2>/dev/null`;

		// Read the out file
		$pdf = file_get_contents($filename);

		// Delete the outfile
		unlink($filename);

		return $pdf;
	}

    /**
     * @param $url
     * @return bool
     */
    private function webservice( $url )
	{
		global $tikilib;

		$target = $this->location . '?' . $url;
		return $tikilib->httprequest($target);
	}

	/**
	 * Setup mPDF Cache locations to a folder (mpdf) inside the filesystem cache
	 */
	static public function setupMPDFCacheLocation()
	{
		// set cache paths
		$cache = new CacheLibFileSystem();
		$mPDFBaseCachePath = $cache->folder . '/mpdf/';
		if (!is_dir($mPDFBaseCachePath)) {
			mkdir($mPDFBaseCachePath);
			chmod($mPDFBaseCachePath, 0777);
		}

		$constantsAndDirectories = array(
			'_MPDF_TEMP_PATH'      => 'tmp/',
			'_MPDF_TTFONTDATAPATH' => 'ttfontdata/',
		);

		foreach ($constantsAndDirectories as $constant => $directory) {
			if (!is_dir($mPDFBaseCachePath . $directory)) {
				mkdir($mPDFBaseCachePath . $directory);
				chmod($mPDFBaseCachePath . $directory, 0777);
			}
			if (!defined($constant)) {
				define($constant, $mPDFBaseCachePath . $directory);
			}
		}
	}

	/**
	 * @param $url string - address of the item to print as PDF
	 * @return string     - contents of the PDF
	 */
	private function mpdf($url,$parsedData='')
	{
		global $prefs;
		if (!extension_loaded('curl')) {
			TikiLib::lib('reporterror')->report(tra('mPDF: CURL PHP extension not available'));
			return '';
		}

      if($parsedData!='')
	      $html=$parsedData;

       //getting n replacing images
	   $tempImgArr=array();
       $this->_getImages($html,$tempImgArr);
       
	   $this->_parseHTML($html);
	   self::setupMPDFCacheLocation();
		if (!class_exists('mPDF')){
	    	include_once($this->location . 'mpdf.php');
		}
		//checking preferences
		$orientation=$prefs['print_pdf_mpdf_orientation']!=''?$prefs['print_pdf_mpdf_orientation']:'P';

		$pageSize=$prefs['print_pdf_mpdf_size']!=''?$prefs['print_pdf_mpdf_size']:'Letter';

		//custom size needs to be passed for Tabloid
		if($prefs['print_pdf_mpdf_size']=="Tabloid")
		  $pageSize=array(279,432);
		elseif($orientation=='L')
		  $pageSize=$pageSize.'-'.$orientation;

		$marginLeft=$prefs['print_pdf_mpdf_margin_left']!=''?$prefs['print_pdf_mpdf_margin_left']:'10';
		$marginRight=$prefs['print_pdf_mpdf_margin_right']!=''?$prefs['print_pdf_mpdf_margin_right']:'10';
		$marginTop=$prefs['print_pdf_mpdf_margin_top']!=''?$prefs['print_pdf_mpdf_margin_top']:'10';
		$marginBottom=$prefs['print_pdf_mpdf_margin_bottom']!=''?$prefs['print_pdf_mpdf_margin_bottom']:'10';
		$marginHeader=$prefs['print_pdf_mpdf_margin_header']!=''?$prefs['print_pdf_mpdf_margin_header']:'5';
		$marginFooter=$prefs['print_pdf_mpdf_margin_footer']!=''?$prefs['print_pdf_mpdf_margin_footer']:'5';

	  	$mpdf=new mPDF('utf-8',$pageSize,'','',$marginLeft,$marginRight , $marginTop , $marginBottom , $marginHeader , $marginFooter ,$orientation);

		//custom fonts add, currently fontawesome support is added, more fonts can be added in future
		$custom_fontdata = array(
		 'fontawesome'=>array(
            'R' => "fontawesome.ttf",
            'I' => "fontawesome.ttf",
        ));

		//calling function to add custom fonts
		add_custom_font_to_mpdf($mpdf, $custom_fontdata);

		//for Cantonese support
	    $mpdf->autoScriptToLang = true;
		$mpdf->autoLangToFont = true;

		//setting header and footer
		if($prefs['print_pdf_mpdf_header'])
	      $mpdf->SetHeader($prefs['print_pdf_mpdf_header']);
        if($prefs['print_pdf_mpdf_footer'])
		$mpdf->SetFooter($prefs['print_pdf_mpdf_footer']);
		
		//password protection
		if($prefs['print_pdf_mpdf_password'])
		   $mpdf->SetProtection(array(), 'UserPassword', $prefs['print_pdf_mpdf_password']);

		$mpdf->CSSselectMedia = 'print';				// assuming you used this in the document header

		//getting main base css file
		$basecss = file_get_contents('themes/base_files/css/tiki_base.css'); // external css

		//getting theme css
		$themeLib = TikiLib::lib('theme');
        $themecss=$themeLib->get_theme_path($prefs['theme'], '', $prefs['theme'] . '.css');
		$themecss = file_get_contents($themecss); // external css

		//checking if print friendly option is enabled, then attach print css otherwise theme styles will be retained by theme css
		if($prefs['print_pdf_mpdf_printfriendly']=='y')
		{
			 $printcss = file_get_contents('themes/base_files/css/printpdf.css'); // external css

		}
		else
		{//preserving theme styles by removing media print styles to print what is shown on screen
		  $themecss=str_replace(array("media print","color : fff"),array("media p","color : #fff"),$themecss);
		  $basecss=str_replace(array("media print","color : fff"),array("media p","color : #fff"),$basecss);
		}
		$mpdf->WriteHTML(str_replace(array(".tiki","opacity: 0;"),array("","fill: #fff;opacity:0.3;stroke:black"),'<style>'.$basecss.$themecss.$printcss.$this->bootstrapReplace().'</style>'.$html));
	    $this->clearTempImg($tempImgArr);
	    return $mpdf->Output('', 'S');					// Return as a string
	}
	
	function _getImages(&$html,&$tempImgArr)
	{
			$doc = new DOMDocument();
			@$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

			$tags = $doc->getElementsByTagName('img');

			foreach ($tags as $tag) {
       			$imgSrc=$tag->getAttribute('src');
				
				//replacing image with new temp image, all these images will be unlinked after pdf creation
				$newFile=$this->file_get_contents_by_fget($imgSrc);
				//replacing old protected image path with temp image
				if($newFile!='')
				   $tag->setAttribute('src',$newFile);
				   
				   
				$tempImgArr[]=$newFile;
				}
				
				$html=@$doc->saveHTML();
				
	}
	
	function file_get_contents_by_fget($url)
    {
		global $base_url;
		//check if image is internal with full path
		$internalImg=0;
		  if(substr($url,0,strlen($base_url))==$base_url)  
		    $internalImg=1;
		//checking for external images
		$checkURL = parse_url($url);
	    //not replacing in case of external image
       if(($checkURL['scheme'] == 'https' || $checkURL['scheme'] == 'http') && !$internalImg){
          return '';
	   }
	   if(!$internalImg)
		  $url=$base_url.$url;	  
	   if(! file_exists ('temp/pdfimg'))
	   {
		 mkdir('temp/pdfimg');
		 chmod('temp/pdfimg',0755);
	   }
	   $opts = array('http' => array('header'=> 'Cookie: ' . $_SERVER['HTTP_COOKIE']."\r\n"));
	   $context = stream_context_create($opts);
	   session_write_close();
	   $data=file_get_contents($url, false, $context);
	   $newFile='temp/pdfimg/pdfimg'.mt_rand(9999,999999).'.png';
	   file_put_contents($newFile, $data);
	   chmod($newFile,0755);
       return $newFile;

	}
	
    function clearTempImg($tempImgArr){
	   foreach ($tempImgArr as $tempImg) {
       unlink($tempImg);
       }
	}
	  
    function _parseHTML(&$html)
	{
	   $doc = new DOMDocument();
       $doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
	   
	   $tables = $doc->getElementsByTagName('table');
       $tempValue=array();
	   $sortedContent=array();
       foreach ($tables as $table) {
		    $this->sortContent($table,$tempValue,$sortedContent,$table->tagName);
		}
		$xpath = new DOMXpath($doc);
		
		//defining array of plugins to be sorted
		$pluginArr=array(array("class","customsearch_results","div"),array("id","container_pivottable","div"));
		foreach($pluginArr as $pluginInfo)
		{
           $customdivs = $xpath->query('//*[contains(@'.$pluginInfo[0].', "'.$pluginInfo[1].'")]');
	       for ($i = 0; $i < $customdivs->length; $i++) {
             $customdiv = $customdivs->item($i);
             $this->sortContent($customdiv,$tempValue,$sortedContent,$pluginInfo[2]);
	       }
		}
        $html=@$doc->saveHTML();
	   //replacing temp table with sorted content
			for($i=0;$i<count($sortedContent);$i++)
			{
			    $html=str_replace($tempValue[$i],$sortedContent[$i],$html);
				$html=cleanContent($html,array(array("input","tablesorter-filter","class"),array("select","tablesorter-filter","class"),array("select","pvtRenderer","class"),array("select","pvtAggregator","class"),array("td","pvtCols","class"),array("td","pvtUnused","class"),array("td","pvtRows","class"),array("div","plot-container","class")));

		    }
		
			//making tablesorter and pivottable charts wrapper divs visible
		$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
		$xpath = new DOMXpath($doc);
		$wrapperDefs=array(array("class","ts-wrapperdiv","visibility:visible"),array("id","png_container_pivottable","display:''"));
		foreach($wrapperDefs as $wrapperDef)
		{  $wrapperdivs = $xpath->query('//*[contains(@'.$wrapperDef[0].', "'.$wrapperDef[1].'")]');
		   for ($i = 0; $i < $wrapperdivs->length; $i++) {
			   $wrapperdiv = $wrapperdivs->item($i);
        	   $wrapperdiv->setAttribute("style",$wrapperDef[2]);
		   }
        }
		$html=@$doc->saveHTML();
		//font awesome support call
		$this->fontawesome($html);
		//& sign added in fa unicodes for proper printing in pdf
        $html=str_replace('#x',"&#x",$html); 

	 }
	 
	 function fontawesome(&$html)
	 {
	   $doc = new DOMDocument();
       $doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
	   $xpath = new DOMXpath($doc);
      //font awesome code insertion
	   $fadivs = $xpath->query('//*[contains(@class, "fa")]');
	   //loading json file if there is any font-awesome tag in html
	    if($fadivs->length)
		{
		 $faCodes=file_get_contents('lib/pdf/fontdata/fa-codes.json');
		 $jfo = json_decode($faCodes,true);
         for ($i = 0; $i < $fadivs->length; $i++) {
               $fadiv = $fadivs->item($i);
               $faClass=explode(" ",str_replace(array("fa ","-"),"",$fadiv->getAttribute('class')));
			   foreach($faClass as $class)
			   {
				   if($jfo[$class][codeValue])
				   {
			           $faCode=$doc->createElement('span',$jfo[$class][codeValue]);
					   $faCode->setAttribute("style","font-family: FontAwesome;float:left;".$fadiv->getAttribute('style'));
					   //span with fontawesome code inserted before fa div
					   $fadiv->parentNode->insertBefore($faCode,$fadiv);
					   $fadiv->parentNode->removeChild($fadiv);
				   }
			   }
			 
         }
		}

       $html=@$doc->saveHTML();
     }

	 function bootstrapReplace(){
	    return ".col-xs-12 {width: 90%;}.col-xs-11 {width: 81.66666667%;}.col-xs-10 {width: 72%;}.col-xs-9 {width: 64%;}.col-xs-8 {width: 57%;}.col-xs-7 {width: 49%;}.col-xs-6 {width: 42%;}.col-xs-5 {width: 35%;}.col-xs-4 {width: 28%;}.col-xs-3{width: 20%;}.col-xs-2 {width: 12.2%;}.col-xs-1 {width: 3.92%;}    .table-striped {border:1px solid #ccc;} .table-striped td { padding: 8px; line-height: 1.42857143;vertical-align: center;border-top: 1px solid #ccc;} .table-striped th { padding: 10px; line-height: 1.42857143;vertical-align: center;   } .table-striped .odd {padding:10px;} .trackerfilter form{display:none;} table.pvtTable tr td {border:1px solid}";
	}
	
	function sortContent(&$table,&$tempValue,&$sortedContent,$tag)
	{
	   $content='';
	   $tid= $table->getAttribute("id");
	   
	     
		   if(file_exists("temp/#".$tid."_".session_id().".txt"))
           {
			   $content=file_get_contents("temp/#".$tid."_".session_id().".txt");
			   //formating content
			   $tableTag="<".$tag;
			      if ($table->hasAttributes()) {
                       foreach ($table->attributes as $attr) {
                            $tableTag.=" ".$attr->nodeName."=\"".$attr->nodeValue."\"";
	                   }
                  }
			   $tableTag.=">";
               $content=$tableTag.$content.'</'.$tag.'>';
			   //end of cleaning content
			   $sortedContent[]=str_replace('<sc<x>ript type="text/javascript">
<!--//--><![CDATA[//><!--
$(document).ready(function(){
// jq_onready 0 
$(".convert-mailto").removeClass("convert-mailto").each(function () {
				var address = $(this).data("encode-name") + "@" + $(this).data("encode-domain");
				$(this).attr("href", "mailto:" + address).text(address);
			});
});
//--><!]]>
</script>',"",$content);
			   $tempValue[]=$tableTag;
			   $table->nodeValue="";
			   chmod("temp/#".$tid."_".session_id().".txt",0755);
			   //unlink tmp table file
			   unlink("temp/#".$tid."_".session_id().".txt");
			}
		}

		
		
}


function cleanContent($content,$tagArr){
	$doc = new DOMDocument();
	$doc->loadHTML($content);
	$xpath = new DOMXpath($doc);
       
	   
	foreach($tagArr as $tag)
	{
	  $list = $xpath->query('//'.$tag[0].'[contains(concat(\' \', normalize-space(@'.$tag[2].'), \' \'), "' .$tag[1]. '")]');
      for ($i = 0; $i < $list->length; $i++) {
          $p = $list->item($i);
	      $p->parentNode->removeChild($p);
       }
	}
    return $doc->saveHTML();
}

function add_custom_font_to_mpdf(&$mpdf, $fonts_list) {
    // Logic from line 1146 mpdf.pdf - $this->available_unifonts = array()...
    foreach ($fonts_list as $f => $fs) {
        // add to fontdata array
        $mpdf->fontdata[$f] = $fs;

        // add to available fonts array
        if (isset($fs['R']) && $fs['R']) { $mpdf->available_unifonts[] = $f; }
        if (isset($fs['B']) && $fs['B']) { $mpdf->available_unifonts[] = $f.'B'; }
        if (isset($fs['I']) && $fs['I']) { $mpdf->available_unifonts[] = $f.'I'; }
        if (isset($fs['BI']) && $fs['BI']) { $mpdf->available_unifonts[] = $f.'BI'; }
    }
    $mpdf->default_available_fonts = $mpdf->available_unifonts;
}

