<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$Test_Script_JSON = '[
  {
    "Control" : "Comment",
    "Comment_Type" : "singleLine",
    "Comment" : [

      "This is a single line comment"
    ]
  },

  {
    "Control" : "Comment",
    "Comment_Type" : "sameLine",
    "Comment" : [

      "This is a same-line comment"
    ]
  },

  {
    "Control" : "Comment",
    "Comment_Type" : "multiLine",
    "Comment" : [

      "This is a multi-line comment",
      "because it spans multiple lines,",
      "in this case, three of them."
    ]
  },

  {
    "Control" : "Comment",
    "Comment_Type" : "singleLine",
    "Comment" : [

      "** EXAMPLE OF A FUNCTION **"
    ]
  },

  {
    "Control" : "Function",
    "Name" : "responsiveDesign",
    "Parameters" : [],
    "Block" : [

      {
        "Control" : "Comment",
        "Comment_Type" : "singleLine",
        "Comment" : [

          "** EXAMPLE OF AN IF **"
        ]
      },

      {
        "Control" : "If",
        "Conditions" : [
          
          "window.matchMedia(\"(max-width: 420px)\").matches"
        ],

        "Block" : [

          {
            "Control" : "If",
            "Conditions" : [
          
              "window.scrollY > 70"
            ],

            "Block" : [

              {
                "Control" : "Statements",
                "Statements" : [

                  "header.style.boxShadow = \'0 6px 6px rgba(0,0,0,0.5)\'",
                  "mainMenu.style.boxShadow = \'-2px 2px 2px rgba(0,0,0,0.5)\'",
                  "customerActionBar.style.boxShadow = \'0 -6px 6px rgba(0,0,0,0.5)\'"
                ]
              }
            ]
          },

          {
            "Control" : "Comment",
            "Comment_Type" : "singleLine",
            "Comment" : [

              "** EXAMPLE OF AN ELSE **"
            ]
          },

          {
            "Control" : "Else",
            "Block" : [

              {
                "Control" : "Comment",
                "Comment_Type" : "singleLine",
                "Comment" : [

                  "** EXAMPLES OF STATEMENTS **"
                ]
              },

              {
                "Control" : "Statements",
                "Statements" : [

                  "header.removeAttribute(\'style\')",
                  "mainMenu.removeAttribute(\'style\')",
                  "customerActionBar.removeAttribute(\'style\')"
                ]
              }
            ]
          }
        ]
      }
    ]
  },

  {
    "Control" : "Comment",
    "Comment_Type" : "singleLine",
    "Comment" : [

      "** EXAMPLES OF EVENT LISTENERS WITH CALLBACKS **"
    ]
  },

  {
    "Control" : "Event_Listener",
    "Event_Target" : "window",
    "Event_Type" : "scroll",
    "Event_Callback_Name" : "responsiveDesign",
    "Event_useCapture" : false
  },

  {
    "Control" : "Event_Listener",
    "Event_Target" : "window",
    "Event_Type" : "resize",
    "Event_Callback_Name" : "responsiveDesign",
    "Event_useCapture" : false
  },

  {
    "Control" : "Event_Listener",
    "Event_Target" : "window",
    "Event_Type" : "load",
    "Event_Callback_Name" : "responsiveDesign",
    "Event_useCapture" : false
  },

  {
    "Control" : "Function",
    "Name" : "customerActions",
    "Parameters" : ["e"],
    "Block" : [

      {
        "Control" : "If",
        "Conditions" : [
          
          "e.target.dataset.hasOwnProperty(\'loginStatus\')"
        ],

        "Block" : [

          {
            "Control" : "Statements",
            "Statements" : [

              "window.scrollTo({top: 0, left: 0, behavior: \'smooth\'})"
            ]
          },

          {

            "Control" : "If",
            "Conditions" : [
          
              "e.target.dataset.loginStatus === \'logged-out\'"
            ],

            "Block" : [

              {
                "Control" : "Statements",
                "Statements" : [
                    
                  "window.history.pushState({action : \'login\'}, document.title, window.location.href.split(\'?\')[0] + \'?action=login\')"
                ]
              },

              {
                "Control" : "Comment",
                "Comment_Type" : "singleLine",
                "Comment" : [

                  "** EXAMPLES OF TIMERS WITH ANONYMOUS CALLBACK FUNCTIONS **"
                ]
              },

              {
                "Control" : "Set_Timeout",
                "Time" : 20,
                "Callback_Name" : "",
                "Control_Function" : [

                  {
                    "Control" : "Function",
                    "Name" : "",
                    "Parameters" : [],
                    "Block" : [

                      {
                        "Control" : "Statements",
                        "Statements" : [
                        
                          "history.back()"
                        ]
                      }
                    ]
                  }
                ]  
              },

              {
                "Control" : "Set_Timeout",
                "Time" : 40,
                "Callback_Name" : "",
                "Control_Function" : [

                  {
                    "Control" : "Function",
                    "Name" : "",
                    "Parameters" : [],
                    "Block" : [

                      {
                        "Control" : "Statements",
                        "Statements" : [
                        
                          "history.back()"
                        ]
                      }
                    ]
                  }
                ]  
              }
            ]
          },

          {
            "Control" : "Else_If",
            "Conditions" : [
          
              "e.target.dataset.loginStatus === \'logged-in\'"
            ],

            "Block" : [

              {
                "Control" : "Statements",
                "Statements" : [
                    
                  "window.history.pushState({action : \'logout-confirm\'}, document.title, window.location.href.split(\'?\')[0] + \'?action=logout-confirm\')"
                ]
              },

              {
                "Control" : "Set_Timeout",
                "Time" : 20,
                "Callback_Name" : "",
                "Control_Function" : [

                  {
                    "Control" : "Function",
                    "Name" : "",
                    "Parameters" : [],
                    "Block" : [

                      {
                        "Control" : "Statements",
                        "Statements" : [
                        
                          "history.back()"
                        ]
                      }
                    ]
                  }
                ]  
              },

              {
                "Control" : "Set_Timeout",
                "Time" : 40,
                "Callback_Name" : "",
                "Control_Function" : [

                  {
                    "Control" : "Function",
                    "Name" : "",
                    "Parameters" : [],
                    "Block" : [

                      {
                        "Control" : "Statements",
                        "Statements" : [
                        
                          "history.back()"
                        ]
                      }
                    ]
                  }
                ]  
              }
            ]
          },

          {
            "Control" : "Else",
            "Block" : [

              {
                "Control" : "Statements",
                "Statements" : [
                    
                  "window.history.pushState({action : \'logout\'}, document.title, window.location.href.split(\'?\')[0] + \'?action=logout\')"
                ]
              },

              {
                "Control" : "Set_Timeout",
                "Time" : 20,
                "Callback_Name" : "",
                "Control_Function" : [

                  {
                    "Control" : "Function",
                    "Name" : "",
                    "Parameters" : [],
                    "Block" : [

                      {
                        "Control" : "Statements",
                        "Statements" : [
                        
                          "history.back()"
                        ]
                      }
                    ]
                  }
                ]  
              },

              {
                "Control" : "Set_Timeout",
                "Time" : 40,
                "Callback_Name" : "",
                "Control_Function" : [

                  {
                    "Control" : "Function",
                    "Name" : "",
                    "Parameters" : [],
                    "Block" : [

                      {
                        "Control" : "Statements",
                        "Statements" : [
                        
                          "history.back()"
                        ]
                      }
                    ]
                  }
                ]  
              }
            ]
          }
        ]
      }
    ]
  },

  {
    "Control" : "Statements",
    "Statements" : [
                    
      "const customerActionBar = document.getElementsByClassName(\'sb-customer»by»scotiaBeauty»»»scotia-beauty-customer-action-bar\')[0]",
      "const scotiaBeautyCustomers = document.getElementsByClassName(\'sb-customer»by»scotiaBeauty»»»scotia-beauty-customers\')[0]"
    ]
  },

  {
    "Control" : "Comment",
    "Comment_Type" : "singleLine",
    "Comment" : [

      "** EXAMPLE OF A TIMER WITH A NAMED CALLBACK FUNCTION **"
    ]
  },

  {
    "Control" :  "Set_Interval",
    "Time" : 400,
    "Callback_Name" : "myFunction",
    "Control_Function" : {}
  },

  {
    "Control" : "Comment",
    "Comment_Type" : "singleLine",
    "Comment" : [

      "** EXAMPLE OF A SWITCH / CASE **"
    ]
  },

  {
    "Control" : "Switch_Case",
    "Control_Expression" : "myVariable",
    "Block" : [

      {
        "Control" : "Switch_Cases",
        "Switch_Cases" : [

          {

            "Case" : [

              "a"
            ],

            "Statements" : [
            
              "window.alert(\'This is working - a!\')"
            ],

            "Break" : false
          },

          {

            "Case" : [

              "b",
              "c"
            ],

            "Operator" : "&&",

            "Statements" : [
            
              "window.alert(\'This is working - b!\')",
              "window.alert(\'This is working - c!\')"
            ],

            "Break" : true
          },

          {

            "Case" : [

              "d",
              "e",
              "f"
            ],

            "Operator" : "||",

            "Statements" : [

              "window.alert(\'This is working - d!\')",
              "window.alert(\'This is working - e!\')",
              "window.alert(\'This is working - f!\')"
            ],

            "Break" : true
          }

        ],

        "Switch_Default" : "window.alert(\'This is working - default.\')"
      }
    ]
  },

  {
    "Control" : "Comment",
    "Comment_Type" : "singleLine",
    "Comment" : [

      "** EXAMPLE OF AN EVENT LISTENER WITH AN ANONYMOUS CALLBACK FUNCTION **"
    ]
  },

  {
    "Control" : "Event_Listener",
    
    "Event_Target" : "window",
    "Event_Type" : "resize",
    "Event_Callback_Name" : "",

    "Control_Function" : [

      {
        "Control" : "Function",
        "Name" : "",
        "Parameters" : [],
        "Block" : [

          {

            "Control" : "Statements",
            "Statements" : [
            
              "window.alert(\'This is also working!\')"
            ]
          }
        ]
      }
    ],

    "Event_useCapture" : false
  }

]';


// FUNCTION :: BUILD SCRIPT
function buildScript($Script_Array, $Module_Info, $indent = 0) {

  $scriptString = '';

  for ($h = 0; $h < count($Script_Array); $h++) {

    if (array_key_exists('Control', $Script_Array[$h])) {

      switch ($Script_Array[$h]['Control']) {

        case ('Function') :

          $scriptString .= "\n";
          if ($Script_Array[$h]['Name'] !== '') {$scriptString .= 'const '.$Script_Array[$h]['Name'].' = ';}
          $scriptString .= '(';
          $scriptString .= implode(',', $Script_Array[$h]['Parameters']);
          $scriptString .= ') =>';
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
          $scriptString .= 'addEventListener(\''.$Script_Array[$h]['Event_Type'].'\', ';
          if ($Script_Array[$h]['Event_Callback_Name'] !== '') {$scriptString .= $Script_Array[$h]['Event_Callback_Name'];}
          else {$scriptString .= buildScript($Script_Array[$h]['Control_Function'], $Module_Info, $indent);}
          $scriptString .= ', ';
          $scriptString .= ($Script_Array[$h]['Event_useCapture'] === FALSE) ? 'false' : 'true';
          $scriptString .= ');'."\n";

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

          $scriptString .= "\n";
          $scriptString .= 'switch ('.$Script_Array[$h]['Control_Expression'].')';

          break;


        case ('Switch_Cases') :

          for ($i = 0; $i < count($Script_Array[$h]['Switch_Cases']); $i++) {

            $scriptString .= "\n\n".indent($indent).'case (';

            if (count($Script_Array[$h]['Switch_Cases'][$i]['Case']) > 1) {
              
              for ($j = 0; $j < count($Script_Array[$h]['Switch_Cases'][$i]['Case']); $j++) {
                  
                if ($j > 0) {$scriptString .= ' '.$Script_Array[$h]['Switch_Cases'][$i]['Operator'].' ';}

                $scriptString .= '('.$Script_Array[$h]['Switch_Cases'][$i]['Case'][$j].')';
              }
            }

            else {

              $scriptString .= $Script_Array[$h]['Switch_Cases'][$i]['Case'][0];
            }

            $scriptString .= ') :'."\n";
              
            for ($j = 0; $j < count($Script_Array[$h]['Switch_Cases'][$i]['Statements']); $j++) {

              $scriptString .= "\n".indent($indent).'  '.$Script_Array[$h]['Switch_Cases'][$i]['Statements'][$j].';';
            }

            if ($Script_Array[$h]['Switch_Cases'][$i]['Break'] === TRUE) {$scriptString .= "\n".indent($indent).'  break;';}
          }

          if ($Script_Array[$h]['Switch_Default'] !== '') {

            $scriptString .= "\n\n".indent($indent).'default : '.$Script_Array[$h]['Switch_Default'].';';
          }

          break;

        case ('Comment') :

          switch ($Script_Array[$h]['Comment_Type']) {

            case ('sameLine') : $scriptString .= "/// ".$Script_Array[$h]['Comment'][0]; break;
            case ('singleLine') : $scriptString .= "\n\n".indent($indent)."// ".$Script_Array[$h]['Comment'][0]."\n\n"; break;
            case ('multiLine') : $scriptString .= "\n\n/*\n\n".implode("\n", $Script_Array[$h]['Comment'])."\n\n*/\n\n"; break;
          }

          break;

        default :

          $scriptString .= indent($indent).'// Ashiva Console: Unknown Javascript Control "'.$Script_Array[$h]['Control'].'" in '.txt($Module_Info['Name']).' Module by '.txt($Module_Info['Publisher']);
      }
    }

    else {

      $scriptString .= "\n".indent($indent).'// Ashiva Console: Javascript Control Missing in '.txt($Module_Info['Name']).' Module by '.txt($Module_Info['Publisher'])."\n";
    }

    if (array_key_exists('Block', $Script_Array[$h])) {

      $scriptString .= ' {'."\n\n";
      $indent++;
      $scriptString .= buildScript($Script_Array[$h]['Block'], $Module_Info, $indent);
      $indent--;
      $scriptString .= "\n".indent($indent).'}'."\n\n";
    }
  }

  return $scriptString;
}



// FUNCTION :: CREATE SCRIPT
function createScript($Script_JSON, $Module_Info) {

  function indent($indent) {

    $Indent_Space = [];

    for ($i = 0; $i < $indent; $i++) {$Indent_Space[] = '  ';}

    return implode('', $Indent_Space);
  }

  $Script_Array = json_decode($Script_JSON, TRUE);
  $scriptString = buildScript($Script_Array, $Module_Info);

  $scriptString = preg_replace("/\s*\n\s*\n(\s*\})/", "\n$1", $scriptString);
  $scriptString = preg_replace("/\n{3,}/", "\n\n", $scriptString);
  $scriptString = preg_replace("/\(\s*\n\s*\(/", "((", $scriptString);
  $scriptString = preg_replace("/\,\s*\n\s*\(/", ", (", $scriptString);
  $scriptString = preg_replace("/\}\s*\n{2}\,/", "},", $scriptString);
  $scriptString = preg_replace("/\n+\/\/\//", " ///", $scriptString);
  $scriptString = preg_replace("/\n{2}([\s]*\/\/\s{1})/", "\n\n\n\n$1", $scriptString);
  $scriptString = preg_replace("/^\s+([^\w])/", "$1", $scriptString);

  return $scriptString;
}


// FUNCTION :: GET SCRIPTS
function getScripts($Modules) {

  $Script = '';
  $Script .= '"use strict";'."\n\n\n";

  foreach ($Modules['Scripts'] as $Module_Name => $Module_Scriptsheet) {

    $Module_Publisher = $Modules['Register'][$Module_Name]['Publisher'];
    $Module_Set = str_replace('::', '°', $Module_Name);

    if ($Module_Name === 'SB_Customer') {

      $Module_Info['Name'] = $Module_Name;
      $Module_Info['Publisher'] = $Module_Publisher;

      $Module_Scriptsheet = createScript($Module_Scriptsheet, $Module_Info);
    }


    $Script .= '  //*'.str_repeat('*', (strlen($Module_Name) + strlen($Module_Publisher) + 13)).'*//'."\n";
    $Script .= ' //* '.strtoupper(txt($Module_Name)).' MODULE by '.strtoupper(txt($Module_Publisher)).' *//'."\n";
    $Script .= '//*'.str_repeat('*', (strlen($Module_Name) + strlen($Module_Publisher) + 13)).'*//'."\n\n";
    $Script .= $Module_Scriptsheet;
    $Script .=  "\n\n\n\n";
  }

  return $Script;
}

$Test_String = createScript($Test_Script_JSON);

echo '<pre>';
print_r($Test_String);
echo '</pre>';

?>
