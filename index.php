<?php

// Function to store the entry in a CSV file
function storeEntry($entry)
{
    $timestamp = date('Y-m-d H:i:s');
    $entry = str_replace(',', ';', $entry); // Replace commas with semicolons to avoid CSV formatting issues

    $data = array($timestamp, $entry);
    $file = fopen('entries.csv', 'a'); // Open the CSV file in append mode
    fputcsv($file, $data); // Write the entry to the file
    fclose($file); // Close the file
}

// Function to display entries on spaced repetition intervals
function displayEntries($date)
{
    $file = fopen('entries.csv', 'r'); // Open the CSV file in read mode

    while (($data = fgetcsv($file)) !== false) {
        $timestamp = $data[0];
        $entry = $data[1];

        // Calculate the time difference in days
        $currentDate = $date ?? date('Y-m-d'); // Use the provided date or the current date if not provided
        $daysDiff = floor((strtotime($currentDate) - strtotime($timestamp)) / (60 * 60 * 24));

        // Check if the entry matches the spaced repetition intervals
        if (in_array($daysDiff, [1, 3, 7, 14, 30, 90, 365])) {
            echo "Entry from $timestamp:\n$entry\n\n";
        }
    }

    fclose($file); // Close the file
}

// Check the command-line arguments
if ($argc < 2) {
    echo "Usage: php spaced_repetition.php [command] [date]\n";
    echo "Available commands: store, display\n";
    exit;
}

// Perform the specified command
$command = $argv[1];
$date = isset($argv[2]) ? $argv[2] : null;

if ($command === 'store') {
    // Prompt the user to enter an entry
    echo "Enter your entry: ";
    $entry = trim(fgets(STDIN));

    // Store the entry
    storeEntry($entry);
    echo "Entry stored successfully.\n";
} elseif ($command === 'display') {
    // Display the entries
    displayEntries($date);
} else {
    echo "Invalid command. Available commands: store, display\n";
}
