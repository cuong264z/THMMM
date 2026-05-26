<?php

echo "Session save handler: ";
echo session_module_name();

echo "<br>";

echo "Session save path: ";
echo ini_get('session.save_path');