<?php
define("DMF_MajorVersion", 3.0);
define("DMF_MainSpecVer" , 1  );
define("DMF_APISpecVer"  , 1  );
$FmtPV['$DMFVersion'] = 
    sprintf("\"dmf-%.2F dmf-spec-%u-%u\"",
        DMF_MajorVersion,
        DMF_MainSpecVer,
        DMF_APISpecVer);