<h2>Hello you,</h2>
<p>
Checking... <br />

You will be receiving bugfixes / updates of the plugin at the following email adress: <strong><?php echo get_option('bbit_register_email');?></strong> <br />

Plugin Validated at: <strong><?php echo date('l jS \of F Y h:i:s A', get_option('bbit_register_timestamp'));?></strong>  <br />

Installed on domain: <strong><?php 
$_domain = parse_url(get_option('siteurl'));
echo $_domain['host'];?></strong><br />

API Key : <?php echo get_option('bbit_register_licence');?><br /><br />

EnyoY! <br /> Bbit
</p>