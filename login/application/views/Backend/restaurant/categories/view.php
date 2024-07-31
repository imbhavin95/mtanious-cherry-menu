<?php
    if(is_sub_admin())
    {
        $restaurant_id = $this->session->userdata('login_user')['restaurant_id'];
    }
    else
    {
        $restaurant_id = $this->session->userdata('login_user')['id'];
    }
?>
<div class="login-form" style="padding-top: 0px;margin: 0px auto;text-shadow: none;border: 0px solid #ddd;">
      <form style="border:0px;">
      <div class="top" style="border:0px;">
          <img src="<?php
                    if (isset($category['image'])) {
                        $path = RESTAURANT_IMAGES . '/' .  $restaurant_id. '/categories/'.$category['image'];
                        if (CheckImageType($path)) 
                        {
                            echo base_url($path);
                        } else {
                            echo base_url(DEFAULT_IMG);
                        }
                    } else {
                        echo base_url(DEFAULT_IMG);
                    }?>" alt="icon" class="icon profile">
        </div>
        <div class="form-area" style="padding: 0px;">
          <div class="group">
          <table align="center" class="table table-bordered table-striped" cellspacing="5" border="0">
          <tr>
            <td><label>Menu  </label></td>
            <td><?php echo htmlentities(isset($menu) ? $menu : '-') ?></td>
          </tr>
          <tr>
            <td><label>Title  </label></td>
            <td><?php echo htmlentities(isset($category) ? $category['title'] : '-') ?></td>
          </tr>
          <tr>
            <td><label>Arabic  </label></td>
            <td><?php echo htmlentities(isset($category) ? $category['arabian_title'] : '-'); ?></td>
          </tr>
          </table>
          </div>
        </div>
      </form>
</div>