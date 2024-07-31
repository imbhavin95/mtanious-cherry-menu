
<div class="login-form" style="padding-top: 0px;margin: 0px auto;text-shadow: none;border: 0px solid #ddd;">
      <form style="border:0px;">
        <div class="top" style="border:0px;">
          <h3><?php echo htmlentities(isset($item) ? $item['title'] : '-'); ?></h3>
        </div>
        <div class="form-area" style="padding: 0px;">
          <div class="group">
          <table align="center" class="table table-bordered table-striped" cellspacing="5" border="0">
          <tr>
            <td><label>Price  </label></td>
            <td><?php echo isset($item) ? 'AED '.number_format((float)$item['price'], 2, '.', '') : '-' ?></td>
          </tr>
          <tr>
            <td><label>Types  </label></td>
            <td><?php echo htmlentities((isset($item) && $item['type'] != null)  ? $item['type'] : '-'); ?></td>
          </tr>
          <tr>
            <td><label>Calories  </label></td>
            <td><?php echo htmlentities(isset($item) ? $item['calories'].' kcal' : '-') ?></td>
          </tr>
          <tr>
            <td><label>Created On  </label></td>
            <td><?php echo isset($item) ? date('d M Y', strtotime($item['created_at'])) : '-' ?></td>
          </tr>
          </table>
          </div>
        </div>
      </form>
</div>