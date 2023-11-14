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
                            <p style="font-size: 22px;font-weight:bold; color: #333; margin: 0 5px;">Welcome to {{ config('app.name') }}!</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px;">
                            <p style="font-size: 22px; color: #333; margin: 0 5px;font-weight: bold !important;">Your life story interview has been scheduled for {{ $formattedStartTime1 }} (PST), and should last for approximately {{ $duration }} minutes.</p>
                            <br>
                            <p style="font-size: 18px; color: #333; margin: 0 5px;">You can use the button or link below to join your meeting from any smartphone, tablet, or computer (with integrated camera). Be sure to load the Zoom application on your device prior to your meeting for the most seamless experience.</p>
                            <br>
                            <p style="font-size: 18px; color: #333; margin: 0 5px;">The cost of your Lifestory recording will be {{ $cost }}, payable by credit card following your interview. Once completed, you will receive an email invitation to log into your Project Lifestory account to view and/or download your new Lifestory video.</p>
                            <br>
                            <p style="font-size: 18px; color: #333; margin: 0 5px;">We also recommend sharing your Lifestory! Please provide a short list of family and friends email accounts you would like us to share your video with. This normally ranges between 5 to 10 people but there is no limit. Just send your list of names and email addresses back to us at {{ env('SUPPORT_EMAIL') }} so they too can receive an invite to view your Lifestory page. It’s as simple as that!</p>
                            <br>
                            <p style="font-size: 18px; color: #333; margin: 0 5px;">We look forward to a fun and enjoyable interview. If you have any questions, do not hesitate to reach out to us at {{ env('SUPPORT_EMAIL') }}.</p>
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
