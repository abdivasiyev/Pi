<?php

return [
    '/' => 'home/index', # Required route
    '/home/:code' => 'home/error', # Returning error response. This route required.
];