<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// HEADERS
ob_start ("ob_gzhandler");
header("Content-type: text/css; charset: UTF-8");
header("Cache-Control: must-revalidate");
$Expires_Header = "Expires: ".gmdate("D, d M Y H:i:s", time())." GMT";
header($Expires_Header);


// INCLUDE CORE
require_once $_SERVER['DOCUMENT_ROOT'].'/.assets/system/core/core.php';


// GET $PAGE
$Page = str_replace('https://'.$_SERVER['HTTP_HOST'], '', $_SERVER['HTTP_REFERER']);
$Page = explode('?', $Page)[0];
$Page = substr($Page, 1, -1);

switch ($Page) {

  case ('') : $Page = 'scotia-beauty-homepage'; break;
  case ('de') : $Page = 'de/scotia-beauty-startseite'; break;
}


// GET PAGE MANIFEST AND PAGE MODULES
$PageManifest = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/.assets/content/pages/'.urldecode($Page).'/page.json'), TRUE);
${$Page.'::ashivaNamespaceAccess'} = $PageManifest['Ashiva_Page_Build']['ashivaNamespaceAccess'] ?? [];
${$Page.'::Modules'} = getModules($PageManifest['Ashiva_Page_Build']['Modules']);



// FUNCTION :: SEPARATE OUT SELECTOR AND FLEXIBLE MODIFIERS
function openSelector($Selector) {

  $Selector = str_replace('‹', '[', $Selector);
  $Selector = str_replace('›', ']', $Selector);
  $Selector = str_replace('\'', '"', $Selector);
  
  $Open_Selector = json_decode($Selector, TRUE);

  return $Open_Selector;
}


// FUNCTION :: GET FLEXIBLE MODIFIERS
function getxMods($xMods_Set) {

  $xMods = '';

  for ($i = 0; $i < count($xMods_Set); $i++) {

    $xMods .= '[data-°'.strtolower($xMods_Set[$i]).']';
  }

  return $xMods;
}


// FUNCTION :: CHECK NAMESPACE ACCESS
function checkNamespaceAccess($Module_Name, $Substitute_ModuleName, $ashivaNamespaceAccess) {

  ${'Access_to_'.$Substitute_ModuleName} = TRUE;

  if ($ashivaNamespaceAccess[0] === TRUE) {

    if ((isset($ashivaNamespaceAccess[1]['access'])) && (isset($ashivaNamespaceAccess[1]['access']['override']))) {

      ${'Access_to_'.$Substitute_ModuleName} = $ashivaNamespaceAccess[1]['access']['override'];
    }


    else {

      if ((isset($ashivaNamespaceAccess[1]['access'])) && (isset($ashivaNamespaceAccess[1]['access']['default']))) {

        ${'Access_to_'.$Substitute_ModuleName} = $ashivaNamespaceAccess[1]['access']['default'];
      }


      if (isset($ashivaNamespaceAccess[1][$Module_Name])) {

        if ((isset($ashivaNamespaceAccess[1][$Module_Name]['access'])) && (isset($ashivaNamespaceAccess[1][$Module_Name]['access']['override']))) {

          ${'Access_to_'.$Substitute_ModuleName} = $ashivaNamespaceAccess[1][$Module_Name]['access']['override'];
        }


        else {

          if (isset($ashivaNamespaceAccess[1][$Module_Name][$Substitute_ModuleName])) {

            ${'Access_to_'.$Substitute_ModuleName} = $ashivaNamespaceAccess[1][$Module_Name][$Substitute_ModuleName];
          }

          elseif ((isset($ashivaNamespaceAccess[1][$Module_Name]['access'])) && (isset($ashivaNamespaceAccess[1][$Module_Name]['access']['default']))) {

            ${'Access_to_'.$Substitute_ModuleName} = $ashivaNamespaceAccess[1][$Module_Name]['access']['default'];
          }
        }
      }
    }
  }

  return ${'Access_to_'.$Substitute_ModuleName};
}


// FUNCTION :: ADD SELECTORS
function addSelectors($Module_Name, $Module_Publisher, $Namespace, $ashivaNamespaceAccess, $Raw_Selectors, $Rule_Section = FALSE) {

  $Namespace_Prefix = $Namespace.'»»»';
  $Selectors = '';
  $Rule_Commented = FALSE;

  for ($s = 0; $s < count($Raw_Selectors); $s++) {
      
    if ($s > 0) {

      $Selectors .= ','."\n";

      if ($Rule_Section === TRUE) {

        $Selectors .= '  ';
      }
    }


    /* "Selector",
       ["Selector"], */

    if (is_array($Raw_Selectors[$s])) {

      $Selectors .= '/* ';
      $Selector = $Raw_Selectors[$s][0];
    }

    else {

      $Selector = $Raw_Selectors[$s];
    }

    if (strpos($Selector, '  ') !== FALSE) {

      $Selector = preg_replace('/\s{2,}/', ' ', trim($Selector));
    }

    $Selector = str_replace('.', '.'.$Namespace_Prefix, $Selector);
    $Selector = str_replace('#', '#'.$Namespace_Prefix, $Selector);

    if (substr($Selector, 0, 3) === '‹') {

      if (substr($Selector, 0, 6) === '‹\'«') {

        $Selector = requestNamespace($Module_Name, $Module_Publisher, $Namespace, $Selector, $ashivaNamespaceAccess);
      }

      elseif (substr($Selector, 0, 4) === '‹[') {

        $Open_Selector = openSelector($Selector);
        $xMods_Set = $Open_Selector[0];
        $Selector = $Open_Selector[1];
        $Selector = getxMods($xMods_Set).' '.$Selector;
      }
    }
    
    // REQUIRES EXPLICIT PARENT MODULE CONTEXT
    $requiresContext = TRUE;

    if ((in_array(substr($Selector, 0, 1), ['#', '.'])) || (in_array(substr($Selector, 0, 5), ['body#', 'body.', 'body[', 'body ']))) {

      $requiresContext = FALSE;
    }

    if ($requiresContext === TRUE) {

      if (substr($Selector, 0, 8) === '[data-°') {

        $Selector = '.'.$Namespace.$Selector;
      }

      elseif (substr($Selector, 0, 1) === ':') {

        $Selector = '.'.$Namespace.$Selector;
      }

      elseif (strlen($Selector) < 1) {

        $Selector = '.'.$Namespace.$Selector;
      }
      
      else {
      
        $Selector = '.'.$Namespace.' '.$Selector;
      }
    }

    
    if (strpos($Selector, ' */') !== FALSE) {

      $Selector = str_replace(' */', '', $Selector);
      $Rule_Commented = TRUE;
    }

    $Selectors .= $Selector;

    if (is_array($Raw_Selectors[$s])) {

      $Selectors .= ' */';
    }
  }

  return [$Selectors, $Rule_Commented];
}


// FUNCTION :: REQUEST NAMESPACE
function requestNamespace($Module_Name, $Module_Publisher, $Namespace, $Selector, $ashivaNamespaceAccess) {
  
  // STEP 1: SET UP FUNDAMENTALS
  $Namespace_Prefix = $Namespace.'»»»';

  $Selector = str_replace('‹', '[', $Selector);
  $Selector = str_replace('›', ']', $Selector);
  $Selector = str_replace('"', '\"', $Selector);
  $Selector = str_replace('\'', '"', $Selector);
  $Selector = str_replace('\--', '--', $Selector);
  
  $Selector_Array = json_decode($Selector, TRUE);

  if (count($Selector_Array) > 2) {

    $Substitute_Module_Address = str_replace(['«', ' ', '»'], '', $Selector_Array[0]);
    $Substitute_xMods_Set = $Selector_Array[1];
    $Substitute_Selector = $Selector_Array[2];
  }

  else {

    $Substitute_Module_Address = str_replace(['«', ' ', '»'], '', $Selector_Array[0]);
    $Substitute_Selector = $Selector_Array[1];
  }



  // STEP 2: ESTABLISH VARIABLES, ACCORDING TO NAMESPACE SUBSTITUTION TYPE
  if ($Substitute_Module_Address === 'GLOBAL') {
      
    $Substitute_Publisher = $Substitute_ModuleName = $Substitute_Namespace = $Substitute_Module_Address;
  }

  elseif (strpos($Substitute_Module_Address, ':::') !== FALSE) {
      
    $Substitute_ModuleAddress_Array = explode(':::', $Substitute_Module_Address);
    $Substitute_Publisher = $Substitute_ModuleAddress_Array[0];

    $Substitute_ModuleName_Array = explode('*', $Substitute_ModuleAddress_Array[1]);
    $Substitute_ModuleName = $Substitute_ModuleName_Array[0];

    $Substitute_Namespace = url(str_replace('::', '•', $Substitute_ModuleName)).'»by»'.txt($Substitute_Publisher, 'camelCase');
    $Substitute_Namespace_Prefix = $Substitute_Namespace.'»»»';
  }

  else {

    $Console = '';
    $Console .= '  ⚠️ Ashiva Console:'."\n\n";
    $Console .= '  ⚠️ Issue: ashiva Namespace Reference does not begin with a Publisher Name followed by \':::\'.'."\n\n";
    $Console .= '  ⚠️ Next Step: In «'.src($Module_Publisher).':::'.src($Module_Name).'» Style Component, edit the reference to «'.$Substitute_Module_Address.'».'."\n\n";
    $Console .= '  ⚠️ Style Declaration: ‹\'«'.$Substitute_Module_Address.'»\', \''.$Substitute_Selector.'\'›';

    $Selector = "\n/*\n\n".$Console.' */';

    return $Selector;
  }



  // CHECK NAMESPACE ACCESS PERMISSIONS
  if (checkNamespaceAccess($Module_Name, $Substitute_ModuleName, $ashivaNamespaceAccess) === TRUE) {

    // GLOBAL Namespaces
    if ($Substitute_Module_Address === 'GLOBAL') {
          
      // REMOVE ALL NAMESPACE PREFIXES FROM SUBSTITUTE SELECTOR
      if (strpos($Substitute_Selector, '»by»') !== FALSE) {

        $Substitute_Selector = str_replace('•', '', $Substitute_Selector);
        $Substitute_Selector = preg_replace('/([\#\.\"])([^»\#\.\s]+»by»[^»]+»»»)+/', '$1', $Substitute_Selector);
      }


      // ADD EXCLUSION QUALIFIERS TO SELECTORS
      $Substitute_Selector_with_Exclusion_Qualifiers = $Substitute_Selector;
      $Substitute_Selector_with_Exclusion_Qualifiers_Array = explode(' ', $Substitute_Selector_with_Exclusion_Qualifiers);

      for ($i = 0; $i < count($Substitute_Selector_with_Exclusion_Qualifiers_Array); $i++) {

        if ($Substitute_Selector_with_Exclusion_Qualifiers_Array[$i] === '') continue;

        if (in_array(substr($Substitute_Selector_with_Exclusion_Qualifiers_Array[$i], 0, 1), ['.', '#', '['])) {

          $Substitute_Selector_with_Exclusion_Qualifiers_Array[$i] .= ':not([id*="»by»"]):not([class*="»by»"])';
        }

        elseif (strpos($Substitute_Selector_with_Exclusion_Qualifiers_Array[$i], '.') !== FALSE) {

          $Substitute_Selector_with_Exclusion_Qualifiers_Array_Squared = explode('.', $Substitute_Selector_with_Exclusion_Qualifiers_Array[$i]);
          $Substitute_Selector_with_Exclusion_Qualifiers_Array_Squared[0] .= ':not([id*="»by»"]):not([class*="»by»"])';
          $Substitute_Selector_with_Exclusion_Qualifiers_Array[$i] = implode('.', $Substitute_Selector_with_Exclusion_Qualifiers_Array_Squared);
        }

        elseif (strpos($Substitute_Selector_with_Exclusion_Qualifiers_Array[$i], '#') !== FALSE) {

          $Substitute_Selector_with_Exclusion_Qualifiers_Array_Squared = explode('#', $Substitute_Selector_with_Exclusion_Qualifiers_Array[$i]);
          $Substitute_Selector_with_Exclusion_Qualifiers_Array_Squared[0] .= ':not([id*="»by»"]):not([class*="»by»"])';
          $Substitute_Selector_with_Exclusion_Qualifiers_Array[$i] = implode('#', $Substitute_Selector_with_Exclusion_Qualifiers_Array_Squared);
        }

        else {

          $Substitute_Selector_with_Exclusion_Qualifiers_Array[$i] .= ':not([id*="»by»"]):not([class*="»by»"])';
        }
      }

      $Substitute_Selector_with_Exclusion_Qualifiers = implode(' ', $Substitute_Selector_with_Exclusion_Qualifiers_Array);
      $Substitute_Selector = $Substitute_Selector_with_Exclusion_Qualifiers;

      // ADD body > IF SELECTOR DOESN'T ALREADY BEGIN WITH body

      if (substr($Substitute_Selector, 0, 4) === 'body') {

        $Substitute_Selector = str_replace('body:not([id*="»by»"]):not([class*="»by»"])', 'body', $Substitute_Selector);
      }

      else {

        $Substitute_Selector = 'body > '.$Substitute_Selector;
      }
    }


    // All Other Namespaces
    else {
      
      $Substitute_Selector = str_replace($Namespace, $Substitute_Namespace, $Substitute_Selector);
      $Substitute_Selector = str_replace('id="', 'id="'.$Substitute_Namespace_Prefix, $Substitute_Selector);
      $Substitute_Selector = str_replace('class="', 'class="'.$Substitute_Namespace_Prefix, $Substitute_Selector);
      $Substitute_Selector = str_replace($Substitute_Namespace_Prefix.$Substitute_Namespace_Prefix, $Substitute_Namespace_Prefix, $Substitute_Selector);

      if (isset($Substitute_xMods_Set)) {

        $Substitute_Selector = getxMods($Substitute_xMods_Set).' '.$Substitute_Selector;
      }

      if (!in_array(substr($Substitute_Selector, 0, 1), ['#', '.'])) {

        $Substitute_Selector = (substr($Substitute_Selector, 0, 8) === '[data-°') ? '.'.$Substitute_Namespace.$Substitute_Selector : '.'.$Substitute_Namespace.' '.$Substitute_Selector;
      }
    }
  }
      
      
  else {

    $Console = '';
    $Console .= '  ⚠️ Ashiva Console:'."\n\n";
    $Console .= '  ⚠️ Issue: «'.src($Module_Publisher).':::'.src($Module_Name).'» has NO ACCESS to the «'.$Substitute_Module_Address.'» Namespace.'."\n\n";
    $Console .= '  ⚠️ Next Step: Enable «'.src($Module_Publisher).':::'.src($Module_Name).'» access to «'.$Substitute_Module_Address.'» Namespace in this page\'s Manifest.'."\n\n";
    $Console .= '  ⚠️ Style Declaration: ‹\'«'.$Substitute_Module_Address.'»\', \''.$Substitute_Selector.'\'›';

    $Selector = "\n/*\n\n".$Console.' */';
  }
  

  // FINAL ADJUSTMENTS
  $Substitute_Selector = str_replace('+:not([id*="»by»"]):not([class*="»by»"])', '+', $Substitute_Selector);
  $Substitute_Selector = str_replace('~:not([id*="»by»"]):not([class*="»by»"])', '~', $Substitute_Selector);
  $Substitute_Selector = str_replace('>:not([id*="»by»"]):not([class*="»by»"])', '>', $Substitute_Selector);

  $Substitute_Selector = str_replace('.--', '.\--', $Substitute_Selector);
  $Substitute_Selector = str_replace('#--', '#\--', $Substitute_Selector);


  // RETURN SELECTOR
  $Selector = $Substitute_Selector;
  
  return $Selector;
}


function getStyles($Modules, $ashivaNamespaceAccess = [FALSE]) {

  $Stylesheet = '';

  foreach ($Modules['Styles'] as $Module_Name => $Module_Stylesheet) {

    $Module_Publisher = $Modules['Register'][$Module_Name]['Publisher'];
    $Namespace = str_replace('::', '•', url($Module_Name)).'»by»'.txt($Module_Publisher, 'camelCase');
    $Namespace_Prefix = $Namespace.'»»»';

    $Stylesheet_Segment = '';
    $Stylesheet_Segment .= '  /*'.str_repeat('*', (strlen($Module_Name) + strlen($Module_Publisher) + 13)).'*/'."\n";
    $Stylesheet_Segment .= ' /* '.strtoupper(txt($Module_Name)).' MODULE by '.strtoupper(txt($Module_Publisher)).' */'."\n";
    $Stylesheet_Segment .= '/*'.str_repeat('*', (strlen($Module_Name) + strlen($Module_Publisher) + 13)).'*/'."\n\n";





    for ($i = 0; $i < count($Module_Stylesheet); $i++) {

      // TO SKIP SECTIONS OF THE STYLESHEET
      if (isset($Module_Stylesheet[$i]['Skip'])) {

        if ($Module_Stylesheet[$i]['Skip'] !== 'Comment') {$Module_Stylesheet[$i]['Skip'] = 'Skip';}
        if ($Module_Stylesheet[$i]['Skip'] === 'Skip') continue;
        if ($Module_Stylesheet[$i]['Skip'] === 'Comment') {$Stylesheet_Segment .= "/*\n\n⚠️ Ashiva Console: The following styles are commentSkipped:\n";}
      }


      // FOR DESCRIPTIVE COMMENTS WITHIN THE STYLESHEET:
      if (isset($Module_Stylesheet[$i]['Comment'])) {

        if (count($Module_Stylesheet[$i]['Comment']) === 1) {

          $Stylesheet_Segment .= "\n\n/* ".$Module_Stylesheet[$i]['Comment'][0]." */\n\n";
        }
        
        else {

          $Stylesheet_Segment .= "\n\n/*\n  ".implode("\n  ", $Module_Stylesheet[$i]['Comment'])."\n*/\n\n";
        }
      }


      // FOR @RULES WITHIN THE STYLESHEET
      elseif (isset($Module_Stylesheet[$i][0]['@Rule'])) {

        for ($j = 0; $j < count($Module_Stylesheet[$i]); $j++) {

          $Stylesheet_Segment .= "\n".'@'.$Module_Stylesheet[$i][$j]['@Rule']['Type'];
          $Stylesheet_Segment .= ' ';

          if ($Module_Stylesheet[$i][$j]['@Rule']['Type'] === 'keyframes') {

            $Stylesheet_Segment .= $Module_Stylesheet[$i][$j]['@Rule']['Animation_Name'];
            $Stylesheet_Segment .= ' {'."\n";

            for ($k = 0; $k < count($Module_Stylesheet[$i][$j]['@Rule']['Animation_Sequence']); $k++) {

              $Stylesheet_Segment .= "\n".'  ';

              for ($l = 0; $l < count($Module_Stylesheet[$i][$j]['@Rule']['Animation_Sequence'][$k]['Frames']); $l++) {

                $Stylesheet_Segment .= $Module_Stylesheet[$i][$j]['@Rule']['Animation_Sequence'][$k]['Frames'][$l];
                if ($l < (count($Module_Stylesheet[$i][$j]['@Rule']['Animation_Sequence'][$k]['Frames']) - 1)) {$Stylesheet_Segment .= ', ';}
              }
          
              $Stylesheet_Segment .= ' {'."\n";

                $Styles = $Module_Stylesheet[$i][$j]['@Rule']['Animation_Sequence'][$k]['Styles'];

                foreach ($Styles as $Property => $Value) {

                  /* "Property" : "Value",
                     "Property" : ["Value", "Comment"] */

                  if (is_array($Value)) {

                    $Stylesheet_Segment .= '    /* '.$Property.': '.$Value[0].'; */'."\n";
                  }

                  else {

                    $Stylesheet_Segment .= '    '.$Property.': '.$Value.';'."\n";
                  }
                }

              $Stylesheet_Segment .= '  }'."\n";
            }

            $Stylesheet_Segment .= '}'."\n\n";
          }


          else if ($Module_Stylesheet[$i][$j]['@Rule']['Type'] === 'media') {

            for ($k = 0; $k < count($Module_Stylesheet[$i][$j]['@Rule']['Directives']); $k++) {

              $Stylesheet_Segment .= preg_replace('/\:([^\s])/', ': $1', $Module_Stylesheet[$i][$j]['@Rule']['Directives'][$k]);

              if ($k < (count($Module_Stylesheet[$i][$j]['@Rule']['Directives']) - 1)) {$Stylesheet_Segment .= ','."\n";}
            }

            $Stylesheet_Segment .= ' {'."\n";

            for ($k = 0; $k < count($Module_Stylesheet[$i][$j]['@Rule']['Rules']); $k++) {


              // FOR @KEYFRAMES WITHIN THE @MEDIAQUERY
              if (isset($Module_Stylesheet[$i][$j]['@Rule']['Rules'][$k][0]['@Rule'])) {

                for ($l = 0; $l < count($Module_Stylesheet[$i][$j]['@Rule']['Rules'][$k]); $l++) {

                  $Stylesheet_Segment .= "\n".'  @'.$Module_Stylesheet[$i][$j]['@Rule']['Rules'][$k][$l]['@Rule']['Type'];
                  $Stylesheet_Segment .= ' ';

                  if ($Module_Stylesheet[$i][$j]['@Rule']['Rules'][$k][$l]['@Rule']['Type'] === 'keyframes') {

                    $Stylesheet_Segment .= $Module_Stylesheet[$i][$j]['@Rule']['Rules'][$k][$l]['@Rule']['Animation_Name'];
                    $Stylesheet_Segment .= ' {'."\n";

                    for ($m = 0; $m < count($Module_Stylesheet[$i][$j]['@Rule']['Rules'][$k][$l]['@Rule']['Animation_Sequence']); $m++) {

                      $Stylesheet_Segment .= "\n".'    ';

                      for ($n = 0; $n < count($Module_Stylesheet[$i][$j]['@Rule']['Rules'][$k][$l]['@Rule']['Animation_Sequence'][$m]['Frames']); $n++) {

                        $Stylesheet_Segment .= $Module_Stylesheet[$i][$j]['@Rule']['Rules'][$k][$l]['@Rule']['Animation_Sequence'][$m]['Frames'][$n];
                        if ($n < (count($Module_Stylesheet[$i][$j]['@Rule']['Rules'][$k][$l]['@Rule']['Animation_Sequence'][$m]['Frames']) - 1)) {$Stylesheet_Segment .= ', ';}
                      }
                  
                      $Stylesheet_Segment .= ' {'."\n";

                        $Styles = $Module_Stylesheet[$i][$j]['@Rule']['Rules'][$k][$l]['@Rule']['Animation_Sequence'][$m]['Styles'];

                        foreach ($Styles as $Property => $Value) {

                          /* "Property" : "Value",
                             "Property" : ["Value", "Comment"] */

                          if (is_array($Value)) {

                            $Stylesheet_Segment .= '    /* '.$Property.': '.$Value[0].'; */'."\n";
                          }

                          else {

                            $Stylesheet_Segment .= '      '.$Property.': '.$Value.';'."\n";
                          }
                        }

                      $Stylesheet_Segment .= '    }'."\n";
                    }

                    $Stylesheet_Segment .= '  }'."\n\n";
                  }
                }
              }


              // FOR STANDARD STYLES WITHIN THE @MEDIA QUERY
              else {

                $Raw_Selectors = $Module_Stylesheet[$i][$j]['@Rule']['Rules'][$k]['Selectors'];

                [$addSelectors, $Rule_Commented] = addSelectors($Module_Name, $Module_Publisher, $Namespace, $ashivaNamespaceAccess, $Raw_Selectors, TRUE);

                $Stylesheet_Segment .= "\n".'  '.$addSelectors.' {';

                if ($Rule_Commented !== TRUE) {$Stylesheet_Segment .= "\n  ";} else {$Stylesheet_Segment .= ' ';}

                $Styles = $Module_Stylesheet[$i][$j]['@Rule']['Rules'][$k]['Styles'];

                foreach ($Styles as $Property => $Value) {

                  /* "Property" : "Value",
                     "Property" : ["Value", "Comment"] */

                  if (is_array($Value)) {

                    $Stylesheet_Segment .= '/* '.$Property.': '.$Value[0].'; */';
                  }

                  else {

                    $Stylesheet_Segment .= '  '.$Property.': '.$Value.';';
                  }

                  if ($Rule_Commented !== TRUE) {$Stylesheet_Segment .= "\n  ";} else {$Stylesheet_Segment .= ' ';}
                }

                $Stylesheet_Segment .= '}';

                if ($Rule_Commented === TRUE) {$Stylesheet_Segment .= "\n\n*/\n"; $Rule_Commented = FALSE;}

                $Stylesheet_Segment .= "\n";
              }
            }

            $Stylesheet_Segment .= '}'."\n\n";
          }
        }
      }


      // FOR STANDARD STYLES WITHIN THE STYLESHEET
      else {

        $Raw_Selectors = $Module_Stylesheet[$i]['Selectors'];

        [$addSelectors, $Rule_Commented] = addSelectors($Module_Name, $Module_Publisher, $Namespace, $ashivaNamespaceAccess, $Raw_Selectors);

        $Stylesheet_Segment .= "\n".$addSelectors.' {';

        if ($Rule_Commented !== TRUE) {$Stylesheet_Segment .= "\n";} else {$Stylesheet_Segment .= ' ';}
        
        $Styles = $Module_Stylesheet[$i]['Styles'];

        foreach ($Styles as $Property => $Value) {

           /* "Property" : "Value",
              "Property" : ["Value", "Comment"] */

          if (is_array($Value)) {

            $Stylesheet_Segment .= '  /* '.$Property.': '.$Value[0].'; */';
          }

          else {

            $Stylesheet_Segment .= '  '.$Property.': '.$Value.';';
          }

          $Stylesheet_Segment .= "\n";
        }

        $Stylesheet_Segment .= '}';

        if ($Rule_Commented === TRUE) {$Stylesheet_Segment .= "\n\n*/\n"; $Rule_Commented = FALSE;}

        $Stylesheet_Segment .= "\n\n";
      }

      if ((isset($Module_Stylesheet[$i]['Skip'])) && ($Module_Stylesheet[$i]['Skip'] === 'Comment')) {$Stylesheet_Segment .= "*/\n\n";}

      // FINAL STEP: REFORMAT COMMENTED OUT SELECTORS
      $Stylesheet_Segment = str_replace(' */,', ', */', $Stylesheet_Segment);
      
      if (strpos($Stylesheet_Segment, ",\n/*") !== FALSE) {
      
        $Stylesheet_Segment = preg_replace("/\,((\n\/\*\s(.*)\s\*\/)+)(\s\{)/", '$1$4', $Stylesheet_Segment);
      }
    }

    $Stylesheet .= $Stylesheet_Segment."\n\n\n\n";
  }

  return $Stylesheet;
}


// GET MODULE STYLES
if ((isset($Page)) && (isset(${$Page.'::Modules'})) && (isset(${$Page.'::ashivaNamespaceAccess'}))) {

  echo getStyles(${$Page.'::Modules'}, ${$Page.'::ashivaNamespaceAccess'});
}
          
?>
