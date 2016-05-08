<?php

header($_SERVER['SERVER_PROTOCOL'] . ' 503 Service Unavailable');
header('Retry-After: 300'); // refresh page after 5 minutes
exit('Tato stránka je v rekonstrukci.');