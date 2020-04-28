<?php

  //******************//
 //* DISPLAY ERRORS *//
//******************//

error_reporting(E_ALL);
ini_set('display_errors', 1);


  //****************//
 //* INCLUDE CORE *//
//****************//

require_once $_SERVER['DOCUMENT_ROOT'].'/.assets/system/core/core.php';


  //*************//
 //* FUNCTIONS *//
//*************//

// FUNCTION :: INDENT
function indent($indent) {

  $Indent_Space = [];

  for ($i = 0; $i < $indent; $i++) {$Indent_Space[] = '  ';}

  return implode('', $Indent_Space);
}


// FUNCTION :: BUILD SCRIPT
function buildScript($Script_Array, $Module_Info, $indent = 0) {

  $Termination_Map[$indent] = FALSE;

  $scriptString = '';

  for ($h = 0; $h < count($Script_Array); $h++) {

    if (isset($Script_Array[$h]['Skip_Control'])) {

      if ($Script_Array[$h]['Skip_Control'] === 'Skip') continue;
      if ($Script_Array[$h]['Skip_Control'] === 'Comment') {$scriptString .= "/*\n\n";}
    }


    if (isset($Script_Array[$h]['Control'])) {

      switch ($Script_Array[$h]['Control']) {

        case ('Function') :

          $Assigner = (isset($Script_Array[$h]['Assigner'])) ? $Script_Array[$h]['Assigner'] : 'const';

          $scriptString .= "\n".indent($indent);

          if ((isset($Script_Array[$h]['Function_Invoked']) && ($Script_Array[$h]['Function_Invoked'] === TRUE))) {
          
            if (isset($Script_Array[$h]['Assigned_Name'])) {$scriptString .= $Assigner.' '.$Script_Array[$h]['Assigned_Name'].' = ';}
            $scriptString .= '('.buildScript($Script_Array[$h]['Control_Function'], $Module_Info, $indent).')();'."\n";
          }
          
          else {

            // Crockford uses the terms "Function Expression" and "Function Statement"
            // If the first token in a statement is "function" then it's a "function statement". Otherwise it's a "function expression".

            // I THINK YOU CAN MAKE THIS SIMPLER :: 'Declared Name' *ALWAYS* MEANS 'Function_Type' IS 'Regular'...
            // No... because regular functions don't need declared names...
            // So a function can be:

            // 1) Arrow / No Declared Name
            // 2) Regular / No Declared Name
            // 3) Regular / Declared Name

            // So this just means that any instance of 4) Arrow / Declared Name is *ACTUALLY* 3) Regular / Declared Name
            // ... THINK ABOUT IT.

            if (isset($Script_Array[$h]['Assigned_Name'])) {$Termination_Map[$indent] = TRUE; $scriptString .= $Assigner.' '.$Script_Array[$h]['Assigned_Name'].' = ';}
            if (isset($Script_Array[$h]['Function_Type']) && ($Script_Array[$h]['Function_Type'] === 'Regular')) {$scriptString .= 'function ';}
            if (isset($Script_Array[$h]['Declared_Name'])) {$scriptString .= $Script_Array[$h]['Declared_Name'].' ';}
            $scriptString .= '('.implode(', ', $Script_Array[$h]['Parameters']).')';
            if (!isset($Script_Array[$h]['Function_Type']) || ($Script_Array[$h]['Function_Type'] === 'Arrow')) {$scriptString .= ' =>';}
          }
          
          break;


        case ('Loop') :

          $scriptString .= "\n".indent($indent);

          switch ($Script_Array[$h]['Type']) {

            case ('For') :

              $scriptString .= 'for ('.$Script_Array[$h]['Header'].')';
              break;

            case ('While') :

              $scriptString .= 'while (';

              if (count($Script_Array[$h]['Conditions']) > 1) {
            
                for ($i = 0; $i < count($Script_Array[$h]['Conditions']); $i++) {
                
                  if ($i > 0) {$scriptString .= ' '.$Script_Array[$h]['Operator'].' ';}
                  $scriptString .= '('.$Script_Array[$h]['Conditions'][$i].')';
                }
              }

              else {$scriptString .= $Script_Array[$h]['Conditions'][0];}
              $scriptString .= ')';
              break;
          }

          break;


        case ('If') :
        case ('Else_If') :

          $controlWord = ($Script_Array[$h]['Control'] === 'If') ? 'if' : 'else if';
          $scriptString .= "\n".indent($indent).$controlWord.' (';

          if (count($Script_Array[$h]['Conditions']) > 1) {
            
            for ($i = 0; $i < count($Script_Array[$h]['Conditions']); $i++) {
                
              if ($i > 0) {$scriptString .= ' '.$Script_Array[$h]['Operator'].' ';}
              $scriptString .= '('.$Script_Array[$h]['Conditions'][$i].')';
            }
          }

          else {$scriptString .= $Script_Array[$h]['Conditions'][0];}
          $scriptString .= ')';

          break;


        case ('Else') :

          $scriptString .= "\n".indent($indent).'else';

          break;


        case ('Statements') :

          for ($i = 0; $i < count($Script_Array[$h]['Statements']); $i++) {$scriptString .= indent($indent).$Script_Array[$h]['Statements'][$i].';'."\n";}

          break;


        case ('Event_Listener') :

          $scriptString .= indent($indent).$Script_Array[$h]['Event_Target'];
          $scriptString .= (($Script_Array[$h]['Event_Target'] === 'window') && ($Script_Array[$h]['Event_Type'] === 'load')) ? '_' : '.';

          if ((isset($Script_Array[$h]['Uses_OnEvent_Handler'])) && ($Script_Array[$h]['Uses_OnEvent_Handler'] === TRUE)) {

          	$scriptString .= 'on'.$Script_Array[$h]['Event_Type'].' = ';
          	$scriptString .= buildScript($Script_Array[$h]['Control_Function'], $Module_Info, $indent);
          	$scriptString .= ';'."\n";
          }

          else {
            
            $scriptString .= 'addEventListener(\''.$Script_Array[$h]['Event_Type'].'\', ';
            if ((isset($Script_Array[$h]['Event_Callback_Name'])) && ($Script_Array[$h]['Event_Callback_Name'] !== '')) {$scriptString .= $Script_Array[$h]['Event_Callback_Name'];}
            else {$scriptString .= buildScript($Script_Array[$h]['Control_Function'], $Module_Info, $indent);}
            if ((isset($Script_Array[$h]['Event_useCapture'])) && ($Script_Array[$h]['Event_useCapture'] === TRUE)) {$scriptString .= ', true';}
            else {$scriptString .= ', false';}
            $scriptString .= ');'."\n";
          }

          break;


        case ('Set_Timeout') :
        case ('Set_Interval') :

          $scriptString .= "\n";
          $controlWord = ($Script_Array[$h]['Control'] === 'Set_Timeout') ? 'setTimeout' : 'setInterval';
          $scriptString .= indent($indent).$controlWord.'(';
          if ($Script_Array[$h]['Callback_Name'] !== '') {$scriptString .= $Script_Array[$h]['Callback_Name'];}
          else {$scriptString .= buildScript($Script_Array[$h]['Control_Function'], $Module_Info, $indent);}
          $scriptString .= ', '.$Script_Array[$h]['Time'].');'."\n";

          break;


        case ('Switch_Case') :

          $scriptString .= "\n".indent($indent).'switch ('.$Script_Array[$h]['Control_Expression'].')';

          if (isset($Script_Array[$h]['Cases'])) {

            $scriptString .= ' {'."\n\n";
            $indent++;

            for ($i = 0; $i < count($Script_Array[$h]['Cases']); $i++) {

              $scriptString .= "\n\n".indent($indent).'case (';

              if (count($Script_Array[$h]['Cases'][$i]['Case']) > 1) {
              
                for ($j = 0; $j < count($Script_Array[$h]['Cases'][$i]['Case']); $j++) {
                  
                  if ($j > 0) {$scriptString .= ' '.$Script_Array[$h]['Cases'][$i]['Operator'].' ';}

                  $scriptString .= '('.$Script_Array[$h]['Cases'][$i]['Case'][$j].')';
                }
              }

              else {

                $scriptString .= $Script_Array[$h]['Cases'][$i]['Case'][0];
              }

              $scriptString .= ') :'."\n\n";

              $indent++;
              $scriptString .= buildScript($Script_Array[$h]['Cases'][$i]['Block'], $Module_Info, $indent);
              if ($Script_Array[$h]['Cases'][$i]['Break'] === TRUE) {$scriptString .= indent($indent).'break;';}
              $indent--;
            }

            if (isset($Script_Array[$h]['Default'])) {

              $scriptString .= "\n\n".indent($indent).'default :'."\n\n";
              $indent++;

              for ($i = 0; $i < count($Script_Array[$h]['Default']); $i++) {

                $scriptString .= indent($indent).$Script_Array[$h]['Default'][$i].';'."\n";
              }

              $indent--;
            }

            $indent--;
            $scriptString .= "\n".indent($indent).'}'."\n\n" ;
          }

          break;
          

        case ('Comment') :

          switch ($Script_Array[$h]['Comment_Type']) {

            case ('sameLine') : $scriptString .= "/// ".$Script_Array[$h]['Comment'][0]."\n"; break;
            case ('singleLine') : $scriptString .= "\n\n".indent($indent)."// ".$Script_Array[$h]['Comment'][0]."\n\n"; break;
            case ('multiLine') : $scriptString .= "\n\n".indent($indent)."/*\n\n".indent($indent).implode("\n".indent($indent), $Script_Array[$h]['Comment'])."\n\n".indent($indent)."*/\n\n"; break;
          }

          break;

        default :

          $scriptString .= indent($indent).'// Ashiva Console: Unknown Javascript Control "'.$Script_Array[$h]['Control'].'" in '.txt($Module_Info['Name']).' Module by '.txt($Module_Info['Publisher']);
      }
    }

    else {

      $scriptString .= "\n".indent($indent).'// Ashiva Console: Javascript Control Missing in '.txt($Module_Info['Name']).' Module by '.txt($Module_Info['Publisher'])."\n";
    }

    if (isset($Script_Array[$h]['Block'])) {

      $scriptString .= ' {'."\n\n";
      $indent++;
      $scriptString .= buildScript($Script_Array[$h]['Block'], $Module_Info, $indent);
      $indent--;
      $scriptString .= "\n".indent($indent).'}';

      if ($Termination_Map[$indent] === TRUE) {

        $scriptString .= ';';
        $Termination_Map[$indent] = FALSE;
      }

      $scriptString .= "\n\n";
    }
    
    if ((isset($Script_Array[$h]['Skip_Control'])) && ($Script_Array[$h]['Skip_Control'] === 'Comment')) {$scriptString .= "\n*/";}
  }

  return $scriptString;
}


// FUNCTION :: CREATE SCRIPT
function createScript($Script_Array, $Module_Info, $Code_Panel = FALSE) {

  $scriptString = buildScript($Script_Array, $Module_Info);

  $scriptString = preg_replace("/\;\s*\,/", ",", $scriptString);
  $scriptString = preg_replace("/\s*\n\s*\n(\s*\})/", "\n$1", $scriptString);
  $scriptString = preg_replace("/\n{3,}/", "\n\n", $scriptString);
  $scriptString = preg_replace("/\(\s*\n\s*\(/", "((", $scriptString);
  $scriptString = preg_replace("/(\,|\=)\s*\n\s*\(/", "$1 (", $scriptString);
  $scriptString = preg_replace("/\}\s*\n{2}(\,|\;)/", "}$1", $scriptString);
  $scriptString = preg_replace("/\n+[\/]{3}/", " ///", $scriptString);
  $scriptString = preg_replace("/\n{2}([\s]*\/\/\s{1})/", "\n\n\n\n$1", $scriptString);
  $scriptString = preg_replace("/^\s+([^\w])/", "$1", $scriptString);
  $scriptString = preg_replace("/\(\nfunction/", "(function", $scriptString);
  $scriptString = preg_replace("/\}\n\n\)/", "})", $scriptString);

  if ($Code_Panel !== TRUE) {

    preg_match_all("/Worker\(\'([^\']+)\'/", $scriptString, $newWorkers); $newWorkers = array_unique($newWorkers[1]); sort($newWorkers);

    $scriptString = preg_replace("/(var|let|const)\s+([^\s]+)\s\=\s+new[\s|_]Worker\(\'([^\']+)\'\,?\s?([^\)]*)\)/", "let $2 = new_Worker($4{workerName: '$3', ashivaModule: '".url($Module_Info['Name'])."', ashivaPublisher: '".url($Module_Info['Publisher'])."', workerFunction: $3})", $scriptString);
    $scriptString = preg_replace("/new_Worker\(\{([^\}]+)\}\{([^\}]+)\}\)/", "new_Worker({\$2, \$1})", $scriptString);

    foreach ($newWorkers as $workerName) {
      
      $workerAddress = url($Module_Info['Name'])."»by»".url($Module_Info['Publisher'])."»»»".$workerName;
      $workerFunctionName = preg_replace("/([A-Za-z0-9]+)(--)?[^\.]*\.js/", "$1WorkerFunction", $workerName);

      $scriptString = preg_replace("/".$workerName."/", $workerFunctionName."ToUse", $scriptString);
      $scriptString = preg_replace("/workerName: '".$workerFunctionName."ToUse'/", "workerName: '".$workerName."'", $scriptString);
      $scriptString = preg_replace("/(let\s[^\s]+\s\=\snew_Worker\(\{workerName:\s'".$workerName."')/", "\nif (!ashivaModuleWorkers.hasOwnProperty('".$workerAddress."')) {ashivaModuleWorkers['".$workerAddress."'] = {ashivaModule: 'sb-customer', ashivaPublisher: 'scotia-beauty'};}\nconst ".$workerFunctionName."ToUse = (typeof ".$workerFunctionName." === 'undefined') ? () => console.log('⚠️ Ashiva Console: workerFunction missing for \"".$workerAddress."\". Attach workerFunction to new Worker(\'".$workerName."\') or define ".$workerFunctionName."().') : ".$workerFunctionName.";\n$1", $scriptString, 1);
    }

    $scriptString = preg_replace("/(\s*workerFunction\:\s*[^\,\}]+\,)(.+?)(workerFunction\:)/", "$2$3", $scriptString);
  }

  return $scriptString;
}


// FUNCTION :: GET SCRIPTS
function getScripts($Modules) {

  $Script = '';
  $Script .= '"use strict";'."\n\n\n";

  foreach ($Modules['Scripts'] as $Module_Name => $Module_Scriptsheet) {

    $Module_Info['Name'] = $Module_Name;
    $Module_Info['Publisher'] = $Module_Publisher = $Modules['Register'][$Module_Name]['Publisher'];

    $Module_Scriptsheet = createScript($Module_Scriptsheet, $Module_Info);

    $Script .= '  //*'.str_repeat('*', (strlen($Module_Name) + strlen($Module_Publisher) + 13)).'*//'."\n";
    $Script .= ' //* '.strtoupper(txt($Module_Name)).' MODULE by '.strtoupper(txt($Module_Publisher)).' *//'."\n";
    $Script .= '//*'.str_repeat('*', (strlen($Module_Name) + strlen($Module_Publisher) + 13)).'*//'."\n\n";
    $Script .= $Module_Scriptsheet;
    $Script .=  "\n\n\n\n";
  }

  return $Script;
}

?>
