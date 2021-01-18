<?php

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
          
?>
