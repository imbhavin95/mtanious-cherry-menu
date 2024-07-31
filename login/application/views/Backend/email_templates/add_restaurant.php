<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<base href="<?php echo base_url(); ?>">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Email Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,700" rel="stylesheet">
    <style type="text/css">
		@font-face {
			font-family: 'Avenir LT Std';
			src: url('assets/Backend/css/font/AvenirLTStd-Roman.woff2') format('woff2'),
				url('assets/Backend/css/font/AvenirLTStd-Roman.woff') format('woff');
			font-weight: normal;
			font-style: normal;
		}

		@font-face {
			font-family: 'Avenir LT Std';
			src: url('assets/Backend/css/font/AvenirLTStd-Black.woff2') format('woff2'),
				url('assets/Backend/css/font/AvenirLTStd-Black.woff') format('woff');
			font-weight: 900;
			font-style: normal;
		}

		@font-face {
			font-family: 'Avenir LT Std';
			src: url('assets/Backend/css/font/AvenirLTStd-Book.woff2') format('woff2'),
				url('assets/Backend/css/font/AvenirLTStd-Book.woff') format('woff');
			font-weight: normal;
			font-style: normal;
		}
        body {
           font-family: 'Avenir LT Std';
		   font-weight:500;   
        }
        
        p {
            margin: 0;
        }
		
    </style>
</head>

<body style="margin: 0; padding: 0;">
    <table align="" border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto;background: #fff;width: 100%;max-width: 600px;">
	<tr>
		<td style="padding: 57px 0 87px;text-align: center;">
			<a href="#" style="display:inline-block;">
				<img src="https://www.cherrymenu.com/login/assets/images/logo.png"/>
			</a>
		</td>
	</tr>
	<tr>
		<td>
			<table style="width: 100%;padding:0 45px;">
				<tr>
						<td>
							<p style="font-size: 14px;color: #9B9B9B;line-height: 19px;margin:0;">Hi <?php echo $name ; ?>,</p>
						<td>
				</tr>
					<tr>
					<td style="padding: 30px 0;">
							<p style="font-size: 14px;color: #000;line-height: 19px;margin:0;">A new <?php echo $type; ?> account has been created for you, your login info are:</p>
						</td>
					</tr>
					<tr>
						<td>
							<p style="font-size: 14px;color: #9B9B9B;line-height: 19px;margin:0;">Username: <span style="color: #000;"><?php echo $email ; ?></span></p>
							<p style="font-size: 14px;color: #9B9B9B;line-height: 19px;margin:0;">Password: <span style="color: #000;"><?php echo $password ?></span></p>
						</td>
					</tr>
					<tr>
						<td>
							<p style="margin:0;">
								<a href="<?php echo $url ?>" style="display: inline-block;line-height: 32px;min-width: 133px;background: #CE0E2D;text-align: center;text-decoration: none;color: #fff;font-size: 14px;border-radius: 2px;margin-top: 35px;    margin-bottom: 35px;">Activate account and sign in</a>
						</p>
						</td>
					</tr>
					<tr>
						<td style="padding-bottom: 50px;">
							<P style="font-size: 14px;color:#9B9B9B;line-height: 19px;margin:0;">Please do let us know if you have any questions.</P>
						</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table style="background: #F7F7FA;width: 100%;padding: 45px;">
				<tr>
					<td style="text-align:left;">
						<a href="#" style="font-size: 14px;color: #000000;text-align: center;line-height: 24px;"><img src="https://www.cherrymenu.com/login/assets/images/Heart.png"/></a>
					</td>
					<td style="text-align: center;">
						<a href="#" style="font-size: 14px;color: #000000;text-align: center;line-height: 24px;">+971 4 557 9200</a>
					</td>
					<td style="text-align: center;">
						<a href="#" style="font-size: 14px;color: #000000;text-align: center;line-height: 24px;">wecare@cherrymenu.com</a>
					</td>
					<td style="text-align: center;">
						<a href="#" style="font-size: 14px;color: #000000;text-align: center;line-height: 24px;">Online chat</a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
    </table>
</body>

</html>