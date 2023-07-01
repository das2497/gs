<?php

// $uniqueId = uniqid();
// $numericPart = hexdec(substr($uniqueId, 0, 8)); // Convert the hexadecimal part to decimal
// $normalizedNumber = $numericPart % 20001; // Normalize the number within 00000-20000 range
// printf("%05d", $normalizedNumber); // Pad the number with leading zeros if necessary


require_once "config/session.php";
require_once "config/database.php";

$sql = "SELECT * , (SUM(quantity) - SUM(qty)) AS 'avlqty' FROM `sales` 
LEFT JOIN `stocks` ON sales.stock_id = stocks.s_id 
LEFT JOIN `products` ON `stocks`.`pro_id` = `products`.`pro_id` 
GROUP BY stocks.s_id;";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        ($row["s_alert"] > $row["avlqty"]) ? ($alert_styl = " color: white;background-color: red;") : ($alert_styl = "");
        echo "<tr><td style='$alert_styl'>" . $row["s_id"] . "</td>
	<td style='$alert_styl'>" . $row["pro_name"] . "</td>
    <td style='$alert_styl'>" . $row["batch_no"] . "</td>
    <td style='$alert_styl'>" . $row["quantity"] . "</td>
    <td style='$alert_styl'>" . $row["avlqty"] . "</td>
    <td style='$alert_styl'>" . $row["s_alert"] . "</td></tr>";
    }
    echo "</tbody></table></div></div></div></div>";
} else {
    echo "</tbody></table></div></div></div></div>";
}

echo "SELECT * , (SUM(quantity) - SUM(qty)) AS 'avlqty' FROM `sales` 
LEFT JOIN `stocks` ON sales.stock_id = stocks.s_id 
LEFT JOIN `products` ON `stocks`.`pro_id` = `products`.`pro_id` 
GROUP BY stocks.s_id;";
