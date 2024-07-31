
<div class="login-form" style="padding-top: 0px;margin: 0px auto;text-shadow: none;border: 0px solid #ddd;">
      <form style="border:0px;">
        <div class="form-area" style="padding: 0px;">
          <div class="group">
          <table align="center" class="table table-bordered table-striped" cellspacing="5" border="0">
            <tr>
              <th>Item</th>
              <th>Qty</th>
              <th>Price</th>
            </tr>
            <tbody>
              <?php foreach($order as $row){?>
              <tr>
                <td><?= $row['title'] ?></td>
                <td><?= $row['item_qty'] ?></td>
                <td><?= $row['item_price'] ?></td>
              </tr>
            <?php } ?>
            </tbody>
          <!-- <tr>
            <td><label>Title  </label></td>
            <td><?php echo htmlentities(isset($order) ? $order['title'] : '-'); ?></td>
          </tr>
          <tr>
            <td><label>Arabic  </label></td>
            <td><?php echo htmlentities(isset($order) ? $order['arabian_title'] : '-'); ?></td>
          </tr> -->
          </table>
          </div>
        </div>
      </form>
</div>