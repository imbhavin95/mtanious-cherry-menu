
<div class="login-form" style="padding-top: 0px;margin: 0px auto;text-shadow: none;border: 0px solid #ddd;">
      <form style="border:0px;">
        <div class="top" style="border:0px;">
          <img src="<?php
                    if(isset($restaurant['image']))
                    {
                        $path = RESTAURANT_IMAGES. '/' .$restaurant['id']. '/' .$restaurant['image'];
                      
                        if (CheckImageType($path)) 
                        {
                            echo base_url(RESTAURANT_IMAGES . '/' .$restaurant['id']. '/' . $restaurant['image']);
                        } else 
                        {   
                            echo base_url(DEFAULT_USER_IMG);  
                        }
                    }else
                    {
                        echo base_url(DEFAULT_USER_IMG); 
                    } ?>" alt="icon" class="icon profile">
          <h3><?php echo htmlentities(isset($restaurant) ? $restaurant['name'] : '-') ?></h3>
        </div>
        <div class="form-area" style="padding: 0px;">
          <div class="group">
          <table align="center" class="table table-bordered table-striped" cellspacing="5" border="0">
          <tr>
            <td><label>Role </label></td>
            <td><?php echo isset($restaurant) ? $restaurant['role'] : '-' ?></td>
          </tr>
          <tr>
            <td><label>Email </label></td>
            <td><?php echo isset($restaurant) ? $restaurant['email'] : '-' ?></td>
          </tr>
          <tr>
            <td><label>Created On </label></td>
            <td><?php echo isset($restaurant) ? date('d M Y', strtotime($restaurant['created_at'])) : '-' ?></td>
          </tr>
          <tr>
            <td><label>Users Limit </label></td>
            <td><?php echo isset($restaurant) ? $restaurant['users_limit'] : '-' ?></td>
          </tr>
          <tr>
            <td><label>Menus Limit </label></td>
            <td><?php echo isset($restaurant) ? $restaurant['menus_limit'] : '-' ?></td>
          </tr>
          <tr>
            <td><label>Categories Limit </label></td>
            <td><?php echo isset($restaurant) ? $restaurant['categories_limit'] : '-' ?></td>
          </tr>
          <tr>
            <td><label>Items Limit  </label></td>
            <td><?php echo isset($restaurant) ? $restaurant['items_limit'] : '-' ?></td>
          </tr>
          <tr>
            <td><label>Devices Limit  </label></td>
            <td><?php echo isset($restaurant) ? $restaurant['devices_limit'] : '-' ?></td>
          </tr>
          </table>
          </div>
        </div>
      </form>
</div>