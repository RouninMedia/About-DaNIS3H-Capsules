<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$Styles_String = <<< ENDOFSTYLES

.pageBack,
.pageForward {

  position: fixed;
  top: calc(50% - 20px);
  z-index: 12;
  width: 40px;
  height: 40px;
  line-height: 40px;
  font-size: 36px;
  text-align: center;
  background-color: rgba(178, 0, 87, 0.3);
  cursor: pointer;
  animation: revealPageButtons 0.6s linear;
}

.pageBack {

  left: 0;
}

.pageForward {

  right: 0;
}

.pageBack::after,
.pageForward::after {

  content: '>';
  color: rgba(255, 255, 255, 0.5);
}

.pageBack::after {

  transform: rotateX(180deg);
}

.pageBack:hover::after,
.pageForward:hover::after {

  color: rgba(255, 255, 255, 1);
}

@keyframes revealPageButtons {

    0%, 75% {opacity: 0; color: rgb(0, 0, 0);}
  100% {opacity: 1; color: rgb(255, 0, 0);}
}

@keyframes revealPageButtons2 {

    0%, 75% {opacity: 0; color: rgb(0, 0, 0);}
  100% {opacity: 1; color: rgb(255, 0, 0);}
}

.pageBack,
.pageForward {

  opacity: 1;
}

@keyframes revealPageButtons3 {

    0%, 75% {opacity: 0; color: rgb(0, 0, 0);}
  100% {opacity: 1; color: rgb(255, 0, 0);}
}


ENDOFSTYLES;



function Css_To_Json ($input) {

  function Build_Styles_JSON ($Styles_String) {

    // PREPARE STYLESHEET FOR CONVERSION
    $Styles_String = str_replace('/*', '‹', $Styles_String);
    $Styles_String = str_replace('*/', '›', $Styles_String);
    $Styles_String = preg_replace('/\s*([\‹|\{|\;|\}|\›])/', '$1', $Styles_String);
    $Styles_String = preg_replace('/([\‹|\{|\;|\}|\›])\s*/', '$1', $Styles_String);
    $Styles_String = preg_replace("/\,[\r\n]+/", ",", $Styles_String);

    // PROCESS DESCRIPTIVE COMMENTS
    $Styles_String = preg_replace('/([^,;])›\s*‹/', "$1«NEWCOMMENT»", $Styles_String);
    $Styles_String = preg_replace("/(?=[^›]+›)[\r\n]+\h*/", "«NEWLINE»", $Styles_String);
    $Styles_String = preg_replace("/(\}\s*)‹([^›]+)›(\s*[^\{])/", "$1DocumentComment{CommentText:$2}$3", $Styles_String);

    // PROCESS COMMENTED SELECTORS
    $Styles_String = str_replace(',›', '›,', $Styles_String);
    $Styles_String = preg_replace("/(\w[^\,])‹(?![^\›]+\:)/", "$1,‹", $Styles_String);

    // PROCESS COMMENTED STYLES
    $Styles_String = str_replace(';›', '›;', $Styles_String);
    $Styles_String = preg_replace('/‹([\w-]+\:)/', '$1‹', $Styles_String);


    // CAPTURE URL FUNCTION PATHS
    preg_match_all('/url\([\"\'](.+?)[\"\']\)/', $Styles_String, $URL_Paths);
    $URL_Paths = $URL_Paths[1];

    for ($i = 0; $i < count($URL_Paths); $i++) {

      $URL_Path_Start = strpos($Styles_String, $URL_Paths[$i]);

      $Styles_String = substr_replace($Styles_String, 'REPLACEMENT_URL_PATH_'.$i, $URL_Path_Start, strlen($URL_Paths[$i]));
    }
    

    // CAPTURE ALL RULES
    ${'@Rules'} = [];
    $Styles_Associative_Array = [];


    // @KEYFRAMES RULES
    function Process_Keyframes(&$String) {
     
      // INITIALISE KEYFRAMES ARRAY
      $Keyframes_Array = [];

      // CAPTURE @KEYFRAMES AND SAVE TO @Rules
      preg_match_all("/@keyframes([^\{]+)\{(.+?)\}\}/", $String,  $Keyframes_Animation_Matches);

      $Keyframes_Animation_Sets = $Keyframes_Animation_Matches[0];
    
      for ($i = 0; $i < count($Keyframes_Animation_Sets); $i++) {
  
        $String = str_replace($Keyframes_Animation_Sets[$i], '', $String);
  
        $Keyframes_Animation_Sets[$i] = substr($Keyframes_Animation_Sets[$i], 10, -2);
      }
  
      ${'@Rules'}['@keyframes'] = $Keyframes_Animation_Sets;

  
      // BUILD AND APPEND @RULES
      $Rule_Types = ['@keyframes'];
  
      for ($i = 0; $i < count(${'@Rules'}['@keyframes']); $i++) {
  
        $RuleToAdd['@Rule']['Type'] = strtolower(str_replace('@', '', '@keyframes'));

        $RuleToAdd['@Rule']['Animation_Name'] = trim(explode('{', ${'@Rules'}['@keyframes'][$i])[0]);
        $Animation_Sequence_Blocks = [];
  
  
        // BUILD ANIMATION SEQUENCE (ARRAY)
        $Animation_Sequence = preg_split("/{|}/", ${'@Rules'}['@keyframes'][$i]);
  
        for ($j = 0; $j < count($Animation_Sequence); $j++) {
  
          $Animation_Sequence[$j] = preg_split("/;/", $Animation_Sequence[$j]);
        }
  
        for ($j = (count($Animation_Sequence) - 1); ($j + 1) > 0; $j--) {
  
          for ($k = (count($Animation_Sequence[$j]) - 1); ($k + 1) > 0; $k--) {
  
            if ($Animation_Sequence[$j][$k] === '') {unset($Animation_Sequence[$j][$k]);}
          }
        }
          
        // TIDY UP ANIMATION SEQUENCE
        $Animation_Sequence = array_values($Animation_Sequence);
  
        for ($j = 0; $j < count($Animation_Sequence); $j++) {
   
          $Animation_Sequence[$j] = array_values($Animation_Sequence[$j]);
        }
  
  
        // BUILD ANIMATION SEQUENCE BLOCKS (ASSOCIATIVE ARRAY)
        for ($j = 1; $j < count($Animation_Sequence); $j = $j + 2) {
  
          $Animation_Sequence_Block['Frames'] = explode(',', $Animation_Sequence[$j][0]);

          // REMOVE ANY PRECEDING SPACES FROM EACH PERCENTAGE
          for ($k = 0; $k < count($Animation_Sequence_Block['Frames']); $k++) {

            $Animation_Sequence_Block['Frames'][$k] = trim($Animation_Sequence_Block['Frames'][$k]);
          }

          $Animation_Sequence_Block_Styles = [];
  
          for ($k = 0; $k < count($Animation_Sequence[($j + 1)]); $k++) {
  
            $Property = explode(':', $Animation_Sequence[($j + 1)][$k])[0];
            $Value = trim(explode(':', $Animation_Sequence[($j + 1)][$k])[1]);
            $Animation_Sequence_Block_Styles[$Property] = $Value;
            $Animation_Sequence_Block['Styles'] = $Animation_Sequence_Block_Styles;
          }
  
          $Animation_Sequence_Blocks[] = $Animation_Sequence_Block;
        }
  
        // BUILD @RULE
        $RuleToAdd['@Rule']['Animation_Sequence'] = $Animation_Sequence_Blocks;
          
        // APPEND @RULE
        $Keyframes_Array[] = $RuleToAdd;
      }

      return $Keyframes_Array;
    }



    // @MEDIAQUERIES RULES
    function Process_Media_Queries(&$String) {
     
      // INITIALISE @MEDIAQUERIES ARRAY
      $Media_Queries_Array = [];

      ${'@Rules'}['media'] = [];

      // CAPTURE @MEDIAQUERIES AND SAVE TO @Rules
      // preg_match_all("/@media([^\{]+)\{(.+?)\}\}/", $String,  $Media_Query_Matches);
      // print_r($Media_Query_Matches);

      preg_match_all("/@media([^\{]+)\{((\@[^\{]+\{([^\@\}]+\}\,?\s*)+\}\,?\s*)?([^\@\}]+\}\,?\s*)?)*\}/", $String,  $Media_Query_Matches);
      
      $Media_Queries_Sets = $Media_Query_Matches[0];
      $Media_Queries = $Media_Query_Matches[1];


      for ($i = 0; $i < count($Media_Queries_Sets); $i++) {

        $Media_Query_Set = $Media_Queries_Sets[$i];
        $String = str_replace($Media_Query_Set, '', $String);

        // GET @KEYFRAMES ANIMATIONS FROM @MEDIAQUERY SET
        if (strpos($Media_Query_Set, '@keyframes') !== FALSE) {

          $Media_Query_Set_Keyframes_Array = Process_Keyframes($Media_Query_Set);
        }

        // BUILD MEDIA QUERY SET ARRAY FROM MEDIA QUERY SET STRING
        $Media_Query_Set_Array = preg_split("/{|}/", trim($Media_Query_Set));

        while (preg_match('/^\s*$/', $Media_Query_Set_Array[count($Media_Query_Set_Array) - 1])) {array_pop($Media_Query_Set_Array);}
  
        // PROCESS MEDIA QUERY SET SELECTORS
        for ($j = 1; $j < count($Media_Query_Set_Array); $j = $j + 2) {

          $Media_Query_Set_Array_Selectors = preg_split('/\,/', $Media_Query_Set_Array[$j]);

          for ($k = 0; $k < count($Media_Query_Set_Array_Selectors); $k++) {
  
            $Media_Query_Set_Array_Selectors[$k] = trim(preg_replace('/(\#|\.|\").+?»»»/', '$1', $Media_Query_Set_Array_Selectors[$k]));
            $Media_Query_Set_Array_Selectors[$k] = trim(preg_replace('/.+»by»[^\.\#\[\>\+\~\:\s]+/', '', $Media_Query_Set_Array_Selectors[$k]));
          }

          $Media_Query_Set_Array[$j] = implode(',', $Media_Query_Set_Array_Selectors);
        }

        // PROCESS MEDIA QUERY SET DECLARATIONS
        $Media_Query_Set_Array[0] .= '{';

        for ($j = 2; $j < count($Media_Query_Set_Array); $j = $j + 2) {
  
          $Media_Query_Set_Array[$j] = '{'.$Media_Query_Set_Array[$j].'}';
        }

        $Media_Query_Set_Array[] .= '}';

        $Media_Query_Set = implode('', $Media_Query_Set_Array);

        $Media_Query_Set = substr(implode('{', array_slice(explode('{', $Media_Query_Set), 1)), 0);

        while (in_array(substr($Media_Query_Set, -1, 1), [';', '}', ' '])) {

          $Media_Query_Set = substr($Media_Query_Set, 0, -1);
        }
        
        $Media_Query_Set = preg_split('/{|}/', $Media_Query_Set);


        // @MEDIA QUERY SELECTORS AND @MEDIA QUERY STYLES
        $Media_Query_Selectors = [];
        $Media_Query_Styles = [];

        for ($j = 0; $j < count($Media_Query_Set); $j++) {

          // BUILD @MEDIA QUERY SELECTORS
          if (($j % 2) === 0) {

            $Media_Query_Selectors[] = explode(',', $Media_Query_Set[$j]);
          }

          // BUILD @MEDIA QUERY STYLES
          elseif (($j % 2) > 0) {

            if (substr($Media_Query_Set[$j], -1, 1) === ';') {

              $Media_Query_Set[$j] = substr($Media_Query_Set[$j], 0, -1);
            }

            $Media_Query_Styles[] = explode(';', $Media_Query_Set[$j]);
          }
        }


        // PROCESS @MEDIA QUERY STYLES
        for ($j = 0; $j < count($Media_Query_Styles); $j++) {

          for ($k = (count($Media_Query_Styles[$j]) - 1); ($k + 1) > 0; $k--) {

            $Property = explode(':', $Media_Query_Styles[$j][$k])[0];
            $Value = explode(':', $Media_Query_Styles[$j][$k])[1];
            $Media_Query_Styles[$j][$Property] = trim($Value);
            unset($Media_Query_Styles[$j][$k]);
          }

          $Media_Query_Styles[$j] = array_reverse($Media_Query_Styles[$j]);
        }


        // BUILD @MEDIA RULES
        for ($j = 0; $j < count($Media_Query_Selectors); $j++) {

          ${'@Rules'}['media'][trim($Media_Queries[$i])][$j] = [];
          ${'@Rules'}['media'][trim($Media_Queries[$i])][$j]['Selectors'] = $Media_Query_Selectors[$j];
          ${'@Rules'}['media'][trim($Media_Queries[$i])][$j]['Styles'] = $Media_Query_Styles[$j];
        }
      }

      $Media_Queries = array_keys(${'@Rules'}['media']);

      for ($i = 0; $i < count($Media_Queries); $i++) {

        $Media_Query = [

          '@Rule' => [

            'Type' => 'media',
            'Directives' => [$Media_Queries[$i]],
            'Rules' => []
          ]
        ];

        for ($j = 0; $j < count(${'@Rules'}['media'][$Media_Queries[$i]]); $j++) {

          $Media_Query['@Rule']['Rules'][$j]['Selectors'] = ${'@Rules'}['media'][$Media_Queries[$i]][$j]['Selectors'];
          $Media_Query['@Rule']['Rules'][$j]['Styles'] = ${'@Rules'}['media'][$Media_Queries[$i]][$j]['Styles'];
        }


        if (isset($Media_Query_Set_Keyframes_Array)) {

          $Media_Query['@Rule']['Rules'][] = $Media_Query_Set_Keyframes_Array; /* <= 1) use array_push instead */
          unset($Media_Query_Set_Keyframes_Array);
        }

        // APPEND @MEDIA QUERY
        $Media_Queries_Array[] = $Media_Query;
      }

      return $Media_Queries_Array;
    }


    if (strpos($Styles_String, '@media') !== FALSE) {

      $Media_Queries_Array = Process_Media_Queries($Styles_String);
    }


    if (strpos($Styles_String, '@keyframes') !== FALSE) {

      $Keyframes_Array = Process_Keyframes($Styles_String);
    }


    // BUILD STYLES ARRAY FROM STYLES STRING
    $Styles_Array = preg_split("/{|}/", trim($Styles_String));

    while ((!empty($Styles_Array)) && ($Styles_Array[count($Styles_Array) - 1] === '')) {array_pop($Styles_Array);}
  
    // PROCESS SELECTORS
    for ($i = 0; $i < count($Styles_Array); $i = $i + 2) {
  
      $Styles_Array[$i] = preg_replace('/(\#|\.|\")[^\s]+?»»»/', '$1', $Styles_Array[$i]);
      $Styles_Array[$i] = preg_replace('/.+»by»[^\.\#\[\>\+\~\s]+/', '', $Styles_Array[$i]);
      $Styles_Array[$i] = explode(',', $Styles_Array[$i]);
    }

  
    // PROCESS DECLARATIONS
    for ($i = 1; $i < count($Styles_Array); $i = $i + 2) {
  
      $Styles_Array[$i] = explode(';', $Styles_Array[$i]);
  
      for ($j = (count($Styles_Array[$i]) - 1); ($j + 1) > 0; $j--) {
  
        if ($Styles_Array[$i][$j] === '') {unset($Styles_Array[$i][$j]);}
      }
    }

  
    // RE-ORDER ALL SUB-ARRAYS
    for ($i = 0; $i < count($Styles_Array); $i++) {
  
      $Styles_Array[$i] = array_values($Styles_Array[$i]);
    }


    
  
  
    // CONVERT SERIES OF DECLARATIONS INTO AN OBJECT
    for ($i = 1; $i < count($Styles_Array); $i = $i + 2) {
  
      for ($j = 0; $j < count($Styles_Array[$i]); $j++) {
  
        $Styles_Array[$i][$j] = explode(':', $Styles_Array[$i][$j]);
      }
    }


    
  
    
    // BUILD STYLES ASSOCIATIVE ARRAY FROM STYLES ARRAY
    for ($i = 0; $i < count($Styles_Array); $i = $i + 2) {
  
      $New_Style_Entry = ['Selectors' => $Styles_Array[$i], 'Styles' => []];

      for ($j = 0; $j < count($New_Style_Entry['Selectors']); $j++) {

        $New_Style_Entry['Selectors'][$j] = trim($New_Style_Entry['Selectors'][$j]);
      }
  
      $Styles = [];
  
      for ($j = 0; $j < count($Styles_Array[($i + 1)]); $j++) {

        $Styles[$Styles_Array[($i + 1)][$j][0]] = trim($Styles_Array[($i + 1)][$j][1]);
      }
  
      $New_Style_Entry['Styles'] = $Styles;
    
      $Styles_Associative_Array[] = $New_Style_Entry;
    }


    // APPEND @KEYFRAMES
    if (isset($Keyframes_Array)) {

      $Styles_Associative_Array[] = $Keyframes_Array;
    }


    // APPEND @MEDIA QUERIES
    if (isset($Media_Queries_Array)) {

      $Styles_Associative_Array[] = $Media_Queries_Array;
    }

      // APPEND @MEDIA QUERY
      // $Styles_Associative_Array[] = $Media_Query;


    $Styles_JSON = json_encode($Styles_Associative_Array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    // REPLACE URL FUNCTION PATHS
    for ($i = 0; $i < count($URL_Paths); $i++) {

      $Styles_JSON = str_replace('REPLACEMENT_URL_PATH_'.$i, $URL_Paths[$i], $Styles_JSON);
    }
  
    return $Styles_JSON;
  }
  
  
  $Styles_JSON = Build_Styles_JSON($input);


  function ashivaFormatJSON($String) {

    // RESOLVE DOCUMENT COMMENTS
    $String = str_replace('«NEWLINE»', '\\\\n', $String);
    $String = str_replace('«NEWCOMMENT»', '"}},{"Selectors": ["DocumentComment"],"Styles":{"CommentText": "', $String);

  	if (!is_null(json_decode($String, TRUE))) {
    
      $Data = json_decode($String, TRUE);
  	}

    $String = json_encode($Data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    // RE-PROCESS COMMENTED SELECTORS AND STYLES
    $String = str_replace('"‹', '["', $String);
    $String = str_replace('›"', '"]', $String);

    // MINIMIZE HORIZONTAL SPACING
    $String = str_replace('    ', '  ', $String);

    // COMPLETE ASHIVA FORMATTING
    $String = preg_replace("/(\:\s\{|\:\s\[|\,)\n([\s]+)(\"|\{|\[)/", "$1\n\n$2$3", $String);

    return $String;
  }


  $Styles_JSON = ashivaFormatJSON($Styles_JSON);

  return $Styles_JSON;
}


$Styles_JSON = CSS_to_JSON($Styles_String);


echo '<pre>';
echo '<h2>Styles JSON</h2>';
print_r($Styles_JSON);
echo '</pre>';

?>
