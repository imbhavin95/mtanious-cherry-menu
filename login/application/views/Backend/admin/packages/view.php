
<div class="login-form" style="padding-top: 0px;margin: 0px auto;text-shadow: none;border: 0px solid #ddd;">
      <form style="border:0px;">
        <div class="form-area" style="padding: 0px;">
          <div class="group">
          <table align="center" class="table table-bordered table-striped" cellspacing="5" border="0">
          <tr>
            <td><label>Name </label></td>
            <td><?php echo isset($package) ? $package['name'] : '-' ?></td>
          </tr>
          <tr>
            <td><label>Arabic Name </label></td>
            <td><?php echo isset($package) ? $package['arabic_name'] : '-' ?></td>
          </tr>
          <tr>
            <td><label>Price </label></td>
            <td><?php echo isset($package) ? $package['price'] : '-' ?></td>
          </tr>
          <tr>
            <td><label>Start Date </label></td>
            <td><?php echo isset($package) ? $package['start_date'] : '-' ?></td>
          </tr>
          <tr>
            <td><label>End Date </label></td>
            <td><?php echo isset($package) ? $package['end_date'] : '-' ?></td>
          </tr>
          <tr>
            <td><label>Type </label></td>
            <td><?php echo isset($package) ? $package['type'] : '-' ?></td>
          </tr>
          <tr>
            <td><label>Users Limit </label></td>
            <td><?php echo isset($package) ? $package['users'] : '-' ?></td>
          </tr>
          <tr>
            <td><label>Menus Limit </label></td>
            <td><?php echo isset($package) ? $package['menus'] : '-' ?></td>
          </tr>
          <tr>
            <td><label>Categories Limit </label></td>
            <td><?php echo isset($package) ? $package['categories'] : '-' ?></td>
          </tr>
          <tr>
            <td><label>Items Limit  </label></td>
            <td><?php echo isset($package) ? $package['items'] : '-' ?></td>
          </tr>
          <tr>
            <td><label>Description  </label></td>
            <td><?php echo isset($package) ? $package['description'] : '-' ?></td>
          </tr>
          <tr>
            <td><label>Arabic Description </label></td>
            <td><?php echo isset($package) ? $package['arabic_description'] : '-' ?></td>
          </tr>
          <tr>
            <td><label>Created On </label></td>
            <td><?php echo isset($package) ? date('d M Y', strtotime($package['created_at'])) : '-' ?></td>
          </tr>
          </table>
          </div>
        </div>
      </form>
</div>