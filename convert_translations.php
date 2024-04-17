<?php

$directory = './'; // Change this to the path of your PHP files
$outputFile = 'lang/en/messages2.php'; // The file where the array will be saved

// This function scans directories recursively and processes each PHP file
function scanAndProcessDirectory($dir, &$messages)
{
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

    foreach ($iterator as $file) {
        if ($file->isDir()) {
            continue;
        }

        // Check if the file is a PHP or STUB file
        $fileExtension = $file->getExtension();
        if ($fileExtension === 'php' || $fileExtension === 'stub') {
            processFile($file->getPathname(), $messages);
        }
    }
}

// This function processes each PHP file to find and convert __() strings
function processFile($filePath, &$messages)
{
    $content = file_get_contents($filePath);
    // Updated regex to exclude __() calls that contain 'Str::' after the opening __('
    preg_match_all("/__\(\s*['\"](?!.*Str::)(.*?)['\"]\s*\)/", $content, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
        $originalString = $match[1]; // The original string inside __()
        // Check if the string contains '{{' and '}}', indicating a variable
        if (strpos($originalString, '{{') !== false && strpos($originalString, '}}') !== false) {
            continue; // Skip processing this match
        }
        $key = generateTransKey($originalString); // Generate a key for the trans() function
        $messages['plugins'][$key] = $originalString;

        // Prepare the replacement string
        $replacement = "trans('filament-plugins::messages.plugins.$key')";
        // Use preg_replace to replace only the first occurrence
        $pattern = '/' . preg_quote($match[0], '/') . '/';
        $content = preg_replace($pattern, $replacement, $content, 1);
    }

    // Write the modified content back to the file
    file_put_contents($filePath, $content);
}

function generateTransKey($string)
{
    // Handle the case where the string contains variable placeholders
    if (strpos($string, '{{') !== false && strpos($string, '}}') !== false) {
        return $string; // Return the string as is, assuming it's a variable
    }

    // Convert the string to a slug-like format
    $key = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $string));

    // Trim trailing underscores
    $key = rtrim($key, '_');

    return $key;
}

// This function saves the messages array to a PHP file
function saveMessagesToFile($messages, $filePath)
{
    $export = var_export($messages, true);
    $content = "<?php\n\nreturn $export;\n";
    file_put_contents($filePath, $content);
}


// Main logic
$messages = [];
scanAndProcessDirectory($directory, $messages);
saveMessagesToFile($messages, $outputFile);

echo "Conversion completed. Check the $outputFile file.";
