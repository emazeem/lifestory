<!DOCTYPE html>
<html>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f4f4;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0"
                    style="background-color: #ffffff; margin: 20px auto; border-radius: 5px; box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="padding: 20px; text-align:center">
                            <p style="font-size: 22px;font-weight:bold; color: #333; margin: 0 5px;">{{ config('app.name') }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px;">
                            <p style="font-size: 20px; color: #333; margin: 0 5px;">Welcome to Project Lifestory! Your account has been successfully created. We're thrilled to have you join the project. You can now use the button or link below to access your new account and view your Lifestory Video!</p>
                            <br>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div style="margin: 0 100px !important; border-radius:7px !important;background-color: #6096b4; padding: 10px; text-align: center;">
                                <a href="{{ url('login') }}"
                                style="font-size: 16px; color: #ffffff; text-decoration: none !important;">Click to login to your account</a>
                            </div>
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px;">
                            <p style="font-size: 20px; color: #333; margin: 0 5px;">Thank you,</p>
                            <p style="font-size: 20px; color: #333; margin: 0 5px;">The Project Lifestory Team</p>
                        </td>
                    </tr>

        
                    <tr>
                        <td style="padding: 20px;">
                            <p style="font-size: 16px; color: #333; margin: 0 5px;">If you're having trouble clicking the button above, just copy and paste the link below (without quotes) into your browser instead:</p>
                            <br>
                            <p style="font-size: 16px; color: #333; margin: 0 5px;">"{{ url('login') }}"</p>
                            <br>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center; padding: 20px;">
                            © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
