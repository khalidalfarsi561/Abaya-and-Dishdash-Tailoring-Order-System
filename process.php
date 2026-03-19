<?php
// Abaya and Dishdash Tailoring Order System (CSWD2101)
// Supervisor: Vinesh Jain

// VAT rate MUST be defined as a Constant using define (not a variable)
define("VAT_RATE", 0.05);

// -------------------------
// Strict Pricing Tables
// -------------------------
$clothPrices = [
  "Cotton" => 10,
  "Linen" => 15,
  "Silk" => 20,
  "Premium Fabric" => 25
];

$sizeCosts = [
  "Small" => 2,
  "Medium" => 3,
  "Large" => 5,
  "Extra Large" => 7
];

$tailorCosts = [
  "Ahmed" => 12,
  "Salim" => 15,
  "Khalid" => 18
];

$extraServiceCosts = [
  "Embroidery" => 5,
  "Express Delivery" => 3
];

// -------------------------
// Helpers
// -------------------------
function stop_with_error($message)
{
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Order Error</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <div class="container">
      <div class="header">
        <h1>Abaya and Dishdash Tailoring Order System</h1>
        <p>Order Validation Error</p>
      </div>

      <div class="card">
        <div class="alert error"><?php echo htmlspecialchars($message); ?></div>
        <a class="back-link" href="index.php">Back to Order Form</a>
      </div>
    </div>
  </body>
  </html>
  <?php
  exit;
}

function format_omr($amount)
{
  // Always show 2 decimal places for currency clarity
  return number_format((float)$amount, 2, ".", "");
}

// Only accept POST submissions
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  stop_with_error("Invalid request method.");
}

// -------------------------
// Read inputs
// -------------------------
$customerName = isset($_POST["customer_name"]) ? trim($_POST["customer_name"]) : "";
$garment = isset($_POST["garment"]) ? trim($_POST["garment"]) : "";
$cloth = isset($_POST["cloth"]) ? trim($_POST["cloth"]) : "";
$size = isset($_POST["size"]) ? trim($_POST["size"]) : "";
$extras = isset($_POST["extras"]) && is_array($_POST["extras"]) ? $_POST["extras"] : [];
$tailor = isset($_POST["tailor"]) ? trim($_POST["tailor"]) : "";

// -------------------------
// Validations (STRICT)
// -------------------------

// 1) Customer Name must not be empty
if ($customerName === "") {
  stop_with_error("Customer Name is required.");
}

// 2) Name format: English letters (A-Z, a-z) and whitespace only
// preg_match checks whether the input matches the REGEX pattern.
// Pattern explanation: ^[A-Za-z ]+$ means:
//  ^ start of string
//  [A-Za-z ] allowed characters: English letters and spaces
//  + one or more characters
//  $ end of string
if (!preg_match("/^[A-Za-z ]+$/", $customerName)) {
  stop_with_error("Customer Name must contain English letters and spaces only.");
}

// 3) Garment Type must be Abaya or Dishdash (radio)
if ($garment !== "Abaya" && $garment !== "Dishdash") {
  stop_with_error("Garment Type must be Abaya or Dishdash.");
}

// 4) Cloth Type must not be default (must exist in pricing table)
if ($cloth === "" || !array_key_exists($cloth, $clothPrices)) {
  stop_with_error("Please select a valid Cloth Type.");
}

// 5) Size must not be default (must exist in size table)
if ($size === "" || !array_key_exists($size, $sizeCosts)) {
  stop_with_error("Please select a valid Size.");
}

// 6) Extras: must select at least one checkbox
if (count($extras) < 1) {
  stop_with_error("Please select at least one Extra Service.");
}

// Also ensure all selected extras are valid (from the given strict table)
foreach ($extras as $ex) {
  if (!array_key_exists($ex, $extraServiceCosts)) {
    stop_with_error("Invalid Extra Service selected.");
  }
}

// 7) Tailor is optional
// If not selected -> Assigned by Shop, cost 0
$tailorDisplay = "Assigned by Shop";
$tailorCost = 0;

if ($tailor !== "") {
  if (!array_key_exists($tailor, $tailorCosts)) {
    stop_with_error("Invalid Tailor selected.");
  }
  $tailorDisplay = $tailor;
  $tailorCost = $tailorCosts[$tailor];
}

// -------------------------
// Calculations
// -------------------------
$clothPrice = $clothPrices[$cloth];
$sizeCost = $sizeCosts[$size];

$extrasCost = 0;
foreach ($extras as $ex) {
  $extrasCost += $extraServiceCosts[$ex];
}

$subtotal = $clothPrice + $sizeCost + $tailorCost + $extrasCost;

// VAT = subtotal * 5% (VAT_RATE constant)
$vatValue = $subtotal * VAT_RATE;

$totalPrice = $subtotal + $vatValue;

// Extras display (comma separated)
$extrasDisplay = implode(", ", $extras);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Order Summary</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Abaya and Dishdash Tailoring Order System</h1>
      <p>Order Summary</p>
    </div>

    <div class="card">
      <div class="alert success">Order processed successfully.</div>

      <div class="summary">
        <pre><?php
// IMPORTANT: Output format must match the required template exactly.
echo "Customer Name: " . htmlspecialchars($customerName) . "\n";
echo "Garment: " . htmlspecialchars($garment) . "\n";
echo "Cloth: " . htmlspecialchars($cloth) . "\n";
echo "Size: " . htmlspecialchars($size) . "\n";
echo "Extras: " . htmlspecialchars($extrasDisplay) . "\n";
echo "Tailor: " . htmlspecialchars($tailorDisplay) . "\n\n";

echo "Cloth Price: OMR " . format_omr($clothPrice) . "\n";
echo "Size Cost: OMR " . format_omr($sizeCost) . "\n";
echo "Tailor Cost: OMR " . format_omr($tailorCost) . "\n";
echo "Extras Cost: OMR " . format_omr($extrasCost) . "\n";
echo "Subtotal: OMR " . format_omr($subtotal) . "\n";
echo "VAT (5%): OMR " . format_omr($vatValue) . "\n";
echo "Total Price: OMR " . format_omr($totalPrice);
        ?></pre>
      </div>

      <a class="back-link" href="index.php">Back to Order Form</a>
    </div>
  </div>
</body>
</html>
