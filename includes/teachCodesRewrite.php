<?php
/**
 * Rewrite class extending wordpress rewrite and inforcing new theme and redirections
 * 
 * @version 1.0
 * @author Vikas Bhardwaj
 */
class teachCodesRewrite {
	
/**
 * Adds variables to work with this class
 * 
 * @var unknown
 */
private $data, $publicVar,$rewriteRule,$templates,$private;

public function __construct() {
    $this->data= apply_filters('teachCodes_rewrite_manager',array()); 

    $this->generateRules();
}



/**
 * Generates ReWrite Rule for Our Custom Theme
 * 
 * @since 1.0
 * @version 1.0
 */
protected function generateRules(){
        $this->addRulesToExecute();
	add_action('template_include', array(&$this,'template_redirect'));
	add_action('generate_rewrite_rules',array(&$this, 'rewrite_rules') );
	add_action('query_vars', array(&$this,'add_query_vars'));
}

/**
 * Adds New query to default wordpress queries
 * @param type $public_query_vars
 * @since 1.0
 * @version 1.2
 * @return string
 */
public function  add_query_vars($public_query_vars) {
	$public_query_vars[] = 'teach_codes_custom_page';
	if ($this->publicVar) {
		foreach ($this->publicVar as $var) {
			$public_query_vars[]=$var;
		}
	}
	return $public_query_vars;
	}
	
/**
 * Renders Rewrite Format for Number and String
 * @param String $name For Formating
 * @since 1.0
 * @version 1.0
 * @return String
 */
private function renderFormat($name) {
		switch ($name) {
			case 'number':
				$format='/([0-9]{1,})';
			break;
			case 'string':
				$format='/([^/]+)';
				break;
			default:
				$format=null;
			break;
		}
		return $format;
	}
/**
 * Adds new rules to previous ones
 * @since 1.0
 * @version 1.2
 * @param type $wp_rewrite
 */
public function rewrite_rules($wp_rewrite){
    $more_rules='';
    $new_rules=null;
	if($this->rewriteRule){
            foreach ($this->rewriteRule as $rule=>$ru) {
                $more_rule=$this->renderCustomRewrite($rule,  array_keys($ru), array_values($ru));
                $more_rules=array_merge((array)$more_rules,$more_rule);
            }
            $new_rules=array_merge((array)$new_rules,$more_rules);
        }
        
    $wp_rewrite->rules = ($new_rules?$new_rules + $wp_rewrite->rules:$wp_rewrite->rules);
    
}
/**
 * Render Custom rewrite page to the wordpress rewrite
 * 
 * @global object $wp_rewrite Wordpress rewrite object
 * @param string $mainPage Name of main page
 * @param array $keys 
 * @param array $value
 * @return string
 */  
private function renderCustomRewrite($mainPage,$keys=array(),$value=array()) {
    global $wp_rewrite;
        if(count($keys)==0){
            $myPage=array($mainPage.'/?$'=>'index.php?teach_codes_custom_page='.$mainPage
                );
        }elseif (count($keys)==1){
            $format=$this->renderFormat($value[0]);
          $myPage=array(
              $mainPage.'/?$'=>'index.php?teach_codes_custom_page='.$mainPage,
              $mainPage.$format.'/?$'=>'index.php?teach_codes_custom_page='.$mainPage.'&'.$keys[0].'='.$wp_rewrite->preg_index(1)
              );
        }elseif (count($keys)==2){
            $format1=$this->renderFormat($value[0]);
            $format2=$this->renderFormat($value[1]);
          $myPage=array(
             $mainPage.'/?$'=>'index.php?teach_codes_custom_page='.$mainPage,
             $mainPage.$format1.'/?$'=>'index.php?teach_codes_custom_page='.$mainPage.'&'.$keys[0].'='.$wp_rewrite->preg_index(1),
             $mainPage.$format1.$format2.'/?$'=>'index.php?teach_codes_custom_page='.$mainPage.'&'.$keys[0].'='.$wp_rewrite->preg_index(1).'&'.$keys[1].'='.$wp_rewrite->preg_index(2)
              );
        }elseif (count($keys)==3){
            $format1=$this->renderFormat($value[0]);
            $format2=$this->renderFormat($value[1]);
            $format3=$this->renderFormat($value[2]);
          $myPage=array(
             $mainPage.'/?$'=>'index.php?teach_codes_custom_page='.$mainPage,
             $mainPage.$format1.'/?$'=>'index.php?teach_codes_custom_page='.$mainPage.'&'.$keys[0].'='.$wp_rewrite->preg_index(1),
             $mainPage.$format1.$format2.'/?$'=>'index.php?teach_codes_custom_page='.$mainPage.'&'.$keys[0].'='.$wp_rewrite->preg_index(1).'&'.$keys[1].'='.$wp_rewrite->preg_index(2),
             $mainPage.$format1.$format2.$format3.'/?$'=>'index.php?teach_codes_custom_page='.$mainPage.'&'.$keys[0].'='.$wp_rewrite->preg_index(1).'&'.$keys[1].'='.$wp_rewrite->preg_index(2).'&'.$keys[2].'='.$wp_rewrite->preg_index(3)
              );
        }
        return $myPage;
  }

/**
 * Add termplate redirect to the custom page
 * 
 * @param string  - $redirect_page 
 * @return Redirected URL
 * @since 1.0
 * @version 1.2
 */
public function template_redirect($template){
    global $wp_query;
    $reditect_page = isset($wp_query->query_vars['teach_codes_custom_page'])?$wp_query->query_vars['teach_codes_custom_page']:null;
    if($reditect_page){
        if ($this->private[$reditect_page] && !is_user_logged_in()) {
              wp_redirect(wp_login_url($_SERVER['REQUEST_URI']));
            exit();
          }     
         $wp_query->is_home=false;
         $this->customTitle($this->templates[$reditect_page]['title']);
        // $is_plugin = is_p
         $new_template = $this->templates[$reditect_page]['path'];
		if ( '' != $new_template ) {
			return $new_template ;
		}
   	}
        return $template;
}
/**
 * Add Rule to be executed on theme load
 * 
 * @since 1.0
 * @version 1.0
 * @param String $slug Slug to be rendered
 * @param Array $rule Any Additional Rule to be executed should be in array('otherSlug'=>'string|number')
 * @param string $path Path of template to be loaded
 */
protected function addRulesToExecute() {
    foreach ($this->data as $data) {
        if (isset($data['slug'])) {
              $this->addVar($data['slug']);
        }
      
	if(!empty($data['rules']) && is_array($data['rules'])){
            foreach ($data['rules'] as $name=>$value) {
                    $this->addVar($name);
            }
        }
      $this->addPrivate($data['slug'],$data['is_private']);
      $this->addRewrite($data['slug'], $data['rules']);
      $this->addTemplate($data['slug'], $data['path'],$data['title']);

    }
}

private function addPrivate($slug,$prv=false){
    $this->private[$slug]=$prv;
}

/**
 * Add variable
 * 
 * @version 1.0
 * @since 4.0
 * @param string $var
 */
private function addVar($var) {
	$this->publicVar[$var]=$var;
}

/**
 * Adds rewrite rule to array
 * @param string $slug slug for the rewrite
 * @param string $index name of page to loaded
 * @since 4.0
 * @version 1.0
 */
private function addRewrite($slug,$index) {
	$this->rewriteRule[$slug]=$index;
}

/**
 * Adds Template to the rewrite
 * @param string $slug slug for page
 * @param string $index Template
 */
private function addTemplate($slug,$path,$title='') {
	$this->templates[$slug]=array(
			'path'  => $path,
                        'title' => $title,
                        'slug'  => $slug
	);
}

/**
 * Include custom file 
 * @param string $fileName name of custom file name
 * @param string $title title of page
 * @since 4.0
 * @version 1.0
 */
private function includeCustomFile($fileName,$title=false){
  header("Status: 200 OK", '',200);
  $this->tcustomTitle($title);
  include ($fileName);
}


/**
 * Renders custom title for custom page
 * @param string $title Title for the page
 * @return type
 */
private function customTitle($title='Bhardwaja') {
  add_filter('wp_title',function () use(&$title) { return $title ;});
}
}