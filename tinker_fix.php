<?php 
$db = app('db'); 
$db->statement('ALTER TABLE users ADD COLUMN IF NOT EXISTS profile_picture VARCHAR(255) NULL'); 
echo "Done!\n";