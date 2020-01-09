<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$Styles_String = <<< ENDOFSTYLES

.pageBack,
.pageForward {

  position: absolute;
  top: calc(50% - 20px);
  z-index: 12;
  width: 40px;
  height: 40px;
  line-height: 40px;
  font-size: 36px;
  text-align: center;
  background-color: rgba(0, 0, 0, 0.2);
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

  transform: rotateY(180deg);
  color: rgba(255, 255, 255, 0.5);
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


function CSS_to_JSON ($Styles_String) {

  // DELETE ALL WHITESPACE
  $Styles_String = preg_replace("/\s*/", "", $Styles_String);



  // CAPTURE KEYFRAMES AND SAVE TO @Rules
  preg_match_all("/@keyframes([^\{]+)\{(.+?)\}\}/", $Styles_String,  $matches);
  $Keyframes_Matches = $matches[0];
  
  for ($i = 0; $i < count($Keyframes_Matches); $i++) {

    $Styles_String = str_replace($Keyframes_Matches[$i], '', $Styles_String);

    $Keyframes_Matches[$i] = substr($Keyframes_Matches[$i], 10, -2);
  }

  ${'@Rules'}['@keyframes'] = $Keyframes_Matches;



  // BUILD STYLES ARRAY FROM STYLES STRING
  $Styles_Array = preg_split("/({|})/", $Styles_String);

  if ($Styles_Array[count($Styles_Array) - 1] === '') {array_pop($Styles_Array);}

  // PROCESS SELECTORS
  for ($i = 0; $i < count($Styles_Array); $i = $i + 2) {

    $Styles_Array[$i] = explode(',', $Styles_Array[$i]);
  }

  // PROCESS DECLARATIONS
  for ($i = 1; $i < count($Styles_Array); $i = $i + 2) {

    $Styles_Array[$i] = explode(';', $Styles_Array[$i]);

    for ($j = (count($Styles_Array[$i]) - 1); ($j + 1) > 0; $j--) {

      if ($Styles_Array[$i][$j] === '') {unset($Styles_Array[$i][$j]);}
    }

    for ($j = 0; $j < count($Styles_Array[$i]); $j++) {

      $Styles_Array[$i][$j] = preg_replace("/(\,)/", '$1 ', $Styles_Array[$i][$j]);
      $Styles_Array[$i][$j] = preg_replace("/[^A-Za-z](\+|\-|\*|\/)[^A-Za-z]/", " $1 ", $Styles_Array[$i][$j]);
      $Styles_Array[$i][$j] = preg_replace("/(\d+\.?\d+s)/", ' $1 ', $Styles_Array[$i][$j]);
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
  $Styles_Associative_Array = [];

  for ($i = 0; $i < count($Styles_Array); $i = $i + 2) {

    $New_Style_Entry = ['Selectors' => $Styles_Array[$i], 'Styles' => []];

    $Styles = [];

    for ($j = 0; $j < count($Styles_Array[($i + 1)]); $j++) {

      $Styles[$Styles_Array[($i + 1)][$j][0]] =  $Styles_Array[($i + 1)][$j][1];
    }

    $New_Style_Entry['Styles'] = $Styles;
    
    $Styles_Associative_Array[] = $New_Style_Entry;
  }


  // BUILD AND APPEND @RULES
  $Rule_Types = array_keys(${'@Rules'});

  for ($i = 0; $i < count($Rule_Types); $i++) {

    for ($j = 0; $j < count(${'@Rules'}[$Rule_Types[$i]]); $j++) {

      $RuleToAdd['@Rule']['Type'] = strtolower($Rule_Types[$i]);
      $RuleToAdd['@Rule']['Animation Name'] = explode('{', ${'@Rules'}[$Rule_Types[$i]][$j])[0];
      $Animation_Sequence_Blocks = [];


      // BUILD ANIMATION SEQUENCE (ARRAY)
      $Animation_Sequence = preg_split("/{|}/", ${'@Rules'}[$Rule_Types[$i]][$j]);

      for ($k = 0; $k < count($Animation_Sequence); $k++) {

        $Animation_Sequence[$k] = preg_split("/;/", $Animation_Sequence[$k]);
      }

      for ($k = (count($Animation_Sequence) - 1); ($k + 1) > 0; $k--) {

        for ($l = (count($Animation_Sequence[$k]) - 1); ($l + 1) > 0; $l--) {

          if ($Animation_Sequence[$k][$l] === '') {unset($Animation_Sequence[$k][$l]);}
        }
      }
      
      // TIDY UP ANIMATION SEQUENCE
      $Animation_Sequence = array_values($Animation_Sequence);

      for ($k = 0; $k < count($Animation_Sequence); $k++) {
 
        $Animation_Sequence[$k] = array_values($Animation_Sequence[$k]);
      }


      // BUILD ANIMATION SEQUENCE BLOCKS (ASSOCIATIVE ARRAY)
      for ($k = 1; $k < count($Animation_Sequence); $k = $k + 2) {

        $Animation_Sequence_Block['Frames'] = explode(',', $Animation_Sequence[$k][0]);

        $Animation_Sequence_Block_Styles = [];

        for ($l = 0; $l < count($Animation_Sequence[($k + 1)]); $l++) {

          $Property = explode(':', $Animation_Sequence[($k + 1)][$l])[0];
          $Value = explode(':', $Animation_Sequence[($k + 1)][$l])[1];
          $Animation_Sequence_Block_Styles[$Property] = $Value;
          $Animation_Sequence_Block['Styles'] = $Animation_Sequence_Block_Styles;
        }

        $Animation_Sequence_Blocks[] = $Animation_Sequence_Block;
      }

      // BUILD @RULE
      $RuleToAdd['@Rule']['Animation Sequence'] = $Animation_Sequence_Blocks;
      
      // APPEND @RULE
      $Styles_Associative_Array[] = $RuleToAdd;
    }
  }


  $Styles_JSON = json_encode($Styles_Associative_Array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

  return $Styles_JSON;
}


$Styles_JSON = CSS_to_JSON($Styles_String);


echo '<pre>';
echo '<h2>Styles JSON</h2>';
print_r($Styles_JSON);
echo '</pre>';

?>
