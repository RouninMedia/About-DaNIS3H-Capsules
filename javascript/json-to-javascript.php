<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// INCLUDE CORE
require_once $_SERVER['DOCUMENT_ROOT'].'/.assets/system/core/core.php';


$Test_Script_JSON = '

[
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
    "Function_Expression" : true,
    "Assigned_Name" : "responsiveDesign",
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
    "Function_Expression" : true,
    "Assigned_Name" : "customerActions",
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
                    "Function_Expression" : true,
                    "Assigned_Name" : "",
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
                    "Function_Expression" : true,
                    "Assigned_Name" : "",
                    "Parameters" : [],
                    "Block" : [

                      {
                        "Control" : "Statements",
                        "Statements" : [
                        
                          "history.forward()"
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
                    "Function_Expression" : true,
                    "Assigned_Name" : "",
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
                    "Function_Expression" : true,
                    "Assigned_Name" : "",
                    "Parameters" : [],
                    "Block" : [

                      {
                        "Control" : "Statements",
                        "Statements" : [
                        
                          "history.forward()"
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
                    "Function_Expression" : true,
                    "Assigned_Name" : "",
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
                    "Function_Expression" : true,
                    "Assigned_Name" : "",
                    "Parameters" : [],
                    "Block" : [

                      {
                        "Control" : "Statements",
                        "Statements" : [
                        
                          "history.forward()"
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
        "Function_Expression" : true,
        "Assigned_Name" : "",
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
  },

  {
    "Control" : "Switch_Case",
    "Control_Expression" : "true",
    "Cases" : [

      {

        "Case" : [

          "caseOne"
        ],

        "Block" : [

          {
            "Control" : "Statements",
            "Statements" : [
      
              "console.log(\'This is Case One\')"
            ]
          }
        ],

        "Break" : true
      },

      {
        "Case" : [

          "caseTwo"
        ],

        "Block" : [

          {
            "Control" : "Statements",
            "Statements" : [
      
              "console.log(\'This is Case Two\')"
            ]
          }
        ],

        "Break" : true
      }
    ],

    "Default" : "window.alert(\'This is the Default\')"
  },

  {
    "Control" : "Function",
    "Function_Type" : "Regular",
    "Assigned_Name" : "",
    "Declared_Name" : "myFunction1",
    "Parameters" : [],
    "Block" : [

      {
        "Control" : "Statements",
        "Statements" : [

          "console.log(\'This is myFunction1 (Named Regular Function Not Assigned to Variable)\')"
        ]
      }
    ]
  },

  {
    "Control" : "Function",
    "Function_Type" : "Regular",
    "Assigner" : "var",
    "Assigned_Name" : "myFunction2",
    "Declared_Name" : "myFunction22",
    "Parameters" : [],
    "Block" : [

      {
        "Control" : "Statements",
        "Statements" : [

          "console.log(\'This is myFunction2 (Named Regular Function Assigned to Variable)\')"
        ]
      }
    ]
  },

  {
    "Control" : "Function",
    "Function_Type" : "Regular",
    "Assigned_Name" : "",
    "Parameters" : [],
    "Block" : [

      {
        "Control" : "Statements",
        "Statements" : [

          "console.log(\'This is myFunction3 (Unnamed Regular Function Not Assigned to Variable)\')"
        ]
      }
    ]
  },

  {
    "Control" : "Function",
    "Function_Type" : "Regular",
    "Assigner" : "var",
    "Assigned_Name" : "myFunction4",
    "Parameters" : [],
    "Block" : [

      {
        "Control" : "Statements",
        "Statements" : [

          "console.log(\'This is myFunction4 (Unnamed Regular Function Assigned to Variable)\')"
        ]
      }
    ]
  },

  {
    "Control" : "Function",
    "Assigned_Name" : "",
    "Parameters" : [],
    "Block" : [

      {
        "Control" : "Statements",
        "Statements" : [

          "console.log(\'This is myFunction5 (Unnamed Arrow Function Not Assigned to Variable)\')"
        ]
      }
    ]
  },

  {
    "Control" : "Function",
    "Assigned_Name" : "myFunction6",
    "Parameters" : [],
    "Block" : [

      {
        "Control" : "Statements",
        "Statements" : [

          "console.log(\'This is myFunction6 (Unnamed Arrow Function Assigned to Variable)\')"
        ]
      }
    ]
  },

  {
    "Control" : "Function",
    "Function_Invoked" : true,
    "Assigned_Name" : "",
    "Parameters" : [],
    "Control_Function" : [

      {
        "Control" : "Function",
        "Function_Type" : "Regular",
        "Assigned_Name" : "",
        "Declared_Name" : "myFunction7",
        "Parameters" : [],
        "Block" : [

          {
            "Control" : "Statements",
            "Statements" : [
                        
              "console.log(\'This is myFunction7 (IIFE using Named Regular Function, Not Assigned to Variable)\')"
            ]
          }
        ]
      }
    ]
  },

  {
    "Control" : "Function",
    "Function_Invoked" : true,
    "Assigner" : "var",
    "Assigned_Name" : "myFunction8",
    "Parameters" : [],
    "Control_Function" : [

      {
        "Control" : "Function",
        "Function_Type" : "Regular",
        "Assigned_Name" : "",
        "Declared_Name" : "myFunction8",
        "Parameters" : [],
        "Block" : [

          {
            "Control" : "Statements",
            "Statements" : [
                        
              "console.log(\'This is myFunction8 (IIFE using Named Regular Function, Assigned to Variable)\')"
            ]
          }
        ]
      }
    ]
  },

  {
    "Control" : "Function",
    "Function_Invoked" : true,
    "Assigned_Name" : "",
    "Parameters" : [],
    "Control_Function" : [

      {
        "Control" : "Function",
        "Function_Type" : "Regular",
        "Assigned_Name" : "",
        "Parameters" : [],
        "Block" : [

          {
            "Control" : "Statements",
            "Statements" : [
                        
              "console.log(\'This is myFunction9 (IIFE using Unnamed Regular Function, Not Assigned to Variable)\')"
            ]
          }
        ]
      }
    ]
  },

  {
    "Control" : "Function",
    "Function_Invoked" : true,
    "Assigner" : "var",
    "Assigned_Name" : "myFunction10",
    "Parameters" : [],
    "Control_Function" : [

      {
        "Control" : "Function",
        "Function_Type" : "Regular",
        "Assigned_Name" : "",
        "Parameters" : [],
        "Block" : [

          {
            "Control" : "Statements",
            "Statements" : [
                        
              "console.log(\'This is myFunction10 (IIFE using Unnamed Regular Function, Assigned to Variable)\')"
            ]
          }
        ]
      }
    ]
  },

  {
    "Control" : "Function",
    "Function_Invoked" : true,
    "Assigned_Name" : "",
    "Parameters" : [],
    "Control_Function" : [

      {
        "Control" : "Function",
        "Assigned_Name" : "",
        "Parameters" : [],
        "Block" : [

          {
            "Control" : "Statements",
            "Statements" : [
                        
              "console.log(\'This is myFunction11 (IIFE using Unnamed Arrow Function, Not Assigned to Variable)\')"
            ]
          }
        ]
      }
    ]
  },

  {
    "Control" : "Function",
    "Function_Invoked" : true,
    "Assigned_Name" : "myFunction12",
    "Parameters" : [],
    "Control_Function" : [

      {
        "Control" : "Function",
        "Assigned_Name" : "",
        "Parameters" : [],
        "Block" : [

          {
            "Control" : "Statements",
            "Statements" : [

              "console.log(\'This is myFunction12 (IIFE using Unnamed Arrow Function, Assigned to Variable)\')"
            ]
          }
        ]
      }
    ]
  }
]';


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

    if (array_key_exists('Control', $Script_Array[$h])) {

      switch ($Script_Array[$h]['Control']) {

        case ('Function') :

          $scriptString .= "\n".indent($indent);

          $Assigner = (array_key_exists('Assigner', $Script_Array[$h])) ? $Script_Array[$h]['Assigner'] : 'const';

          if ((array_key_exists('Function_Invoked', $Script_Array[$h]) && ($Script_Array[$h]['Function_Invoked'] === TRUE))) {
          
            switch (TRUE) {
          
              case ((array_key_exists('Function_Type', $Script_Array[$h]) && ($Script_Array[$h]['Function_Type'] === 'Regular')) && (array_key_exists('Declared_Name', $Script_Array[$h]))) :
          
                if ($Script_Array[$h]['Assigned_Name'] !== '') {$scriptString .= $Assigner.' '.$Script_Array[$h]['Assigned_Name'].' = ';}
                $scriptString .= '('.buildScript($Script_Array[$h]['Control_Function'], $Module_Info, $indent).')();'."\n";
                break;
          
              case ((array_key_exists('Function_Type', $Script_Array[$h]) && ($Script_Array[$h]['Function_Type'] === 'Regular'))) :
                
                if ($Script_Array[$h]['Assigned_Name'] !== '') {$scriptString .= $Assigner.' '.$Script_Array[$h]['Assigned_Name'].' = ';}
                $scriptString .= '('.buildScript($Script_Array[$h]['Control_Function'], $Module_Info, $indent).')();'."\n";
                break;
          
              default :
          
                if ($Script_Array[$h]['Assigned_Name'] !== '') {$scriptString .= $Assigner.' '.$Script_Array[$h]['Assigned_Name'].' = ';}
                $scriptString .= '('.buildScript($Script_Array[$h]['Control_Function'], $Module_Info, $indent).')();'."\n";
                break;
            }
          }
          
          else {
          
            switch (TRUE) {

              case ((array_key_exists('Function_Type', $Script_Array[$h]) && ($Script_Array[$h]['Function_Type'] === 'Regular')) && (array_key_exists('Declared_Name', $Script_Array[$h]))) :
                
                if ($Script_Array[$h]['Assigned_Name'] !== '') {

                  $Termination_Map[$indent] = TRUE;
                  $scriptString .= $Assigner.' '.$Script_Array[$h]['Assigned_Name'].' = ';
                }

                $scriptString .= 'function '.$Script_Array[$h]['Declared_Name'].' ('.implode(', ', $Script_Array[$h]['Parameters']).')';
                break;
          
              case (array_key_exists('Function_Type', $Script_Array[$h]) && ($Script_Array[$h]['Function_Type'] === 'Regular')) :
          
                if ($Script_Array[$h]['Assigned_Name'] !== '') {

                  $Termination_Map[$indent] = TRUE;
                  $scriptString .= $Assigner.' '.$Script_Array[$h]['Assigned_Name'].' = ';
                }

                $scriptString .= 'function ('.implode(', ', $Script_Array[$h]['Parameters']).')';
                break;
          
              default :
          
                if ($Script_Array[$h]['Assigned_Name'] !== '') {

                  $Termination_Map[$indent] = TRUE;
                  $scriptString .= $Assigner.' '.$Script_Array[$h]['Assigned_Name'].' = ';
                }

                $scriptString .= '('.implode(', ', $Script_Array[$h]['Parameters']).') =>';
            }
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

          $scriptString .= "\n".indent($indent).'switch ('.$Script_Array[$h]['Control_Expression'].')';

          if (array_key_exists('Cases', $Script_Array[$h])) {

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

            if ((array_key_exists('Default', $Script_Array[$h])) && ($Script_Array[$h]['Default'] !== '')) {

              $scriptString .= "\n\n".indent($indent).'default :'."\n\n";
              $indent++;
              $scriptString .= indent($indent).$Script_Array[$h]['Default'].';';
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

    if (array_key_exists('Block', $Script_Array[$h])) {

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
  }

  return $scriptString;
}


// FUNCTION :: CREATE SCRIPT
function createScript($Script_JSON, $Module_Info, $Code_Panel = FALSE) {

  $Script_Array = json_decode($Script_JSON, TRUE);
  $scriptString = buildScript($Script_Array, $Module_Info);

  $scriptString = preg_replace("/\;\s*\,/", ",", $scriptString);
  $scriptString = preg_replace("/\s*\n\s*\n(\s*\})/", "\n$1", $scriptString);
  $scriptString = preg_replace("/\n{3,}/", "\n\n", $scriptString);
  $scriptString = preg_replace("/\(\s*\n\s*\(/", "((", $scriptString);
  $scriptString = preg_replace("/\,\s*\n\s*\(/", ", (", $scriptString);
  $scriptString = preg_replace("/\}\s*\n{2}\,/", "},", $scriptString);
  $scriptString = preg_replace("/\n+\/\/\//", " ///", $scriptString);
  $scriptString = preg_replace("/\n{2}([\s]*\/\/\s{1})/", "\n\n\n\n$1", $scriptString);
  $scriptString = preg_replace("/^\s+([^\w])/", "$1", $scriptString);
  $scriptString = preg_replace("/\(\nfunction/", "(function", $scriptString);
  $scriptString = preg_replace("/\}\n\n\)/", "})", $scriptString);

  if ($Code_Panel !== TRUE) {
  
    $scriptString = preg_replace("/new[\s|_]Worker\(\'([^\']+)\'\,?\s?([^\)]*)\)/", "new_Worker($2{workerName: '$1', ashivaModule: '".url($Module_Info['Name'])."', ashivaPublisher: '".url($Module_Info['Publisher'])."'})", $scriptString);
    $scriptString = preg_replace("/new_Worker\(\{([^\}]+)\}\{([^\}]+)\}\)/", "new_Worker({\$2, \$1})", $scriptString);
  }

  return $scriptString;
}


// FUNCTION :: GET SCRIPTS
function getScripts($Modules) {

  $Script = '';
  $Script .= '"use strict";'."\n\n\n";

  foreach ($Modules['Scripts'] as $Module_Name => $Module_Scriptsheet) {

    $Module_Publisher = $Modules['Register'][$Module_Name]['Publisher'];
    $Module_Set = str_replace('::', '°', $Module_Name);
    
    if (in_array($Module_Name, ['SB_Consoles', 'SB_Customer', 'SB_Translations::EN'])) {

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

$Module_Info = ['Name' => 'moduleName', 'Publisher' => 'modulePublisher'];

$Test_String = createScript($Test_Script_JSON, $Module_Info);

echo '<pre>';
print_r($Test_String);
echo '</pre>';

?>
