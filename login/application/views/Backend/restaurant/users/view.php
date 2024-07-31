
<div class="login-form" style="padding-top: 0px;margin: 0px auto;text-shadow: none;border: 0px solid #ddd;">
      <form style="border:0px;">
        <div class="top" style="border:0px;">
          <img src="<?php
                    if(isset($user['image']))
                    {
                        $path = RESTAURANT_IMAGES . '/' . $this->session->userdata('login_user')['id']. '/users/'.$user['id'].'/'.$user['image'];
                        if (CheckImageType($path)) 
                        {
                            echo base_url($path);
                        } else 
                        {   
                            echo base_url(DEFAULT_USER_IMG);  
                        }
                    }else
                    {
                        echo base_url(DEFAULT_USER_IMG); 
                    } ?>" alt="icon" class="icon profile">
          <h3><?php echo htmlentities(isset($user) ? $user['name'] : '-') ?></h3>
        </div>
        <div class="form-area" style="padding: 0px;">
          <div class="group">
          <table align="center" class="table table-bordered table-striped" cellspacing="5" border="0">
          <tr>
            <td><label>Role  </label></td>
            <td><?php echo isset($user) ? $user['role'] : '-' ?></td>
          </tr>
          <tr>
            <td><label>Email </label></td>
            <td><?php echo isset($user) ? $user['email'] : '-' ?></td>
          </tr>
          <tr>
            <td><label>Created On  </label></td>
            <td><?php echo isset($user) ? date('d M Y', strtotime($user['created_at'])) : '-' ?></td>
          </tr>
          </table>
          </div>
        </div>
      </form>
</div>