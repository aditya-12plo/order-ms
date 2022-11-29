@extends('layouts.emails')
@section('content-emails')
<style>
  button {
 border: none;
 color: #090909;
 padding: 0.7em 1.7em;
 font-size: 18px;
 border-radius: 0.5em;
 background: #e8e8e8;
 border: 1px solid #e8e8e8;
 transition: all .3s;
 box-shadow: 6px 6px 12px #c5c5c5,
             -6px -6px 12px #ffffff;
}
button:active {
 color: #666;
 box-shadow: inset 4px 4px 12px #c5c5c5,
             inset -4px -4px 12px #ffffff;
}
</style>
 <!-- START CENTERED WHITE CONTAINER -->
 <table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">
  <!-- START MAIN CONTENT AREA -->
  <tr>
    <td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">


      <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
        <tr>
          <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Hi,</p>
            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
              You have new message support from user.
            </p>
            <br>
            <br> 
            <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                <tr>
                    <th><p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;text-align: left;">Name</p></th>
                    <th><p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;text-align: left;"> <?php echo $user->name;?></p></th>
                </tr>
                <tr>
                    <th><p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;text-align: left;">Email</p></th>
                    <th><p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;text-align: left;"> <?php echo $user->email;?></p></th>
                </tr>
                <tr>
                    <th><p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;text-align: left;">Company ID</p></th>
                    <th><p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;text-align: left;"> <?php echo $user->company_detail->code;?></p></th>
                </tr>
                <tr>
                    <th><p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;text-align: left;">Company Name</p></th>
                    <th><p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;text-align: left;"> <?php echo $user->company_detail->name;?></p></th>
                </tr>
                <tr>
                    <th><p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;text-align: left;">Subject</p></th>
                    <th><p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;text-align: left;"> <?php echo $subject;?></p></th>
                </tr>
            </table>
            <br>
            <br> 
            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
              <?php echo $content;?>
            </p>



          </td>
        </tr>
      </table>


    </td>
  </tr>
<!-- END MAIN CONTENT AREA -->
</table>             
@endsection