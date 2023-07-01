<?php

require_once "../config/database.php";
$sql = "SELECT * , (SUM(quantity)-SUM(qty)) AS 'avlqty' FROM `sales` 
LEFT JOIN `stocks` ON sales.stock_id = stocks.s_id 
LEFT JOIN `products` ON `stocks`.`pro_id` = `products`.`pro_id` 
GROUP BY stocks.s_id;";
$result = $conn->query($sql);
$alartt = "";
$alart_count = 0;
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    if (!($row["s_alert"] > $row["avlqty"])) {
      continue;
    }
    $alartt .= '<li><hr class="dropdown-divider"></li><li class="notification-item"><i class="bi bi-exclamation-circle text-warning"></i><div><h4> ' . $row["pro_name"] . '  - ' . $row["batch_no"] . ' -  Available : (' . $row["avlqty"] . ')  </h4><p> ' . $row["s_alert"] . '</p></div></li>';
    $alart_count .= $alart_count + 1;
  }
}

?>
<li class="nav-item dropdown">
  <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="bi bi-bell">

      <?php
      if ($alart_count > 0) {
      ?>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light rounded-circle" style="font-size: 10px;">
          <?php

          $value =  strval($alart_count);

          if ($value[0] === '0') {
            $hiddenValue = substr($value, 1); // Remove the first digit
          } else {
            $hiddenValue = $value; // Keep the original value
          }

          echo $hiddenValue;

          ?>
        </span>
      <?php
      }

      ?>
    </i>
  </a>
  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
    <li class="dropdown-header">
      <a href="Stocks_Alert.php"><span class="badge rounded-pill bg-primary p-2 ms-2"><?php echo $alartt; ?></span></a>
    </li>
  </ul>
</li>