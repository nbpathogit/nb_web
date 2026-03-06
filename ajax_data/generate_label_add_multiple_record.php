<?php

/*
 * Backend API to add multiple SN numbers to the print label list at once
 * This is more efficient than sending individual requests for each SN number
 */

require "../includes/init.php";
$conn = require "../includes/db.php";

header("Content-Type: application/json");

// Read raw input from request body
$rawInput = file_get_contents("php://input");

try {
    // Check if the request data is JSON
    $jsonData = $rawInput;
    $data = json_decode($jsonData, true);

    // If JSON decode fails, try regular POST
    if (json_last_error() !== JSON_ERROR_NONE) {
        $data = $_POST;

        // If records are sent as JSON string in POST data
        if (isset($_POST["records"])) {
            $decodedRecords = json_decode($_POST["records"], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception(
                    "Invalid JSON data in records parameter: " .
                        json_last_error_msg(),
                );
            }
            // Wrap the decoded records in the expected format
            $data = ["records" => $decodedRecords];
        }
    }

    // Validate that we have an array of records
    if (!isset($data["records"]) || !is_array($data["records"])) {
        throw new Exception("No records array provided");
    }

    $records = $data["records"];

    if (empty($records)) {
        throw new Exception("Records array is empty");
    }

    $results = [];
    $successCount = 0;
    $failCount = 0;

    // Process each record
    foreach ($records as $index => $record) {
        try {
            // Validate required fields for this record
            // Note: patient_id is not used by createByLoopNum, so we don't need to validate it
            if (
                !isset($record["userid"]) ||
                !isset($record["sn_num"]) ||
                !isset($record["hn_num"]) ||
                !isset($record["patho_abbrev"]) ||
                !isset($record["accept_date"]) ||
                !isset($record["company_name"]) ||
                !isset($record["letter"]) ||
                !isset($record["start_num"]) ||
                !isset($record["end_num"])
            ) {
                throw new Exception(
                    "Missing required fields in record " . ($index + 1),
                );
            }

            // Call the LabelPrint method to create the record
            // This method handles the loop to generate multiple labels based on letter, start_num, and end_num
            $result = LabelPrint::createByLoopNum(
                $conn,
                $record["userid"],
                $record["sn_num"],
                $record["hn_num"],
                $record["patho_abbrev"],
                $record["accept_date"],
                $record["company_name"],
                $record["letter"],
                $record["start_num"],
                $record["end_num"],
            );

            $results[] = [
                "index" => $index,
                "sn_num" => $record["sn_num"],
                "success" => true,
                "result" => $result,
            ];

            $successCount++;
        } catch (Exception $e) {
            $results[] = [
                "index" => $index,
                "sn_num" => $record["sn_num"] ?? "unknown",
                "success" => false,
                "error" => $e->getMessage(),
            ];

            $failCount++;
        }
    }

    // Return success response with details
    echo json_encode([
        "success" => true,
        "message" =>
            "Processed " .
            count($records) .
            " records. Success: $successCount, Failed: $failCount",
        "total_records" => count($records),
        "success_count" => $successCount,
        "fail_count" => $failCount,
        "results" => $results,
    ]);
} catch (Exception $e) {
    // Return error response
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage(),
        "total_records" => 0,
        "success_count" => 0,
        "fail_count" => 0,
        "results" => [],
    ]);
}
