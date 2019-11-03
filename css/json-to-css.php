<?php

function getStyles($Modules) {

  $Stylesheet = '';

  foreach ($Modules['Styles'] as $Module_Name => $Module_Stylesheet) {

    $Module_Publisher = $Modules['Register'][$Module_Name]['Publisher'];
    $Module_Set = str_replace('::', '°', $Module_Name);
    $Module_Stylesheet = json_decode($Module_Stylesheet, TRUE);

    $Stylesheet .= '  //*'.str_repeat('*', (strlen($Module_Name) + strlen($Module_Publisher) + 13)).'*//'."\n";
    $Stylesheet .= ' //* '.strtoupper(txt($Module_Name)).' MODULE by '.strtoupper(txt($Module_Publisher)).' *//'."\n";
    $Stylesheet .= '//*'.str_repeat('*', (strlen($Module_Name) + strlen($Module_Publisher) + 13)).'*//'."\n\n";

    for ($i = 0; $i < count($Module_Stylesheet); $i++) {

      if (isset($Module_Stylesheet[$i]['@Rule'])) {

        $Stylesheet .= "\n".'@'.$Module_Stylesheet[$i]['@Rule']['Type'];
        $Stylesheet .= ' ';

        if ($Module_Stylesheet[$i]['@Rule']['Type'] === 'keyframes') {

          $Stylesheet .= $Module_Stylesheet[$i]['@Rule']['Animation Name'];
          $Stylesheet .= ' {'."\n";

          for ($j = 0; $j < count($Module_Stylesheet[$i]['@Rule']['Animation Sequence']); $j++) {

          	$Stylesheet .= "\n".'  ';

            for ($k = 0; $k < count($Module_Stylesheet[$i]['@Rule']['Animation Sequence'][$j]['Frames']); $k++) {

              $Stylesheet .= $Module_Stylesheet[$i]['@Rule']['Animation Sequence'][$j]['Frames'][$k];
              if ($k < (count($Module_Stylesheet[$i]['@Rule']['Animation Sequence'][$j]['Frames']) - 1)) {$Stylesheet .= ', ';}
            }
          
            $Stylesheet .= ' {'."\n";

              $Styles = $Module_Stylesheet[$i]['@Rule']['Animation Sequence'][$j]['Styles'];

              foreach ($Styles as $Property => $Value) {

                $Stylesheet .= '    '.$Property.': '.$Value.';'."\n";
              }

            $Stylesheet .= '  }'."\n";
          }

          $Stylesheet .= '}'."\n\n";
        }

        else if ($Module_Stylesheet[$i]['@Rule']['Type'] === 'media') {

          for ($j = 0; $j < count($Module_Stylesheet[$i]['@Rule']['Directives']); $j++) {

            $Stylesheet .= $Module_Stylesheet[$i]['@Rule']['Directives'][$j];

            if ($j < (count($Module_Stylesheet[$i]['@Rule']['Directives']) - 1)) {$Stylesheet .= ','."\n";}
          }

          $Stylesheet .= ' {'."\n";

          for ($j = 0; $j < count($Module_Stylesheet[$i]['@Rule']['Rules']); $j++) {

            $Selectors = $Module_Stylesheet[$i]['@Rule']['Rules'][$j]['Selectors'];

            for ($k = 0; $k < count($Selectors); $k++) {
    
              if ($k > 0) {$Stylesheet .= ','."\n";}

              $Namespace = url($Module_Set).'»by»'.cml($Module_Publisher).'»»»';
              $Selector = $Selectors[$k];
              $Selector = str_replace('.', '.'.$Namespace, $Selector);
              $Selector = str_replace('#', '#'.$Namespace, $Selector);
              $Stylesheet .= "\n".'  '.$Selector;
            }

            $Stylesheet .= ' {'."\n";

            $Styles = $Module_Stylesheet[$i]['@Rule']['Rules'][$j]['Styles'];

            foreach ($Styles as $Property => $Value) {

              $Stylesheet .= '    '.$Property.': '.$Value.';'."\n";
            }

            $Stylesheet .= '  }'."\n";
          }

          $Stylesheet .= '}'."\n\n";
        }
      }

      else {

        $Selectors = $Module_Stylesheet[$i]['Selectors'];

        for ($j = 0; $j < count($Selectors); $j++) {
    
          if ($j > 0) {$Stylesheet .= ','."\n";}

          $Namespace = url($Module_Set).'»by»'.cml($Module_Publisher).'»»»';

          $Selector = $Selectors[$j];
          $Selector = str_replace('.', '.'.$Namespace, $Selector);
          $Selector = str_replace('#', '#'.$Namespace, $Selector);
          $Stylesheet .= $Selector;
        }

        $Stylesheet .= ' {'."\n";

        $Styles = $Module_Stylesheet[$i]['Styles'];

        foreach ($Styles as $Property => $Value) {

          $Stylesheet .= '  '.$Property.': '.$Value.';'."\n";
        }

        $Stylesheet .= '}'."\n\n";
      }
    }
  
    $Stylesheet .=  "\n\n\n\n";
  }

  return $Stylesheet;
}
          
?>
