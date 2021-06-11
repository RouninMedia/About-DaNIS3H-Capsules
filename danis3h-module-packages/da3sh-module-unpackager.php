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


  //**********************//
 //* GET ASHIVA PACKAGE *//
//**********************//

$ashivaModuleJSON = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/.assets/modules/ashiva-core/ashiva-test-module/sb-nextpage2.json');


  //************************************//
 //* GET ELEMENTS FROM ASHIVA PACKAGE *//
//************************************//

$ashivaModulePackage = json_decode($ashivaModuleJSON, TRUE);

$ashivaModulePublisher = url($ashivaModulePackage['ashivaModuleManifest']['ashivaPublisher']);
$ashivaModuleFunctionName = $ashivaModulePackage['ashivaModuleManifest']['ashivaPublisherCode'].'_'.$ashivaModulePackage['ashivaModuleManifest']['ashivaModuleName'];
$ashivaModuleName = url($ashivaModuleFunctionName);
$moduleSheetList = $ashivaModulePackage['ashivaModuleManifest']['ashivaModuleBlock']['ashivaModuleSheetList'];


  //***********************************//
 //* GENERATE ASHIVA MODULE MANIFEST *//
//***********************************//

$ashivaModuleManifest = '';

$indent = '  ';

$ashivaModuleManifest .= '<?php'."\n\n";
$ashivaModuleManifest .= 'function '.$ashivaModuleFunctionName.'(...$moduleParameters) {'."\n\n";

$ashivaModuleManifest .= $indent.'// GET MODULE SHEETS'."\n";

for ($i = 0; $i < count($moduleSheetList); $i++) {

  $moduleBlockEntry = '';
  $moduleBlockEntry .= '$moduleBlock[\'Codesheets\'][] = [\'';
  $moduleBlockEntry .= $ashivaModulePackage['ashivaModuleManifest']['ashivaPublisherCode'].'_';
  $moduleBlockEntry .= $moduleSheetList[$i]['modulesheetName'].'\', \'';
  $moduleBlockEntry .= $ashivaModulePackage['ashivaModuleManifest']['ashivaPublisherCode'].'_';
  $moduleBlockEntry .= $moduleSheetList[$i]['modulesheetFilename'].'\'];';

  $ashivaModuleManifest .= $indent.$moduleBlockEntry."\n";
}

$ashivaModuleManifest .= "\n";

$ashivaModuleManifest .= $indent.'// INITIALISE MODULE'."\n";
$initialiseModule = '';
$initialiseModule .= $indent.'extract(initialiseModule(__DIR__, $moduleParameters';

if (array_key_exists('ashivaModuleSheetList', $ashivaModulePackage['ashivaModuleManifest']['ashivaModuleBlock'])) {

	$initialiseModule .= ', $moduleBlock';
}

if (array_key_exists('ashivaPrimeComponent', $ashivaModulePackage['ashivaModuleManifest']['ashivaModuleBlock'])) {

  if (!array_key_exists('ashivaModuleSheetList', $ashivaModulePackage['ashivaModuleManifest']['ashivaModuleBlock'])) {

	$initialiseModule .= ', []';
  }

  $initialiseModule .= ', \''.$ashivaModulePackage['ashivaModuleManifest']['ashivaModuleBlock']['ashivaPrimeComponent'].'\'';
}

$initialiseModule .= '));';
$ashivaModuleManifest .= $initialiseModule."\n\n";

$ashivaModuleManifest .= $indent.'// BUILD ASHIVA MODULE'."\n";
$ashivaModuleManifest .= $indent.'$Module[\'Register\'] = $Register;'."\n";

foreach ($ashivaModulePackage['ashivaModuleManifest']['ashivaModuleBuild'] as $Component_Type => $ashivaModuleSheet_Name) {

  $ashivaModuleManifest .= $indent.'$Module[\''.$Component_Type.'\'] = $'.$ashivaModulePackage['ashivaModuleManifest']['ashivaPublisherCode'].'_'.$ashivaModuleSheet_Name.';'."\n";
}

$ashivaModuleManifest .= "\n";

$ashivaModuleManifest .= $indent.'// SEND ASHIVA MODULE'."\n";
$ashivaModuleManifest .= $indent.'return $Module;'."\n";
$ashivaModuleManifest .= '}'."\n\n";
$ashivaModuleManifest .= '?>';



  //*************************//
 //* INSTALL ASHIVA MODULE *//
//*************************//

  // CREATE PUBLISHER FOLDER

if (!is_dir($_SERVER['DOCUMENT_ROOT'].'/.assets/modules/'.$ashivaModulePublisher)) {

  mkdir($_SERVER['DOCUMENT_ROOT'].'/.assets/modules/'.$ashivaModulePublisher, 0777);

  echo '<p>💖 <strong>Ashiva Console ::</strong> Created :: <code><em>/.assets/modules/'.$ashivaModulePublisher.'/</em></code></p>';  
}

if (is_dir($_SERVER['DOCUMENT_ROOT'].'/.assets/modules/'.$ashivaModulePublisher.'/'.$ashivaModuleName)) {
  
  echo '<p>⚠️ <strong>Ashiva Console ::</strong> Stopping execution :: <code><em>/.assets/modules/'.$ashivaModulePublisher.'/'.$ashivaModuleName.'/</em></code> already exists.</p>';  
}

  // CREATE MODULE FOLDER

else {

  mkdir($_SERVER['DOCUMENT_ROOT'].'/.assets/modules/'.$ashivaModulePublisher.'/'.$ashivaModuleName, 0777);

  echo '<p>💖 <strong>Ashiva Console ::</strong> Created :: <code><em>/.assets/modules/'.$ashivaModulePublisher.'/'.$ashivaModuleName.'/</em></code></p>';
  
  $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/.assets/modules/'.$ashivaModulePublisher.'/'.$ashivaModuleName.'/'.$ashivaModuleName.'.php', 'w');

  fwrite($fp, $ashivaModuleManifest);

  fclose($fp);

  echo '<p>💖 <strong>Ashiva Console ::</strong> Created :: <code><em>/.assets/modules/'.$ashivaModulePublisher.'/'.$ashivaModuleName.'/'.$ashivaModuleName.'.php</em></code></p>';


  // CREATE CODESHEETS

  mkdir($_SERVER['DOCUMENT_ROOT'].'/.assets/modules/'.$ashivaModulePublisher.'/'.$ashivaModuleName.'/codesheets', 0777);

  echo '<p>💖 <strong>Ashiva Console ::</strong> Created :: <code><em>/.assets/modules/'.$ashivaModulePublisher.'/'.$ashivaModuleName.'/codesheets/</em></code></p>';

  if (array_key_exists('ashivaModuleSheetList', $ashivaModulePackage['ashivaModuleManifest']['ashivaModuleBlock'])) {

    $moduleSheetList = $ashivaModulePackage['ashivaModuleManifest']['ashivaModuleBlock']['ashivaModuleSheetList'];

    $moduleSheetLocation = $ashivaModulePublisher.'/'.$ashivaModuleName.'/codesheets';

    for ($i = 0; $i < count($moduleSheetList); $i++) {

      $Component_Sheet_Type = $moduleSheetList[$i]['modulesheetType'];
      $Component_Sheet_Name = $moduleSheetList[$i]['modulesheetName'];
      $Component_Sheet_Filename = url($moduleSheetList[$i]['modulesheetFilename'], 'raw');
  	  $Component_Sheet_JSON = json_encode($ashivaModulePackage['ashivaCodeSheets'][$Component_Sheet_Type][$Component_Sheet_Name]);

      $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/.assets/modules/'.$moduleSheetLocation.'/'.$Component_Sheet_Filename.'.json', 'w');

      fwrite($fp, $Component_Sheet_JSON);

      fclose($fp);

      echo '<p>💖 <strong>Ashiva Console ::</strong> Created :: <code><em>/.assets/modules/'.$moduleSheetLocation.'/'.url($moduleSheetList[$i]['modulesheetFilename'], 'raw').'.json</em></code></p>';
    }
  }
}

echo '</pre>';

?>
