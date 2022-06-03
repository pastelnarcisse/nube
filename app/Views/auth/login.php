<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<!------ CSS ---------->
<link 
  href="<?=base_url('public/plugins/bootstrap-4.6.1/dist/css/bootstrap.min.css')?>" 
  rel="stylesheet" 
  id="bootstrap-css"
>
<link 
  href="<?=base_url('public/assets/css/login.css')?>" 
  rel="stylesheet" 
  id="bootstrap-css"
>

<!------ JS ---------->
<script src="<?=base_url('public/plugins/jquery/jquery.js')?>"></script>
<script src="<?=base_url('public/plugins/bootstrap-4.6.1/dist/js/bootstrap.min.js')?>"></script>

<!------ Include the above in your HEAD tag ---------->

<div class="wrapper fadeInDown">
  <div id="formContent">
    <!-- Tabs Titles -->

    <!-- Icon -->
    <div class="fadeIn first">
      <img src="<?=base_url('public/assets/images/login/logo.png')?>" id="icon" alt="User Icon" />
    </div>

    <!-- Login Form -->
    <?php echo form_open('auth/login');?>
      <?php echo form_input($identity);?>
      <?php echo form_input($password);?>
      <p>
      <?php echo form_label(lang('Auth.login_remember_label'), 'remember');?>
      <?php echo form_checkbox('remember', '1', false, 'id="remember"');?></p>
      <?php echo form_submit('submit', lang('Auth.login_submit_btn'));?>
    <?php echo form_close();?>

    <!-- Remind Passowrd -->
    <div id="formFooter">
      <a href="forgot_password" class="underlineHover"><?php echo lang('Auth.login_forgot_password');?></a>
    </div>

  </div>
</div>
