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
                            <p style="font-size: 22px;font-weight:bold; color: #333; margin: 0 5px;">Greetings from {{ config('app.name') }}!</p>
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px;">
                            <p style="font-size: 22px; color: #333; margin: 0 5px;font-weight: bold !important;">This is a reminder that your life story interview is scheduled for {{ $firstLineDate }} (PST).</p>
                            <br>
                            <p style="font-size: 18px; color: #333; margin: 0 5px;">Please use the button or link below to join your meeting from any smartphone, tablet, or computer (with integrated camera). Don’t forget to load the Zoom application on your device prior to your meeting for the most seamless experience. <u>Be sure to also have your device plugged into power and connected to a good Wi-Fi connection.</u></p>
                            <br>
                            <p style="font-size: 18px; color: #333; margin: 0 5px;">The cost of your Lifestory recording is {{ $cost }}, payable by credit card following your interview. Once completed, you will receive an email invitation to log into your Project Lifestory account to view and/or download your new Lifestory video.</p>
                            <br>
                            <p style="font-size: 18px; color: #333; margin: 0 5px;">If you have not sent us your list of Friends & Family yet, please do so by sending to support@projectlifestory.com with the names and email accounts you would like us to share your video with.</p>
                            <br>
                            <p style="font-size: 18px; color: #333; margin: 0 5px;">We think you’re going to enjoy recording your Lifestory with us. If you have any questions, do not hesitate to reach out to us at {{ env('SUPPORT_EMAIL') }}.</p>
                            <br>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div style="white-space: nowrap !important; margin: 0 80px !important; border-radius:7px !important;background-color: #6096b4; padding: 10px; text-align: center;">
                                <a target="_blank" href="{{ $join_url }}"
                                style="font-size: 16px; color: #ffffff; text-decoration: none !important;">Click to join Zoom meeting at {{ $linkDate }}</a>
                            </div>
                            <br>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 20px;">
                            <p style="font-size: 18px; color: #333; margin: 0 5px;">Thank you,</p>
                            <p style="font-size: 18px; color: #333; margin: 0 5px;">The Project Lifestory Team</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 20px;">
                            <p style="font-size: 16px; color: #333; margin: 0 5px;">If you are having trouble clicking the button above, just paste the link below (without quotes) into your browser instead:</p>
                            <br>
                            <p style="font-size: 16px; color: #333; margin: 0 5px;">"{{ $join_url }}"</p>
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
