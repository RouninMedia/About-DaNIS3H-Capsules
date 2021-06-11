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

echo '<pre>';


  //*************************//
 //* ASHIVA MODULE DETAILS *//
//*************************//
  



  ///////

  // NEED TO DERIVE THIS !!

  ///////
  
  $ashivaPublisher = 'Scotia Beauty';
  $ashivaPublisherCode = 'SB';
  $ashivaModuleName = 'nextPage';
  $ashivaModuleFullName = $ashivaPublisherCode.'_'.$ashivaModuleName;
  $ashivaModuleCode = '___MULTIPLY_OBFUSCATED_HASH_OF_PASSWORD_AND_CHECKSUM_HERE___';


  //*********************//
 //* GET ASHIVA MODULE *//
//*********************//

$ashivaModulePath = '/.assets/modules/'.url($ashivaPublisher).'/'.url($ashivaModuleFullName).'/'.url($ashivaModuleFullName).'.php';

$ashivaModuleManifest = file_get_contents($_SERVER['DOCUMENT_ROOT'].$ashivaModulePath);

$ashivaModuleManifest = str_replace('<?php', '', $ashivaModuleManifest);
$ashivaModuleManifest = str_replace('?>', '', $ashivaModuleManifest);

echo $ashivaModuleManifest;


// ANALYSE INITIALISE-MODULE-STATEMENT
preg_match('/extract\(initialiseModule\(__DIR__\, \$moduleParameters\, \$moduleBlock[^;]+;/', $ashivaModuleManifest, $initialiseModuleStatement);
$initialiseModuleStatement = str_replace('extract(initialiseModule(__DIR__, $moduleParameters', '', $initialiseModuleStatement[0]);

$ashivaModulePackage = [];
$ashivaModulePackage['ashivaModuleManifest'] = [];
$ashivaModulePackage['ashivaModuleManifest']['ashivaPublisher'] = $ashivaPublisher;
$ashivaModulePackage['ashivaModuleManifest']['ashivaPublisherCode'] = $ashivaPublisherCode;
$ashivaModulePackage['ashivaModuleManifest']['ashivaModuleName'] = $ashivaModuleName;
$ashivaModulePackage['ashivaModuleManifest']['ashivaModuleCode'] = $ashivaModuleCode;


switch (substr($initialiseModuleStatement, 0, 3)) {

  case (', $') :

    $ashivaModuleBlock = TRUE;

    preg_match('/\'([^\']+)\'\)\)/', $initialiseModuleStatement, $ashivaPrimeComponent);
    $ashivaPrimeComponent = (isset($ashivaPrimeComponent[1])) ? $ashivaPrimeComponent[1] : NULL;
    break;

  case (', [') :

    $ashivaModuleBlock = FALSE;

    preg_match('/\'([^\']+)\'\)\)/', $initialiseModuleStatement, $ashivaPrimeComponent);
    $ashivaPrimeComponent = $ashivaPrimeComponent[1];
    break;

  case ('));') :

    $ashivaModuleBlock = FALSE;

    $ashivaPrimeComponent = NULL;
    break;
}


if ($ashivaModuleBlock === TRUE) {

  $ashivaModulePackage['ashivaModuleManifest']['ashivaModuleBlock'] = [];

  // ADD PRIME COMPONENT
  if (!is_null($ashivaPrimeComponent)) {

    $ashivaModulePackage['ashivaModuleManifest']['ashivaModuleBlock']['ashivaPrimeComponent'] = $ashivaPrimeComponent;
  }

  // ADD CODESHEETS
  $ashivaModulePackage['ashivaModuleManifest']['ashivaModuleBlock']['ashivaCodeSheetList'] = [];


  preg_match_all('/\$moduleBlock\[\'Codesheets\'\]\[\][^\;]+\;/', $ashivaModuleManifest, $Codesheet_Statements);

  for ($i = 0; $i < count($Codesheet_Statements[0]); $i++) {

    preg_match_all('/\'([^\']+)\'/', $Codesheet_Statements[0][$i], $Codesheets[$i]);

    $Codesheets[$i] = $Codesheets[$i][1];
    array_shift($Codesheets[$i]);
  }

  for ($i = 0; $i < count($Codesheets); $i++) {

    $ashivaModulePackage['ashivaModuleManifest']['ashivaModuleBlock']['ashivaCodeSheetList'][$i]['codesheetName'] = str_replace($ashivaPublisherCode.'_', '', $Codesheets[$i][0]);
    $ashivaModulePackage['ashivaModuleManifest']['ashivaModuleBlock']['ashivaCodeSheetList'][$i]['codesheetFilename'] = str_replace($ashivaPublisherCode.'_', '', $Codesheets[$i][1]);
    $ashivaModulePackage['ashivaModuleManifest']['ashivaModuleBlock']['ashivaCodeSheetList'][$i]['codesheetType'] = 'Static';
    $ashivaModulePackage['ashivaModuleManifest']['ashivaModuleBlock']['ashivaCodeSheetList'][$i]['codesheetNotes'] = [];
  }
}


// ADD MODULE BUILD
$ashivaModulePackage['ashivaModuleManifest']['ashivaModuleBuild'] = [];

preg_match_all('/\$Module\[\'([^\']+)\'\]\s\=\s\$([^\;]+)\;/', $ashivaModuleManifest, $ModuleBuild_Statements);

for ($i = 0; $i < count($ModuleBuild_Statements[0]); $i++) {

  if ($ModuleBuild_Statements[1][$i] === 'Register') continue;

  $ashivaModulePackage['ashivaModuleManifest']['ashivaModuleBuild'][$ModuleBuild_Statements[1][$i]] = str_replace($ashivaPublisherCode.'_', '', $ModuleBuild_Statements[2][$i]);
}


// ADD CODE SHEETS
$ashivaModulePackage['ashivaCodeSheets'] = [];


for ($i = 0; $i < count($ashivaModulePackage['ashivaModuleManifest']['ashivaModuleBlock']['ashivaCodeSheetList']); $i++) {

  $Codesheet = $ashivaModulePackage['ashivaModuleManifest']['ashivaModuleBlock']['ashivaCodeSheetList'][$i];

  if ($Codesheet['codesheetType'] === 'Static') {

    $ashivaCodesheetPath = '/.assets/modules/'.url($ashivaPublisher).'/'.url($ashivaModuleFullName).'/codesheets/';
    $ashivaCodesheetPath .= url($ashivaPublisherCode).'-'.url($Codesheet['codesheetFilename'], 'raw').'.json';
    $ashivaCodesheet = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'].$ashivaCodesheetPath), TRUE);
    $ashivaModulePackage['ashivaCodeSheets']['Static'][$Codesheet['codesheetFilename']] = $ashivaCodesheet;
  }

  if ($Codesheet['codesheetType'] === 'Dynamic') {

    $ashivaModulePackage['ashivaCodeSheets']['Dynamic'][$Codesheet['codesheetFilename']] = [];
  }
}

print_r(json_encode($ashivaModulePackage, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

print_r(json_encode($ashivaModulePackage));

echo '</pre>';

?>
