<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Invoice</title>
</head>
<body>
    <center><h2 style="text-align: center;">Invoice</h2></center>
    <br/>
    <div style="width:100%;border-bottom: 1px solid #777;padding:0 0 15px 0">
      <div style="width:70%;float:left;" >
        <h2>Cherrymenu</h2>
        311 Rd, MBZ Rd<br/>
        Renamed, previously Emirates Rd.
      </div>
      <div style="width:30%;float:right;padding-top:20px;">
        <!-- <b>Invoice No.</b> <?php echo isset($package_detail['id']) ? $package_detail['id'] : '-' ?> <br> -->
        <b>Date</b> <?php echo isset($package_detail['created_at']) ? date('d M Y', strtotime($package_detail['created_at'])) : date('d M Y'); ?>
      </div>
  </div>

  <br/>
  <b>Bill To.</b> <br/>
  <?php echo isset($restaurant['name'])? $restaurant['name'] : '-' ?> <br/>
  <?php echo isset($restaurant['email']) ? $restaurant['email'] : '-' ?>

  <br/><br/><br/>

<table style="border:1px solid #777;width:100%;border-spacing:0;">
	<thead>
		<tr>
			<th style="border-bottom:1px solid #777;padding:5px;font-size:13px;text-align:left;">Quantity</th>
			<th style="border-bottom:1px solid #777;padding:5px;font-size:13pxl;text-align:left;">Package</th>
			<th style="border-bottom:1px solid #777;padding:5px;font-size:13px;text-align:left;">Amount</th>
		</tr>
	</thead>
	<tbody>
    <tr>
      <td style="border-bottom:1px solid #ddd;padding:5px 10px;font-size:13px">1</td>
      <td style="border-bottom:1px solid #ddd;padding:5px 10px;font-size:13px">Name : <?php echo isset($package['name']) ? $package['name'] : '-' ?></td>
      <td style="border-bottom:1px solid #ddd;padding:5px 10px;font-size:13px"><?php echo isset($package['price']) ? 'AED '.$package['price'] : '-' ?></td>
    </tr>
    <tr>
      <td style="border-bottom:1px solid #ddd;padding:5px 10px;font-size:13px"></td>
      <td style="border-bottom:1px solid #ddd;padding:5px 10px;font-size:13px">Users Limit : <?php echo isset($package['users']) ? $package['users'] : '-' ?></td>
      <td style="border-bottom:1px solid #ddd;padding:5px 10px;font-size:13px"></td>
    </tr>
    <tr>
      <td style="border-bottom:1px solid #ddd;padding:5px 10px;font-size:13px"></td>
      <td style="border-bottom:1px solid #ddd;padding:5px 10px;font-size:13px">Menus Limit : <?php echo isset($package['menus']) ? $package['menus']: '-' ?></td>
      <td style="border-bottom:1px solid #ddd;padding:5px 10px;font-size:13px"></td>
    </tr>
    <tr>
      <td style="border-bottom:1px solid #ddd;padding:5px 10px;font-size:13px"></td>
      <td style="border-bottom:1px solid #ddd;padding:5px 10px;font-size:13px">Categories Limit : <?php echo isset($package['categories']) ? $package['categories'] : '-' ?></td>
      <td style="border-bottom:1px solid #ddd;padding:5px 10px;font-size:13px"></td>
    </tr>
    <tr>
      <td style="border-bottom:1px solid #ddd;padding:5px 10px;font-size:13px"></td>
      <td style="border-bottom:1px solid #ddd;padding:5px 10px;font-size:13px">Items Limit : <?php echo isset($package['items']) ? $package['items'] : '-' ?></td>
      <td style="border-bottom:1px solid #ddd;padding:5px 10px;font-size:13px"></td>
    </tr>
    <tr>
      <td style="border-bottom:1px solid #ddd;padding:5px 10px;font-size:13px"></td>
      <td style="border-bottom:1px solid #ddd;padding:5px 10px;font-size:13px">Devices Limit : <?php echo isset($package['devices_limit']) ? $package['devices_limit'] : '-' ?></td>
      <td style="border-bottom:1px solid #ddd;padding:5px 10px;font-size:13px"></td>
    </tr>
    <tr>
      <td style="border-bottom:1px solid #ddd;padding:5px 10px;font-size:13px"></td>
      <td style="border-bottom:1px solid #ddd;padding:5px 10px;font-size:13px"></td>
      <td style="border-bottom:1px solid #ddd;padding:5px 10px;font-size:13px">Total (billed annually) : <?php echo isset($package['price']) ? 'AED '.$package['price'].' (VAT excluded)' : '-' ?></td>
    </tr>
	</tbody>
</table>

<br/><br/><br/><br/><br/><br/>
</div style="width:100%;">
  <b>Terms & Conditions</b>
  <br>
  <a href="https://cherrymenu.com/terms-and-conditions/">Click here to view</a>

<div>
</body>
</html>

