<?php
/**
 * Fill these out when you want to use the examples and rename or copy it to apikey.php
 */

// Specify the later introduced bearer token
define('TMDB_BEARER_TOKEN', 'eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJmYTIwMGQ3NjQ2NThlM2E2OTA0OTIyZTNmYzhhMDEzOCIsInN1YiI6IjY0MzZiZGM3YWVkZTU5MDA3N2Y5YWMwOSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.AFI2EmKPReLOdm5sHbvGqc_KvcuQaJKXwGb_4bDsHdo');

// Or specify the which I guess can now be considered "legacy api key".
define('TMDB_API_KEY', 'fa200d764658e3a6904922e3fc8a0138');

// Globals
define('TMDB_LANGUAGE', 'vi-VN');
define('TMDB_REGION', 'vn');

// Session based
define('TMDB_REQUEST_TOKEN', 'TMDB_REQUEST_TOKEN'); // for accounts
define('TMDB_SESSION_TOKEN', 'TMDB_SESSION_TOKEN'); // for accounts
define('TMDB_GUEST_SESSION_TOKEN', 'TMDB_GUEST_SESSION_TOKEN'); // for guest sessions
define('TMDB_ACCOUNT_ID', 'TMDB_ACCOUNT_ID'); // numeric id, fetch through account info

// Account based
define('TMDB_LIST_ID', 'TMDB_LIST_ID');