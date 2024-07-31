
<div class="login-form" style="padding-top: 0px;margin: 0px auto;text-shadow: none;border: 0px solid #ddd;">
      <form style="border:0px;">
        
        <div class="form-area" style="padding: 0px;">
          <div class="group">
          <table align="center" cellspacing="5" border="0">
          <tr>
            <td><h3><?php echo htmlentities(isset($helptopic) ? $helptopic['title'] : '-'); ?></h3></td>
          </tr>
          <tr>
            <td><label> Description</label></td>
          </tr>
          <tr>
            <td><?php echo isset($helptopic) ? $helptopic['description'] : '-'; ?></td>
          </tr>
          <tr>
            <td><label>Created On : </label><?php echo isset($helptopic) ? date('d M Y', strtotime($helptopic['created_at'])) : '-' ?></td>
          </tr>
          </table>
          </div>
        </div>
      </form>
</div>