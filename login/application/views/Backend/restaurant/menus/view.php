
<div class="login-form" style="padding-top: 0px;margin: 0px auto;text-shadow: none;border: 0px solid #ddd;">
      <form style="border:0px;">
        <div class="form-area" style="padding: 0px;">
          <div class="group">
          <table align="center" class="table table-bordered table-striped" cellspacing="5" border="0">
          <tr>
            <td><label>Title  </label></td>
            <td><?php echo htmlentities(isset($menu) ? $menu['title'] : '-'); ?></td>
          </tr>
          <tr>
            <td><label>Arabic  </label></td>
            <td><?php echo htmlentities(isset($menu) ? $menu['arabian_title'] : '-'); ?></td>
          </tr>
          </table>
          </div>
        </div>
      </form>
</div>