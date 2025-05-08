<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bh";
$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


function truncateTables($conn, $excludedTables, $dbname)
{
    $tableQuery = "SHOW TABLES FROM `$dbname`";
    $tableResult = $conn->query($tableQuery);
    if ($tableResult->num_rows > 0) {
        while ($tableRow = $tableResult->fetch_array()) {
            $tableName = $tableRow[0];
            if (!in_array($tableName, $excludedTables)) {
                $truncateQuery = "TRUNCATE TABLE `$tableName`";
                if (!$conn->query($truncateQuery)) {
                    echo "<p>Error truncating $tableName: " . $conn->error . "</p>";
                }
            }
        }
        echo "<p>All tables except the specified ones have been cleared.</p>";
    } else {
        echo "<p>No tables found in the database.</p>";
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['truncate_tables'])) {
    echo "Button clicked, calling truncateTables function.";
    truncateTables($conn, ['admin'], $dbname);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['table']) && isset($_POST['primary_key']) && isset($_POST['primary_key_value'])) {
    $table = $_POST['table'];
    $primaryKey = $_POST['primary_key'];
    $primaryKeyValue = $_POST['primary_key_value'];
    $updateQuery = "UPDATE `$table` SET ";
    $updateData = [];
    foreach ($_POST['fields'] as $field => $value) {
        if (empty($value)) {
            $updateData[] = "`$field`=NULL";
        } else {
            $updateData[] = "`$field`='" . $conn->real_escape_string($value) . "'";
        }
    }
    $updateQuery .= implode(", ", $updateData) . " WHERE `$primaryKey` = '" . $conn->real_escape_string($primaryKeyValue) . "'";
    if ($conn->query($updateQuery) === TRUE) {
        echo "Record updated successfully!";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}


echo "<form method='POST' action=''>";
echo "<button type='submit' name='truncate_tables'>Clear All Tables Except Admin</button>";
echo "</form>";
$tableQuery = "SHOW TABLES FROM `$dbname`";
$tableResult = $conn->query($tableQuery);

if ($tableResult->num_rows > 0) {
    while ($tableRow = $tableResult->fetch_array()) {
        $tableName = $tableRow[0];
        echo "<h3>Table: $tableName</h3>";
        $primaryKeyQuery = "SHOW KEYS FROM `$tableName` WHERE Key_name = 'PRIMARY'";
        $primaryKeyResult = $conn->query($primaryKeyQuery);
        $primaryKeyRow = $primaryKeyResult->fetch_assoc();
        $primaryKey = $primaryKeyRow['Column_name'] ?? 'id';
        $dataQuery = "SELECT * FROM `$tableName`";
        $dataResult = $conn->query($dataQuery);
        if ($dataResult->num_rows > 0) {
            echo "<form method='POST'><table border='1' cellpadding='5' cellspacing='0'><tr>";
            $fields = $dataResult->fetch_fields();
            foreach ($fields as $field) {
                echo "<th>{$field->name}</th>";
            }
            echo "<th>Actions</th></tr>";
            while ($dataRow = $dataResult->fetch_assoc()) {
                echo "<tr>";
                echo "<form method='POST' action=''>";
                foreach ($dataRow as $fieldName => $value) {
                    $displayValue = is_null($value) ? '' : htmlspecialchars($value);
                    echo "<td><input type='text' name='fields[$fieldName]' value='$displayValue'></td>";
                }
                echo "<input type='hidden' name='table' value='$tableName'>";
                echo "<input type='hidden' name='primary_key' value='$primaryKey'>";
                echo "<input type='hidden' name='primary_key_value' value='{$dataRow[$primaryKey]}'>";
                echo "<td><button type='submit'>Update</button></td>";
                echo "</form>";
                echo "</tr>";
            }
            echo "</table><br>";
        } else {
            echo "<p>No data found in the table.</p>";
        }
    }
} else {
    echo "No tables found in the database.";
}
$conn->close();
