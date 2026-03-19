<?php
// Abaya and Dishdash Tailoring Order System (CSWD2101)
// Supervisor: Vinesh Jain
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Abaya and Dishdash Tailoring Order System</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Abaya and Dishdash Tailoring Order System</h1>
      <p>Nizwa Tailoring Shop - CSWD2101 (Supervisor: Vinesh Jain)</p>
    </div>

    <div class="card">
      <form action="process.php" method="post" novalidate>
        <div class="grid">
          <div class="field">
            <div class="label">Customer Name</div>
            <input type="text" name="customer_name" placeholder="e.g., Vinesh Jain" />
            <p class="help">English letters and spaces only.</p>
          </div>

          <div class="field">
            <div class="label">Tailor (Optional)</div>
            <select name="tailor">
              <option value="">Assigned by Shop</option>
              <option value="Ahmed">Ahmed</option>
              <option value="Salim">Salim</option>
              <option value="Khalid">Khalid</option>
            </select>
            <p class="help">If not selected, Tailor Cost = 0 OMR.</p>
          </div>

          <fieldset class="group">
            <legend>Garment Type</legend>
            <div class="inline-options">
              <label><input type="radio" name="garment" value="Abaya" /> Abaya</label>
              <label><input type="radio" name="garment" value="Dishdash" /> Dishdash</label>
            </div>
          </fieldset>

          <div class="field">
            <div class="label">Cloth Type</div>
            <select name="cloth">
              <option value="">Select Cloth</option>
              <option value="Cotton">Cotton</option>
              <option value="Linen">Linen</option>
              <option value="Silk">Silk</option>
              <option value="Premium Fabric">Premium Fabric</option>
            </select>
          </div>

          <div class="field">
            <div class="label">Size</div>
            <select name="size">
              <option value="">Select Size</option>
              <option value="Small">Small</option>
              <option value="Medium">Medium</option>
              <option value="Large">Large</option>
              <option value="Extra Large">Extra Large</option>
            </select>
          </div>

          <fieldset class="group">
            <legend>Extra Services (choose at least one)</legend>
            <div class="inline-options">
              <label><input type="checkbox" name="extras[]" value="Embroidery" /> Embroidery</label>
              <label><input type="checkbox" name="extras[]" value="Express Delivery" /> Express Delivery</label>
            </div>
          </fieldset>
        </div>

        <div class="actions">
          <button type="submit">Submit Order</button>
          <button type="reset">Reset</button>
        </div>

        <div class="note">
          Note: Prices are calculated in OMR based on fixed tables defined in <strong>process.php</strong>.
        </div>
      </form>
    </div>
  </div>
</body>
</html>
