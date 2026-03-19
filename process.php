<?php
// Abaya and Dishdash Tailoring Order System (CSWD2101)
// supervisor: Vinesh Jain

// لازم تكون الضريبة ثابت (Constant) باستخدام define (مو متغير)
define("VAT_RATE", 0.05);

// جداول الأسعار (لا أغيرها)
$clothPrices = array(
  "Cotton" => 10,
  "Linen" => 15,
  "Silk" => 20,
  "Premium Fabric" => 25
);

$sizeCosts = array(
  "Small" => 2,
  "Medium" => 3,
  "Large" => 5,
  "Extra Large" => 7
);

$tailorCosts = array(
  "Ahmed" => 12,
  "Salim" => 15,
  "Khalid" => 18
);

$extraServiceCosts = array(
  "Embroidery" => 5,
  "Express Delivery" => 3
);

// لازم يكون الطلب POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
  echo "<p style='color:red'>Invalid request.</p>";
  exit();
}

/* أقرأ البيانات من الفورم
   استخدمت if عادي بدل ? : */
$customerName = "";
if (isset($_POST["customer_name"])) {
  $customerName = trim($_POST["customer_name"]);
}

$garment = "";
if (isset($_POST["garment"])) {
  $garment = trim($_POST["garment"]);
}

$cloth = "";
if (isset($_POST["cloth"])) {
  $cloth = trim($_POST["cloth"]);
}

$size = "";
if (isset($_POST["size"])) {
  $size = trim($_POST["size"]);
}

$tailor = "";
if (isset($_POST["tailor"])) {
  $tailor = trim($_POST["tailor"]);
}

$extras = array();
if (isset($_POST["extras"])) {
  $extras = $_POST["extras"];
}

/* -------------------------
   VALIDATION (الشروط)
-------------------------- */

// Customer Name لازم ما يكون فاضي
if ($customerName == "") {
  echo "<p style='color:red'>Customer Name is required.</p>";
  die();
}

/* preg_match:
   يفحص إذا الاسم يطابق نمط (Regex)
   هنا فقط حروف انجليزية ومسافات */
if (!preg_match("/^[A-Za-z ]+$/", $customerName)) {
  echo "<p style='color:red'>Customer Name must contain English letters and spaces only.</p>";
  die();
}

// Garment لازم Abaya او Dishdash
if ($garment != "Abaya" && $garment != "Dishdash") {
  echo "<p style='color:red'>Garment Type must be Abaya or Dishdash.</p>";
  die();
}

// Cloth لازم يختار شيء صحيح
if ($cloth == "" || !array_key_exists($cloth, $clothPrices)) {
  echo "<p style='color:red'>Please select a valid Cloth Type.</p>";
  die();
}

// Size لازم يختار شيء صحيح
if ($size == "" || !array_key_exists($size, $sizeCosts)) {
  echo "<p style='color:red'>Please select a valid Size.</p>";
  die();
}

// Extras لازم خدمة واحدة على الأقل
if (!is_array($extras) || count($extras) < 1) {
  echo "<p style='color:red'>Please select at least one Extra Service.</p>";
  die();
}

// أتأكد الخدمات المختارة موجودة بالجدول
for ($i = 0; $i < count($extras); $i++) {
  if (!array_key_exists($extras[$i], $extraServiceCosts)) {
    echo "<p style='color:red'>Invalid Extra Service selected.</p>";
    die();
  }
}

// Tailor اختياري
$tailorDisplay = "Assigned by Shop";
$tailorCost = 0;

if ($tailor != "") {
  if (!array_key_exists($tailor, $tailorCosts)) {
    echo "<p style='color:red'>Invalid Tailor selected.</p>";
    die();
  } else {
    $tailorDisplay = $tailor;
    $tailorCost = $tailorCosts[$tailor];
  }
}

/* -------------------------
   CALCULATIONS (الحسابات)
-------------------------- */

$clothPrice = $clothPrices[$cloth];
$sizeCost = $sizeCosts[$size];

$extrasCost = 0;
for ($i = 0; $i < count($extras); $i++) {
  $extrasCost = $extrasCost + $extraServiceCosts[$extras[$i]];
}

$subtotal = $clothPrice + $sizeCost + $tailorCost + $extrasCost;

// VAT = Subtotal * 5% (استخدمت الثابت VAT_RATE)
$vatValue = $subtotal * VAT_RATE;

$totalPrice = $subtotal + $vatValue;

// عرض الخدمات كنص
$extrasDisplay = implode(", ", $extras);

// تنسيق بسيط للأرقام (بدون دالة)
$clothPriceF = number_format($clothPrice, 2);
$sizeCostF = number_format($sizeCost, 2);
$tailorCostF = number_format($tailorCost, 2);
$extrasCostF = number_format($extrasCost, 2);
$subtotalF = number_format($subtotal, 2);
$vatValueF = number_format($vatValue, 2);
$totalPriceF = number_format($totalPrice, 2);

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
      <div class="summary">
<pre><?php
// نفس التنسيق المطلوب بالضبط
echo "Customer Name: " . $customerName . "\n";
echo "Garment: " . $garment . "\n";
echo "Cloth: " . $cloth . "\n";
echo "Size: " . $size . "\n";
echo "Extras: " . $extrasDisplay . "\n";
echo "Tailor: " . $tailorDisplay . "\n\n";

echo "Cloth Price: OMR " . $clothPriceF . "\n";
echo "Size Cost: OMR " . $sizeCostF . "\n";
echo "Tailor Cost: OMR " . $tailorCostF . "\n";
echo "Extras Cost: OMR " . $extrasCostF . "\n";
echo "Subtotal: OMR " . $subtotalF . "\n";
echo "VAT (5%): OMR " . $vatValueF . "\n";
echo "Total Price: OMR " . $totalPriceF;
?></pre>
      </div>

      <a class="back-link" href="index.php">Back to Order Form</a>
    </div>
  </div>
</body>
</html>
