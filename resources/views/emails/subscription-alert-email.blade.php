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
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px;">
                            <p style="font-size: 20px; color: #333; margin: 0 5px;"></p>


                            <div style="text-align: center; margin:30px; position: relative;" class="customer_video_ss">
                                @if($screenshot_url)
                                <a target="_blank" href="{{ $password_link }}" style="text-decoration: none;">
                                    <img style="border: 1px solid black !important;"
                                        src="{{ $message->embed($screenshot_url) }}" alt="Customer Video" width="300">
                                </a>
                                @else
                                <a target="_blank" href="{{ $password_link }}" style="text-decoration: none;">
                                    <img style="border: 1px solid black !important;"
                                        src="{{ $message->embed(asset('video-thumbnail.png')) }}" alt="Customer Video" width="300">
                                </a>
                                @endif
                                {{-- @if($screenshot_url)
                                <a href="{{ $password_link }}" style="text-decoration: none;">
                                    <img style="border: 1px solid black !important;"
                                        src="{{ $screenshot_url }}" alt="Customer Video" width="300">
                                </a>
                                @else
                                <a href="{{ $password_link }}" style="text-decoration: none;">
                                    <img style="border: 1px solid black !important;"
                                        src="{{ asset('video-thumbnail.png') }}" alt="Customer Video" width="300">
                                </a>
                                @endif --}}
                            </div>

                            <p style="font-size: 20px; color: #333; margin: 0 5px;">Your access is private and
                                completely free. Feel free to view the video and/or upload any accompanying pictures or
                                files into their photo library.</p>

                            <br><br>

                            <p style="font-size: 20px; color: #333; margin: 0 5px;">Thank you and welcome to the future
                                of genealogy!</p>

                            <br><br>
                            <p style="font-size: 20px; color: #333; margin: 0 5px;">The Project Lifestory Staff</p>

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
