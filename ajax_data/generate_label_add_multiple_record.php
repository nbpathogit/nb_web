<?php

/*
 * Backend API to add multiple SN numbers to the print label list at once
 * OPTIMIZED VERSION - Uses single bulk INSERT instead of nested loops
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

    // Prepare arrays for bulk insert
    $insertRows = [];
    $params = [];
    $totalLabels = 0;
    $successCount = 0;
    $failCount = 0;

    // Collect all label data first before bulk insertion
    foreach ($records as $record) {
        // Validate required fields
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
            throw new Exception("Missing required fields in record");
        }

        $userid = $record["userid"];
        $sn_num = $record["sn_num"];
        $hn_num = $record["hn_num"];
        $patho_abbrev = $record["patho_abbrev"];
        $accept_date = $record["accept_date"];
        $company_name = $record["company_name"];
        $letter = $record["letter"];
        $start_num = intval($record["start_num"]);
        $end_num = intval($record["end_num"]);

        // Loop through the range to collect all labels
        for ($i = $start_num; $i <= $end_num; $i++) {
            $speciment_abbrev = $letter . $i;
            $insertRows[] = "(?, ?, ?, ?, ?, ?, ?)";
            $params[] = $userid;
            $params[] = $sn_num;
            $params[] = $hn_num;
            $params[] = $patho_abbrev;
            $params[] = $speciment_abbrev;
            $params[] = $accept_date;
            $params[] = $company_name;
        }

        $totalLabels += $end_num - $start_num + 1;
    }

    // Execute single bulk INSERT query with transaction
    try {
        $conn->beginTransaction();

        $sql =
            "INSERT INTO `labelprint_tmp_a`(`userid`, `sn_num`, `hn_num`, `patho_abbreviation`, `speciment_abbreviation`, `accept_date`, `company_name`) " .
            "VALUES " .
            implode(", ", $insertRows);

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        $conn->commit();
        $successCount = $totalLabels;
    } catch (Exception $e) {
        $conn->rollBack();
        $failCount = $totalLabels;
        throw $e;
    }

    // Return success response with details
    echo json_encode([
        "success" => true,
        "message" =>
            "Processed " .
            count($records) .
            " record(s) generating $totalLabels label(s). Success: $successCount, Failed: $failCount",
        "total_records" => count($records),
        "total_labels" => $totalLabels,
        "success_count" => $successCount,
        "fail_count" => $failCount,
        "results" => [
            "labels_generated" => $totalLabels,
            "sql_queries" => 1, // Now only 1 INSERT query!
            "optimization" => "Bulk insert with single query",
        ],
    ]);
} catch (Exception $e) {
    // Return error response
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage(),
        "total_records" => 0,
        "total_labels" => 0,
        "success_count" => 0,
        "fail_count" => 0,
        "results" => [],
    ]);
}
